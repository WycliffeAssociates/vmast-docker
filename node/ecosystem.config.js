// pm2-runtime process list for the node container (see Dockerfile). A crashed
// process restarts after 1s, backing off up to 15s while it keeps failing (e.g.
// mailer during a RabbitMQ outage), and back to 1s once it stays up.
//
// pm2-runtime prints both processes' output to the container's log (`docker
// logs`); out_file and error_file stop pm2 also writing copies to files under
// ~/.pm2/logs inside the container, which nothing reads and nothing rotates.
const common = {
  exp_backoff_restart_delay: 1000,
  out_file: "/dev/null",
  error_file: "/dev/null",
};

module.exports = {
  apps: [
    { name: "server", script: "server.js", ...common },
    { name: "mailer", script: "mailer.js", ...common },
  ],
};
