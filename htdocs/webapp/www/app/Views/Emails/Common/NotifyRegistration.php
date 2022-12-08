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
        <h2><?php echo Language::instance('app')->get("new_account_title", "En"); ?></h2>

        <div style="font-size: 18px">
            <div style="margin-top: 20px">
                <div>
                    <strong><?php echo Language::instance('app')->get("name", "En"); ?>:</strong> <?php echo $name ?>,
                    <strong><?php echo Language::instance('app')->get("userName", "En"); ?>:</strong> <?php echo $userName ?>
                </div>
                <div>
                    <strong><?php echo Language::instance('app')->get("proj_lang_public", "En") ?>: </strong>
                    <?php echo $projectLanguage ?>
                </div>
                <div>
                    <strong><?php echo Language::instance('app')->get("Projects", "En")  ?>: </strong>
                    <?php echo $projects ?>
                </div>
            </div>

            <div style="margin-top: 20px">
                <div><?php echo Language::instance('app')->get("member_profile_message", "En") ?>:</div>
                <div>
                    <a href="<?php echo site_url("members/profile/$id") ?>">
                        <?php echo $name." (".$userName.")" ?>
                    </a>
                </div>
            </div>

            <div style="margin-top: 20px">
                <div><a href="<?php echo site_url("admin/members") ?>"><?php echo Language::instance('app')->get("members_area", "En") ?></a></div>
            </div>
        </div>
    </body>
</html>
