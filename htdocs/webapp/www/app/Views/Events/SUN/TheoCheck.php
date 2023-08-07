<?php
use Helpers\Constants\EventMembers;

if(isset($data["error"])) return;

$textDirection = $data["event"][0]->tLangDir;
$fontLanguage = $data["event"][0]->targetLang;
$enableFootNotes = false;
require(app_path() . "Views/Components/CommentEditor.php");
require(app_path() . "Views/Components/HelpTools.php");
?>

<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="main_content_title"><?php echo __("step_num", ["step_number" => 6]) . ": " . __("theo-check")?></div>
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
                        <?php foreach($data["chunks"] as $key => $chunk) : ?>
                            <div class="flex_container chunk_block">
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
                                        <div>
                                            <strong dir="<?php echo $data["event"][0]->sLangDir ?>"
                                                    class="<?php echo $data["event"][0]->sLangDir ?>">
                                                <sup><?php echo $verse; ?></sup>
                                            </strong>
                                            <div class="<?php echo "kwverse_".$data["currentChapter"]."_".$key."_".$verse ?>"
                                                 dir="<?php echo $data["event"][0]->sLangDir ?>">
                                                <?php echo $data["text"][$verse]; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="flex_middle editor_area" dir="<?php echo $data["event"][0]->tLangDir ?>">
                                    <?php
                                    $bt = $data["translation"][$key][EventMembers::TRANSLATOR]["bt"] != "" ?
                                        $data["translation"][$key][EventMembers::TRANSLATOR]["bt"] :
                                        $data["translation"][$key][EventMembers::TRANSLATOR]["symbols"];
                                    ?>
                                    <div class="vnote">
                                        <div class="verse_block font_backsun">
                                            <p><?php echo $bt; ?></p>
                                        </div>
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
            <div class="step_right alt"><?php echo __("step_num", ["step_number" => 6])?></div>
        </div>
    </div>
</div>

<div class="content_help closed">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps is_checker_page_help">
            <div class="help_name_steps">
                <span><?php echo __("step_num", ["step_number" => 6])?>: </span>
                <?php echo __("theo-check")?>
            </div>
            <div class="help_descr_steps">
                <ul>
                    <li><b><?php echo __("purpose") ?></b> <?php echo __("theo-check_purpose") ?></li>
                    <li><?php echo __("theo-check_help_1") ?></li>
                    <li><?php echo __("move_to_next_step", ["step" => __($data["next_step"])]) ?></li>
                </ul>
                <div class="show_tutorial_popup"> >>> <?php echo __("show_more")?></div>
            </div>
        </div>

        <div class="event_info is_checker_page_help">
            <div class="participant_info">
                <div class="additional_info">
                    <a href="/events/information-sun/<?php echo $data["event"][0]->eventID ?>"><?php echo __("event_info") ?></a>
                </div>
            </div>
        </div>

        <div class="tr_tools">
            <?php
            renderSailDict();
            renderTn($data["event"][0]->tnLangID);
            renderTw($data["event"][0]->twLangID);
            ?>
        </div>
    </div>
</div>

<div class="tutorial_container">
    <div class="tutorial_popup">
        <div class="tutorial-close glyphicon glyphicon-remove"></div>
        <div class="tutorial_pic">
            <img src="<?php echo template_url("img/steps/icons/keyword-check.png") ?>" width="100px" height="100px">
            <img src="<?php echo template_url("img/steps/big/keyword-check.png") ?>" width="280px" height="280px">
        </div>

        <div class="tutorial_content is_checker_page_help">
            <h3><?php echo __("theo-check")?></h3>
            <ul>
                <li><b><?php echo __("purpose") ?></b> <?php echo __("theo-check_purpose") ?></li>
                <li><?php echo __("theo-check_help_1") ?></li>
                <li><?php echo __("move_to_next_step", ["step" => __($data["next_step"])]) ?></li>
            </ul>
        </div>
    </div>
</div>

<!-- Data for tools -->
<input type="hidden" id="bookCode" value="<?php echo $data["event"][0]->bookCode ?>">
<input type="hidden" id="chapter" value="<?php echo $data["event"][0]->currentChapter ?>">
<input type="hidden" id="tn_lang" value="<?php echo $data["event"][0]->tnLangID ?>">
<input type="hidden" id="tq_lang" value="<?php echo $data["event"][0]->tqLangID ?>">
<input type="hidden" id="tw_lang" value="<?php echo $data["event"][0]->twLangID ?>">
<input type="hidden" id="totalVerses" value="<?php echo $data["totalVerses"] ?>">
<input type="hidden" id="targetLang" value="<?php echo $data["event"][0]->targetLang ?>">

<script>
    isChecker = true;
    isSun = true;
</script>