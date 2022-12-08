<?php
if(isset($data["error"])) return;

use Helpers\Constants\EventMembers;
?>
<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="main_content_title"><?php echo __("step_num", ["step_number" => 1]) . ": " . __("consume")?></div>
    </div>

    <div class="">
        <div class="main_content">
            <div class="main_content_text" dir="<?php echo $data["event"][0]->sLangDir ?>">
                <h4><?php echo $data["event"][0]->tLang." - "
                        .__($data["event"][0]->bookProject)." - "
                    .($data["event"][0]->sort <= 39 ? __("old_test") : __("new_test"))." - "
                    ."<span class='book_name'>".$data["event"][0]->name." ".$data["currentChapter"].":1-".$data["totalVerses"]."</span>"?></h4>

                <ul class="nav nav-tabs">
                    <li role="presentation" id="target_scripture" class="my_tab">
                        <a href="#"><?php echo __("target_text") ?></a>
                    </li>
                    <li role="presentation" id="source_scripture" class="my_tab">
                        <a href="#"><?php echo __("source_text") ?></a>
                    </li>
                </ul>

                <div id="target_scripture_content" class="my_content shown">
                    <?php foreach ($data["translation"] as $translation): ?>
                        <?php foreach ($translation[EventMembers::TRANSLATOR]["verses"] as $verse => $text): ?>
                            <p class="target_content font_<?php echo $data["event"][0]->targetLang ?>"><?php echo "<strong><sup>".$verse."</sup></strong> ".preg_replace("/(\\\\f(?:.*?)\\\\f\\*)/", "<span class='footnote'>$1</span>", $text); ?></p>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>

                <div id="source_scripture_content" class="my_content">
                    <?php foreach($data["text"] as $verse => $text): ?>
                        <p><?php echo "<strong><sup>".$verse."</sup></strong> ".$text; ?></p>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="main_content_footer row">
                <form action="" method="post">
                    <div class="form-group">
                        <div class="main_content_confirm_desc"><?php echo __("confirm_finished")?></div>
                        <label><input name="confirm_step" id="confirm_step" type="checkbox" value="1" /> <?php echo __("confirm_yes")?></label>
                    </div>

                    <button id="next_step" type="submit" name="submit" class="btn btn-primary" disabled>
                        <?php echo __($data["next_step"])?>
                    </button>
                </form>
                <div class="step_right"><?php echo __("step_num", ["step_number" => 1])?></div>
            </div>
        </div>
    </div>
</div>

<div class="content_help closed">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps is_checker_page_help">
            <div class="help_name_steps"><span><?php echo __("step_num", ["step_number" => 1])?>: </span><?php echo __("consume")?></div>
            <div class="help_descr_steps">
                <ul>
                    <li><b><?php echo __("purpose") ?></b> <?php echo __("consume_l2_purpose") ?></li>
                    <li><b><?php echo __("length") ?></b> <?php echo __("consume_l2_length") ?></li>
                    <li><?php echo __("consume_l2_help_1") ?>
                        <ol>
                            <li><?php echo __("consume_l2_help_1a") ?></li>
                        </ol>
                    </li>
                    <li><?php echo __("consume_tn_help_2") ?></li>
                    <li><?php echo __("consume_help_3", ["icon" => "<span class='mdi mdi-bookmark'></span>"]) ?></li>
                    <li><?php echo __("move_to_next_step", ["step" => __($data["next_step"])]) ?></li>
                </ul>
                <div class="show_tutorial_popup"> >>> <?php echo __("show_more")?></div>
            </div>
        </div>

        <div class="event_info is_checker_page_help">
            <div class="participant_info">
                <div class="additional_info">
                    <a href="/events/information-revision/<?php echo $data["event"][0]->eventID ?>"><?php echo __("event_info") ?></a>
                </div>
            </div>
        </div>

        <div class="tr_tools">

            <?php if (str_contains($data["event"][0]->targetLang, "sgn")): ?>
                <button class="btn btn-warning ttools" data-tool="saildict"><?php echo __("show_dictionary") ?></button>
            <?php else: ?>
                <button class="btn btn-warning ttools" data-tool="rubric"><?php echo __("show_rubric") ?></button>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="tutorial_container">
    <div class="tutorial_popup">
        <div class="tutorial-close glyphicon glyphicon-remove"></div>
        <div class="tutorial_pic">
            <img src="<?php echo template_url("img/steps/icons/consume.png") ?>" width="100px" height="100px">
            <img src="<?php echo template_url("img/steps/big/consume.png") ?>" width="280px" height="280px">
        </div>

        <div class="tutorial_content">
            <h3><?php echo __("consume")?></h3>
            <ul>
                <li><b><?php echo __("purpose") ?></b> <?php echo __("consume_l2_purpose") ?></li>
                <li><b><?php echo __("length") ?></b> <?php echo __("consume_l2_length") ?></li>
                <li><?php echo __("consume_l2_help_1") ?>
                    <ol>
                        <li><?php echo __("consume_l2_help_1a") ?></li>
                    </ol>
                </li>
                <li><?php echo __("consume_tn_help_2") ?></li>
                <li><?php echo __("consume_help_3", ["icon" => "<span class='mdi mdi-bookmark'></span>"]) ?></li>
                <li><?php echo __("move_to_next_step", ["step" => __($data["next_step"])]) ?></li>
            </ul>
        </div>
    </div>
</div>
