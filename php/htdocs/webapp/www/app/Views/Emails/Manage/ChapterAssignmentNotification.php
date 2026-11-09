<!DOCTYPE html>
<html lang="<?php echo LANGUAGE_CODE; ?>">
<head>
    <meta charset="utf-8">
</head>
<body>

<h3>
    <?php echo __("chapter_assigned_msg") ?>
</h3>
<div style="margin-top: 20px;">

    <div style="font-weight: bold; margin-top: 20px; ">
        <?php echo __("chapter", $chapter) ?>
    </div><br/>

    <span style="font-weight: bold;"><?php echo __("book")?></span>
    <?php echo ": " . $book ?><br/>
    <span style="font-weight: bold;"><?php echo __("project")?></span>
    <?php echo ": " . __($project) ?><br/>
    <span style="font-weight: bold;"><?php echo __("gateway_language")?></span>
    <?php echo ": " . $language ?><br/>
    <span style="font-weight: bold;"><?php echo __("target_lang")?></span>
    <?php echo ": " . $target?>

</div>
</body>
</html>
