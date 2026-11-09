<?php if(isset($data['books']) && !empty($data['books'])): ?>
    <a href="/translations">
        <?php echo __("bible") ?>
    </a>
    →
    <a href="/translations/<?php echo $data['language'][0]->langID ?>">
        <?php echo $data['language'][0]->angName
            .($data['language'][0]->langName != $data['language'][0]->angName ? ' ('.$data['language'][0]->langName.')' : '') ?>
    </a>
    →
    <?php echo __($data['project']["bookProject"])
        .($data['project']['sourceBible'] == "odb" ? " - " . __("odb") : "") ?>
    <br>
    <br>

    <?php if(sizeof($data['books']) > 0 && $data['books'][0]->bookCode != ""): ?>
        <?php if(in_array($data['books'][0]->sourceBible, ["odb","rad"])): ?>
            <h4 style="text-align: right">
                <a href="<?php echo $data['books'][0]->sourceBible ?>/dl/json">
                    <?php echo __("download_json") ?>
                </a>
            </h4>
        <?php elseif(!in_array($data["mode"], ["tn","tq","tw"])): ?>
            <h4 style="text-align: right">
                <a href="<?php echo $data['books'][0]->sourceBible ?>/dl/usfm">
                    <?php //echo __("download_usfm") ?>
                </a>
            </h4>
        <?php else: ?>
            <h4 style="text-align: right">
                <a href="<?php echo $data['books'][0]->sourceBible ?>/dl/md">
                    <?php //echo __("download_markdown") ?>
                </a>
            </h4>
        <?php endif; ?>

        <?php foreach ($data['books'] as $book): ?>
            <a href="/translations/<?php echo $book->targetLang . "/" .$book->bookProject . "/" . $book->sourceBible . "/" . $book->bookCode ?>">
                <?php echo __($book->bookCode) ?>
            </a>
            <br>
        <?php endforeach; ?>
    <?php endif; ?>
<?php endif; ?>
