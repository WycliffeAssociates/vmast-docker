<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 1/17/19
 * Time: 5:11 PM
 */

if(!empty($data["questions"])): ?>
<div class="ttools_panel tq_tool panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title"><?php echo __("tq") ?></h1>
        <span class="panel-close glyphicon glyphicon-remove" data-tool="tq"></span>
    </div>

    <div class="ttools_content page-content panel-body">
        <div class="labels_list">
            <?php if(isset($data["questions"])): ?>
                <?php foreach ($data["questions"] as $verse => $questions): ?>
                    <label>
                        <ul>
                            <li>
                                <div class="word_term">
                                    <span style="font-weight: bold;"><?php echo __("verse_number", $verse) ?> </span>
                                </div>
                                <div class="word_def">
                                    <?php foreach ($questions as $question): ?>
                                        <?php echo preg_replace('#<a.*?>(.*?)</a>#i', '<b>\1</b>', $question) ?>
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
<?php endif; ?>