<?php require(app_path() . "Views/Components/HelpTools.php"); ?>

<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="main_content_title">
            <div class="demo_title"><?php echo __("demo") . " (".__("vsail").")" ?></div>
            <div><?php echo __("step_num", ["step_number" => 3]). ": " . __("rearrange")?></div>
        </div>
    </div>

    <div class="">
        <div class="main_content">
            <form action="" method="post" id="main_form">
                <div class="main_content_text row" style="padding-left: 15px">
                    <h4 dir="ltr">English - <?php echo __("sun") ?> - <?php echo __("new_test") ?> - <span class="book_name">Matthew 17:1-2</span> </h4>

                    <div class="col-sm-12 no_padding">
                        <div class="row chunk_block words_block">
                            <div class="chunk_verses col-sm-6" dir="ltr">
                                <p><strong><sup>1</sup></strong> Six days later Jesus took with him Peter, James, and John his brother,
                                    and brought them up a high mountain by themselves.</p>
                                <p><strong><sup>2</sup></strong> He was transfigured before them. His face shone like the sun, and
                                    his garments became as brilliant as the light.</p>
                            </div>
                            <div class="col-sm-6 editor_area" dir="ltr">
                                <textarea name="draft"
                                          class="col-sm-6 verse_ta textarea"
                                          style="overflow: hidden; word-wrap: break-word; height: 80px; min-height: 150px;"></textarea>
                            </div>
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
                    <img src="<?php echo template_url("img/saving.gif") ?>" class="unsaved_alert" style="float:none">
                </div>
            </form>
            <div class="step_right alt"><?php echo __("step_num", ["step_number" => 3])?></div>
        </div>
    </div>
</div>

<div class="content_help closed">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps">
            <div class="help_name_steps"><span><?php echo __("step_num", ["step_number" => 3])?>:</span> <?php echo __("rearrange")?></div>
            <div class="help_descr_steps">
                <ul>
                    <li><b><?php echo __("purpose") ?></b> <?php echo __("rearrange_purpose") ?></li>
                    <li><?php echo __("rearrange_help_1") ?>
                        <ol>
                            <li><?php echo __("rearrange_help_1a") ?></li>
                            <li><?php echo __("rearrange_help_1b") ?></li>
                            <li><?php echo __("rearrange_help_1c") ?>
                                <ul>
                                    <li><?php echo __("rearrange_help_1c1") ?></li>
                                    <li><?php echo __("rearrange_help_1c2") ?></li>
                                    <li><?php echo __("rearrange_help_1c3") ?></li>
                                    <li><?php echo __("rearrange_help_1c4") ?></li>
                                </ul>
                            </li>
                            <li><?php echo __("rearrange_help_1d") ?>
                                <ul>
                                    <li><?php echo __("rearrange_help_1d1") ?></li>
                                    <li><?php echo __("rearrange_help_1d2") ?></li>
                                </ul>
                            </li>
                            <li><?php echo __("rearrange_help_1e") ?></li>
                            <li><?php echo __("rearrange_help_1f") ?></li>
                            <li><?php echo __("rearrange_help_1g") ?></li>
                            <li><?php echo __("rearrange_help_1h") ?></li>
                            <li><?php echo __("rearrange_help_1i") ?></li>
                            <li><?php echo __("rearrange_help_1j") ?></li>
                            <li><?php echo __("rearrange_help_1k") ?></li>
                        </ol>
                    </li>
                    <li><?php echo __("rearrange_help_2") ?>
                        <ol>
                            <li><?php echo __("rearrange_help_2a") ?></li>
                            <li><?php echo __("rearrange_help_2b") ?></li>
                            <li><?php echo __("rearrange_help_2c") ?></li>
                            <li><?php echo __("rearrange_help_2d") ?></li>
                        </ol>
                    </li>
                </ul>
                <div class="show_tutorial_popup"> >>> <?php echo __("show_more")?></div>
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
            <img src="<?php echo template_url("img/steps/icons/rearrange.png") ?>" width="100" height="100">
            <img src="<?php echo template_url("img/steps/big/rearrange.png") ?>" width="280" height="280">
            
        </div>

        <div class="tutorial_content">
            <h3><?php echo __("rearrange")?></h3>
            <ul>
                <li><b><?php echo __("purpose") ?></b> <?php echo __("rearrange_purpose") ?></li>
                <li><?php echo __("rearrange_help_1") ?>
                    <ol>
                        <li><?php echo __("rearrange_help_1a") ?></li>
                        <li><?php echo __("rearrange_help_1b") ?></li>
                        <li><?php echo __("rearrange_help_1c") ?>
                            <ul>
                                <li><?php echo __("rearrange_help_1c1") ?></li>
                                <li><?php echo __("rearrange_help_1c2") ?></li>
                                <li><?php echo __("rearrange_help_1c3") ?></li>
                                <li><?php echo __("rearrange_help_1c4") ?></li>
                            </ul>
                        </li>
                        <li><?php echo __("rearrange_help_1d") ?>
                            <ul>
                                <li><?php echo __("rearrange_help_1d1") ?></li>
                                <li><?php echo __("rearrange_help_1d2") ?></li>
                            </ul>
                        </li>
                        <li><?php echo __("rearrange_help_1e") ?></li>
                        <li><?php echo __("rearrange_help_1f") ?></li>
                        <li><?php echo __("rearrange_help_1g") ?></li>
                        <li><?php echo __("rearrange_help_1h") ?></li>
                        <li><?php echo __("rearrange_help_1i") ?></li>
                        <li><?php echo __("rearrange_help_1j") ?></li>
                        <li><?php echo __("rearrange_help_1k") ?></li>
                    </ol>
                </li>
                <li><?php echo __("rearrange_help_2") ?>
                    <ol>
                        <li><?php echo __("rearrange_help_2a") ?></li>
                        <li><?php echo __("rearrange_help_2b") ?></li>
                        <li><?php echo __("rearrange_help_2c") ?></li>
                        <li><?php echo __("rearrange_help_2d") ?></li>
                    </ol>
                </li>
            </ul>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#next_step").click(function (e) {
            e.preventDefault();
            if(!hasChangesOnPage) window.location.href = '/events/demo-sun/symbol-draft';
            return false;
        });

        $(".ttools_panel .word_def").each(function() {
            let html = convertRcLinks($(this).html());
            $(this).html(html);
        });
    });
</script>