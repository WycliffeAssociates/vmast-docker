<?php
use Helpers\Constants\EventMembers;
use Helpers\Constants\EventSteps;

if(isset($data["error"])) return;
?>

<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="main_content_title"><?php echo __("step_num", ["step_number" => 1]) . ": " . __(EventSteps::MULTI_DRAFT)?></div>
    </div>

    <div class="" style="position: relative">
        <div class="main_content">
            <form action="" id="main_form" method="post">
                <div class="main_content_text">
                    <h4><?php echo $data["event"][0]->tLang." - "
                            ."<span class='book_name'>" . __("bc") . " - ".$data["event"][0]->name." ".
                            ($data["event"][0]->currentChapter > 0 ? $data["event"][0]->currentChapter : __("intro"))."</span>"?></h4>

                    <div class="col-sm-12 no_padding">
                        <?php if (str_contains($data["event"][0]->targetLang, "sgn")): ?>
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

                        <?php foreach($data["chunks"] as $key => $chunk) : ?>
                            <div class="row flex_container chunk_block">
                                <div class="chunk_verses flex_left" dir="<?php echo $data["event"][0]->sLangDir ?>">
                                    <div class="resource_chunk no_margin" data-chunk="<?php echo $key ?>">
                                        <div class="resource_text"><?php echo $data["bc"]->get($key)->text ?></div>
                                    </div>
                                </div>
                                <div class="flex_middle editor_area font_<?php echo $data["event"][0]->targetLang ?>" dir="<?php echo $data["event"][0]->tLangDir ?>">
                                    <?php $translation = isset($data["translation"][$key]) ? $data["translation"][$key][EventMembers::TRANSLATOR]["verses"] : ""; ?>
                                    <div class="vnote" data-chunk="<?php echo $key ?>">
                                        <textarea name="chunks[<?php echo $key ?>][text]" class="peer_verse_ta textarea"><?php echo $translation["text"] ?? "" ?></textarea>
                                        <input name="chunks[<?php echo $key ?>][meta]" type="hidden" value="<?php echo $data["bc"][$key]->meta ?? "" ?>" />
                                        <input name="chunks[<?php echo $key ?>][type]" type="hidden" value="<?php echo $data["bc"][$key]->type ?? "" ?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="chunk_divider"></div>
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
            <div class="step_right alt"><?php echo __("step_num", ["step_number" => 1])?></div>
        </div>
    </div>
</div>

<div class="content_help closed">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps">
            <div class="help_name_steps"><span><?php echo __("step_num", ["step_number" => 1])?>: </span><?php echo __(EventSteps::MULTI_DRAFT)?></div>
            <div class="help_descr_steps">
                <ul><?php echo __("multi-draft_bc_desc", ["step" => __($data["next_step"])])?></ul>
                <div class="show_tutorial_popup"> >>> <?php echo __("show_more")?></div>
            </div>
        </div>

        <div class="event_info">
            <div class="participant_info">
                <div class="additional_info">
                    <a href="/events/information-bc/<?php echo $data["event"][0]->eventID ?>"><?php echo __("event_info") ?></a>
                </div>
            </div>
        </div>

        <div class="tr_tools">
            <?php if (str_contains($data["event"][0]->targetLang, "sgn")): ?>
                <button class="btn btn-warning ttools" data-tool="saildict"><?php echo __("show_dictionary") ?></button>
            <?php endif; ?>
        </div>
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
            <h3><?php echo __(EventSteps::MULTI_DRAFT)?></h3>
            <ul><?php echo __("multi-draft_bc_desc", ["step" => __($data["next_step"])])?></ul>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
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