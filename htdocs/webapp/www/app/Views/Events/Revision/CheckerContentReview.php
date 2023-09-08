<?php
if(isset($data["error"])) return;

use Helpers\Constants\EventCheckSteps;
use Helpers\Constants\EventMembers;

$textDirection = $data["event"][0]->tLangDir;
$fontLanguage = $data["event"][0]->targetLang;
$level = 2;
$enableFootNotes = false;
require(app_path() . "Views/Components/CommentEditor.php");
require(app_path() . "Views/Components/HelpTools.php");
?>

<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="main_content_title"><?php echo __("step_num", ["step_number" => 5]) . ": " . __(EventCheckSteps::CONTENT_REVIEW)?></div>
    </div>

    <div class="" style="position: relative">
        <div class="main_content">
            <form action="" id="checker_submit" method="post" >
                <div class="main_content_text" dir="<?php echo $data["event"][0]->sLangDir ?>">
                    <h4><?php echo $data["event"][0]->tLang." - "
                            .__($data["event"][0]->bookProject)." - "
                        .($data["event"][0]->sort <= 39 ? __("old_test") : __("new_test"))." - "
                        ."<span class='book_name'>".$data["event"][0]->name." ".$data["currentChapter"].":1-".$data["totalVerses"]."</span>"?></h4>

                    <div class="my_content">
                        <div class="no_padding">
                            <div class="compare_flex_container">
                                <div class="source_mode">
                                    <label>
                                        <?php echo __("show_source") ?>
                                        <input type="checkbox" autocomplete="off"
                                               data-toggle="toggle"
                                               data-on="ON"
                                               data-off="OFF" />
                                    </label>
                                </div>
                                <div class="compare_notes revision">
                                    <label>
                                        <?php echo __("compare"); ?>
                                        <input type="checkbox" autocomplete="off" data-toggle="toggle"
                                               data-on="<?php echo __("on") ?>"
                                               data-off="<?php echo __("off") ?>" />
                                    </label>
                                </div>
                            </div>

                            <?php foreach($data["chunks"] as $key => $chunk) : ?>
                                <div class="row chunk_block">
                                    <div class="flex_container">
                                        <div class="flex_left flex_column">
                                            <?php
                                            $firstVerse = 0;
                                            $orig_verses = $data["translation"][$key][EventMembers::TRANSLATOR]["verses"];
                                            $verses = $data["translation"][$key][EventMembers::L2_CHECKER]["verses"];

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
                                                    <div class="flex_one chunk_verses font_<?php echo $data["event"][0]->sourceLangID ?>" dir="<?php echo $data["event"][0]->sLangDir ?>">
                                                        <p class="verse_text"
                                                           data-verse="<?php echo $verse ?>">
                                                            <?php if ($verse > 0): ?>
                                                                <strong class="<?php echo $data["event"][0]->sLangDir ?>"><sup><?php echo $verse; ?></sup></strong>
                                                            <?php endif; ?>
                                                            <span class="verse_text_source <?php echo "kwverse_".$data["currentChapter"]."_".$key."_".$verse ?>"><?php echo $data["text"][$verse]; ?></span>
                                                            <span class="verse_text_original"><?php echo $orig_verses[$verse] ?></span>
                                                        </p>
                                                    </div>
                                                    <div class="flex_one editor_area font_<?php echo $data["event"][0]->targetLang ?>" dir="<?php echo $data["event"][0]->tLangDir ?>">
                                                        <p class="original_verse" data-verse="<?php echo $verse; ?>"><?php echo $orig_verses[$verse]; ?></p>
                                                        <div class="vnote">
                                                            <div class="verse_block flex_chunk" data-verse="<?php echo $verse; ?>">
                                                                <p class="target_verse" data-verse="<?php echo $verse ?>"><?php echo preg_replace("/(\\\\f(?:.*?)\\\\f\\*)/", "<span class='footnote'>$1</span>", $verses[$verse]); ?></p>
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
                </div>

                <div class="main_content_footer">
                    <div class="form-group">
                        <div class="main_content_confirm_desc"><?php echo __("confirm_finished")?></div>
                        <label><input name="confirm_step" id="confirm_step" type="checkbox" value="1" /> <?php echo __("confirm_yes")?></label>
                    </div>

                    <input type="hidden" name="level" value="l2">
                    <button id="checker_ready" class="btn btn-warning" disabled>
                        <?php echo __("ready_to_check")?>
                    </button>
                    <button id="next_step" type="submit" name="submit_chk" class="btn btn-primary checker" disabled>
                        <?php echo __($data["next_step"])?>
                    </button>
                    <img src="<?php echo template_url("img/loader.gif") ?>" class="ready_loader">
                    <input type="hidden" class="event_data_to" value="<?php echo $data["event"][0]->memberID ?>" />
                    <input type="hidden" class="event_data_step" value="<?php echo $data["event"][0]->step ?>" />
                    <input type="hidden" class="event_data_chapter" value="<?php echo $data["event"][0]->currentChapter ?>" />
                    <input type="hidden" class="event_data_manage" value="<?php echo $data["event"][0]->manageMode ?>" />
                </div>
            </form>
            <div class="step_right alt"><?php echo __("step_num", ["step_number" => 5])?></div>
        </div>
    </div>
