<?php

use Helpers\Constants\EventSteps;

$enableFootNotes = false;
require(app_path() . "Views/Components/CommentEditor.php");
?>

<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="main_content_title">
            <div class="demo_title"><?php echo __("demo") . " (" . __("obs") . ")" ?></div>
            <div><?php echo __("step_num", ["step_number" => 1]) . ": " . __("keyword-check") ?></div>
        </div>
    </div>

    <div class="">
        <div class="main_content">
            <form action="" method="post" id="main_form">
                <div class="main_content_text row" style="padding-left: 15px">
                    <h4><?php echo $data["targetLangName"] ?> - <span class='book_name'><?php echo __("obs") ?> 4</span></h4>

                    <div class="col-sm-12 no_padding">
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

                        <?php foreach($data["source_text"] as $key => $chunk) : ?>
                            <div class="row flex_container chunk_block chunk_block_divider">
                                <div class="chunk_verses flex_left" dir="<?php echo $data["sourceLangDir"] ?>">
                                    <div class="resource_chunk no_margin" data-chunk="<?php echo $key ?>">
                                        <div class="resource_text"><?php echo $chunk["text"] ?></div>
                                        <?php if (isset($chunk["img"])): ?>
                                            <div class="obs_img mdi mdi-image" data-img="<?php echo $chunk["img"] ?>"></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="flex_middle editor_area font_<?php echo $data["targetLang"] ?>" dir="<?php echo $data["targetLangDir"] ?>">
                                    <div class="vnote" data-chunk="<?php echo $key ?>">
                                        <textarea class="col-sm-6 peer_verse_ta textarea"><?php echo $data["target_text"][$key] ?? "" ?></textarea>
                                    </div>
                                </div>
                                <div class="flex_right">
                                    <?php
                                    $hasComments = array_key_exists($key, $data["comments"]);
                                    if ($hasComments) {
                                        $comments = $data["comments"][$key];
                                        $commentsNumber = sizeof(array_filter($comments, function($item) { return $item->saved; }));
                                    }
                                    require(app_path() . "Views/Components/Comments.php");
                                    ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="clear"></div>
                </div>

                <div class="main_content_footer row">
                    <div class="form-group">
                        <div class="main_content_confirm_desc"><?php echo __("confirm_finished") ?></div>
                        <label><input name="confirm_step" id="confirm_step" type="checkbox"
                                      value="1"/> <?php echo __("confirm_yes") ?></label>
                    </div>

                    <button id="next_step" type="submit" name="submit" class="btn btn-primary" disabled>
                        <?php echo __($data["next_step"]) ?>
                    </button>
                    <img src="<?php echo template_url("img/saving.gif") ?>" class="unsaved_alert">
                </div>
            </form>
            <div class="step_right alt"><?php echo __("step_num", ["step_number" => 1]) ?></div>
        </div>
    </div>
</div>

<div class="content_help closed">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps is_checker_page_help">
            <div class="help_name_steps">
                <span><?php echo __("step_num", ["step_number" => 1]) ?>:</span> <?php echo __(EventSteps::KEYWORD_CHECK) ?>
            </div>
            <div class="help_descr_steps">
                <ul><?php echo __("keyword-check_obs_desc", ["step" => __($data["next_step"])]) ?></ul>
                <div class="show_tutorial_popup"> >>> <?php echo __("show_more") ?></div>
            </div>
        </div>

        <div class="event_info is_checker_page_help">
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
            <img src="<?php echo template_url("img/steps/icons/keyword-check.png") ?>" width="100" height="100">
            <img src="<?php echo template_url("img/steps/big/keyword-check.png") ?>" width="280" height="280">
        </div>

        <div class="tutorial_content">
            <h3><?php echo __(EventSteps::KEYWORD_CHECK) ?></h3>
            <ul><?php echo __("keyword-check_obs_desc", ["step" => __($data["next_step"])]) ?></ul>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#next_step").click(function (e) {
            e.preventDefault();
            if (!hasChangesOnPage) window.location.href = '/events/demo-obs/peer_review';
            return false;
        });

        $(".sun_mode input").change(function () {
            const active = $(this).prop('checked');
            if (active) {
                $(".flex_middle").removeClass("font_backsun");
                $(".flex_middle").addClass("font_sgn-US-symbunot");
                $(".flex_middle .textarea").removeClass("wide");
                $(".flex_middle .textarea").addClass("narrow");
            } else {
                $(".flex_middle").removeClass("font_sgn-US-symbunot");
                $(".flex_middle").addClass("font_backsun");
                $(".flex_middle .textarea").addClass("wide");
                $(".flex_middle .textarea").removeClass("narrow");
            }
        });
    });
</script>