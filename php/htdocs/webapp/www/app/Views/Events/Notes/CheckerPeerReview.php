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
            <div class="action_type type_checking <?php echo isset($data["isPeerPage"]) ? "isPeer" : "" ?>">
                <?php echo __("type_checking2"); ?>
            </div>
        </div>
        <div class="main_content_title">
            <div><?php echo __("step_num", ["step_number" => 5]). ": " . __("peer-review_tn")?></div>
        </div>
    </div>

    <div class="">
        <div class="main_content">
            <div class="main_content_text">
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
                        <div class="compare_notes">
                            <label>
                                <?php echo __("compare"); ?>
                                <input type="checkbox" autocomplete="off" checked data-toggle="toggle"
                                       data-on="<?php echo __("on") ?>"
                                       data-off="<?php echo __("off") ?>" />
                            </label>
                        </div>
                        <div class="flex_container">
                            <div class="flex_left" dir="<?php echo $data["event"][0]->resLangDir ?>">
                                <?php foreach(array_values($chunk) as $verse): ?>
                                    <div class="note_content">
                                        <?php if (isset($data["notes"][$verse])): ?>
                                            <?php foreach ($data["notes"][$verse] as $note): ?>
                                                <?php echo $note ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="flex_middle vnote font_<?php echo $data["event"][0]->targetLang ?>"
                                 dir="<?php echo $data["event"][0]->tLangDir ?>">
                                <?php
                                $text = isset($data["translation"][$chunkNo]) && isset($data["translation"][$chunkNo][EventMembers::CHECKER])
                                    && !empty($data["translation"][$chunkNo][EventMembers::CHECKER]["verses"])
                                        ? $parsedown->text($data["translation"][$chunkNo][EventMembers::CHECKER]["verses"])
                                        : $parsedown->text($data["translation"][$chunkNo][EventMembers::TRANSLATOR]["verses"]);

                                $text = preg_replace('/( title=".*")/', '', $text);
                                ?>
                                <div class="notes_target"><?php echo $text ?></div>
                                <div class="notes_target_compare"></div>
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
                        <div class="notes_translator">
                            <?php 
                            $text = isset($data["translation"][$chunkNo])
                                ? $parsedown->text($data["translation"][$chunkNo][EventMembers::TRANSLATOR]["verses"])
                                : "";
                            echo preg_replace('/( title=".*")/', '', $text);
                            ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

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
                <div class="step_right chk"><?php echo __("step_num", ["step_number" => 5])?></div>
            </div>
        </div>
    </div>
</div>

<div class="content_help closed">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps is_checker_page_help isPeer">
            <div class="help_name_steps"><span><?php echo __("step_num", ["step_number" => 5])?>: </span> <?php echo __("peer-review_tn")?></div>
            <div class="help_descr_steps">
                <ul>
                    <li><b><?php echo __("purpose") ?></b> <?php echo __("peer-review_tn_purpose") ?></li>
                    <li><?php echo __("peer-review_tn_help_1") ?></li>
                    <li><?php echo __("peer-review_tn_chk_help_2") ?>
                        <ol>
                            <li><?php echo __("peer-review_tn_chk_help_2a") ?></li>
                            <li><?php echo __("peer-review_tn_chk_help_2b") ?></li>
                            <li><?php echo __("peer-review_tn_chk_help_2c") ?></li>
                            <li><?php echo __("peer-review_tn_chk_help_2d") ?></li>
                        </ol>
                    </li>
                    <li><?php echo __("peer-review_tn_chk_help_3") ?></li>
                    <li><?php echo __("self-check_help_2") ?></li>
                    <li><?php echo __("self-check_tn_help_2") ?></li>
                    <li><?php echo __("self-check_tn_chk_help_4") ?></li>
                    <li><?php echo __("self-check_tn_chk_help_5") ?></li>
                    <li><?php echo __("self-check_tn_help_4") ?></li>
                    <li><?php echo __("peer-review_tn_chk_help_9", ["icon" => "<span class='mdi mdi-lead-pencil'></span>"]) ?></li>
                    <li><?php echo __("peer-review_tn_chk_help_10") ?></li>
                    <li><?php echo __("peer-review_tn_chk_help_11") ?></li>
                    <li><?php echo __("peer-review_tn_chk_help_12", ["step" => __($data["next_step"])]) ?></li>
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
            <img src="<?php echo template_url("img/steps/icons/peer-review.png") ?>" width="100" height="100">
            <img src="<?php echo template_url("img/steps/big/peer-review.png") ?>" width="280" height="280">
        </div>

        <div class="tutorial_content<?php echo $data["isCheckerPage"] ? " is_checker_page_help" .
            (isset($data["isPeerPage"]) ? " isPeer" : ""): "" ?>">
            <h3><?php echo __("peer-review_tn")?></h3>
            <ul>
                <li><b><?php echo __("purpose") ?></b> <?php echo __("peer-review_tn_purpose") ?></li>
                <li><?php echo __("peer-review_tn_help_1") ?></li>
                <li><?php echo __("peer-review_tn_chk_help_2") ?>
                    <ol>
                        <li><?php echo __("peer-review_tn_chk_help_2a") ?></li>
                        <li><?php echo __("peer-review_tn_chk_help_2b") ?></li>
                        <li><?php echo __("peer-review_tn_chk_help_2c") ?></li>
                        <li><?php echo __("peer-review_tn_chk_help_2d") ?></li>
                    </ol>
                </li>
                <li><?php echo __("peer-review_tn_chk_help_3") ?></li>
                <li><?php echo __("self-check_help_2") ?></li>
                <li><?php echo __("self-check_tn_help_2") ?></li>
                <li><?php echo __("self-check_tn_chk_help_4") ?></li>
                <li><?php echo __("self-check_tn_chk_help_5") ?></li>
                <li><?php echo __("self-check_tn_help_4") ?></li>
                <li><?php echo __("peer-review_tn_chk_help_9", ["icon" => "<span class='mdi mdi-lead-pencil'></span>"]) ?></li>
                <li><?php echo __("peer-review_tn_chk_help_10") ?></li>
                <li><?php echo __("peer-review_tn_chk_help_11") ?></li>
                <li><?php echo __("peer-review_tn_chk_help_12", ["step" => __($data["next_step"])]) ?></li>
            </ul>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo template_url("js/diff_match_patch.js?v=2")?>"></script>
<script type="text/javascript" src="<?php echo template_url("js/diff.js?v=7")?>"></script>
<script>
    var isChecker = true;
    var disableHighlight = true;

    $(document).ready(function() {
        $(".note_chunk").each(function(i, v) {
            var elm1 = $(".notes_translator", this).html();
            var elm2 = $(".notes_target", this).html();
            var out = $(".notes_target_compare", this);

            if(typeof elm1 == "undefined") return true;

            diff_plain(htmlToText(elm1), htmlToText(elm2), out);
        });

        $(".compare_notes input").change(function () {
            var parent = $(this).parents(".note_chunk");
            var active = $(this).prop('checked');

            if (active) {
                $(".notes_target", parent).hide();
                $(".notes_target_compare", parent).show();
            } else {
                $(".notes_target_compare", parent).hide();
                $(".notes_target", parent).show();
            }
        });
    });
</script>
<?php endif; ?>