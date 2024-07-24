<?php
use Helpers\Constants\EventMembers;
use Helpers\Constants\EventCheckSteps;

if (isset($error)) return;

$textDirection = $data["event"][0]->tLangDir;
$fontLanguage = $data["event"][0]->targetLang;
$level = 3;
$enableFootNotes = false;
require(app_path() . "Views/Components/CommentEditor.php");
require(app_path() . "Views/Components/HelpTools.php");
?>

<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="main_content_title"><?php echo __("step_num", ["step_number" => $data["event"][0]->step == EventCheckSteps::PEER_REVIEW_L3 ? 1 : 2]) . ": " . __($data["event"][0]->step . "_full")?></div>
    </div>

    <div class="">
        <div class="main_content">
            <div class="main_content_text">
                <?php if($data["event"][0]->checkerFName == null): ?>
                    <div class="alert alert-success check_request"><?php echo __("check_request_sent_success") ?></div>
                <?php endif; ?>

                <h4><?php echo $data["event"][0]->tLang." - "
                        .__($data["event"][0]->bookProject)." - "
                        .($data["event"][0]->sort <= 39 ? __("old_test") : __("new_test"))." - "
                        ."<span class='book_name'>".$data["event"][0]->name." ".
                        $data["currentChapter"].":1-".$data["totalVerses"]."</span>"?></h4>

                    <?php
                    $bookTitleRendered = $data["currentChapter"] > 1;
                    $chapterTitleRendered = false;
                    ?>
                    <div id="my_notes_content" class="my_content">
                    <?php foreach($data["chunks"] as $chunkNo => $chunk): ?>
                        <div class="note_chunk l3">
                            <div class="flex_container">
                                <div class="scripture_compare_alt flex_left" dir="<?php echo $data["event"][0]->sLangDir ?>">
                                    <?php $firstVerse = 0; ?>
                                    <?php foreach ($chunk as $verse): ?>
                                        <?php
                                        if (!isset($data["text"][$verse])) {
                                            if($firstVerse == 0) {
                                                $firstVerse = $verse;
                                                if (!$bookTitleRendered) {
                                                    echo "<p class='book_title_alt'>".$data["bookTitle"]."</p>";
                                                    $bookTitleRendered = true;
                                                } elseif (!$chapterTitleRendered) {
                                                    echo "<p class='chapter_title_alt'>".$data["chapterTitle"]."</p>";
                                                    $chapterTitleRendered = true;
                                                }
                                                continue;
                                            }

                                            // process combined verses
                                            $combinedVerse = $firstVerse . "-" . $verse;

                                            if(!isset($data["text"][$combinedVerse]))
                                                continue;
                                            $verse = $combinedVerse;
                                        }
                                        ?>
                                        <p>
                                            <?php if ($verse > 0): ?>
                                                <strong class="<?php echo $data["event"][0]->sLangDir ?>"><sup><?php echo $verse; ?></sup></strong>
                                            <?php endif; ?>
                                            <span><?php echo $data["text"][$verse]; ?></span>
                                        </p>
                                    <?php endforeach; ?>
                                </div>
                                <div class="vnote l3 flex_middle font_<?php echo $data["event"][0]->targetLang ?>"
                                     dir="<?php echo $data["event"][0]->tLangDir ?>">
                                    <?php
                                    if(!empty($data["translation"][$chunkNo][EventMembers::L3_CHECKER]["verses"]))
                                        $verses = $data["translation"][$chunkNo][EventMembers::L3_CHECKER]["verses"];
                                    else
                                        $verses = $data["translation"][$chunkNo][EventMembers::L2_CHECKER]["verses"];
                                    ?>
                                    <?php foreach($verses as $verse => $text): ?>
                                        <div class="verse_block">
                                            <p>
                                                <?php if ($verse > 0): ?>
                                                    <strong class="<?php echo $data["event"][0]->sLangDir ?>"><sup><?php echo $verse; ?></sup></strong>
                                                <?php endif; ?>
                                                <span class="targetVerse" data-orig-verse="<?php echo $verse ?>"><?php echo preg_replace("/(\\\\f(?:.*?)\\\\f\\*)/", "<span class='footnote'>$1</span>", $text); ?></span>
                                            </p>
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
                <form id="<?php echo $data["isChecker"] ? "checker_submit" : "main_form" ?>" action="" method="post">
                    <div class="form-group">
                        <div class="main_content_confirm_desc"><?php echo __("confirm_finished")?></div>
                        <label><input name="confirm_step" id="confirm_step" type="checkbox" value="1" /> <?php echo __("confirm_yes")?></label>
                    </div>

                    <input type="hidden" name="step" value="<?php echo $data["event"][0]->step ?>">
                    <?php if ($data["isChecker"]): ?>
                        <button id="checker_ready" class="btn btn-warning" disabled>
                            <?php echo __("ready_to_check")?>
                        </button>
                        <input type="hidden" class="event_data_to" value="<?php echo $data["event"][0]->memberID ?>" />
                        <input type="hidden" class="event_data_step" value="<?php echo $data["event"][0]->step ?>" />
                        <input type="hidden" class="event_data_chapter" value="<?php echo $data["event"][0]->currentChapter ?>" />
                        <input type="hidden" class="event_data_manage" value="<?php echo $data["event"][0]->manageMode ?>" />
                    <?php endif; ?>
                    <button id="next_step" type="submit" name="submit" class="btn btn-primary" disabled>
                        <?php echo __($data["next_step"])?>
                    </button>
                    <img src="<?php echo template_url("img/loader.gif") ?>" class="ready_loader">
                </form>
            </div>
            <div class="step_right alt">
                <?php echo __("step_num", ["step_number" => $data["event"][0]->step == EventCheckSteps::PEER_REVIEW_L3 ? 1 : 2])?>
            </div>
        </div>
    </div>
