<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="main_content_title">
            <div class="demo_title">
                <?php echo __("demo") . " (".__("odb")." - ".__("vsail").")" ?>
            </div>
            <div><?php echo __("step_num", ["step_number" => 1]) . ": " . __("consume_odb")?></div>
        </div>
    </div>

    <div class="">
        <div class="main_content">
            <div class="main_content_text">
                <h4>Symbolic Universal Notation - <?php echo __("odb") ?> - <span class="book_name">A01 4</span></h4>

                <br>

                <p><strong><?php echo __("title") ?>:</strong> A Good Man</p>
                <p><strong><?php echo __("author") ?>:</strong> Cindy Hess Kasper</p>
                <p><strong><?php echo __("passage") ?>:</strong> Romans 3:10-18</p>
                <p><strong><?php echo __("bible_in_a_year") ?>:</strong> Numbers 26-27; Mark 8:1-21</p>
                <p><strong><?php echo __("verse") ?>:</strong> Salvation is God’s gift. It is not based on
                    anything you have done. Ephesians 2:8</p>
                <p><strong><?php echo __("thought") ?>:</strong> We are saved by God’s work </p>
                <p><strong><?php echo __("content", ["number" => 1]) ?>:</strong> My friend Jerry was a good
                    man. When he died the past said Jerry loved his family. Jerry’s wife trusted him Jerry served
                    his country in the military. Jerry was a good dad and grandfather. Jerry was a great friend.
                    Jerry was a good man. </p>
                <p><strong><?php echo __("content", ["number" => 2]) ?>:</strong> The pastor said Jerry’s good
                    things do not get him to heaven. The Bible says no one is perfect. The good things Jerry did are
                    not good enough. Jerry knew that when you sin, the cost is death and hell. His final place after
                    death was not from a good life. Jerry knew he needed Jesus. Jesus died in place of Jerry. Jerry
                    knew believing in Jesus was his way to heaven. </p>
                <p><strong><?php echo __("content", ["number" => 3]) ?>:</strong> Every person needs God to
                    forgive them. Jesus died on the cross for our sins. We cannot be good enough. We need faith in
                    Jesus. Heaven is God’s figt. It is not based on anything we do. His gift is wonderful. </p>
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
                <div class="step_right"><?php echo __("step_num", ["step_number" => 1])?></div>
            </div>
        </div>
    </div>
</div>

<div class="content_help closed">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps">
            <div class="help_name_steps">
                <span><?php echo __("step_num", ["step_number" => 1])?>:</span>
                <?php echo __("consume_odb")?>
            </div>
            <div class="help_descr_steps">
                <ul>
                    <li><b><?php echo __("purpose") ?></b> <?php echo __("consume_sun_purpose") ?></li>
                    <li><?php echo __("consume_sun_help_1") ?></li>
                    <li><?php echo __("consume_sun_help_2") ?></li>
                    <li><?php echo __("consume_sun_help_3") ?></li>
                    <li><?php echo __("move_to_next_step", ["step" => __($data["next_step"])]) ?></li>
                </ul>
                <div class="show_tutorial_popup"> >>> <?php echo __("show_more")?></div>
            </div>
        </div>

        <div class="event_info">
            <div class="participant_info">
                <div class="additional_info">
                    <a href="/events/demo-sun-odb/information"><?php echo __("event_info") ?></a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="tutorial_container">
    <div class="tutorial_popup">
        <div class="tutorial-close glyphicon glyphicon-remove"></div>
        <div class="tutorial_pic">
            <img src="<?php echo template_url("img/steps/icons/consume.png") ?>" height="100px" width="100px">
            <img src="<?php echo template_url("img/steps/big/consume.png") ?>" height="280px" width="280px">
            
        </div>

        <div class="tutorial_content">
            <h3><?php echo __("consume_odb")?></h3>
            <ul>
                <li><b><?php echo __("purpose") ?></b> <?php echo __("consume_sun_purpose") ?></li>
                <li><?php echo __("consume_sun_help_1") ?></li>
                <li><?php echo __("consume_sun_help_2") ?></li>
                <li><?php echo __("consume_sun_help_3") ?></li>
                <li><?php echo __("move_to_next_step", ["step" => __($data["next_step"])]) ?></li>
            </ul>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#next_step").click(function (e) {
            e.preventDefault();
            window.location.href = '/events/demo-sun-odb/rearrange';
            return false;
        });
    });
</script>