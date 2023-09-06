<?php

use Helpers\Constants\EventMembers;
use Helpers\Constants\EventSteps;

if(isset($data["error"])) return;
?>

<?php
$textDirection = $data["event"][0]->tLangDir;
$fontLanguage = $data["event"][0]->targetLang;
require(app_path() . "Views/Components/CommentEditor.php");
require(app_path() . "Views/Components/FootnotesEditor.php");
require(app_path() . "Views/Components/HelpTools.php");
?>

<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="action_type_container">
            <div class="action_type type_translation"><?php echo __("type_translation"); ?></div>
        </div>
        <div class="main_content_title"><?php echo __("step_num", ["step_number" => 3]). ": " . __(EventSteps::PEER_REVIEW)?></div>
    </div>

    <div class="">
        <div class="main_content">
            <form action="" method="post" id="main_form">
                <div class="main_content_text row" style="padding-left: 15px" dir="<?php echo $data["event"][0]->sLangDir ?>">
                    <?php if($data["event"][0]->checkerID == 0): ?>
                        <div class="alert alert-success check_request"><?php echo __("check_request_sent_success") ?></div>
                    <?php endif; ?>

                    <h4 dir="<?php echo $data["event"][0]->sLangDir ?>"><?php echo $data["event"][0]->tLang." - "
                            .__($data["event"][0]->bookProject)." - "
                            .($data["event"][0]->sort <= 39 ? __("old_test") : __("new_test"))." - "
                            ."<span class='book_name'>".$data["event"][0]->name." ".$data["currentChapter"].":1-".$data["chunks"][sizeof($data["chunks"])-1][0]."</span>"?></h4>

                    <div class="no_padding">
                        <?php foreach($data["chunks"] as $key => $chunk) : ?>
                            <div class="row chunk_block">
                                <div class="flex_container">
                                    <div class="flex_left flex_column">
                                        <?php
                                        $firstVerse = 0;
                                        $verses = $data["translation"][$key][EventMembers::TRANSLATOR]["verses"];

                                        foreach ($chunk as $verse): ?>
                                            <?php
                                            // process combined verses
                                            if (!isset($data["text"][$verse])) {
                                                if($firstVerse == 0) {
                                                    $firstVerse = $verse;
                                                    continue;
                                                }
                                                $combinedVerse = $firstVerse . "-" . $verse;

                                                if(!isset($data["text"][$combinedVerse]))
                                                    continue;
                                                $verse = $combinedVerse;
                                            }
                                            ?>
                                            <div class="flex_sub_container">
                                                <div class="flex_one chunk_verses font_<?php echo $data["event"][0]->sourceLangID ?>" dir="<?php echo $data["event"][0]->sLangDir ?>">
                                                    <p class="verse_text <?php echo "kwverse_".$data["currentChapter"]."_".$key."_".$verse ?>"
                                                       data-verse="<?php echo $verse ?>">
                                                        <strong><sup><?php echo $verse; ?></sup></strong>
                                                        <span><?php echo $data["text"][$verse]; ?></span>
                                                    </p>
                                                </div>
                                                <div class="flex_one editor_area font_<?php echo $data["event"][0]->targetLang ?>" dir="<?php echo $data["event"][0]->tLangDir ?>">
                                                    <div class="vnote">
                                                        <div class="verse_block flex_chunk" data-verse="<?php echo $verse ?>">
                                                            <textarea name="verses[<?php echo $verse ?>]"
                                                                      class="input_mode_ta textarea"><?php echo $verses[$verse]; ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="flex_right">
                                        <?php
                                        $commentChunk = $data["currentChapter"].":".$key;
                                        $hasComments = array_key_exists($data["currentChapter"], $data["comments"]) && array_key_exists($key, $data["comments"][$data["currentChapter"]]);
                                        if ($hasComments) {
                                            $comments = $data["comments"][$data["currentChapter"]][$key];
                                            $commentsNumber = sizeof(array_filter($comments, function($item) { return $item->saved; }));
                                        }
                                        require(app_path() . "Views/Components/Comments.php");
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="chunk_divider"></div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="main_content_footer row">
                    <div class="form-group">
                        <div class="main_content_confirm_desc"><?php echo __("confirm_finished")?></div>
                        <label><input name="confirm_step" id="confirm_step" type="checkbox" value="1" /> <?php echo __("confirm_yes")?></label>
                    </div>

                    <input type="hidden" name="checkingChapter" value="<?php echo $data["event"][0]->currentChapter ?>" />
                    <input type="hidden" name="isChecking" value="1" />

                    <button id="next_step" type="submit" name="submit" class="btn btn-primary" disabled>
                        <?php echo __($data["next_step"])?>
                    </button>
                    <img src="<?php echo template_url("img/saving.gif") ?>" class="unsaved_alert">
                </div>
            </form>
            <div class="step_right alt"><?php echo __("step_num", ["step_number" => 3])?></div>
        </div>
    </div>
