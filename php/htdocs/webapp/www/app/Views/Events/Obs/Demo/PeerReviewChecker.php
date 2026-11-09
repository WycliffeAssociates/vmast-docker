<?php

use Helpers\Constants\EventSteps;

$enableFootNotes = false;
require(app_path() . "Views/Components/CommentEditor.php");
?>

<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="action_type_container">
            <div class="demo_title"><?php echo __("demo") . " (" . __("obs") . ")" ?></div>
            <div class="action_type type_checking isPeer"><?php echo __("type_checking2"); ?></div>
            <div class="action_region"></div>
        </div>
        <div class="main_content_title">
            <div><?php echo __("step_num", ["step_number" => 2]) . ": " . __(EventSteps::PEER_REVIEW . "_obs") ?></div>
        </div>
    </div>

    <div class="">
        <div class="main_content">
            <div class="main_content_text" dir="ltr">
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

                    <?php foreach($data["source_text"] as $key => $chunk): ?>
                        <div class="row chunk_block chunk_block_divider">
                            <div class="flex_container">
                                <div class="chunk_verses flex_left" dir="<?php echo $data["sourceLangDir"] ?>">
                                    <div class="resource_chunk no_margin" data-chunk="<?php echo $key ?>">
                                        <div class="resource_text"><?php echo $chunk["text"] ?></div>
                                        <?php if (isset($chunk["img"])): ?>
                                            <div class="obs_img mdi mdi-image" data-img="<?php echo $chunk["img"] ?>"></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="flex_middle font_<?php echo $data["targetLang"] ?>"
                                     dir="<?php echo $data["targetLangDir"] ?>">
                                    <div class="chunk_translator" data-chunk="<?php echo $key ?>"><?php echo $data["target_text"][$key] ?></div>
                                    <div class="chunk_checker" data-chunk="<?php echo $key ?>"><?php echo $data["target_text"][$key]; ?></div>
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
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="clear"></div>
            </div>

            <div class="main_content_footer row">
                <form action="" method="post">
                    <div class="form-group">
                        <div class="main_content_confirm_desc"><?php echo __("confirm_finished") ?></div>
                        <label><input name="confirm_step" id="confirm_step" value="1"
                                      type="checkbox"> <?php echo __("confirm_yes") ?></label>
                    </div>

                    <button id="checker_ready" class="btn btn-warning" disabled>
                        <?php echo __("ready_to_check")?>
                    </button>
                    <button id="next_step" class="btn btn-primary" disabled="disabled">
                        <?php echo __($data["next_step"]) ?>
                    </button>
                </form>
                <div class="step_right"><?php echo __("step_num", ["step_number" => 2]) ?></div>
            </div>
        </div>
    </div>
</div>

<div class="content_help closed">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps is_checker_page_help isPeer">
            <div class="help_name_steps">
                <span><?php echo __("step_num", ["step_number" => 2]) ?>:</span> <?php echo __(EventSteps::PEER_REVIEW . "_obs") ?>
            </div>
            <div class="help_descr_steps">
                <ul><?php echo __("peer-review_obs_chk_desc", ["step" => __($data["next_step"])]) ?></ul>
                <div class="show_tutorial_popup"> >>> <?php echo __("show_more") ?></div>
            </div>
        </div>

        <div class="event_info is_checker_page_help isPeer">
            <div class="participant_info">
                <div class="participant_name">
                    <span><?php echo __("your_partner") ?>:</span>
                    <span class="checker_name_span">
                                Ketut S.
                            </span>
                </div>
                <div class="additional_info">
                    <a href="/events/demo-obs/information"><?php echo __("event_info") ?></a>
                </div>
            </div>
        </div>

        <div class="checker_view">
            <a href="/events/demo-obs/peer_review"><?php echo __("checker_other_view", [1]) ?></a>
        </div>
    </div>
</div>

<div class="tutorial_container">
    <div class="tutorial_popup">
        <div class="tutorial-close glyphicon glyphicon-remove"></div>
        <div class="tutorial_pic">
            <img src="<?php echo template_url("img/steps/icons/peer-review.png") ?>" height="100px" width="100px">
            <img src="<?php echo template_url("img/steps/big/peer-review.png") ?>" height="280px" width="280px">
        </div>

        <div class="tutorial_content is_checker_page_help isPeer">
            <h3><?php echo __(EventSteps::PEER_REVIEW . "_obs") ?></h3>
            <ul><?php echo __("peer-review_obs_chk_desc", ["step" => __($data["next_step"])]) ?></ul>
        </div>
    </div>
</div>

<script>
    var isChecker = true;
    $(document).ready(function () {
        $("#next_step").click(function (e) {
            e.preventDefault();
            window.location.href = '/events/demo-obs/peer_review';
            return false;
        });

        $(".sun_mode input").change(function () {
            const active = $(this).prop('checked');
            if (active) {
                $(".flex_middle").removeClass("font_backsun");
                $(".flex_middle").addClass("font_sgn-US-symbunot");
            } else {
                $(".flex_middle").removeClass("font_sgn-US-symbunot");
                $(".flex_middle").addClass("font_backsun");
            }

            $(".resource_chunk").css("height", "initial");
            $(".chunk_translator").css("height", "initial");
        });
    });
</script>