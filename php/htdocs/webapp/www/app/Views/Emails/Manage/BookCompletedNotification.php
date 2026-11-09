<?php

use Support\Facades\Language;

Language::instance('app')->load("messages", "En");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
</head>
<body>

<h3>
    <?php echo Language::instance('app')->get("book_completed_msg", "En")?>
</h3>
<div style="margin-top: 20px;">

    <span style="font-weight: bold;"><?php echo Language::instance('app')->get("book", "En")?></span>
    <?php echo ": " . $book ?><br/>
    <span style="font-weight: bold;"><?php echo Language::instance('app')->get("project", "En")?></span>
    <?php echo ": " . Language::instance('app')->get($project, "En") ?><br/>
    <span style="font-weight: bold;"><?php echo Language::instance('app')->get("gateway_language", "En")?></span>
    <?php echo ": " . $language ?><br/>
    <span style="font-weight: bold;"><?php echo Language::instance('app')->get("target_lang", "En")?></span>
    <?php echo ": " . $target?><br/>
    <span style="font-weight: bold;"><?php echo Language::instance('app')->get("event_status", "En")?></span>
    <?php echo ": " . Language::instance('app')->get("state_".$level, "En")?>
</div>
</body>
</html>
