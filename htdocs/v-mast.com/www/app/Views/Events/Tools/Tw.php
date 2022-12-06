<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 1/17/19
 * Time: 5:11 PM
 */

if(!empty($data["keywords"])): ?>
    <div class="ttools_panel tw_tool panel panel-default">
        <div class="panel-heading">
            <h1 class="panel-title"><?php echo __("tw") ?></h1>
            <span class="panel-close glyphicon glyphicon-remove" data-tool="tw"></span>
        </div>

        <div class="ttools_content page-content panel-body">
            <div class="labels_list">
                <?php foreach ($data["keywords"] as $title => $tWord): ?>
                    <?php if(!isset($tWord["text"])) continue; ?>
                    <label>
                        <ul>
                            <li>
                                <div class="word_term">
                                    <span style="font-weight: bold;"><?php echo ucfirst($tWord["name"] ?? (isset($tWord["term"]) ?: $title)) ?> </span>
                                    <span style="color: red;">(<?php echo strtolower(__("verses").": ".join(", ", $tWord["range"])); ?>)<span>
                                </div>
                                <div class="word_def"><?php echo  preg_replace('#<a.*?>(.*?)</a>#i', '<b>\1</b>', $tWord["text"]); ?></div>
                            </li>
                        </ul>
                    </label>
                <?php endforeach; ?>
            </div>
            <div class="word_def_popup">
                <div class="word_def-close glyphicon glyphicon-remove"></div>

                <div class="word_def_title"></div>
                <div class="word_def_content"></div>
            </div>
        </div>
    </div>
<?php endif; ?>