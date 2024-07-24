<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>Contact Form Notification</h2>

        <div style="font-size: 18px">
            <div style="margin-top: 20px">
                <div><strong>Name:</strong> <?php echo $name ?></div>
                <div><strong>Email:</strong> <?php echo $email ?></div>
                <div><strong>Language:</strong> <?php echo $language ?></div>
            </div>

            <div style="margin-top: 20px">
                <div>Message:</div>
                <div><?php echo $userMessage ?></div>
            </div>
        </div>
    </body>
</html>
