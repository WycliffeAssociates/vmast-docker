// pm2-runtime process list for the node container (see Dockerfile). Both
// processes restart on crash, one second apart, as the old entrypoint did.
module.exports = {
  apps: [
    { name: "server", script: "server.js", restart_delay: 1000 },
    { name: "mailer", script: "mailer.js", restart_delay: 1000 },
  ],
};
