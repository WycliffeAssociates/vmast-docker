<?php
use Helpers\Constants\EventMembers;
use Helpers\Session;

if (isset($error)) return;

$textDirection = $data["event"][0]->tLangDir;
$fontLanguage = $data["event"][0]->targetLang;
$level = 3;
$enableFootNotes = false;
require(app_path() . "Views/Components/CommentEditor.php");
require(app_path() . "Views/Components/FootnotesEditor.php");
require(app_path() . "Views/Components/HelpTools.php");
?>

<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="main_content_title"><?php echo __("step_num", ["step_number" => 2]) . ": " . __("peer-edit-l3_full")?></div>
    </div>

    <div class="">
        <div class="main_content">
            <form action="" id="main_form" method="post">
                <div class="main_content_text">
                    <?php if($data["event"][0]->checkerFName == null): ?>
                        <div class="alert alert-success check_request"><?php echo __("check_request_sent_success") ?></div>
                    <?php endif; ?>

                    <h4><?php echo $data["event"][0]->tLang." - "
                            .__($data["event"][0]->bookProject)." - "
                            .($data["event"][0]->sort <= 39 ? __("old_test") : __("new_test"))." - "
                            ."<span class='book_name'>".$data["event"][0]->name." ".
                            ($data["currentChapter"] > 0
                                ? $data["currentChapter"].":1-".$data["totalVerses"]
                                : __("front"))."</span>"?></h4>

                        <div id="my_notes_content" class="my_content">
                        <?php foreach($data["chunks"] as $chunkNo => $chunk): ?>
                            <div class="note_chunk l3">
                                <div class="flex_container">
                                    <div class="flex_left">
                                        <?php
                                        $firstVerse = 0;

                                        if(!empty($data["translation"][$chunkNo][EventMembers::L3_CHECKER]["verses"]))
                                            $verses = $data["translation"][$chunkNo][EventMembers::L3_CHECKER]["verses"];
                                        else
                                            $verses = $data["translation"][$chunkNo][EventMembers::L2_CHECKER]["verses"];

                                        foreach ($chunk as $verse): ?>
                                            <?php
                                            // process combined verses
                                            if (!isset($data["text"][$verse]))
                                            {
                                                if($firstVerse == 0)
                                                {
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
                                                <div class="flex_one scripture_compare_alt" dir="<?php echo $data["event"][0]->sLangDir ?>">
                                                    <p class="verse_text" data-verse="<?php echo $verse; ?>">
                                                        <strong class="<?php echo $data["event"][0]->sLangDir ?>"><sup><?php echo $verse; ?></sup></strong>
                                                        <span><?php echo $data["text"][$verse]; ?></span>
                                                    </p>
                                                </div>
                                                <div class="flex_one vnote l3 font_<?php echo $data["event"][0]->targetLang ?>">
                                                    <div class="verse_block flex_chunk" data-verse="<?php echo $verse; ?>">
                                                        <span class="verse_number_l3"><?php echo $verse?></span>
                                                        <textarea name="chunks[<?php echo $chunkNo ?>][<?php echo $verse ?>]"
                                                                  style="min-width: 400px; flex-grow: 1;"
                                                                  class="peer_verse_ta textarea"
                                                                  data-orig-verse="<?php echo $verse ?>"><?php echo $verses[$verse]; ?></textarea>

                                                        <span class="editFootNote mdi mdi-bookmark"
                                                              style="margin-top: -5px"
                                                              title="<?php echo __("write_footnote_title") ?>"></span>
                                                    </div>
                                                </div>
                                            </div>

                                        <?php endforeach; ?>
                                    </div>
                                    <div class="flex_right">
                                        <?php
                                        $commentChunk = $data["currentChapter"].":".$chunkNo;
                                        $hasComments = array_key_exists($data["currentChapter"], $data["comments"]) && array_key_exists($chunkNo, $data["comments"][$data["currentChapter"]]);
                                        if ($hasComments) {
                                            $comments = $data["comments"][$data["currentChapter"]][$chunkNo];
                                            $commentsNumber = sizeof(array_filter($comments, function($item) { return $item->saved; }));
                                        }
                                        require(app_path() . "Views/Components/Comments.php");
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        </div>
                </div>

                <div class="main_content_footer row">
                    <div class="form-group">
                        <div class="main_content_confirm_desc"><?php echo __("confirm_finished")?></div>
                        <label><input name="confirm_step" id="confirm_step" type="checkbox" value="1" /> <?php echo __("confirm_yes")?></label>
                    </div>

                    <input type="hidden" name="step" value="<?php echo $data["event"][0]->step ?>">
                    <input type="hidden" name="level" value="l3">

                    <button id="next_step" type="submit" name="submit" class="btn btn-primary" disabled>
                        <?php echo __($data["next_step"])?>
                    </button>
                    <img src="<?php echo template_url("img/saving.gif") ?>" class="unsaved_alert">
                </div>
            </form>
            <div class="step_right alt"><?php echo __("step_num", ["step_number" => 2])?></div>
        </div>
    </div>
</div>

<div class="content_help closed">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps <?php echo isset($data["isCheckerPage"]) ? "is_checker_page_help" : "is_checker_page_help isPeer" ?>">
            <div class="help_name_steps">
                <span><?php echo __("step_num", ["step_number" => 2])?>: </span>
                <?php echo __("peer-edit-l3")?>
            </div>
            <div class="help_descr_steps">
                <ul>
                    <li><b><?php echo __("purpose") ?></b> <?php echo __("self-check_l2_purpose") ?></li>
                    <li><?php echo __("peer-edit-l3_help_1") ?></li>
                    <li><?php echo __("peer-edit-l3_help_2") ?></li>
                    <li><?php echo __("peer-edit-l3_help_3", ["icon" => "<span class='mdi mdi-lead-pencil'></span>"]) ?></li>
                    <li><?php echo __("peer-edit-l3_help_4") ?></li>
                    <li><?php echo __("peer-edit-l3_help_5") ?></li>
                    <li><?php echo __("peer-edit-l3_help_6") ?></li>
                    <li><?php echo __("peer-review_help_5") ?>
                        <ol>
                            <li><?php echo __("self-check_help_5a") ?></li>
                            <li><?php echo __("keyword-check_help_4a") ?></li>
                            <li><?php echo __("self-check_help_5b") ?></li>
                            <li><?php echo __("self-check_help_5c") ?></li>
                        </ol>
                    </li>
                    <li><?php echo __("edit_footnote_help_1") ?>
                        <ol>
                            <li><?php echo __("edit_footnote_help_1a") ?></li>
                            <li><?php echo __("edit_footnote_help_1b", ["icon" => "<i class='mdi mdi-bookmark'></i>"]) ?></li>
                            <li><?php echo __("edit_footnote_help_1c") ?></li>
                            <li><?php echo __("edit_footnote_help_1d") ?></li>
                            <li><?php echo __("edit_footnote_help_1e") ?></li>
                            <li><?php echo __("edit_footnote_help_1f") ?></li>
                            <li><?php echo __("edit_footnote_help_1g", ["icon" => "<i class='mdi mdi-bookmark'></i>"]) ?></li>
                            <li><?php echo __("edit_footnote_help_1h") ?></li>
                        </ol>
                    </li>
                    <li><?php echo __("move_to_next_step_alt", ["step" => __($data["next_step"])]) ?></li>
                    <li><b><?php echo __("peer-edit-l3_help_8") ?></b></li>
                </ul>
                <div class="show_tutorial_popup"> >>> <?php echo __("show_more")?></div>
            </div>
        </div>

        <div class="event_info <?php echo isset($data["isCheckerPage"]) ? "is_checker_page_help" : "is_checker_page_help isPeer" ?>">
            <div class="participant_info">
                <div class="participant_name">
                    <span><?php echo __("your_checker") ?>:</span>
                    <span class="checker_name_span">
                                <?php echo $data["event"][0]->checkerFName !== null
                                    ? $data["event"][0]->checkerFName . " "
                                    . mb_substr($data["event"][0]->checkerLName, 0, 1)."."
                                    : __("not_available") ?>
                            </span>
                </div>
                <div class="additional_info">
                    <a href="/events/information-review/<?php echo $data["event"][0]->eventID ?>"><?php echo __("event_info") ?></a>
                </div>
            </div>
        </div>

        <div class="tr_tools">
            <?php
            renderTn($data["event"][0]->tnLangID);
            renderTq($data["event"][0]->tqLangID);
            renderTw($data["event"][0]->twLangID);
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
            <img src="<?php echo template_url("img/steps/icons/peer-review.png") ?>" width="100px" height="100px">
            <img src="<?php echo template_url("img/steps/big/peer-review.png") ?>" width="280px" height="280px">
        </div>

        <div class="tutorial_content <?php echo "is_checker_page_help" ?>">
            <h3><?php echo __("peer-edit-l3_full")?></h3>
            <ul>
                <li><b><?php echo __("purpose") ?></b> <?php echo __("self-check_l2_purpose") ?></li>
                <li><?php echo __("peer-edit-l3_help_1") ?></li>
                <li><?php echo __("peer-edit-l3_help_2") ?></li>
                <li><?php echo __("peer-edit-l3_help_3", ["icon" => "<span class='mdi mdi-lead-pencil'></span>"]) ?></li>
                <li><?php echo __("peer-edit-l3_help_4") ?></li>
                <li><?php echo __("peer-edit-l3_help_5") ?></li>
                <li><?php echo __("peer-edit-l3_help_6") ?></li>
                <li><?php echo __("peer-review_help_5") ?>
                    <ol>
                        <li><?php echo __("self-check_help_5a") ?></li>
                        <li><?php echo __("keyword-check_help_4a") ?></li>
                        <li><?php echo __("self-check_help_5b") ?></li>
                        <li><?php echo __("self-check_help_5c") ?></li>
                    </ol>
                </li>
                <li><?php echo __("edit_footnote_help_1") ?>
                    <ol>
                        <li><?php echo __("edit_footnote_help_1a") ?></li>
                        <li><?php echo __("edit_footnote_help_1b", ["icon" => "<i class='mdi mdi-bookmark'></i>"]) ?></li>
                        <li><?php echo __("edit_footnote_help_1c") ?></li>
                        <li><?php echo __("edit_footnote_help_1d") ?></li>
                        <li><?php echo __("edit_footnote_help_1e") ?></li>
                        <li><?php echo __("edit_footnote_help_1f") ?></li>
                        <li><?php echo __("edit_footnote_help_1g", ["icon" => "<i class='mdi mdi-bookmark'></i>"]) ?></li>
                        <li><?php echo __("edit_footnote_help_1h") ?></li>
                    </ol>
                </li>
                <li><?php echo __("move_to_next_step_alt", ["step" => __($data["next_step"])]) ?></li>
                <li><b><?php echo __("peer-edit-l3_help_8") ?></b></li>
            </ul>
        </div>
    </div>
</div>
