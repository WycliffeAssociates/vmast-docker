<?php
require(app_path() . "Views/Components/CommentEditor.php");
require(app_path() . "Views/Components/HelpTools.php");
?>

<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="main_content_title">
            <div class="demo_title"><?php echo __("demo") . " (" . __("vsail") . ")" ?></div>
            <div><?php echo __("step_num", ["step_number" => 5]) . ": " . __("self-check") ?></div>
        </div>
    </div>

    <div class="">
        <div class="main_content">
            <form action="" method="post" id="main_form">
                <div class="main_content_text row" style="padding-left: 15px">

                    <h4>English - <?php echo __("sun") ?> - <?php echo __("new_test") ?> - <span class='book_name'>Matthew 17:1-27</span>
                    </h4>

                    <div class="col-sm-12 no_padding">
                        <div class="flex_container chunk_block words_block verse" style="width: 100%;">
                            <div class="chunk_verses flex_left sun_content sun_ta" dir="ltr">
                                <strong class="ltr"> <sup>1-2</sup> </strong>
                                <textarea name="symbols[]" class="peer_verse_ta narrow textarea"
                                          style="min-height: 200px">      ,                               </textarea>
                            </div>
                            <div class="flex_middle editor_area" dir="ltr">
                                <div class="vnote">
                                    <textarea name="chunks[]"
                                              class="peer_verse_ta wide textarea font_backsun"
                                              style="min-height: 200px;">      ,                               </textarea>

                                </div>
                            </div>
                            <div class="flex_right">
                                <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                        <div class="chunk_divider"></div>
                        <div class="flex_container chunk_block words_block">
                            <div class="chunk_verses flex_left sun_content sun_ta" dir="ltr">
                                <strong class="ltr"> <sup>3-4</sup> </strong>
                                <textarea name="symbols[]" class="peer_verse_ta narrow textarea"
                                          style="min-height: 200px">  ,  ,                  ”  ,        ,                  </textarea>
                            </div>
                            <div class="flex_middle editor_area" dir="ltr">
                                <div class="vnote">
                                    <textarea name="chunks[]"
                                              class="peer_verse_ta wide textarea font_backsun"
                                              style="min-height: 200px;">  ,  ,                  ”  ,        ,                  </textarea>

                                </div>
                            </div>
                            <div class="flex_right">
                                <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                        <div class="chunk_divider"></div>
                        <div class="flex_container chunk_block words_block">
                            <div class="chunk_verses flex_left sun_content sun_ta" dir="ltr">
                                <strong class="ltr"> <sup>5-9</sup> </strong>
                                <textarea name="symbols[]" class="peer_verse_ta narrow textarea"
                                          style="min-height: 350px;">    ,           ”                  ,                  ”      ”                  ”        </textarea>
                            </div>
                            <div class="flex_middle editor_area" dir="ltr">
                                <div class="vnote">
                                    <textarea name="chunks[]"
                                              class="peer_verse_ta wide textarea font_backsun"
                                              style="min-height: 350px;">    ,           ”                  ,                  ”      ”                  ”        </textarea>

                                </div>
                            </div>
                            <div class="flex_right">
                                <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                        <div class="chunk_divider"></div>
                        <div class="flex_container chunk_block words_block">
                            <div class="chunk_verses flex_left sun_content sun_ta" dir="ltr">
                                <strong class="ltr"> <sup>10-13</sup> </strong>
                                <textarea name="symbols[]" class="peer_verse_ta narrow textarea"
                                          style="min-height: 240px;">    ”       ? “    ”           ,                           </textarea>
                            </div>
                            <div class="flex_middle editor_area" dir="ltr">
                                <div class="vnote">
                                    <textarea name="chunks[]"
                                              class="peer_verse_ta wide textarea font_backsun"
                                              style="min-height: 240px;">    ”       ? “    ”           ,                           </textarea>

                                </div>
                            </div>
                            <div class="flex_right">
                                <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                        <div class="chunk_divider"></div>
                        <div class="flex_container chunk_block words_block">
                            <div class="chunk_verses flex_left sun_content sun_ta" dir="ltr">
                                <strong class="ltr"> <sup>14-16</sup> </strong>
                                <textarea name="symbols[]" class="peer_verse_ta narrow textarea"
                                          style="min-height: 260px;">                     ”  ,                                  ” </textarea>
                            </div>
                            <div class="flex_middle editor_area" dir="ltr">
                                <div class="vnote">
                                    <textarea name="chunks[]"
                                              class="peer_verse_ta wide textarea font_backsun"
                                              style="min-height: 260px;">                     ”  ,                                  ” </textarea>

                                </div>
                            </div>
                            <div class="flex_right">
                                <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                        <div class="chunk_divider"></div>
                        <div class="flex_container chunk_block words_block">
                            <div class="chunk_verses flex_left sun_content sun_ta" dir="ltr">
                                <strong class="ltr"> <sup>17-18</sup> </strong>
                                <textarea name="symbols[]" class="peer_verse_ta narrow textarea"
                                          style="min-height: 170px;">    ”             ?        ”                </textarea>
                            </div>
                            <div class="flex_middle editor_area" dir="ltr">
                                <div class="vnote">
                                    <textarea name="chunks[]"
                                              class="peer_verse_ta wide textarea font_backsun"
                                              style="min-height: 170px;">    ”             ?        ”                </textarea>

                                </div>
                            </div>
                            <div class="flex_right">
                                <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                        <div class="chunk_divider"></div>
                        <div class="flex_container chunk_block words_block">
                            <div class="chunk_verses flex_left sun_content sun_ta" dir="ltr">
                                <strong class="ltr"> <sup>19-20</sup> </strong>
                                <textarea name="symbols[]" class="peer_verse_ta narrow textarea"
                                          style="min-height: 260px;">          ”        ? “   ”                       ’       ’         ” </textarea>
                            </div>
                            <div class="flex_middle editor_area" dir="ltr">
                                <div class="vnote">
                                    <textarea name="chunks[]"
                                              class="peer_verse_ta wide textarea font_backsun"
                                              style="min-height: 260px;">          ”        ? “   ”                      ’       ’         ” </textarea>

                                </div>
                            </div>
                            <div class="flex_right">
                                <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                        <div class="chunk_divider"></div>
                        <div class="flex_container chunk_block words_block">
                            <div class="chunk_verses flex_left sun_content sun_ta" dir="ltr">
                                <strong class="ltr"> <sup>21-23</sup> </strong>
                                <textarea name="symbols[]" class="peer_verse_ta narrow textarea"
                                          style="min-height: 200px;"> Verse 21 removed in ULB.            ”                ”     </textarea>
                            </div>
                            <div class="flex_middle editor_area" dir="ltr">
                                <div class="vnote">
                                    <textarea name="chunks[]"
                                              class="peer_verse_ta wide textarea font_backsun"
                                              style="min-height: 200px;"> Verse 21 removed in ULB.            ”                ”     </textarea>

                                </div>
                            </div>
                            <div class="flex_right">
                                <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                        <div class="chunk_divider"></div>
                        <div class="flex_container chunk_block words_block">
                            <div class="chunk_verses flex_left sun_content sun_ta" dir="ltr">
                                <strong class="ltr"> <sup>24-27</sup> </strong>
                                <textarea name="symbols[]" class="peer_verse_ta narrow textarea"
                                          style="min-height: 450px;">                 ? “   ”  ”     ,      "    ?      ?   , “  ”    ,                                            ” </textarea>
                            </div>
                            <div class="flex_middle editor_area" dir="ltr">
                                <div class="vnote">
                                    <textarea name="chunks[]"
                                              class="peer_verse_ta wide textarea font_backsun"
                                              style="min-height: 450px;">                 ? “   ”  ”     ,      "    ?      ?   , “  ”    ,                                            ” </textarea>

                                </div>
                            </div>
                            <div class="flex_right">
                                <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                        <div class="chunk_divider"></div>
                    </div>
                </div>

                <div class="main_content_footer row">
                    <div class="form-group">
                        <div class="main_content_confirm_desc"><?php echo __("confirm_finished") ?></div>
                        <label><input name="confirm_step" id="confirm_step" value="1"
                                      type="checkbox"> <?php echo __("confirm_yes") ?></label>
                    </div>

                    <button id="next_step" class="btn btn-primary" disabled="disabled">
                        <?php echo __($data["next_step"]) ?>
                    </button>
                    <img src="<?php echo template_url("img/saving.gif") ?>" class="unsaved_alert">
                </div>
            </form>
            <div class="step_right alt"><?php echo __("step_num", ["step_number" => 5]) ?></div>
        </div>
    </div>
