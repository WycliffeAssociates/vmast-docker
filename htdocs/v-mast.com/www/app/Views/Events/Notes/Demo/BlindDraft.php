<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="main_content_title">
            <div class="demo_title"><?php echo __("demo") . " (".__("tn").")" ?></div>
            <div><?php echo __("step_num", ["step_number" => 3]) . ": " . __("blind-draft_tn")?></div>
        </div>
    </div>

    <div class="">
        <div class="main_content">
            <div class="main_content_text" dir="ltr">
                <h4>Bahasa Indonesia - <?php echo __("tn") ?> - <?php echo __("new_test") ?> - <span class="book_name">Acts 1:1</span></h4>

                <div class="scripture_chunk">
                    <p><strong><sup>1</sup></strong> Dalam buku yang aku tulis sebelumnya <span data-toggle="tooltip" data-placement="auto auto" title="" class="booknote mdi mdi-bookmark" data-original-title="Buku yang dimaksud adalah Injil Lukas."></span> , Teofilus, tentang semua yang Yesus mulai lakukan dan ajarkan,</p>
                </div>

                <ul class="nav nav-tabs">
                    <li role="presentation" id="my_read_chunk" class="my_tab">
                        <a href="#"><?php echo __("read_chunk") ?></a>
                    </li>
                    <li role="presentation" id="my_translate_chunk" class="my_tab">
                        <a href="#"><?php echo __("translate_chunk") ?></a>
                    </li>
                </ul>

                <div id="my_read_chunk_content" class="my_content shown">
                    <div class="note_content" id="read_chunk_0">
                        <h1>The former book I wrote</h1>
                        <p>The former book is the Gospel of Luke.</p>
                        <h1>Theophilus</h1>
                        <p>Luke wrote this book to a man named Theophilus. Some translations follow their own culture's way of addressing a letter and write "Dear Theophilus" at the beginning of the sentence. Theophilus means "friend of God" (See: <span class="uwlink" title="Leave it as it is">[[rc://en/ta/man/translate/translate-names]]</span>)</p>
                    </div>
                </div>

                <div id="my_translate_chunk_content" class="my_content">
                    <div class="notes_editor">
                        <textarea name="draft" class="add_notes_editor blind_ta" data-key="0"></textarea>
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
                <div class="step_right"><?php echo __("step_num", ["step_number" => 3])?></div>
            </div>
        </div>
    </div>
</div>

<div class="content_help closed">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps">
            <div class="help_name_steps"><span><?php echo __("step_num", ["step_number" => 3])?>:</span> <?php echo __("blind-draft_tn")?></div>
            <div class="help_descr_steps">
                <ul>
                    <li><b><?php echo __("purpose") ?></b> <?php echo __("blind-draft_tn_purpose") ?></li>
                    <li><?php echo __("blind-draft_tn_help_1") ?></li>
                    <li><?php echo __("blind-draft_tn_help_2") ?></li>
                    <li><?php echo __("blind-draft_tn_help_3") ?></li>
                    <li><?php echo __("blind-draft_tn_help_4") ?></li>
                    <li><?php echo __("blind-draft_tn_help_5") ?></li>
                    <li><?php echo __("blind-draft_tn_help_6") ?></li>
                    <li><?php echo __("blind-draft_tn_help_7") ?></li>
                    <li><?php echo __("blind-draft_tn_help_8", ["icon" => "<i class='note-icon-magic'></i>"]) ?>
                        <ol>
                            <li><?php echo __("blind-draft_tn_help_8a") ?></li>
                            <li><?php echo __("blind-draft_tn_help_8b") ?></li>
                            <li><?php echo __("blind-draft_tn_help_8c") ?></li>
                        </ol>
                    </li>
                    <li><?php echo __("blind-draft_tn_help_9") ?></li>
                    <li><?php echo __("blind-draft_tn_help_10", ["step" => __($data["next_step"])]) ?></li>
                </ul>
                <div class="show_tutorial_popup"> >>> <?php echo __("show_more")?></div>
            </div>
        </div>

        <div class="event_info">
            <div class="participant_info">
                <div class="additional_info">
                    <a href="/events/demo-tn/information"><?php echo __("event_info") ?></a>
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
            <h3><?php echo __("blind-draft_tn")?></h3>
            <ul>
                <li><b><?php echo __("purpose") ?></b> <?php echo __("blind-draft_tn_purpose") ?></li>
                <li><?php echo __("blind-draft_tn_help_1") ?></li>
                <li><?php echo __("blind-draft_tn_help_2") ?></li>
                <li><?php echo __("blind-draft_tn_help_3") ?></li>
                <li><?php echo __("blind-draft_tn_help_4") ?></li>
                <li><?php echo __("blind-draft_tn_help_5") ?></li>
                <li><?php echo __("blind-draft_tn_help_6") ?></li>
                <li><?php echo __("blind-draft_tn_help_7") ?></li>
                <li><?php echo __("blind-draft_tn_help_8", ["icon" => "<i class='note-icon-magic'></i>"]) ?>
                    <ol>
                        <li><?php echo __("blind-draft_tn_help_8a") ?></li>
                        <li><?php echo __("blind-draft_tn_help_8b") ?></li>
                        <li><?php echo __("blind-draft_tn_help_8c") ?></li>
                    </ol>
                </li>
                <li><?php echo __("blind-draft_tn_help_9") ?></li>
                <li><?php echo __("blind-draft_tn_help_10", ["step" => __($data["next_step"])]) ?></li>
            </ul>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#next_step").click(function (e) {
            e.preventDefault();
            if(!hasChangesOnPage) window.location.href = '/events/demo-tn/self_check';
            return false;
        });

        $("#my_translate_chunk").click(function () {
            $(".add_notes_editor").each(function() {
                var key = $(this).data("key");
                var noteContent = $("#read_chunk_" + key);
                var height = noteContent.actual("height");
                var parent = $(this).parents(".notes_editor");

                setTimeout(function () {
                    parent.css("min-height", height);
                }, 10);
            })
        });
    });
</script>