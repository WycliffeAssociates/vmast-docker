<?php if(isset($data['bookProjects']) && !empty($data['bookProjects'])): ?>
    <a href="/translations">
        <?php echo __("bible") ?>
    </a>
    →
    <?php echo $data['language'][0]->angName
        .($data['language'][0]->langName != $data['language'][0]->angName ? ' ('.$data['language'][0]->langName.')' : '') ?>
    <br>
    <br>
    <?php foreach ($data['bookProjects'] as $bookProject): ?>
        <a href="/translations/<?php echo $bookProject->targetLang . "/". $bookProject->bookProject . "/" . $bookProject->sourceBible ?>">
            <?php echo strtoupper($bookProject->bookProject)
                ." (".__($bookProject->bookProject).")"
                .($bookProject->sourceBible == "odb" ? " - " . __("odb") : "") ?>
        </a>
        <br>
    <?php endforeach; ?>
<?php endif; ?>

