<?php
if(isset($data["error"])) return;

require(app_path() . "Views/Components/HelpTools.php");
?>
<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="main_content_title"><?php echo __("step_num", ["step_number" => 4]). ": " . __("symbol-draft")?></div>
    </div>

    <div class="">
        <div class="main_content">
            <form action="" method="post" id="main_form">
                <div class="main_content_text row" style="padding-left: 15px">
                    <h4 dir="<?php echo $data["event"][0]->sLangDir ?>"><?php echo $data["event"][0]->tLang." - "
                            .__($data["event"][0]->bookProject)." - "
                            .($data["event"][0]->sort <= 39 ? __("old_test") : __("new_test"))." - "
                            ."<span class='book_name'>".$data["event"][0]->name." ".$data["currentChapter"].":".$data["chunk"][0]."-".$data["chunk"][sizeof($data["chunk"])-1]."</span>"?>
                    </h4>

                    <div class="col-sm-12 no_padding">
                        <div class="row chunk_block words_block">
                            <div class="chunk_verses col-sm-6" dir="<?php echo $data["event"][0]->sLangDir ?>">
                                <?php echo $data["words"] ?>
                            </div>
                            <div class="col-sm-6 editor_area" dir="<?php echo $data["event"][0]->tLangDir ?>">
                                <?php $text = isset($data["symbols"]) ? $data["symbols"] : ""; ?>
                                <textarea name="symbols" class="col-sm-6 verse_ta textarea sun_content"><?php echo $text ?></textarea>
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
            <div class="step_right alt"><?php echo __("step_num", ["step_number" => 4])?></div>
        </div>
    </div>
</div>

<div class="content_help closed">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps">
            <div class="help_name_steps"><span><?php echo __("step_num", ["step_number" => 4])?>:</span> <?php echo __("symbol-draft")?></div>
            <div class="help_descr_steps">
                <ul>
                    <li><b><?php echo __("purpose") ?></b> <?php echo __("symbol-draft_purpose") ?></li>
                    <li><?php echo __("symbol-draft_help_1") ?></li>
                    <li><?php echo __("symbol-draft_help_2") ?>
                        <ol>
                            <li><?php echo __("symbol-draft_help_2a") ?></li>
                            <li><?php echo __("symbol-draft_help_2b") ?></li>
                            <li><?php echo __("symbol-draft_help_2c") ?></li>
                            <li><?php echo __("symbol-draft_help_2d") ?></li>
                        </ol>
                    </li>
                </ul>
                <div class="show_tutorial_popup"> >>> <?php echo __("show_more")?></div>
            </div>
        </div>

        <div class="event_info">
            <div class="participant_info">
                <div class="additional_info">
                    <a href="/events/information-sun/<?php echo $data["event"][0]->eventID ?>"><?php echo __("event_info") ?></a>
                </div>
            </div>
        </div>

        <div class="tr_tools">
            <?php
            renderSailDict();
            renderTn($data["event"][0]->tnLangID);
            renderTw($data["event"][0]->twLangID);
            ?>
        </div>
    </div>
</div>

<div class="tutorial_container">
    <div class="tutorial_popup">
        <div class="tutorial-close glyphicon glyphicon-remove"></div>
        <div class="tutorial_pic">
            <img src="<?php echo template_url("img/steps/icons/symbol-draft.png") ?>" width="100" height="100">
            <img src="<?php echo template_url("img/steps/big/symbol-draft.png") ?>" width="280" height="280">
        </div>

        <div class="tutorial_content">
            <h3><?php echo __("symbol-draft")?></h3>
            <ul>
                <li><b><?php echo __("purpose") ?></b> <?php echo __("symbol-draft_purpose") ?></li>
                <li><?php echo __("symbol-draft_help_1") ?></li>
                <li><?php echo __("symbol-draft_help_2") ?>
                    <ol>
                        <li><?php echo __("symbol-draft_help_2a") ?></li>
                        <li><?php echo __("symbol-draft_help_2b") ?></li>
                        <li><?php echo __("symbol-draft_help_2c") ?></li>
                        <li><?php echo __("symbol-draft_help_2d") ?></li>
                    </ol>
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- Data for tools -->
<input type="hidden" id="bookCode" value="<?php echo $data["event"][0]->bookCode ?>">
<input type="hidden" id="chapter" value="<?php echo $data["event"][0]->currentChapter ?>">
<input type="hidden" id="tn_lang" value="<?php echo $data["event"][0]->tnLangID ?>">
<input type="hidden" id="tq_lang" value="<?php echo $data["event"][0]->tqLangID ?>">
<input type="hidden" id="tw_lang" value="<?php echo $data["event"][0]->twLangID ?>">
<input type="hidden" id="totalVerses" value="<?php echo $data["totalVerses"] ?>">
<input type="hidden" id="targetLang" value="<?php echo $data["event"][0]->targetLang ?>">

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
    });
</script>