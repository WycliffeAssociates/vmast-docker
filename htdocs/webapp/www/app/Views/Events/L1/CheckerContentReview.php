<?php
/**
 * Created by PhpStorm.
 * User: Maxim
 * Date: 12 Apr 2016
 * Time: 17:30
 */
use Helpers\Constants\EventMembers;

if(empty($error) && empty($data["success"])):
?>

<?php
$textDirection = $data["event"][0]->tLangDir;
$fontLanguage = $data["event"][0]->targetLang;
$enableFootNotes = false;
require(app_path() . "Views/Components/CommentEditor.php");
?>


<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="main_content_title">
            <div><?php echo __("step_num", ["step_number" => 8]). ": " . __("content-review")?></div>
            <div class="action_type type_checking"><?php echo __("type_checking"); ?></div>
        </div>
    </div>

    <div class="main_content">
        <div class="main_content_text row">
            <h4 dir="<?php echo $data["event"][0]->sLangDir ?>"><?php echo $data["event"][0]->tLang." - "
                    .__($data["event"][0]->bookProject)." - "
                    .($data["event"][0]->sort <= 39 ? __("old_test") : __("new_test"))." - "
                    ."<span class='book_name'>".$data["event"][0]->bookName." ".$data["currentChapter"].":1-".$data["totalVerses"]."</span>"?></h4>

            <div class="row">
                <div class="col-sm-12 side_by_side_toggle">
                    <label><input type="checkbox" id="side_by_side_toggle" value="0" /> <?php echo __("side_by_side_toggle") ?></label>
                </div>
            </div>

            <div class="col-sm-12 side_by_side_checker">
                <?php foreach($data["chunks"] as $key => $chunk) : ?>
                    <div class="row chunk_block">
                        <div class="flex_container">
                            <div class="chunk_verses flex_left" dir="<?php echo $data["event"][0]->sLangDir ?>">
                                <?php $firstVerse = 0; ?>
                                <?php foreach ($chunk as $verse): ?>
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
                                    <strong dir="<?php echo $data["event"][0]->sLangDir ?>" class="<?php echo $data["event"][0]->sLangDir ?>"><sup><?php echo $verse; ?></sup></strong><div class="<?php echo "kwverse_".$data["currentChapter"]."_".$key."_".$verse ?>" dir="<?php echo $data["event"][0]->sLangDir ?>"><?php echo $data["text"][$verse]; ?></div>
                                <?php endforeach; ?>
                            </div>
                            <div class="flex_middle editor_area" style="padding: 0;" dir="<?php echo $data["event"][0]->tLangDir ?>">
                                <?php $text = $data["translation"][$key][EventMembers::TRANSLATOR]["blind"]; ?>
                                <div class="vnote">
                                    <div><?php echo preg_replace("/(\\\\f(?:.*?)\\\\f\\*)/", "<span class='footnote'>$1</span>", $text); ?></div>
                                </div>
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
                <input type="hidden" class="event_data_to" value="<?php echo $data["event"][0]->memberID ?>" />
                <input type="hidden" class="event_data_step" value="<?php echo $data["event"][0]->step ?>" />
                <input type="hidden" class="event_data_chapter" value="<?php echo $data["event"][0]->currentChapter ?>" />
            </form>
            <div class="step_right chk"><?php echo __("step_num", ["step_number" => 8])?></div>
        </div>
    </div>
</div>

