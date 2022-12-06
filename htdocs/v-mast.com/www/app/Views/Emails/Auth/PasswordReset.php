<!DOCTYPE html>
<html lang="<?= LANGUAGE_CODE; ?>">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2><?= __("passwordreset_title"); ?></h2>

<div>
    <?= __("passwordreset_link_message", ["link" => site_url('members/resetpassword/' .$member->memberID."/".$member->token)]); ?><br/>
    <?= __("url_use_problem_hint"); ?>
</div>
</body>
</html>
