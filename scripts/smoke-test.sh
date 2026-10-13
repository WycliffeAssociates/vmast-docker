#!/bin/bash
#
# Boots the whole stack with throwaway settings and checks that the app
# actually serves pages: the home page renders, routing sends each URL to its
# own route, unknown URLs 404, the socket server answers, and nothing reached
# the app's error log. CI runs this before publishing images; it also runs
# locally from the repo root.
#
# Env:
#   SMOKE_COMPOSE_ARGS  compose -f flags (default: -f docker-compose.yml)
#   SMOKE_BUILD_ARGS    extra `docker compose build` flags, e.g. "--pull --no-cache"
#   SMOKE_NO_BUILD=1    use existing images instead of building
#   SMOKE_KEEP=1        leave the stack running afterwards

set -euo pipefail

cd "$(dirname "$0")/.."

WEB_PORT=${SMOKE_WEB_PORT:-8080}
NODE_PORT=${SMOKE_NODE_PORT:-8001}
BASE="http://localhost:$WEB_PORT"

# The web image bakes in web/nginx/localhost.conf, which is gitignored.
[ -f web/nginx/localhost.conf ] || cp web/nginx/localhost.conf.example web/nginx/localhost.conf

# .env.example with ports and URLs for this run. DEPLOY_ENV is neither
# "development" nor a real environment, so the app behaves as in production
# (no debug output, no profiler).
env_file=$(mktemp)
sed -e '/^DEPLOY_ENV=/d' -e '/^APP_URL=/d' -e '/^MAIN_HOST=/d' \
    -e '/^SOCKET_PORT=/d' -e '/^WEB_PORT=/d' -e '/^NODE_PORT=/d' .env.example > "$env_file"
cat >> "$env_file" <<EOF
DEPLOY_ENV=smoke
APP_URL=$BASE/
MAIN_HOST=localhost
SOCKET_PORT=$NODE_PORT
WEB_PORT=$WEB_PORT
NODE_PORT=$NODE_PORT
REDIS_PASS=
EOF

read -r -a compose_files <<< "${SMOKE_COMPOSE_ARGS:--f docker-compose.yml}"
compose() { docker compose -p vmast-smoke --env-file "$env_file" "${compose_files[@]}" "$@"; }

cleanup() {
  status=$?
  if [ "$status" -ne 0 ]; then
    echo "::group::Diagnostics"
    compose ps -a || true
    compose logs --tail 80 || true
    echo "--- app/Storage/Logs/error.log"
    compose exec -T php cat app/Storage/Logs/error.log 2>/dev/null || true
    echo "::endgroup::"
  fi
  [ "${SMOKE_KEEP:-}" = 1 ] || compose down -v --remove-orphans >/dev/null 2>&1 || true
  rm -f "$env_file"
  exit "$status"
}
trap cleanup EXIT

if [ "${SMOKE_NO_BUILD:-}" != 1 ]; then
  read -r -a build_args <<< "${SMOKE_BUILD_ARGS:-}"
  compose build "${build_args[@]}"
fi
compose up -d --wait --wait-timeout 300

failures=0
pass() { echo "ok   $1"; }
fail() { echo "FAIL $1"; failures=$((failures + 1)); }

# Expect an HTTP status (and optionally a body substring) for a path.
check() {
  local path=$1 want=$2 needle=${3:-} body code
  body=$(mktemp)
  code=$(curl -s -o "$body" -w '%{http_code}' "$BASE$path" || true)
  if [ "$code" != "$want" ]; then
    fail "$path -> $code, want $want"
  elif [ -n "$needle" ] && ! grep -q "$needle" "$body"; then
    fail "$path -> $code but body lacks '$needle'"
  else
    pass "$path -> $code"
  fi
  rm -f "$body"
}

# php-fpm can still be starting when its container reports running.
for _ in $(seq 1 30); do
  code=$(curl -s -o /dev/null -w '%{http_code}' "$BASE/" || true)
  [ "$code" = 200 ] && break
  sleep 2
done

check /                200 "V-MAST"
check /members/login   200
# Each of these must reach its own route. When route matching breaks, every
# URL lands on the first route instead and they all answer alike.
check /events          302
check /does-not-exist  404

# node_entrypoint.sh runs `yarn install` before starting the server, so the
# socket can come up well after the web page.
for _ in $(seq 1 60); do
  code=$(curl -s -o /dev/null -w '%{http_code}' "http://localhost:$NODE_PORT/socket.io/?EIO=4&transport=polling" || true)
  [ "$code" = 200 ] && break
  sleep 2
done
[ "$code" = 200 ] && pass "socket.io handshake -> 200" || fail "socket.io handshake -> $code, want 200"

if compose exec -T php test -s app/Storage/Logs/error.log; then
  fail "app/Storage/Logs/error.log has entries"
else
  pass "error.log empty"
fi

if [ "$failures" -ne 0 ]; then
  echo "$failures smoke check(s) failed"
  exit 1
fi
echo "All smoke checks passed"
