const amqp = require('amqplib/callback_api');
const nodemailer = require('nodemailer');

const transportOptions = {
    host: process.env.MAIL_HOST,
    port: process.env.MAIL_PORT,
    secure: false,
    ignoreTLS: true,
};

// Only log in when credentials are set. With an auth block that has an empty
// password, nodemailer refuses to send ("Missing credentials") whenever the
// server offers AUTH, even if the server would accept mail without logging in.
if (process.env.MAIL_USER && process.env.MAIL_PASS) {
    transportOptions.auth = {
        user: process.env.MAIL_USER,
        pass: process.env.MAIL_PASS,
    };
}

const systemTransporter = nodemailer.createTransport(transportOptions);

// Check the SMTP settings at startup, so wrong credentials or an unreachable
// mail server show up in the container log right away instead of only when
// the first email fails.
systemTransporter.verify()
    .then(() => console.log("SMTP: Server is ready"))
    .catch((err) => console.error("SMTP: Verification failed:", err.message));

amqp.connect(`amqp://${process.env.RABBITMQ_USER}:${process.env.RABBITMQ_PASS}@${process.env.RABBITMQ_HOST}`, function(error0, connection) {
    if (error0) {
        throw error0;
    }

    console.log("AMQP: Connected successfully");

    // Exit if the connection drops (e.g. RabbitMQ restarted) so pm2 restarts
    // this process and it reconnects, instead of idling without consuming.
    connection.on("error", function(err) {
        console.error("AMQP: Connection error", err.message);
    });
    connection.on("close", function() {
        console.error("AMQP: Connection closed, exiting so pm2 restarts the mailer");
        process.exit(1);
    });

    connection.createChannel(function(error1, channel) {
        if (error1) {
            throw error1;
        }

        console.log("AMQP: Channel created successfully");

        const queue = process.env.RABBITMQ_QUEUE;

        channel.assertQueue(queue, {
            durable: false
        });

        // Handle one message at a time.
        channel.prefetch(1);

        console.log(`AMQP: Waiting for messages on queue "${queue}"`);

        channel.consume(queue, async function (msg) {
            // RabbitMQ canceled the consumer (e.g. the queue was deleted).
            if (msg === null) {
                console.error("AMQP: Consumer cancelled by the broker, exiting");
                process.exit(1);
            }

            let data;
            try {
                data = JSON.parse(msg.content.toString());
            } catch (err) {
                console.error("Mailer: Invalid JSON, dropping message:", err.message);
                channel.ack(msg);
                return;
            }

            if (!Array.isArray(data.emails)
                || typeof data.subject == "undefined"
                || typeof data.message == "undefined") {
                console.error("Mailer: Incorrect data, dropping message");
                channel.ack(msg);
                return;
            }

            // Send to every recipient and wait for all of them. A failure for one
            // address is logged here instead of becoming an unhandled promise
            // rejection, which in Node 22 crashes the whole process.
            const results = await Promise.allSettled(data.emails.map(function(to) {
                const message = {
                    from: { name: process.env.MAIL_NAME, address: process.env.MAIL_FROM },
                    to: to,
                    subject: data.subject,
                    html: data.message,
                };
                if (typeof data.replyTo != "undefined") {
                    message.replyTo = data.replyTo;
                }
                return systemTransporter.sendMail(message);
            }));

            results.forEach(function(result, i) {
                if (result.status === "fulfilled") {
                    console.log(`Mailer: Sent "${data.subject}" to ${data.emails[i]} (${result.value.response})`);
                } else {
                    console.error(`Mailer: FAILED to send "${data.subject}" to ${data.emails[i]}:`,
                        result.reason && result.reason.message);
                }
            });

            // Acknowledge only after the send attempts have finished. Failed
            // sends are logged above rather than retried, so a bad address
            // can't loop forever.
            channel.ack(msg);
        }, {
            noAck: false
        });
    });
});