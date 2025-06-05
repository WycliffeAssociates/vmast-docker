<!DOCTYPE html>
<html lang="<?= LANGUAGE_CODE; ?>">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2><?= __("passwordreset_title"); ?></h2>

<div>
    <?php $link = site_url('members/resetpassword/' .$member->memberID."/".$member->token) ?>
    <?php echo __("passwordreset_link_message", ["link" => "<a href='$link'>$link</a>"]); ?><br/>
    <?php echo __("url_use_problem_hint"); ?>
</div>
</body>
</html>
