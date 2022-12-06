<?php
/**
 * Created by PhpStorm.
 * User: Maxim
 * Date: 12 Apr 2016
 * Time: 17:30
 */
use Helpers\Constants\EventMembers;
use Helpers\Parsedown;

if(empty($error) && empty($data["success"])):
?>

<?php
$parsedown = new Parsedown();
$textDirection = $data["event"][0]->tLangDir;
$fontLanguage = $data["event"][0]->targetLang;
$level = 2;
$enableFootNotes = false;
require(app_path() . "Views/Components/CommentEditor.php");
?>

<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="main_content_title">
            <div><?php echo __("step_num", ["step_number" => 2]). ": " . __("peer-review_tw")?></div>
        </div>
    </div>

    <div class="">
        <div class="main_content">
            <div class="main_content_text">
                <h4><?php echo $data["event"][0]->tLang." - "
                        .__($data["event"][0]->bookProject)." - "
                        ."<span class='book_name'>".$data["event"][0]->name
                        ." [".$data["group"][0]."...".$data["group"][sizeof($data["group"])-1]."]</span>"?></h4>

                <div id="my_notes_content" class="my_content">
                    <?php foreach($data["chunks"] as $chunkNo => $chunk): ?>
                    <div class="row note_chunk">
                        <div class="row">
                            <div class="col-md-4" style="color: #00a74d; font-weight: bold;">
                                <?php //echo $data["words"][$chunkNo]["word"] ?>
                            </div>
                        </div>
                        <div class="flex_container">
                            <div class="flex_left" dir="<?php echo $data["event"][0]->resLangDir ?>">
                                <div class="note_content">
                                    <?php
                                    $source = $data["words"][$chunkNo]["text"];
                                    $source = preg_replace("/(title=\"([^\"]+)\")/", "", $source);
                                    $source = preg_replace("/(href=\"([^\"]+)\")/", "$1 title='$2'", $source);
                                    echo $source
                                    ?>
                                </div>
                            </div>
                            <div class="flex_middle vnote font_<?php echo $data["event"][0]->targetLang ?>"
                                 dir="<?php echo $data["event"][0]->tLangDir ?>">
                                <?php
                                $text = isset($data["translation"][$chunkNo]) && isset($data["translation"][$chunkNo][EventMembers::CHECKER])
                                    && !empty($data["translation"][$chunkNo][EventMembers::CHECKER]["verses"])
                                        ? $parsedown->text($data["translation"][$chunkNo][EventMembers::CHECKER]["verses"])
                                        : $parsedown->text($data["translation"][$chunkNo][EventMembers::TRANSLATOR]["verses"]);
                                ?>
                                <div class="questions_target"><?php echo $text ?></div>
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

            <?php //if(empty($error)):?>
            <div class="main_content_footer row">
                <form action="" method="post" id="checker_submit">
                    <div class="form-group">
                        <div class="main_content_confirm_desc"><?php echo __("confirm_finished")?></div>
                        <label><input name="confirm_step" id="confirm_step" type="checkbox" value="1" /> <?php echo __("confirm_yes")?></label>
                    </div>

                    <button id="checker_ready" class="btn btn-warning" disabled>
                        <?php echo __("ready_to_check")?>
                    </button>
                    <button id="next_step" type="submit" name="submit" class="btn btn-primary checker" disabled>
                        <?php echo __($data["next_step"])?>
                    </button>
                    <img src="<?php echo template_url("img/loader.gif") ?>" class="ready_loader">
                    <input type="hidden" class="event_data_to" value="<?php echo $data["event"][0]->checkerID ?>" />
                    <input type="hidden" class="event_data_step" value="<?php echo $data["event"][0]->step ?>" />
                    <input type="hidden" class="event_data_chapter" value="<?php echo $data["event"][0]->currentChapter ?>" />
                    <input type="hidden" class="event_data_manage" value="<?php echo $data["event"][0]->manageMode ?>" />
                </form>
                <div class="step_right chk"><?php echo __("step_num", ["step_number" => 2])?></div>
            </div>
            <?php //endif; ?>
        </div>
    </div>
</div>

<div class="content_help closed">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps is_checker_page_help isPeer">
            <div class="help_name_steps">
                <span><?php echo __("step_num", ["step_number" => 2])?>: </span>
                <?php echo __("peer-review_tw")?>
            </div>
            <div class="help_descr_steps">
                <ul>
                    <li><b><?php echo __("purpose") ?></b> <?php echo __("peer-review_tw_purpose") ?></li>
                    <li><?php echo __("self-check_help_2") ?></li>
                    <li><?php echo __("self-check_tn_help_2") ?></li>
                    <li><?php echo __("self-check_tn_chk_help_4") ?></li>
                    <li><?php echo __("peer-review_tw_chk_help_4") ?></li>
                    <li><?php echo __("peer-review_tq_chk_help_6") ?></li>
                    <li><?php echo __("peer-review_tq_chk_help_7") ?></li>
                    <li><?php echo __("move_to_next_step_alt", ["step" => __($data["next_step"])]) ?></li>
                    <li><b><?php echo __("peer-review_tw_help_5") ?></b></li>
                </ul>
                <div class="show_tutorial_popup"> >>> <?php echo __("show_more")?></div>
            </div>
        </div>

        <div class="event_info is_checker_page_help isPeer">
            <div class="participant_info">
                <div class="participant_name">
                    <span><?php echo __("your_partner") ?>:</span>
                    <span><?php echo $data["event"][0]->checkerFName . " " . mb_substr($data["event"][0]->checkerLName, 0, 1)."." ?></span>
                </div>
                <div class="additional_info">
                    <a href="/events/information-tw/<?php echo $data["event"][0]->eventID ?>"><?php echo __("event_info") ?></a>
                </div>
            </div>
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

        <div class="tutorial_content is_checker_page_help">
            <h3><?php echo __("peer-review_tw")?></h3>
            <ul>
                <li><b><?php echo __("purpose") ?></b> <?php echo __("peer-review_tw_purpose") ?></li>
                <li><?php echo __("self-check_help_2") ?></li>
                <li><?php echo __("self-check_tn_help_2") ?></li>
                <li><?php echo __("self-check_tn_chk_help_4") ?></li>
                <li><?php echo __("peer-review_tw_chk_help_4") ?></li>
                <li><?php echo __("peer-review_tq_chk_help_6") ?></li>
                <li><?php echo __("peer-review_tq_chk_help_7") ?></li>
                <li><?php echo __("move_to_next_step_alt", ["step" => __($data["next_step"])]) ?></li>
                <li><b><?php echo __("peer-review_tw_help_5") ?></b></li>
            </ul>
        </div>
    </div>
</div>

<script>
    isChecker = true;
</script>
<?php endif; ?>