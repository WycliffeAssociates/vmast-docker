<?php require(app_path() . "Views/Components/HelpTools.php"); ?>
<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="action_type_container">
            <div class="demo_title"><?php echo __("demo") . " (".__("8steps_vmast").")" ?></div>
        </div>
        <div class="main_content_title">
            <div><?php echo __("step_num", ["step_number" => 4]) . ": "   . __("read-chunk")?></div>
        </div>
    </div>

    <div class="main_content">
        <div class="main_content_text">
            <h4>English - <?php echo __("ulb") ?> - <?php echo __("new_test") ?> - <span class="book_name">2 Timothy 2:1-3</span></h4>

            <p><strong><sup>1</sup></strong> You therefore, my child, be strengthened in the grace that is in Christ Jesus.</p>
            <p><strong><sup>2</sup></strong> And the things you heard from me among many witnesses, entrust them to faithful people who will be able to teach others also.</p>
            <p><strong><sup>3</sup></strong> Suffer hardship with me, as a good soldier of Christ Jesus.</p>
        </div>

        <div class="main_content_footer row">
            <form action="" method="post">
                <div class="form-group">
                    <div class="main_content_confirm_desc"><?php echo __("confirm_finished")?></div>
                    <label><input name="confirm_step" id="confirm_step" value="1" type="checkbox"> <?php echo __("confirm_yes")?></label>
                </div>

                <button id="next_step" class="btn btn-primary" disabled="disabled">
                    <?php echo __($data["next_step"])?>
                </button>
            </form>
            <div class="step_right"><?php echo __("step_num", ["step_number" => 4])?></div>
        </div>
    </div>
</div>

<div class="content_help closed">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps">
            <div class="help_name_steps"><span><?php echo __("step_num", ["step_number" => 4])?>:</span> <?php echo __("read-chunk")?></div>
            <div class="help_descr_steps">
                <ul>
                    <li><b><?php echo __("purpose") ?></b> <?php echo __("read-chunk_purpose") ?></li>
                    <li><b><?php echo __("length") ?></b> <?php echo __("read-chunk_length") ?></li>
                    <li><?php echo __("read-chunk_help_1") ?></li>
                    <li><?php echo __("read-chunk_help_2") ?></li>
                    <li><?php echo __("read-chunk_help_3") ?></li>
                    <li><?php echo __("move_to_next_step", ["step" => __($data["next_step"])]) ?></li>
                </ul>
                <div class="show_tutorial_popup"> >>> <?php echo __("show_more")?></div>
            </div>
        </div>

        <div class="event_info">
            <div class="participant_info">
                <div class="additional_info">
                    <a href="/events/demo/information"><?php echo __("event_info") ?></a>
                </div>
            </div>
        </div>

        <div class="tr_tools">
            <?php renderRubric(); ?>
        </div>
    </div>
</div>

<div class="tutorial_container">
    <div class="tutorial_popup">
        <div class="tutorial-close glyphicon glyphicon-remove"></div>
        <div class="tutorial_pic">
            <img src="<?php echo template_url("img/steps/icons/blind-draft.png") ?>" height="100px" width="100px">
            <img src="<?php echo template_url("img/steps/big/blind-draft.png") ?>" height="280px" width="280px">
        </div>

        <div class="tutorial_content">
            <h3><?php echo __("read-chunk")?></h3>
            <ul>
                <li><b><?php echo __("purpose") ?></b> <?php echo __("read-chunk_purpose") ?></li>
                <li><b><?php echo __("length") ?></b> <?php echo __("read-chunk_length") ?></li>
                <li><?php echo __("read-chunk_help_1") ?></li>
                <li><?php echo __("read-chunk_help_2") ?></li>
                <li><?php echo __("read-chunk_help_3") ?></li>
                <li><?php echo __("move_to_next_step", ["step" => __($data["next_step"])]) ?></li>
            </ul>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#next_step").click(function (e) {
            e.preventDefault();
            if(!hasChangesOnPage) window.location.href = '/events/demo/blind_draft';
            return false;
        });
    });
</script>