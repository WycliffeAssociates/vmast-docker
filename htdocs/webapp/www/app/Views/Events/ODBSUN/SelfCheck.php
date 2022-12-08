<?php
use Helpers\Constants\EventMembers;
use Helpers\Constants\OdbSections;

if(isset($data["error"])) return;

$textDirection = $data["event"][0]->tLangDir;
$fontLanguage = $data["event"][0]->targetLang;
require(app_path() . "Views/Components/CommentEditor.php");
?>

<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="main_content_title"><?php echo __("step_num", ["step_number" => 4]). ": " . __("self-check")?></div>
    </div>

    <div class="">
        <div class="main_content">
            <form action="" method="post" id="main_form">
                <div class="main_content_text row" style="padding-left: 15px">
                    <h4 dir="<?php echo $data["event"][0]->sLangDir ?>"><?php echo $data["event"][0]->tLang." - "
                            .__($data["event"][0]->sourceBible)." - "
                            ."<span class='book_name'>".$data["event"][0]->name." ".$data["currentChapter"]."</span>"?></h4>

                    <div class="col-sm-12 no_padding">
                        <?php foreach($data["chunks"] as $key => $chunk) : $verse = $chunk[0] ?>
                            <?php $hidden = $verse == OdbSections::DATE || trim($data["text"][$verse]) == ""; ?>
                            <strong class="<?php echo $data["event"][0]->sLangDir ?>" style="font-size: 20px; <?php echo $hidden ? "display: none;" : "" ?>">
                                <sup><?php echo $verse >= OdbSections::CONTENT
                                        ? __(OdbSections::enum($verse), ["number" => $verse - OdbSections::DATE])
                                        : __(OdbSections::enum($verse)); ?></sup>
                            </strong>
                            <div class="flex_container chunk_block words_block"
                                 style="width: 100%; <?php echo $hidden ? "display: none;" : "" ?>">
                                <div class="chunk_verses flex_left sun_content sun_ta" dir="<?php echo $data["event"][0]->sLangDir ?>">
                                    <?php $text = $data["translation"][$key][EventMembers::TRANSLATOR]["symbols"]; ?>
                                    <textarea name="symbols[]" class="col-sm-6 verse_ta textarea"><?php echo $text ?></textarea>
                                </div>
                                <div class="flex_middle editor_area" dir="<?php echo $data["event"][0]->tLangDir ?>">
                                    <?php $text = $data["translation"][$key][EventMembers::TRANSLATOR]["bt"] != "" ?
                                        $data["translation"][$key][EventMembers::TRANSLATOR]["bt"] :
                                        $data["translation"][$key][EventMembers::TRANSLATOR]["symbols"]; ?>
                                    <div class="vnote">
                                        <textarea name="chunks[]" class="col-sm-6 peer_verse_ta textarea font_backsun"><?php echo $text ?></textarea>
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
                            <div class="chunk_divider" style="<?php echo $hidden ? "display: none;" : "" ?>"></div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="main_content_footer row">
                    <div class="form-group">
                        <div class="main_content_confirm_desc"><?php echo __("confirm_finished")?></div>
                        <label><input name="confirm_step" id="confirm_step" type="checkbox" value="1" /> <?php echo __("confirm_yes")?></label>
                    </div>

                    <button id="next_step" type="submit" name="submit" class="btn btn-primary" disabled>
                        <?php echo __($data["next_step"])?>
                    </button>
                    <img src="<?php echo template_url("img/saving.gif") ?>" class="unsaved_alert" style="float:none">
                </div>
            </form>
            <div class="step_right alt"><?php echo __("step_num", ["step_number" => 4])?></div>
        </div>
    </div>
</div>

<div class="content_help closed">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps">
            <div class="help_name_steps"><span><?php echo __("step_num", ["step_number" => 4])?>:</span> <?php echo __("self-check")?></div>
            <div class="help_descr_steps">
                <ul>
                    <li><b><?php echo __("purpose") ?></b> <?php echo __("self-edit_sun_purpose") ?></li>
                    <li><?php echo __("self-edit_sun_help_1") ?></li>
                    <li><?php echo __("move_to_next_step", ["step" => __($data["next_step"])]) ?></li>
                </ul>
                <div class="show_tutorial_popup"> >>> <?php echo __("show_more")?></div>
            </div>
        </div>

        <div class="event_info">
            <div class="participant_info">
                <div class="additional_info">
                    <a href="/events/information-odb-sun/<?php echo $data["event"][0]->eventID ?>"><?php echo __("event_info") ?></a>
                </div>
            </div>
        </div>

        <div class="tr_tools">
            <button class="btn btn-warning ttools" data-tool="saildict"><?php echo __("show_dictionary") ?></button>
            <button class="btn btn-primary ttools" data-tool="sunbible"><?php echo __("go_sun_bible") ?></button>
        </div>
    </div>
</div>

<div class="tutorial_container">
    <div class="tutorial_popup">
        <div class="tutorial-close glyphicon glyphicon-remove"></div>
        <div class="tutorial_pic">
            <img src="<?php echo template_url("img/steps/icons/self-check.png") ?>" width="100" height="100">
            <img src="<?php echo template_url("img/steps/big/self-check.png") ?>" width="280" height="280">
        </div>

        <div class="tutorial_content">
            <h3><?php echo __("self-check")?></h3>
            <ul>
                <li><b><?php echo __("purpose") ?></b> <?php echo __("self-edit_sun_purpose") ?></li>
                <li><?php echo __("self-edit_sun_help_1") ?></li>
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
