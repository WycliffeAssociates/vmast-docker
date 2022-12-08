<?php
use Helpers\Constants\EventSteps;
use Helpers\Constants\EventMembers;

if(isset($data["error"])) return;
?>
<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="main_content_title"><?php echo __("step_num", ["step_number" => 1]) . ": " . __(EventSteps::BLIND_DRAFT)?></div>
    </div>

    <div class="" style="position: relative">
        <div class="main_content">
            <form action="" id="main_form" method="post">
                <div class="main_content_text">

                    <h4><?php echo $data["event"][0]->tLang." - "
                            ."<span class='book_name'>" . __("bca") . " - ".$data["event"][0]->name." "
                            . " - " . $data["word"]
                            .":".($data["event"][0]->currentChunk+1)."</span>"?></h4>

                    <ul class="nav nav-tabs">
                        <li role="presentation" id="read_chunk" class="my_tab">
                            <a href="#"><?php echo __("read_chunk") ?></a>
                        </li>
                        <li role="presentation" id="translate_chunk" class="my_tab">
                            <a href="#"><?php echo __("translate_chunk") ?></a>
                        </li>
                    </ul>

                    <div id="read_chunk_content" class="my_content shown" dir="<?php echo $data["event"][0]->resLangDir ?>">
                        <div class="resource_chunk">
                            <div><?php echo $data["bca"]->text ?></div>
                        </div>
                    </div>

                    <div id="translate_chunk_content" class="my_content" dir="<?php echo $data["event"][0]->tLangDir ?>">
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

                        <div class="textarea_content font_<?php echo $data["event"][0]->targetLang ?>"
                             dir="<?php echo $data["event"][0]->tLangDir ?>">
                            <?php
                            $translation = $data["translation"] ?? "";
                            ?>
                            <textarea name="draft" rows="5"
                                      class="blind_ta textarea"><?php echo $translation["text"] ?? "" ?></textarea>
                            <input type="hidden" name="type" value="<?php echo $data["bca"]->type ?? "" ?>" />
                            <input type="hidden" name="meta" value="<?php echo $data["bca"]->meta ?? "" ?>" />
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
            <div class="step_right alt"><?php echo __("step_num", ["step_number" => 1])?></div>
        </div>
    </div>
</div>

<div class="content_help closed">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps<?php echo $data["isCheckerPage"] ? " is_checker_page_help" : "" ?>">
            <div class="help_name_steps">
                <span><?php echo __("step_num", ["step_number" => 1])?>: </span><?php echo __(EventSteps::BLIND_DRAFT)?>
            </div>
            <div class="help_descr_steps">
                <ul><?php echo __("blind-draft_bc_desc", ["step" => __($data["next_step"])])?></ul>
                <div class="show_tutorial_popup"> >>> <?php echo __("show_more")?></div>
            </div>
        </div>

        <div class="event_info<?php echo $data["isCheckerPage"] ? " is_checker_page_help" : "" ?>">
            <div class="participant_info">
                <div class="additional_info">
                    <a href="/events/information-bca/<?php echo $data["event"][0]->eventID ?>"><?php echo __("event_info") ?></a>
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
            <img src="<?php echo template_url("img/steps/icons/blind-draft.png") ?>" width="100px" height="100px">
            <img src="<?php echo template_url("img/steps/big/blind-draft.png") ?>" width="280px" height="280px">
        </div>

        <div class="tutorial_content">
            <h3><?php echo __(EventSteps::BLIND_DRAFT)?></h3>
            <ul><?php echo __("blind-draft_bc_desc", ["step" => __($data["next_step"])])?></ul>
        </div>
    </div>
</div>

<script>
    $(function() {
        /* Clean BFCache on page load */
        if(localStorage.getItem("prev") == window.location.href) {
            $(window).bind("pageshow", function() {
                $('form').each(function() {
                    this.reset();
                });
            });
        }

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
    })
</script>