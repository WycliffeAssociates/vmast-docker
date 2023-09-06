<?php
use \Helpers\Constants\EventMembers;
use \Helpers\Parsedown;

if(isset($data["error"])) return;

$parsedown = new Parsedown();
$textDirection = $data["event"][0]->tLangDir;
$fontLanguage = $data["event"][0]->targetLang;
$level = 2;
$enableFootNotes = false;
require(app_path() . "Views/Components/CommentEditor.php");
?>

<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="action_type_container">
            <div class="action_type type_checking"><?php echo __("type_checking1"); ?></div>
        </div>
        <div class="main_content_title">
            <?php echo __("step_num", ["step_number" => 5]) . ": " . __("peer-review_tn")?>
        </div>
    </div>

    <div class="" style="position: relative">
        <div class="main_content">
            <form action="" id="main_form" method="post">
            <div class="main_content_text">
            
                <?php if($data["event"][0]->checkerID == 0): ?>
                    <div class="alert alert-success check_request"><?php echo __("check_request_sent_success") ?></div>
                <?php endif; ?>

                <h4><?php echo $data["event"][0]->tLang." - "
                        .__($data["event"][0]->bookProject)." - "
                    .($data["event"][0]->sort <= 39 ? __("old_test") : __("new_test"))." - "
                    ."<span class='book_name'>".$data["event"][0]->name." ".
                    (!$data["nosource"] 
                        ? $data["currentChapter"].":1-".$data["totalVerses"]
                        : __("front"))."</span>"?></h4>

                <div id="my_notes_content" class="my_content">
                    <?php foreach($data["chunks"] as $chunkNo => $chunk): $fv = $chunk[0]; ?>
                    <div class="row note_chunk">
                        <div class="row scripture_chunk" dir="<?php echo $data["event"][0]->sLangDir ?>">
                            <?php if(!$data["nosource"] && isset($data["text"][$fv])): ?>
                                <?php foreach(array_values($chunk) as $verse): ?>
                                    <div class="chunk_verses">
                                        <strong><sup><?php echo $verse ?></sup></strong>
                                        <div class="<?php echo "kwverse_".$data["currentChapter"]."_".$chunkNo."_".$verse ?>">
                                            <?php echo $data["text"][$verse] ?? ""; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <div class="flex_container">
                            <div class="flex_left" dir="<?php echo $data["event"][0]->resLangDir ?>">
                                <?php foreach(array_values($chunk) as $verse): ?>
                                    <div class="note_content">
                                        <?php if (isset($data["notes"][$verse])): ?>
                                            <?php foreach ($data["notes"][$verse] as $note): ?>
                                                <?php echo preg_replace(
                                                    "/(\[\[[0-9a-z:\/\-]+\]\])/",
                                                    "<span class='uwlink' title='".__("leaveit")."'>$1</span>",
                                                    $note) ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="flex_middle notes_editor font_<?php echo $data["event"][0]->targetLang ?>"
                                 dir="<?php echo $data["event"][0]->tLangDir ?>"
                                 data-chunkno="<?php echo $chunkNo ?>">
                                <?php
                                $text = isset($data["translation"][$chunkNo]) && isset($data["translation"][$chunkNo][EventMembers::CHECKER])
                                    && !empty($data["translation"][$chunkNo][EventMembers::CHECKER]["verses"])
                                        ? $parsedown->text($data["translation"][$chunkNo][EventMembers::CHECKER]["verses"])
                                        : $parsedown->text($data["translation"][$chunkNo][EventMembers::TRANSLATOR]["verses"]);

                                $text = preg_replace(
                                    "/(\[\[[a-z:\/\-]+\]\])/",
                                    "<span class='uwlink' title='".__("leaveit")."'>$1</span>",
                                    $text);
                                ?>
                                <textarea
                                        name="chunks[<?php echo $chunkNo ?>]"
                                        class="add_notes_editor"><?php echo htmlentities($text) ?></textarea>
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

                <input type="hidden" name="chk" value="1">
                <input type="hidden" name="level" value="tnContinue">
                <input type="hidden" name="chapter" value="<?php echo $data["event"][0]->currentChapter ?>">
                <input type="hidden" name="memberID" value="<?php echo $data["event"][0]->memberID ?>">

                <button id="next_step" type="submit" name="submit" class="btn btn-primary" disabled>
                    <?php echo __($data["next_step"])?>
                </button>
                <img src="<?php echo template_url("img/saving.gif") ?>" class="unsaved_alert" style="float:none">
            </div>
            </form>
            <div class="step_right alt"><?php echo __("step_num", ["step_number" => 5])?></div>
        </div>
    </div>
