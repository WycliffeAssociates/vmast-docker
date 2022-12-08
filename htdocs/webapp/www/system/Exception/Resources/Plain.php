<?php
use Config\Config;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo __("error_occurred", [""]) ?></title>

    <style>
        .button {
            margin-top: 50px;
            display: inline-block;
            padding: 6px 12px;
            margin-bottom: 0;
            font-size: 14px;
            font-weight: 400;
            line-height: 1.42857143;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            -ms-touch-action: manipulation;
            touch-action: manipulation;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            background-image: none;
            border: 1px solid #2e6da4;
            border-radius: 4px;
            color: #fff;
            background-color: #337ab7;
        }
    </style>
</head>
<body>
<div style="display: flex; flex-direction: column; align-items: center;">
    <h3 style="margin-top: 50px;"><?php echo __("error_occurred", [""]) ?></h3>

    <img style="margin-top: 50px;" width="200" src="<?php echo template_url("img/gear_broken.png") ?>" />

    <script
            src='https://js.sentry-cdn.com/<?php echo Config::get("sentry.dsn_js") ?>.min.js'
            crossorigin="anonymous">
    </script>

    <?php if (Sentry\SentrySdk::getCurrentHub()->getLastEventId()) { ?>
        <button
                class="button"
                onclick="showDialog()">
            <?php echo __("submit_crash_report")?>
        </button>

        <script>
            Sentry.init({
                dsn: '<?php echo Config::get("sentry.dsn") ?>'
            });

            function showDialog() {
                Sentry.showReportDialog({
                    eventId: '<?php echo Sentry\SentrySdk::getCurrentHub()->getLastEventId(); ?>'
                })
            }
        </script>
    <?php } ?>
</div>
</body>
</html>
