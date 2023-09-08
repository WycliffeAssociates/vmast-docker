<?php
use Helpers\Constants\EventMembers;
use Helpers\Constants\InputMode;

if(isset($data["error"])) return;
?>
<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="main_content_title"><?php echo __("step_num", ["step_number" => 1]) . ": " . __("multi-draft_input_mode")?></div>
    </div>

    <div class="">
        <div class="main_content">
            <form action="" method="post" id="main_form">
                <div class="main_content_text" dir="<?php echo $data["event"][0]->sLangDir ?>">
                    <h4><?php echo $data["event"][0]->tLang." - "
                            .__($data["event"][0]->bookProject)." - "
                        .($data["event"][0]->sort <= 39 ? __("old_test") : __("new_test"))." - "
                        ."<span class='book_name'>".$data["event"][0]->name." ".$data["currentChapter"]."</span>"?></h4>

                    <?php
                    $media = $data["event"][0]->inputMode == InputMode::SPEECH_TO_TEXT && $data["media"] ? $data["media"] : null;
                    require(app_path() . "Views/Components/SourceAudio.php");
                    ?>

                    <div class="source_mode">
                        <label>
                            <?php echo __("show_source") ?>
                            <input type="checkbox" autocomplete="off" checked
                                   data-toggle="toggle"
                                   data-on="ON"
                                   data-off="OFF" />
                        </label>
                    </div>

                    <div class="no_padding flex_container chunk_block">
                        <div class="flex_left">
                            <?php foreach($data["text"] as $verse => $text): ?>
                                <?php if ($verse > 0): ?>
                                <p style="margin: 0 0 10px;" class="verse_p" data-verse="<?php echo $verse ?>"><?php echo "<strong><sup>".$verse."</sup></strong> ".$text; ?></p>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>

                        <?php
                        $text = "";
                        foreach ($data["translation"] as $verse) {
                            $vNumber = key($verse[EventMembers::TRANSLATOR]["verses"]);

                            if ($vNumber > 0) {
                                $vText = $verse[EventMembers::TRANSLATOR]["verses"][$vNumber];
                                $text .= "<div class='bubble'>$vNumber</div>$vText";
                            }
                        }
                        ?>
                        <div class="flex_middle input_draft">
                            <div class="input_editor textarea"
                                 data-totalmarkers="<?php echo $data["totalVerses"] ?>"><?php echo $text ?></div>
                        </div>
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
                    <img src="<?php echo template_url("img/saving.gif") ?>" class="unsaved_alert">
                </div>
            </form>
            <div class="step_right alt"><?php echo __("step_num", ["step_number" => 1])?></div>
        </div>
    </div>
</div>

<div class="content_help closed">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps">
            <div class="help_name_steps"><span><?php echo __("step_num", ["step_number" => 1])?>: </span><?php echo __("multi-draft_input_mode")?></div>
            <div class="help_descr_steps">
                <ul>
                    <li><b><?php echo __("purpose") ?></b> <?php echo __("multi-draft_input_mode_purpose") ?></li>
                    <li><?php echo __("verse_markers_help_1") ?></li>
                    <li><?php echo __("verse_markers_help_2") ?></li>
                    <li><?php echo __("verse_markers_help_3") ?></li>
                    <li><?php echo __("verse_markers_help_4") ?></li>
                    <li><?php echo __("verse_markers_help_5", ["step" => __($data["next_step"])]) ?></li>
                </ul>
                <div class="show_tutorial_popup"> >>> <?php echo __("show_more")?></div>
            </div>
        </div>

        <div class="event_info">
            <div class="participant_info">
                <div class="additional_info">
                    <a href="/events/information/<?php echo $data["event"][0]->eventID ?>"><?php echo __("event_info") ?></a>
                </div>
            </div>
        </div>

        <div class="tr_tools"></div>
    </div>
</div>

<div class="tutorial_container">
    <div class="tutorial_popup">
        <div class="tutorial-close glyphicon glyphicon-remove"></div>
        <div class="tutorial_pic">
            <img src="<?php echo template_url("img/steps/icons/content-review.png") ?>" width="100px" height="100px">
            <img src="<?php echo template_url("img/steps/big/content-review.png") ?>" width="280px" height="280px">
        </div>

        <div class="tutorial_content">
            <h3><?php echo __("multi-draft_input_mode")?></h3>
            <ul>
                <li><b><?php echo __("purpose") ?></b> <?php echo __("multi-draft_input_mode_purpose") ?></li>
                <li><?php echo __("verse_markers_help_1") ?></li>
                <li><?php echo __("verse_markers_help_2") ?></li>
                <li><?php echo __("verse_markers_help_3") ?></li>
                <li><?php echo __("verse_markers_help_4") ?></li>
                <li><?php echo __("verse_markers_help_5", ["step" => __($data["next_step"])]) ?></li>
            </ul>
        </div>
    </div>
</div>

<script src="<?php echo template_url("js/markers.js?5") ?>"></script>
<link rel="stylesheet" href="<?php echo template_url("css/markers.css?3") ?>">

<script>
    $(document).ready(function() {
        $(".input_editor").markers({
            inputName: "draft",
            inputClass: "input_mode_ta",
            movableButton: true
        });

        $(".source_mode input").change(function () {
            const active = $(this).prop('checked');
            if (active) {
                $(".flex_left").show();
                $(".flex_middle").css("min-height", "initial");
            } else {
                $(".flex_left").hide();
                $(".flex_middle").css("min-height", 600);
            }
        });
    });
</script>