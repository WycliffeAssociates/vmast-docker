<?php
use Helpers\Constants\EventCheckSteps;
use Helpers\Constants\EventMembers;

if(isset($data["error"])) return;

$textDirection = $data["event"][0]->tLangDir;
$fontLanguage = $data["event"][0]->targetLang;
$level = 2;
$enableFootNotes = false;
require(app_path() . "Views/Components/CommentEditor.php");
?>

<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="main_content_title"><?php echo __("step_num", ["step_number" => 2]) . ": " . __(EventCheckSteps::SELF_CHECK . "_sun")?></div>
    </div>

    <div class="" style="position: relative">
        <div class="main_content">
            <form action="" id="main_form" method="post" >
                <div class="main_content_text" dir="<?php echo $data["event"][0]->sLangDir ?>">
                    <h4><?php echo $data["event"][0]->tLang." - "
                            .__($data["event"][0]->bookProject)." - "
                        .($data["event"][0]->sort <= 39 ? __("old_test") : __("new_test"))." - "
                        ."<span class='book_name'>".$data["event"][0]->name." ".$data["currentChapter"].":1-".$data["totalVerses"]."</span>"?></h4>

                    <div class="no_padding">
                        <div class="sun_mode">
                            <label>
                                <input type="checkbox" autocomplete="off" checked
                                       data-toggle="toggle"
                                       data-on="SUN"
                                       data-off="BACKSUN" />
                            </label>
                        </div>

                        <?php foreach($data["chunks"] as $key => $chunk) : ?>
                            <div class="row chunk_block">
                                <div class="flex_container">
                                    <div class="flex_left flex_column">
                                        <?php
                                        $firstVerse = 0;
                                        if(!empty($data["translation"][$key][EventMembers::L2_CHECKER]["verses"]))
                                            $verses = $data["translation"][$key][EventMembers::L2_CHECKER]["verses"];
                                        else
                                            $verses = $data["translation"][$key][EventMembers::TRANSLATOR]["verses"];

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
                                                    <p class="verse_text <?php echo "kwverse_".$data["currentChapter"]."_".$key."_".$verse ?>"
                                                       data-verse="<?php echo $verse ?>">
                                                        <strong class="<?php echo $data["event"][0]->sLangDir ?>">
                                                            <sup><?php echo $verse; ?></sup>
                                                        </strong>
                                                        <?php echo $data["text"][$verse]; ?>
                                                    </p>
                                                </div>
                                                <div class="flex_one editor_area sun_content font_<?php echo $data["event"][0]->targetLang ?>" dir="<?php echo $data["event"][0]->tLangDir ?>">
                                                    <div class="vnote">
                                                        <div class="verse_block flex_chunk" data-verse="<?php echo $verse ?>">
                                                            <textarea name="chunks[<?php echo $key ?>][<?php echo $verse ?>]"
                                                                      class="peer_verse_ta narrow textarea" style="min-width: 400px"><?php echo $verses[$verse]; ?></textarea>
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

                <div class="main_content_footer">
                    <div class="form-group">
                        <div class="main_content_confirm_desc"><?php echo __("confirm_finished")?></div>
                        <label><input name="confirm_step" id="confirm_step" type="checkbox" value="1" /> <?php echo __("confirm_yes")?></label>
                    </div>

                    <input type="hidden" name="level" value="l2">
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
        <div class="help_info_steps is_checker_page_help">
            <div class="help_name_steps"><span><?php echo __("step_num", ["step_number" => 2])?>: </span><?php echo __(EventCheckSteps::SELF_CHECK . "_sun")?></div>
            <div class="help_descr_steps">
                <ul>
                    <li><b><?php echo __("purpose") ?></b> <?php echo __("theo-check_sun_l2_purpose") ?></li>
                    <li><?php echo __("peer-review-l2_sun_help_1") ?></li>
                    <li><?php echo __("peer-review-l2_sun_help_2") ?></li>
                    <li><?php echo __("peer-review-l2_sun_help_3") ?></li>
                    <li><?php echo __("theo-check_sun_l2_help_2") ?></li>
                    <li><?php echo __("theo-check_sun_l2_help_3") ?></li>
                    <li><?php echo __("peer-review_checker_help_2") ?></li>
                    <li><?php echo __("theo-check_sun_l2_help_5") ?></li>
                    <li><?php echo __("peer-review-l2_sun_help_8") ?></li>
                    <li><?php echo __("peer-review-l2_sun_help_9") ?></li>
                    <li><?php echo __("theo-check_sun_l2_help_7") ?></li>
                    <li><?php echo __("move_to_next_step_alt", ["step" => __($data["next_step"])]) ?></li>
                </ul>
                <div class="show_tutorial_popup"> >>> <?php echo __("show_more")?></div>
            </div>
        </div>

        <div class="event_info is_checker_page_help">
            <div class="participant_info">
                <div class="additional_info">
                    <a href="/events/information-sun-revision/<?php echo $data["event"][0]->eventID ?>"><?php echo __("event_info") ?></a>
                </div>
            </div>
        </div>

        <div class="tr_tools">
            <button class="btn btn-warning ttools" data-tool="saildict"><?php echo __("show_dictionary") ?></button>
            <button class="btn btn-primary ttools" data-tool="tn"><?php echo __("show_notes") ?></button>
            <button class="btn btn-primary ttools" data-tool="tw"><?php echo __("show_keywords") ?></button>
        </div>
    </div>