</div>

<div class="content_help closed">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps">
            <div class="help_name_steps">
                <span><?php echo __("step_num", ["step_number" => 5]) ?>:</span> <?php echo __("self-check") ?></div>
            <div class="help_descr_steps">
                <ul>
                    <li><b><?php echo __("purpose") ?></b> <?php echo __("self-edit_sun_purpose") ?></li>
                    <li><?php echo __("self-edit_sun_help_1") ?></li>
                    <li><?php echo __("move_to_next_step", ["step" => __($data["next_step"])]) ?></li>
                </ul>
                <div class="show_tutorial_popup"> >>> <?php echo __("show_more") ?></div>
            </div>
        </div>

        <div class="event_info">
            <div class="participant_info">
                <div class="additional_info">
                    <a href="/events/demo-sun/information"><?php echo __("event_info") ?></a>
                </div>
            </div>
        </div>

        <div class="tr_tools">
            <?php
            renderSailDict();
            renderTn($data["tnLangID"]);
            renderTw($data["twLangID"]);
            ?>
        </div>
    </div>
</div>

<div class="tutorial_container">
    <div class="tutorial_popup">
        <div class="tutorial-close glyphicon glyphicon-remove"></div>
        <div class="tutorial_pic">
            <img src="<?php echo template_url("img/steps/icons/self-check.png") ?>" height="100px" width="100px">
            <img src="<?php echo template_url("img/steps/big/self-check.png") ?>" height="280px" width="280px">

        </div>

        <div class="tutorial_content">
            <h3><?php echo __("self-check") ?></h3>
            <ul>
                <li><b><?php echo __("purpose") ?></b> <?php echo __("self-edit_sun_purpose") ?></li>
                <li><?php echo __("self-edit_sun_help_1") ?></li>
                <li><?php echo __("move_to_next_step", ["step" => __($data["next_step"])]) ?></li>
            </ul>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#next_step").click(function (e) {
            e.preventDefault();
            if (!hasChangesOnPage) window.location.href = '/events/demo-sun/theo_check_checker';
            return false;
        });

        $(".ttools_panel .word_def").each(function () {
            let html = convertRcLinks($(this).html());
            $(this).html(html);
        });
    });
</script>