</div>

<div class="content_help closed">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps <?php echo isset($data["isCheckerPage"]) ? "is_checker_page_help" : "is_checker_page_help isPeer" ?>">
            <div class="help_name_steps">
                <span><?php echo __("step_num", ["step_number" => $data["event"][0]->step == EventCheckSteps::PEER_REVIEW_L3 ? 1 : 2])?>: </span>
                <?php echo __($data["event"][0]->step)?>
            </div>
            <div class="help_descr_steps">
                <ul>
                    <?php if ($data["isChecker"]): ?>
                        <ul>
                            <li><b><?php echo __("purpose") ?></b> <?php echo __("self-check_l2_purpose") ?></li>
                            <li><?php echo __("peer-edit-l3_help_1") ?></li>
                            <li><?php echo __("peer-edit-l3_help_2") ?></li>
                            <li><?php echo __("peer-edit-l3_help_3", ["icon" => "<span class='mdi mdi-lead-pencil'></span>"]) ?></li>
                            <li><?php echo __("peer-edit-l3_help_4") ?></li>
                            <li><?php echo __("peer-edit-l3_help_5") ?></li>
                            <li><?php echo __("peer-edit-l3_chk_help_6") ?></li>
                            <li><?php echo __("peer-review_help_5") ?>
                                <ol>
                                    <li><?php echo __("self-check_help_5a") ?></li>
                                    <li><?php echo __("keyword-check_help_4a") ?></li>
                                    <li><?php echo __("self-check_help_5b") ?></li>
                                    <li><?php echo __("self-check_help_5c") ?></li>
                                </ol>
                            </li>
                            <li><?php echo __("check_footnote_help_1") ?>
                                <ol>
                                    <li><?php echo __("check_footnote_help_1a") ?></li>
                                    <li><?php echo __("check_footnote_help_1b") ?></li>
                                    <li><?php echo __("check_footnote_help_1c") ?></li>
                                    <li><?php echo __("check_footnote_help_1d") ?></li>
                                    <li><?php echo __("check_footnote_help_1e") ?></li>
                                </ol>
                            </li>
                            <li><?php echo __("move_to_next_step_alt", ["step" => __($data["next_step"])]) ?></li>
                            <li><b><?php echo __("peer-edit-l3_help_8") ?></b></li>
                        </ul>
                    <?php else: ?>
                        <ul>
                            <li><b><?php echo __("purpose") ?></b> <?php echo __("self-check_l2_purpose") ?></li>
                            <li><?php echo __("peer-review-l3_help_1") ?></li>
                            <li><?php echo __("peer-review-l3_help_2") ?></li>
                            <li><?php echo __("peer-review_checker_help_2") ?></li>
                            <li><?php echo __("peer-review-l3_help_4") ?></li>
                            <li><?php echo __("peer-review_checker_help_6") ?></li>
                            <li><?php echo __("peer-review-l3_help_6") ?></li>
                            <li><?php echo __("peer-review-l3_help_7") ?>
                                <ol>
                                    <li><?php echo __("peer-review-l3_help_7a", ["icon" => "<span class='mdi mdi-lead-pencil'></span>"]) ?></li>
                                    <li><?php echo __("peer-review-l3_help_7b", ["icon" => "<span class='mdi mdi-lead-pencil'></span>"]) ?></li>
                                    <li><?php echo __("peer-review-l3_help_7c") ?></li>
                                </ol>
                            </li>
                            <li><?php echo __("peer-review_help_5") ?>
                                <ol>
                                    <li><?php echo __("self-check_help_5a") ?></li>
                                    <li><?php echo __("keyword-check_help_4a") ?></li>
                                    <li><?php echo __("self-check_help_5b") ?></li>
                                    <li><?php echo __("self-check_help_5c") ?></li>
                                </ol>
                            </li>
                            <li><?php echo __("check_footnote_help_1") ?>
                                <ol>
                                    <li><?php echo __("check_footnote_help_1a") ?></li>
                                    <li><?php echo __("check_footnote_help_1b") ?></li>
                                    <li><?php echo __("check_footnote_help_1c") ?></li>
                                    <li><?php echo __("check_footnote_help_1d") ?></li>
                                    <li><?php echo __("check_footnote_help_1e") ?></li>
                                </ol>
                            </li>
                            <li><?php echo __("move_to_next_step_alt", ["step" => __($data["next_step"])]) ?></li>
                        </ul>
                    <?php endif; ?>
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
            <h3><?php echo __($data["event"][0]->step . "_full")?></h3>
            <?php if ($data["isChecker"]): ?>
                <ul>
                    <li><b><?php echo __("purpose") ?></b> <?php echo __("self-check_l2_purpose") ?></li>
                    <li><?php echo __("peer-edit-l3_help_1") ?></li>
                    <li><?php echo __("peer-edit-l3_help_2") ?></li>
                    <li><?php echo __("peer-edit-l3_help_3", ["icon" => "<span class='mdi mdi-lead-pencil'></span>"]) ?></li>
                    <li><?php echo __("peer-edit-l3_help_4") ?></li>
                    <li><?php echo __("peer-edit-l3_help_5") ?></li>
                    <li><?php echo __("peer-edit-l3_chk_help_6") ?></li>
                    <li><?php echo __("peer-review_help_5") ?>
                        <ol>
                            <li><?php echo __("self-check_help_5a") ?></li>
                            <li><?php echo __("keyword-check_help_4a") ?></li>
                            <li><?php echo __("self-check_help_5b") ?></li>
                            <li><?php echo __("self-check_help_5c") ?></li>
                        </ol>
                    </li>
                    <li><?php echo __("check_footnote_help_1") ?>
                        <ol>
                            <li><?php echo __("check_footnote_help_1a") ?></li>
                            <li><?php echo __("check_footnote_help_1b") ?></li>
                            <li><?php echo __("check_footnote_help_1c") ?></li>
                            <li><?php echo __("check_footnote_help_1d") ?></li>
                            <li><?php echo __("check_footnote_help_1e") ?></li>
                        </ol>
                    </li>
                    <li><?php echo __("move_to_next_step_alt", ["step" => __($data["next_step"])]) ?></li>
                    <li><b><?php echo __("peer-edit-l3_help_8") ?></b></li>
                </ul>
            <?php else: ?>
                <ul>
                    <li><b><?php echo __("purpose") ?></b> <?php echo __("self-check_l2_purpose") ?></li>
                    <li><?php echo __("peer-review-l3_help_1") ?></li>
                    <li><?php echo __("peer-review-l3_help_2") ?></li>
                    <li><?php echo __("peer-review_checker_help_2") ?></li>
                    <li><?php echo __("peer-review-l3_help_4") ?></li>
                    <li><?php echo __("peer-review_checker_help_6") ?></li>
                    <li><?php echo __("peer-review-l3_help_6") ?></li>
                    <li><?php echo __("peer-review-l3_help_7") ?>
                        <ol>
                            <li><?php echo __("peer-review-l3_help_7a", ["icon" => "<span class='mdi mdi-lead-pencil'></span>"]) ?></li>
                            <li><?php echo __("peer-review-l3_help_7b", ["icon" => "<span class='mdi mdi-lead-pencil'></span>"]) ?></li>
                            <li><?php echo __("peer-review-l3_help_7c") ?></li>
                        </ol>
                    </li>
                    <li><?php echo __("peer-review_help_5") ?>
                        <ol>
                            <li><?php echo __("self-check_help_5a") ?></li>
                            <li><?php echo __("keyword-check_help_4a") ?></li>
                            <li><?php echo __("self-check_help_5b") ?></li>
                            <li><?php echo __("self-check_help_5c") ?></li>
                        </ol>
                    </li>
                    <li><?php echo __("check_footnote_help_1") ?>
                        <ol>
                            <li><?php echo __("check_footnote_help_1a") ?></li>
                            <li><?php echo __("check_footnote_help_1b") ?></li>
                            <li><?php echo __("check_footnote_help_1c") ?></li>
                            <li><?php echo __("check_footnote_help_1d") ?></li>
                            <li><?php echo __("check_footnote_help_1e") ?></li>
                        </ol>
                    </li>
                    <li><?php echo __("move_to_next_step_alt", ["step" => __($data["next_step"])]) ?></li>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    var disableHighlight = true;

    <?php if($data["isChecker"]): ?>
    $("#next_step").click(function (e) {
        if(typeof step != "undefined" && step == EventCheckSteps.PEER_EDIT_L3)
        {
            renderConfirmPopup(Language.checkerConfirmTitle, Language.checkerConfirm,
                function () {
                    $("#checker_submit").submit();
                    $( this ).dialog("close");
                },
                function () {
                    $("#confirm_step").prop("checked", false);
                    $("#next_step").prop("disabled", true);
                    $("#checker_ready").text(Language.ready_to_check);
                    $( this ).dialog("close");
                },
                function () {
                    $("#confirm_step").prop("checked", false);
                    $("#next_step").prop("disabled", true);
                    $("#checker_ready").text(Language.ready_to_check);
                    $( this ).dialog("close");
                });
        }
        else
        {
            $("#checker_submit").submit();
        }
        e.preventDefault();
    });
    <?php endif; ?>
</script>