</div>

<div class="content_help closed">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps">
            <div class="help_name_steps"><span><?php echo __("step_num", ["step_number" => 3])?>:</span> <?php echo __(EventSteps::PEER_REVIEW)?></div>
            <div class="help_descr_steps">
                <ul>
                    <li><b><?php echo __("purpose") ?></b> <?php echo __("peer-review_purpose") ?></li>
                    <li><b><?php echo __("length") ?></b> <?php echo __("peer-review_length") ?></li>
                    <li><b><?php echo __("self-check_help_1") ?></b></li>
                    <li><?php echo __("self-check_help_2") ?></li>
                    <li><?php echo __("peer-review_help_3") ?></li>
                    <li><?php echo __("peer-review_help_4") ?></li>
                    <li><?php echo __("peer-review_help_5") ?>
                        <ol>
                            <li><?php echo __("self-check_help_5a") ?></li>
                            <li><?php echo __("self-check_help_5b") ?></li>
                            <li><?php echo __("self-check_help_5c") ?></li>
                        </ol>
                    </li>
                    <li><?php echo __("peer-review_help_6") ?></li>
                    <li><?php echo __("peer-review_help_7") ?></li>
                    <li><?php echo __("peer-review_help_8", ["icon" => "<span class='mdi mdi-lead-pencil'></span>"]) ?></li>
                    <li><?php echo __("move_to_next_step_alt", ["step" => __($data["next_step"])]) ?></li>
                </ul>
                <div class="show_tutorial_popup"> >>> <?php echo __("show_more")?></div>
            </div>
        </div>

        <div class="event_info">
            <div class="participant_info">
                <div class="participant_name">
                    <span><?php echo __("your_checker") ?>:</span>
                    <span class="checker_name_span">
                        <?php
                        if (!empty($data["event"][0]->checkers)) {
                            $checkers = array_map(function ($item) {
                                return $item["name"];
                            }, $data["event"][0]->checkers);
                            echo join(", ", $checkers);
                        } else {
                            echo __("not_available");
                        }
                        ?>
                    </span>
                </div>
                <div class="additional_info">
                    <a href="/events/information/<?php echo $data["event"][0]->eventID ?>"><?php echo __("event_info") ?></a>
                </div>
            </div>
        </div>

        <div class="tr_tools">
            <?php
            renderTn($data["event"][0]->tnLangID);
            renderTq($data["event"][0]->tqLangID);
            renderBc($data["event"][0]->bcLangID);
            renderRubric();
            ?>
        </div>
    </div>
</div>

<div class="tutorial_container">
    <div class="tutorial_popup">
        <div class="tutorial-close glyphicon glyphicon-remove"></div>
        <div class="tutorial_pic">
            <img src="<?php echo template_url("img/steps/icons/peer-review.png") ?>" width="100" height="100">
            <img src="<?php echo template_url("img/steps/big/peer-review.png") ?>" width="280" height="280">
        </div>

        <div class="tutorial_content">
            <h3><?php echo __(EventSteps::PEER_REVIEW)?></h3>
            <ul>
                <li><b><?php echo __("purpose") ?></b> <?php echo __("peer-review_purpose") ?></li>
                <li><b><?php echo __("length") ?></b> <?php echo __("peer-review_length") ?></li>
                <li><b><?php echo __("self-check_help_1") ?></b></li>
                <li><?php echo __("self-check_help_2") ?></li>
                <li><?php echo __("peer-review_help_3") ?></li>
                <li><?php echo __("peer-review_help_4") ?></li>
                <li><?php echo __("peer-review_help_5") ?>
                    <ol>
                        <li><?php echo __("self-check_help_5a") ?></li>
                        <li><?php echo __("self-check_help_5b") ?></li>
                        <li><?php echo __("self-check_help_5c") ?></li>
                    </ol>
                </li>
                <li><?php echo __("peer-review_help_6") ?></li>
                <li><?php echo __("peer-review_help_7") ?></li>
                <li><?php echo __("peer-review_help_8", ["icon" => "<span class='mdi mdi-lead-pencil'></span>"]) ?></li>
                <li><?php echo __("move_to_next_step_alt", ["step" => __($data["next_step"])]) ?></li>
            </ul>
        </div>
    </div>
</div>