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
