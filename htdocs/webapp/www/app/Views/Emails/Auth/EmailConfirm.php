<!DOCTYPE html>
<html lang="<?php echo LANGUAGE_CODE; ?>">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2><?php echo __("email_confirm_title"); ?></h2>

<div>
    <h3><?php echo __("email_change_request") ?></h3>
    <br />
    <?php echo __("confirm_email_link_message", ["link" => site_url('members/confirm_email/' .$member->memberID."/".$member->token)]); ?>
    <br/>
    <?php echo __("url_use_problem_hint"); ?>
</div>
</body>
</html>