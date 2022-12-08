<?php
use Helpers\Constants\EventSteps;
?>

<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="main_content_title">
            <div class="demo_title"><?php echo __("demo") . " (".__("obs").")" ?></div>
            <div><?php echo __("step_num", ["step_number" => 1]) . ": " . __(EventSteps::BLIND_DRAFT)?></div>
        </div>
    </div>

    <div class="">
        <div class="main_content">
            <div class="main_content_text" dir="ltr">
                <h4><?php echo $data["targetLangName"] ?> - <span class='book_name'><?php echo __("obs") ?> 4:2</span></h4>

                <ul class="nav nav-tabs">
                    <li role="presentation" id="read_chunk" class="my_tab">
                        <a href="#"><?php echo __("read_chunk") ?></a>
                    </li>
                    <li role="presentation" id="translate_chunk" class="my_tab">
                        <a href="#"><?php echo __("translate_chunk") ?></a>
                    </li>
                </ul>

                <div id="read_chunk_content" class="my_content shown">
                    <div class="resource_chunk">
                        <div>Many years after the flood, there were again many people in the world, and they still sinned against God and each other. Because they all spoke the same language, they gathered together and built a city instead of spreading out over the earth as God had commanded.</div>
                    </div>
                    <img src="https://cdn.door43.org/obs/jpg/360px/obs-en-04-01.jpg">
                </div>

                <div id="translate_chunk_content" class="my_content">
                    <?php if (str_contains($data["targetLang"], "sgn")): ?>
                        <div class="sun_mode">
                            <label>
                                <input type="checkbox" autocomplete="off" checked
                                       data-toggle="toggle"
                                       data-width="100"
                                       data-on="SUN"
                                       data-off="BACKSUN" />
                            </label>
                        </div>
                    <?php endif; ?>

                    <div class="textarea_content font_<?php echo $data["targetLang"] ?>" dir="ltr">
                        <textarea name="draft" rows="5" class="blind_ta textarea" style="overflow: hidden; overflow-wrap: break-word; height: 173px;"></textarea>
                        <input type="hidden" name="img" value="https://cdn.door43.org/obs/jpg/360px/obs-en-04-01.jpg">
                    </div>
                </div>
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
                    <img src="<?php echo template_url("img/saving.gif") ?>" class="unsaved_alert">
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
            <div class="help_name_steps"><span><?php echo __("step_num", ["step_number" => 1])?>:</span> <?php echo __(EventSteps::BLIND_DRAFT)?></div>
            <div class="help_descr_steps">
                <ul><?php echo __("blind-draft_obs_desc", ["step" => __($data["next_step"])])?></ul>
                <div class="show_tutorial_popup"> >>> <?php echo __("show_more")?></div>
            </div>
        </div>

        <div class="event_info">
            <div class="participant_info">
                <div class="additional_info">
                    <a href="/events/demo-obs/information"><?php echo __("event_info") ?></a>
                </div>
            </div>
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
            <h3><?php echo __(EventSteps::BLIND_DRAFT)?></h3>
            <ul><?php echo __("blind-draft_obs_desc", ["step" => __($data["next_step"])])?></ul>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#next_step").click(function (e) {
            e.preventDefault();
            if(!hasChangesOnPage) window.location.href = '/events/demo-obs/self_check';
            return false;
        });

        $(".sun_mode input").change(function () {
            var active = $(this).prop('checked');

            if (active) {
                $(".textarea_content").removeClass("font_backsun");
                $(".textarea_content").addClass("font_sgn-US-symbunot");
            } else {
                $(".textarea_content").removeClass("font_sgn-US-symbunot");
                $(".textarea_content").addClass("font_backsun");
            }
        });
    });
</script>