</div>

<div class="content_help closed">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps is_checker_page_help">
            <div class="help_name_steps">
                <span><?php echo __("step_num", ["step_number" => 5])?>: </span>
                <?php echo __("peer-review_tn")?>
            </div>
            <div class="help_descr_steps">
                <ul>
                    <li><b><?php echo __("purpose") ?></b> <?php echo __("peer-review_tn_purpose") ?></li>
                    <li><?php echo __("peer-review_tn_help_1") ?></li>
                    <li><?php echo __("self-check_help_2") ?></li>
                    <li><?php echo __("self-check_tn_chk_help_3") ?></li>
                    <li><?php echo __("self-check_tn_chk_help_4") ?></li>
                    <li><?php echo __("self-check_tn_chk_help_5") ?></li>
                    <li><?php echo __("self-check_tn_help_4") ?></li>
                    <li><?php echo __("peer-review_tn_help_7") ?></li>
                    <li><?php echo __("self-check_tn_chk_help_9", ["icon" => "<i class='note-icon-magic'></i>"]) ?>
                        <ol>
                            <li><?php echo __("blind-draft_tn_help_8") ?></li>
                            <li><?php echo __("self-check_tn_chk_help_9a") ?></li>
                            <li><?php echo __("self-check_tn_help_6a") ?></li>
                            <li><?php echo __("blind-draft_tn_help_8c") ?></li>
                        </ol>
                    </li>
                    <li><?php echo __("peer-review_tn_help_9") ?></li>
                    <li><?php echo __("peer-review_tn_help_10") ?></li>
                    <li><?php echo __("move_to_next_step_alt", ["step" => __($data["next_step"])]) ?></li>
                    <li><?php echo __("peer-review_tn_help_11") ?></li>
                </ul>
                <div class="show_tutorial_popup"> >>> <?php echo __("show_more")?></div>
            </div>
        </div>

        <div class="event_info is_checker_page_help">
            <div class="participant_name">
                <span><?php echo __("your_checker") ?>:</span>
                <span class="checker_name_span"><?php echo $data["event"][0]->checkerFName !== null ? $data["event"][0]->checkerFName . " " . mb_substr($data["event"][0]->checkerLName, 0, 1)."." : __("not_available") ?></span>
            </div>
            <div class="participant_info">
                <div class="additional_info">
                    <a href="/events/information-tn/<?php echo $data["event"][0]->eventID ?>"><?php echo __("event_info") ?></a>
                </div>
            </div>
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

        <div class="tutorial_content<?php echo $data["isCheckerPage"] ? " is_checker_page_help" : "" ?>">
            <h3><?php echo __("peer-review_tn")?></h3>
            <ul>
                <li><b><?php echo __("purpose") ?></b> <?php echo __("peer-review_tn_purpose") ?></li>
                <li><?php echo __("peer-review_tn_help_1") ?></li>
                <li><?php echo __("self-check_help_2") ?></li>
                <li><?php echo __("self-check_tn_chk_help_3") ?></li>
                <li><?php echo __("self-check_tn_chk_help_4") ?></li>
                <li><?php echo __("self-check_tn_chk_help_5") ?></li>
                <li><?php echo __("self-check_tn_help_4") ?></li>
                <li><?php echo __("peer-review_tn_help_7") ?></li>
                <li><?php echo __("self-check_tn_chk_help_9", ["icon" => "<i class='note-icon-magic'></i>"]) ?>
                    <ol>
                        <li><?php echo __("blind-draft_tn_help_8") ?></li>
                        <li><?php echo __("self-check_tn_chk_help_9a") ?></li>
                        <li><?php echo __("self-check_tn_help_6a") ?></li>
                        <li><?php echo __("blind-draft_tn_help_8c") ?></li>
                    </ol>
                </li>
                <li><?php echo __("peer-review_tn_help_9") ?></li>
                <li><?php echo __("peer-review_tn_help_10") ?></li>
                <li><?php echo __("move_to_next_step_alt", ["step" => __($data["next_step"])]) ?></li>
                <li><?php echo __("peer-review_tn_help_11") ?></li>
            </ul>
        </div>
    </div>
</div>

<script>
    var disableHighlight = true;
</script>