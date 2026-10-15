// pm2-runtime process list for the node container (see Dockerfile). A crashed
// process restarts after 1s, backing off up to 15s while it keeps failing (e.g.
// mailer during a RabbitMQ outage), and back to 1s once it stays up.
module.exports = {
  apps: [
    { name: "server", script: "server.js", exp_backoff_restart_delay: 1000 },
    { name: "mailer", script: "mailer.js", exp_backoff_restart_delay: 1000 },
  ],
};
