<?php if(isset($data['languages']) && !empty($data['languages'])): ?>
    <?php echo __("bible") ?>
    <br>
    <br>
    <?php foreach ($data['languages'] as $language): ?>
        <a href="/translations/<?php echo $language->targetLang ?>">
            <?php echo $language->angName
                .($language->langName != $language->angName ? " (".$language->langName.")" : "") ?>
        </a>
        <br>
    <?php endforeach; ?>
<?php endif; ?>
