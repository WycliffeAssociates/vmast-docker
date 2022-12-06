<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 1/17/19
 * Time: 5:51 PM
 */

if(!empty($data["notes"])): ?>
<div class="ttools_panel tn_tool panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title"><?php echo __("tn") ?></h1>
        <span class="panel-close glyphicon glyphicon-remove" data-tool="tn"></span>
    </div>

    <div class="ttools_content page-content panel-body">
        <div class="labels_list">
            <?php if(isset($data["notes"])): ?>
                <?php foreach ($data["notes"] as $verse => $notes): ?>
                    <?php $chunkVerses = $data["notesVerses"][$verse]; ?>
                    <label>
                        <ul>
                            <li>
                                <div class="word_term">
                        <span style="font-weight: bold;">
                            <?php echo $chunkVerses > 0 ? __("verse_number", $chunkVerses) :
                                __("intro")?>
                        </span>
                                </div>
                                <div class="word_def">
                                    <?php foreach ($notes as $note): ?>
                                        <?php echo preg_replace('#<a.*?>(.*?)</a>#i', '<b>\1</b>', $note) ?>
                                    <?php endforeach; ?>
                                </div>
                            </li>
                        </ul>
                    </label>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="word_def_popup">
            <div class="word_def-close glyphicon glyphicon-remove"></div>

            <div class="word_def_title"></div>
            <div class="word_def_content"></div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $(".ttools_panel .word_def").each(function() {
            let html = convertRcLinks($(this).html());
            $(this).html(html);
        });
    });
</script>
<?php endif; ?>