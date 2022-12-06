const amqp = require('amqplib/callback_api');
const nodemailer = require('nodemailer');

const systemTransporter = nodemailer.createTransport({
    host: process.env.MAIL_HOST,
    port: 587,
    secure: false,
    auth: {
        user: process.env.MAIL_USER,
        pass: process.env.MAIL_PASS,
    },
});

amqp.connect(`amqp://${process.env.RABBITMQ_USER}:${process.env.RABBITMQ_PASS}@${process.env.RABBITMQ_HOST}`, function(error0, connection) {
    if (error0) {
        throw error0;
    }
    connection.createChannel(function(error1, channel) {
        if (error1) {
            throw error1;
        }
        const queue = process.env.RABBITMQ_QUEUE;

        channel.assertQueue(queue, {
            durable: false
        });

        channel.consume(queue, function (msg) {
            const data = JSON.parse(msg.content.toString());
            if (typeof data.emails != "undefined"
                && typeof data.subject != "undefined"
                && typeof data.message != "undefined") {
                data.emails.forEach(async function(to) {
                    const message = {
                        from: `"${process.env.MAIL_NAME}" ${process.env.MAIL_FROM}`,
                        to: to,
                        subject: data.subject,
                        html: data.message,
                    };
                    if (typeof data.replyTo != "undefined") {
                        message.replyTo = data.replyTo;
                    }
                    await systemTransporter.sendMail(message);
                });
            }
        }, {
            noAck: true
        });
    });
});