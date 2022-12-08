<?php
/**
 * Created by PhpStorm.
 * User: Maxim
 * Date: 12 Apr 2016
 * Time: 17:30
 */
use Helpers\Constants\EventMembers;
use Helpers\Constants\EventSteps;
use Helpers\Session;

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
            <div><?php echo __("step_num", ["step_number" => 3]). ": " . __(EventSteps::PEER_REVIEW)?></div>
            <div class="action_type type_checking"><?php echo __("type_checking"); ?></div>
        </div>
    </div>

    <div class="">
        <div class="main_content">
            <div class="main_content_text row">
                <h4 dir="<?php echo $data["event"][0]->sLangDir ?>"><?php echo $data["event"][0]->tLang." - "
                        .__($data["event"][0]->bookProject)." - "
                        .($data["event"][0]->sort <= 39 ? __("old_test") : __("new_test"))." - "
                        ."<span class='book_name'>".$data["event"][0]->bookName." ".$data["currentChapter"].":1-".$data["chunks"][sizeof($data["chunks"])-1][0]."</span>"?></h4>

                <div class="no_padding">
                    <?php foreach($data["chunks"] as $key => $chunk) : ?>
                        <div class="row chunk_block">
                            <div class="flex_container">
                                <div class="flex_left flex_column">
                                    <?php
                                    $firstVerse = 0;
                                    $verses = $data["translation"][$key][EventMembers::TRANSLATOR]["verses"];

                                    foreach ($chunk as $verse):
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
                                                    <?php echo preg_replace("/(\\\\f(?:.*?)\\\\f\\*)/", "<span class='footnote'>$1</span>", $verses[$verse]); ?>
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
                <div class="step_right chk"><?php echo __("step_num", ["step_number" => 3])?></div>
            </div>
        </div>
    </div>
</div>

<div class="content_help closed">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps is_checker_page_help">
            <div class="help_name_steps"><span><?php echo __("step_num", ["step_number" => 3])?>: </span> <?php echo __(EventSteps::PEER_REVIEW)?></div>
            <div class="help_descr_steps">
                <ul>
                    <li><b><?php echo __("purpose") ?></b> <?php echo __("peer-review_checker_purpose") ?></li>
                    <li><b><?php echo __("length") ?></b> <?php echo __("peer-review_checker_length") ?></li>
                    <li><b><?php echo __("self-check_help_1") ?></b></li>
                    <li><b><?php echo __("peer-review_checker_help_2") ?></b></li>
                    <li><?php echo __("peer-review_checker_help_3") ?></li>
                    <li><?php echo __("self-check_help_2") ?></li>
                    <li><?php echo __("self-check_tn_help_2") ?></li>
                    <li><?php echo __("peer-review_checker_help_6") ?></li>
                    <li><?php echo __("peer-review_checker_help_7", ["icon" => "<span class='mdi mdi-lead-pencil'></span>"]) ?></li>
                    <li><?php echo __("self-check_help_5") ?>
                        <ol>
                            <li><?php echo __("self-check_help_5a") ?></li>
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
                    <li><?php echo __("peer-review_checker_help_9") ?></li>
                    <li><?php echo __("peer-review_checker_help_10") ?></li>
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
            <button class="btn btn-primary ttools" data-tool="bc"><?php echo __("show_bible_commentaries") ?></button>
            <button class="btn btn-warning ttools" data-tool="rubric"><?php echo __("show_rubric") ?></button>
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
            <h3><?php echo __(EventSteps::PEER_REVIEW)?></h3>
            <ul>
                <li><b><?php echo __("purpose") ?></b> <?php echo __("peer-review_checker_purpose") ?></li>
                <li><b><?php echo __("length") ?></b> <?php echo __("peer-review_checker_length") ?></li>
                <li><b><?php echo __("self-check_help_1") ?></b></li>
                <li><b><?php echo __("peer-review_checker_help_2") ?></b></li>
                <li><?php echo __("peer-review_checker_help_3") ?></li>
                <li><?php echo __("self-check_help_2") ?></li>
                <li><?php echo __("self-check_tn_help_2") ?></li>
                <li><?php echo __("peer-review_checker_help_6") ?></li>
                <li><?php echo __("peer-review_checker_help_7", ["icon" => "<span class='mdi mdi-lead-pencil'></span>"]) ?></li>
                <li><?php echo __("self-check_help_5") ?>
                    <ol>
                        <li><?php echo __("self-check_help_5a") ?></li>
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
                <li><?php echo __("peer-review_checker_help_9") ?></li>
                <li><?php echo __("peer-review_checker_help_10") ?></li>
                <li><?php echo __("move_to_next_step_alt", ["step" => __($data["next_step"])]) ?></li>
            </ul>
        </div>
    </div>
</div>

<script>
    isChecker = true;
</script>
<?php endif; ?>