<!-- Book content -->
<?php if(isset($data['book'])): ?>
    <a href="/translations">
        <?php echo __("bible") ?>
    </a>
    →
    <a href="/translations/<?php echo $data['language'][0]->langID ?>">
        <?php echo $data['language'][0]->angName
            .($data['language'][0]->langName != $data['language'][0]->angName ? ' ('.$data['language'][0]->langName.')' : '') ?>
    </a>
    →
    <a href="/translations/<?php echo $data['language'][0]->langID . "/" .$data['project']['bookProject'] . "/" . $data['project']['sourceBible'] ?>">
        <?php echo __($data['project']['bookProject'])
            .($data['project']['sourceBible'] == "odb" ? " - " . __("odb") : "") ?>
    </a>
    →
    <?php echo __($data['bookInfo'][0]->code) ?>
    <br>
    <br>

    <?php if(!empty($data['book'])): ?>
        <div id="upload_menu">
            <span class="glyphicon glyphicon-export"></span>
            <ul>
                <?php if(in_array($data["data"]->sourceBible, ["odb","rad"])): ?>
                <li>
                    <a href="<?php echo $data['data']->bookCode ?>/json">
                        <?php echo __("download_json") ?>
                    </a>
                </li>
                <?php elseif(!in_array($data["mode"], ["tn","tq","tw","obs","bc","bca"])): ?>
                <li>
                    <a href="<?php echo $data['data']->bookCode ?>/usfm">
                        <?php echo __("download_usfm") ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $data['data']->bookCode ?>/ts">
                        <?php echo __("download_ts") ?>
                    </a>
                </li>
                <?php else: ?>
                <li>
                    <a href="<?php echo $data['data']->bookCode ?>/md">
                        <?php echo __("download_markdown") ?>
                    </a>
                </li>
                <?php endif; ?>
                <li class="export_cloud">
                    <a href="<?php echo $data['data']->bookCode ?>/wacs/export"><?php echo __("upload_wacs") ?></a>
                </li>
                <li class="export_cloud">
                    <a href="<?php echo $data['data']->bookCode ?>/dcs/export"><?php echo __("upload_door43") ?></a>
                </li>
            </ul>
        </div>

        <h1 style="text-align: center">—— <?php echo !in_array($data["bookInfo"][0]->category, ["odb","rad"])
            ? __($data['data']->bookCode)
            : $data['data']->bookName?> ——</h1>

        <div class="bible_book
            <?php echo ($data["data"]->bookProject == "sun"
                ? " sun_content" : "") . " font_".$data["data"]->targetLang?>"
        dir="<?php echo $data["data"]->direction ?>">
        <?php echo $data["book"] ?>
        </div>
    <?php endif; ?>
<?php endif; ?>

<div class="login_cloud_server form-panel">
    <div class="login_cloud_server_body panel panel-default">
        <div class="panel-heading">
            <h1 class="panel-title"><span class="cloud_server_name"></span></h1>
            <span class="panel-close glyphicon glyphicon-remove"></span>
        </div>

        <div class="page-content panel-body">
            <div>
                <?php echo __("login_cloud_server") ?>
                <a href="" target="_blank" class="create_login_link"><?php echo __("create") ?></a>
            </div>
            <div class="login_cloud_form">
                <div class="form-group" style="width: 250px;">
                    <label for="cloud_username" style="width: 100%; display: block"><?php echo __('userName'); ?></label>
                    <input class="form-control" name="cloud_username" id="cloud_username">
                </div>
                <div class="form-group" style="width: 250px;">
                    <label for="cloud_password" style="width: 100%; display: block"><?php echo __('password'); ?></label>
                    <input class="form-control" name="cloud_password" id="cloud_password" type="password">
                </div>
                <div class="form-group" style="width: 250px;">
                    <input name="cloud_otp" id="cloud_otp" type="checkbox">
                    <label for="cloud_otp"><?php echo __('two_factor_auth'); ?></label>
                </div>
                <div class="form-group cloud_otp_code_group" style="width: 250px;">
                    <label for="cloud_otp_code" style="width: 100%; display: block"><?php echo __('cloud_otp_code'); ?></label>
                    <input class="form-control" name="cloud_otp_code" id="cloud_otp_code" autocomplete="off">
                </div>
                <div class="cloudError"></div>
                <br>
                <button type="submit" name="cloudLogin" class="btn btn-primary"><?php echo __("login"); ?></button>
                <input type="hidden" name="cloudServer" id="cloudServer" value="">
                <input type="hidden" name="cloudUrl" id="cloudUrl" value="">

                <img class="cloudLoginLoader" width="24px" src="<?php echo template_url("img/loader.gif") ?>">
            </div>
        </div>
    </div>
</div>
