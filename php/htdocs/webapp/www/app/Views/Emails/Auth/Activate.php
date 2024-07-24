<!DOCTYPE html>
<html lang="<?php echo LANGUAGE_CODE; ?>">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2><?php echo __("activate_account_title"); ?></h2>

        <div>
            <h3><?php echo __("thank_you_join"); ?></h3>
            <br />
            <?php echo __("activation_link_message", ["link" => site_url('members/activate/' .$member->memberID."/".$member->token)]); ?>
            <br/>
            <?php echo __("url_use_problem_hint"); ?>
        </div>
    </body>
</html>
