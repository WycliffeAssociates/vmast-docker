<?php
use Helpers\Constants\EventMembers;
use Helpers\Parsedown;

if(isset($data["error"])) return;

$parsedown = new Parsedown();
?>

<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="main_content_title"><?php echo __("step_num", ["step_number" => 1]). ": " . __("multi-draft_full")?></div>
    </div>

    <div class="">
        <div class="main_content">
            <form action="" method="post" id="main_form">
                <div class="main_content_text row" style="padding-left: 15px">
                    <h4 dir="<?php echo $data["event"][0]->sLangDir ?>"><?php echo $data["event"][0]->tLang." - "
                            .__($data["event"][0]->bookProject)." - "
                            .($data["event"][0]->sort <= 39 ? __("old_test") : __("new_test"))." - "
                            ."<span class='book_name'>".$data["event"][0]->name." ".$data["currentChapter"]."</span>"?></h4>

                    <div class="col-sm-12 no_padding questions_bd">
                        <?php foreach($data["chunks"] as $chunkNo => $chunk): $verse = $chunk[0]; ?>
                        <div class="parent_q questions_chunk"
                             data-verse="<?php echo $verse ?>"
                             data-chapter="<?php echo $data["currentChapter"] ?>"
                             data-event="<?php echo $data["event"][0]->eventID ?>">
                            <div class="row">
                                <div class="row buttons_chunk">
                                    <div class="col-md-4" style="color: #00a74d; font-weight: bold;">
                                        <?php echo __("verse_number", $verse) ?>
                                    </div>
                                    <div class="col-md-8">
                                        <label><?php echo __("consume") ?>
                                            <input class="consume_q" type="checkbox"></label>
                                        &nbsp;&nbsp;
                                        <label><?php echo __("verbalize") ?>
                                            <input class="verbalize_q" type="checkbox" disabled></label>
                                        &nbsp;&nbsp;
                                        <label><?php echo __("draft") ?>
                                            <input class="draft_q" type="checkbox" disabled></label>
                                    </div>
                                </div>
                            </div>
                            <div class="flex_container">
                                <div class="flex_left question_content" dir="<?php echo $data["event"][0]->resLangDir ?>">
                                    <?php echo $data["questions"][$verse][0] ?>
                                </div>
                                <div class="flex_middle questions_editor font_<?php echo $data["event"][0]->targetLang ?>"
                                     dir="<?php echo $data["event"][0]->tLangDir ?>" style="min-height: 100px">
                                    <?php
                                    $text = isset($data["translation"][$chunkNo])
                                        ? $parsedown->text($data["translation"][$chunkNo][EventMembers::TRANSLATOR]["verses"])
                                        : "";
                                    ?>
                                    <textarea
                                            name="chunks[<?php echo $chunkNo ?>]"
                                            class="add_questions_editor blind_ta draft_question"><?php echo htmlentities($text) ?></textarea>
                                </div>
                            </div>
                            <div class="chunk_divider" style="margin-top: 10px"></div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                </div>

                <div class="main_content_footer row">
                    <div class="form-group">
                        <div class="main_content_confirm_desc"><?php echo __("confirm_finished")?></div>
                        <label><input name="confirm_step" id="confirm_step" type="checkbox" value="1" /> <?php echo __("confirm_yes")?></label>
                    </div>

                    <button id="next_step" type="submit" name="submit" class="btn btn-primary" disabled>
                        <?php echo __($data["next_step"])?>
                    </button>
                    <img src="<?php echo template_url("img/saving.gif") ?>" class="unsaved_alert" style="float:none">
                </div>
            </form>
            <div class="step_right alt"><?php echo __("step_num", ["step_number" => 1])?></div>
        </div>
    </div>
</div>

<div class="content_help closed">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps">
            <div class="help_name_steps"><span><?php echo __("step_num", ["step_number" => 1])?>:</span> <?php echo __("multi-draft")?></div>
            <div class="help_descr_steps">
                <ul>
                    <li><b><?php echo __("purpose") ?></b> <?php echo __("multi-draft_tq_purpose") ?></li>
                    <li><?php echo __("multi-draft_tq_help_1") ?></li>
                    <li><?php echo __("multi-draft_tq_help_2") ?></li>
                    <li><?php echo __("multi-draft_tq_help_3") ?></li>
                    <li><?php echo __("multi-draft_tq_help_4") ?></li>
                    <li><?php echo __("multi-draft_tq_help_5") ?>
                        <ol>
                            <li><?php echo __("multi-draft_tq_help_5a") ?></li>
                            <li><?php echo __("multi-draft_tq_help_5b") ?></li>
                            <li><?php echo __("blind-draft_tn_help_8", ["icon" => "<i class='note-icon-magic'></i>"]) ?></li>
                            <li><?php echo __("multi-draft_tq_help_5d") ?></li>
                            <li><?php echo __("multi-draft_tq_help_5e") ?></li>
                        </ol>
                    </li>
                    <li><?php echo __("multi-draft_tq_help_6") ?></li>
                    <li><?php echo __("multi-draft_tq_help_7") ?></li>
                    <li><?php echo __("move_to_next_step", ["step" => __($data["next_step"])]) ?></li>
                </ul>
                <div class="show_tutorial_popup"> >>> <?php echo __("show_more")?></div>
            </div>
        </div>

        <div class="event_info">
            <div class="participant_info">
                <div class="additional_info">
                    <a href="/events/information-tq/<?php echo $data["event"][0]->eventID ?>"><?php echo __("event_info") ?></a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="tutorial_container">
    <div class="tutorial_popup">
        <div class="tutorial-close glyphicon glyphicon-remove"></div>
        <div class="tutorial_pic">
            <img src="<?php echo template_url("img/steps/icons/content-review.png") ?>" width="100" height="100">
            <img src="<?php echo template_url("img/steps/big/content-review.png") ?>" width="280" height="280">
        </div>

        <div class="tutorial_content">
            <h3><?php echo __("multi-draft_full")?></h3>
            <ul>
                <li><b><?php echo __("purpose") ?></b> <?php echo __("multi-draft_tq_purpose") ?></li>
                <li><?php echo __("multi-draft_tq_help_1") ?></li>
                <li><?php echo __("multi-draft_tq_help_2") ?></li>
                <li><?php echo __("multi-draft_tq_help_3") ?></li>
                <li><?php echo __("multi-draft_tq_help_4") ?></li>
                <li><?php echo __("multi-draft_tq_help_5") ?>
                    <ol>
                        <li><?php echo __("multi-draft_tq_help_5a") ?></li>
                        <li><?php echo __("multi-draft_tq_help_5b") ?></li>
                        <li><?php echo __("blind-draft_tn_help_8", ["icon" => "<i class='note-icon-magic'></i>"]) ?></li>
                        <li><?php echo __("multi-draft_tq_help_5d") ?></li>
                        <li><?php echo __("multi-draft_tq_help_5e") ?></li>
                    </ol>
                </li>
                <li><?php echo __("multi-draft_tq_help_6") ?></li>
                <li><?php echo __("multi-draft_tq_help_7") ?></li>
                <li><?php echo __("move_to_next_step", ["step" => __($data["next_step"])]) ?></li>
            </ul>
        </div>
    </div>
</div>

<script>
    (function () {
        $("#main_form").submit(function (e) {
            var drafts = $(".draft_q:not(:checked)");
            debug(drafts.length);

            if(drafts.length > 0)
            {
                renderPopup(Language.tq_drafts_not_checked);

                e.preventDefault();
                return false;
            }

            return true;
        });
    })()
</script>