<div class="content_help closed">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps is_checker_page_help">
            <div class="help_name_steps"><span><?php echo __("step_num", ["step_number" => 8])?>: </span> <?php echo __("content-review")?></div>
            <div class="help_descr_steps">
                <ul>
                    <li><b><?php echo __("purpose") ?></b> <?php echo __("content-review_checker_purpose") ?></li>
                    <li><b><?php echo __("length") ?></b> <?php echo __("content-review_checker_length") ?></li>
                    <li><b><?php echo __("self-check_help_1") ?></b></li>
                    <li><?php echo __("content-review_checker_help_2") ?></li>
                    <li><?php echo __("content-review_checker_help_3") ?>
                        <ol>
                            <li><?php echo __("content-review_help_2a") ?></li>
                            <li><?php echo __("content-review_help_2b") ?></li>
                            <li><?php echo __("content-review_help_2c") ?></li>
                            <li><?php echo __("content-review_help_2d") ?></li>
                            <li><?php echo __("content-review_help_2e") ?></li>
                        </ol>
                    </li>
                    <li><?php echo __("content-review_help_3") ?>
                        <ol>
                            <li><?php echo __("content-review_checker_help_4a") ?></li>
                            <li><?php echo __("content-review_checker_help_4b") ?></li>
                        </ol>
                    </li>
                    <li><?php echo __("content-review_checker_help_5") ?></li>
                    <li><?php echo __("self-check_help_5") ?>
                        <ol>
                            <li><?php echo __("self-check_help_5a") ?></li>
                            <li><?php echo __("keyword-check_help_4a") ?></li>
                            <li><?php echo __("self-check_help_5a") ?></li>
                            <li><?php echo __("self-check_help_5b") ?></li>
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
                    <li><?php echo __("peer-review_checker_help_9") ?></li>
                    <li><?php echo __("content-review_checker_help_8") ?></li>
                    <li><?php echo __("content-review_checker_help_9") ?></li>
                    <li><?php echo __("content-review_help_8") ?></li>
                    <li><?php echo __("move_to_next_step_alt", ["step" => __($data["next_step"])]) ?></li>
                </ul>
                <div class="show_tutorial_popup"> >>> <?php echo __("show_more")?></div>
            </div>
        </div>

        <div class="event_info is_checker_page_help">
            <div class="participant_info">
                <div class="participant_name">
                    <span><?php echo __("your_translator") ?>:</span>
                    <span><?php echo $data["event"][0]->firstName . " " . mb_substr($data["event"][0]->lastName, 0, 1)."." ?></span>
                </div>
                <div class="additional_info">
                    <a href="/events/information/<?php echo $data["event"][0]->eventID ?>"><?php echo __("event_info") ?></a>
                </div>
            </div>
        </div>

        <div class="tr_tools">
            <button class="btn btn-primary ttools" data-tool="tn"><?php echo __("show_notes") ?></button>
            <button class="btn btn-primary ttools" data-tool="tq"><?php echo __("show_questions") ?></button>
            <button class="btn btn-primary ttools" data-tool="tw"><?php echo __("show_keywords") ?></button>
            <button class="btn btn-primary ttools" data-tool="bc"><?php echo __("show_bible_commentaries") ?></button>
            <button class="btn btn-warning ttools" data-tool="rubric"><?php echo __("show_rubric") ?></button>
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

        <div class="tutorial_content is_checker_page_help">
            <h3><?php echo __("content-review")?></h3>
            <ul>
                <li><b><?php echo __("purpose") ?></b> <?php echo __("content-review_checker_purpose") ?></li>
                <li><b><?php echo __("length") ?></b> <?php echo __("content-review_checker_length") ?></li>
                <li><b><?php echo __("self-check_help_1") ?></b></li>
                <li><?php echo __("content-review_checker_help_2") ?></li>
                <li><?php echo __("content-review_checker_help_3") ?>
                    <ol>
                        <li><?php echo __("content-review_help_2a") ?></li>
                        <li><?php echo __("content-review_help_2b") ?></li>
                        <li><?php echo __("content-review_help_2c") ?></li>
                        <li><?php echo __("content-review_help_2d") ?></li>
                        <li><?php echo __("content-review_help_2e") ?></li>
                    </ol>
                </li>
                <li><?php echo __("content-review_help_3") ?>
                    <ol>
                        <li><?php echo __("content-review_checker_help_4a") ?></li>
                        <li><?php echo __("content-review_checker_help_4b") ?></li>
                    </ol>
                </li>
                <li><?php echo __("content-review_checker_help_5") ?></li>
                <li><?php echo __("self-check_help_5") ?>
                    <ol>
                        <li><?php echo __("self-check_help_5a") ?></li>
                        <li><?php echo __("keyword-check_help_4a") ?></li>
                        <li><?php echo __("self-check_help_5a") ?></li>
                        <li><?php echo __("self-check_help_5b") ?></li>
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
                <li><?php echo __("peer-review_checker_help_9") ?></li>
                <li><?php echo __("content-review_checker_help_8") ?></li>
                <li><?php echo __("content-review_checker_help_9") ?></li>
                <li><?php echo __("content-review_help_8") ?></li>
                <li><?php echo __("move_to_next_step_alt", ["step" => __($data["next_step"])]) ?></li>
            </ul>
        </div>
    </div>
</div>

<script>
    isChecker = true;
</script>
<?php endif; ?>