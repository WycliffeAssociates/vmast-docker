<?php
if(isset($data["error"])) return;

use Helpers\Constants\EventMembers;

require(app_path() . "Views/Components/HelpTools.php");
?>
<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="main_content_title"><?php echo __("step_num", ["step_number" => 1]) . ": " . __("consume")?></div>
    </div>

    <div class="">
        <div class="main_content">
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
                                                <p class="verse_text" data-verse="<?php echo $verse ?>">
                                                    <strong class="<?php echo $data["event"][0]->sLangDir ?>">
                                                        <sup><?php echo $verse; ?></sup>
                                                    </strong>
                                                    <?php echo $data["text"][$verse]; ?>
                                                </p>
                                            </div>
                                            <div class="flex_one editor_area sun_content font_<?php echo $data["event"][0]->targetLang ?>" dir="<?php echo $data["event"][0]->tLangDir ?>">
                                                <div class="verse_block sun flex_chunk" data-verse="<?php echo $verse ?>">
                                                    <?php echo $verses[$verse]; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <div class="chunk_divider"></div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="main_content_footer row">
                <form action="" method="post">
                    <div class="form-group">
                        <div class="main_content_confirm_desc"><?php echo __("confirm_finished")?></div>
                        <label><input name="confirm_step" id="confirm_step" type="checkbox" value="1" /> <?php echo __("confirm_yes")?></label>
                    </div>

                    <button id="next_step" type="submit" name="submit" class="btn btn-primary" disabled>
                        <?php echo __($data["next_step"])?>
                    </button>
                </form>
                <div class="step_right"><?php echo __("step_num", ["step_number" => 1])?></div>
            </div>
        </div>
    </div>
</div>

<div class="content_help closed">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps is_checker_page_help">
            <div class="help_name_steps"><span><?php echo __("step_num", ["step_number" => 1])?>: </span><?php echo __("consume")?></div>
            <div class="help_descr_steps">
                <ul>
                    <li><b><?php echo __("purpose") ?></b> <?php echo __("consume_sun_l2_purpose") ?></li>
                    <li><?php echo __("consume_sun_l2_help_1") ?></li>
                    <li><?php echo __("consume_sun_help_2") ?></li>
                    <li><?php echo __("move_to_next_step", ["step" => __($data["next_step"])]) ?></li>
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
            <?php renderSailDict(); ?>
        </div>
    </div>
</div>

<!-- Data for tools -->
<input type="hidden" id="bookCode" value="<?php echo $data["event"][0]->bookCode ?>">
<input type="hidden" id="chapter" value="<?php echo $data["event"][0]->currentChapter ?>">
<input type="hidden" id="totalVerses" value="<?php echo $data["totalVerses"] ?>">
<input type="hidden" id="targetLang" value="<?php echo $data["event"][0]->targetLang ?>">

<div class="tutorial_container">
    <div class="tutorial_popup">
        <div class="tutorial-close glyphicon glyphicon-remove"></div>
        <div class="tutorial_pic">
            <img src="<?php echo template_url("img/steps/icons/consume.png") ?>" width="100px" height="100px">
            <img src="<?php echo template_url("img/steps/big/consume.png") ?>" width="280px" height="280px">
        </div>

        <div class="tutorial_content">
            <h3><?php echo __("consume")?></h3>
            <ul>
                <li><b><?php echo __("purpose") ?></b> <?php echo __("consume_sun_l2_purpose") ?></li>
                <li><?php echo __("consume_sun_l2_help_1") ?></li>
                <li><?php echo __("consume_sun_help_2") ?></li>
                <li><?php echo __("move_to_next_step", ["step" => __($data["next_step"])]) ?></li>
            </ul>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $(".sun_mode input").change(function () {
            const active = $(this).prop('checked');
            if (active) {
                $(".editor_area").removeClass("font_backsun");
                $(".editor_area").addClass("sun_content");
            } else {
                $(".editor_area").removeClass("sun_content");
                $(".editor_area").addClass("font_backsun");
            }
        });
    });
</script>