</div>

<div class="content_help closed">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps is_checker_page_help isPeer">
            <div class="help_name_steps">
                <?php echo __("step_num", ["step_number" => 5])?>: <span><?php echo __(EventCheckSteps::CONTENT_REVIEW)?></span>
            </div>
            <div class="help_descr_steps">
                <ul>

                </ul>
                <div class="show_tutorial_popup"> >>> <?php echo __("show_more")?></div>
            </div>
        </div>

        <div class="event_info is_checker_page_help isPeer">
            <div class="participant_info">
                <div class="participant_name">
                    <span><?php echo __("your_checker") ?>:</span>
                    <span><?php echo $data["event"][0]->firstName . " " . mb_substr($data["event"][0]->lastName, 0, 1)."." ?></span>
                </div>
                <div class="additional_info">
                    <a href="/events/information-revision/<?php echo $data["event"][0]->eventID ?>"><?php echo __("event_info") ?></a>
                </div>
            </div>
        </div>

        <div class="tr_tools">
            <?php
            renderTn($data["event"][0]->tnLangID);
            renderTq($data["event"][0]->tqLangID);
            renderTw($data["event"][0]->twLangID);
            renderRubric();
            ?>
        </div>
    </div>
</div>

<div class="tutorial_container">
    <div class="tutorial_popup">
        <div class="tutorial-close glyphicon glyphicon-remove"></div>
        <div class="tutorial_pic">
            <img src="<?php echo template_url("img/steps/icons/content-review.png") ?>" width="100px" height="100px">
            <img src="<?php echo template_url("img/steps/big/content-review.png") ?>" width="280px" height="280px">
        </div>

        <div class="tutorial_content is_checker_page_help">
            <h3><?php echo __(EventCheckSteps::CONTENT_REVIEW)?></h3>
            <ul>

            </ul>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo template_url("js/diff_match_patch.js?v=2")?>"></script>
<script type="text/javascript" src="<?php echo template_url("js/diff.js?v=7")?>"></script>
<script>
    (function() {
        $(".original_verse").each(function() {
            const verse = $(this).data("verse");
            const chkVersion = $(".target_verse[data-verse='"+verse+"']");
            const chkText = chkVersion.text();

            diff_plain($(this).text(), unEscapeStr(chkText), $(this));
        });

        $(".compare_notes input").change(function () {
            const active = $(this).prop('checked');

            if (active) {
                $(".vnote").hide();
                $(".original_verse").show();
            } else {
                $(".original_verse").hide();
                $(".vnote").show();
            }
        });

        $(".source_mode input").change(function () {
            const active = $(this).prop('checked');
            if (active) {
                $(".verse_text_source").show();
                $(".verse_text_original").hide();
            } else {
                $(".verse_text_source").hide();
                $(".verse_text_original").show();
            }
        });
    })();

    isLevel2 = true;
    isChecker = true;
</script>