</div>

<!-- Data for tools -->
<input type="hidden" id="bookCode" value="<?php echo $data["event"][0]->bookCode ?>">
<input type="hidden" id="chapter" value="<?php echo $data["event"][0]->currentChapter ?>">
<input type="hidden" id="tn_lang" value="<?php echo $data["event"][0]->tnLangID ?>">
<input type="hidden" id="totalVerses" value="<?php echo $data["totalVerses"] ?>">
<input type="hidden" id="targetLang" value="<?php echo $data["event"][0]->targetLang ?>">

<div class="tutorial_container">
    <div class="tutorial_popup">
        <div class="tutorial-close glyphicon glyphicon-remove"></div>
        <div class="tutorial_pic">
            <img src="<?php echo template_url("img/steps/icons/self-check.png") ?>" width="100px" height="100px">
            <img src="<?php echo template_url("img/steps/big/self-check.png") ?>" width="280px" height="280px">
        </div>

        <div class="tutorial_content">
            <h3><?php echo __(EventCheckSteps::SELF_CHECK . "_sun")?></h3>
            <ul>
                <li><b><?php echo __("purpose") ?></b> <?php echo __("theo-check_sun_l2_purpose") ?></li>
                <li><?php echo __("peer-review-l2_sun_help_1") ?></li>
                <li><?php echo __("peer-review-l2_sun_help_2") ?></li>
                <li><?php echo __("peer-review-l2_sun_help_3") ?></li>
                <li><?php echo __("theo-check_sun_l2_help_2") ?></li>
                <li><?php echo __("theo-check_sun_l2_help_3") ?></li>
                <li><?php echo __("peer-review_checker_help_2") ?></li>
                <li><?php echo __("theo-check_sun_l2_help_5") ?></li>
                <li><?php echo __("peer-review-l2_sun_help_8") ?></li>
                <li><?php echo __("peer-review-l2_sun_help_9") ?></li>
                <li><?php echo __("theo-check_sun_l2_help_7") ?></li>
                <li><?php echo __("move_to_next_step_alt", ["step" => __($data["next_step"])]) ?></li>
            </ul>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $(".sun_mode input").change(function () {
            const active = $(this).prop('checked');
            if (active) {
                $(".editor_area").removeClass("font_backsun");
                $(".editor_area").addClass("sun_content");
                $(".editor_area .textarea").removeClass("wide");
                $(".editor_area .textarea").addClass("narrow");
            } else {
                $(".editor_area").removeClass("sun_content");
                $(".editor_area").addClass("font_backsun");
                $(".editor_area .textarea").addClass("wide");
                $(".editor_area .textarea").removeClass("narrow");
            }
        });
    });
</script>