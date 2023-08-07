<?php
if(isset($data["error"])) return;
require(app_path() . "Views/Components/HelpTools.php");
?>
<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="main_content_title">
            <div><?php echo __("step_num", ["step_number" => 2]) . ": " . __("verbalize")?></div>
        </div>
    </div>

    <div class="">
        <div class="main_content">
            <div class="main_content_text">
                <?php if($data["event"][0]->checkerName == null): ?>
                <div class="add_cheker">
                    <div class="checkers-search">
                        <div class="form-group">
                            <label class="chklabel"><input type="text" class="form-control input-sm" id="add_checker" placeholder="Enter a name" required=""></label>
                            <button class="btn btn-primary add_checker_btn"><?php echo __("add_checker") ?></button>
                            <input type="hidden" id="checker_value" value="">
                            <div class="clear"></div>
                        </div>
                        <div class="membersSearch">
                            <img src="<?php echo template_url("img/loader.gif") ?>" width="32">
                        </div>
                        <ul class="user_checkers">

                        </ul>
                    </div>
                </div>
                <?php endif; ?>

                <h4 dir="<?php echo $data["event"][0]->sLangDir ?>"><?php echo $data["event"][0]->tLang." - "
                        .__($data["event"][0]->bookProject)." - "
                        .($data["event"][0]->sort <= 39 ? __("old_test") : __("new_test"))." - "
                        ."<span class='book_name'>".$data["event"][0]->name." ".$data["currentChapter"].":1-".$data["totalVerses"]."</span>"?></h4>

                <?php foreach($data["text"] as $verse => $text): ?>
                    <p dir="<?php echo $data["event"][0]->sLangDir ?>"><?php echo "<strong><sup>".$verse."</sup></strong> ".$text; ?></p>
                <?php endforeach; ?>
            </div>

            <div class="main_content_footer row">
                <form action="" method="post">
                    <div class="form-group">
                        <div class="main_content_confirm_desc"><?php echo __("confirm_finished")?></div>
                        <label><input name="confirm_step" id="confirm_step" type="checkbox" value="1" /> <?php echo __("confirm_yes")?></label>
                    </div>

                    <button id="next_step" type="submit" name="submit" class="btn btn-primary" disabled>
                        <?php echo __($data["next_step"])?>
                    </button>
                </form>
                <div class="step_right"><?php echo __("step_num", ["step_number" => 2])?></div>
            </div>
        </div>
    </div>
</div>

<div class="content_help closed">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps">
            <div class="help_name_steps"><span><?php echo __("step_num", ["step_number" => 2])?>: </span> <?php echo __("verbalize")?></div>
            <div class="help_descr_steps">
                <ul>
                    <li><b><?php echo __("purpose") ?></b> <?php echo __("verbalize_purpose") ?></li>
                    <li><b><?php echo __("length") ?></b> <?php echo __("verbalize_length") ?></li>
                    <li><?php echo __("verbalize_help_1") ?>
                        <ol>
                            <li><?php echo __("verbalize_help_1a") ?></li>
                            <li><?php echo __("verbalize_help_1b") ?></li>
                            <li><?php echo __("verbalize_help_1c") ?></li>
                        </ol>
                    </li>
                    <li><?php echo __("verbalize_help_2") ?></li>
                    <li><?php echo __("verbalize_help_3") ?></li>
                    <li><?php echo __("verbalize_help_4") ?></li>
                    <li><?php echo __("verbalize_help_5") ?></li>
                    <li><?php echo __("move_to_next_step", ["step" => __($data["next_step"])]) ?></li>
                </ul>
                <div class="show_tutorial_popup"> >>> <?php echo __("show_more")?></div>
            </div>
        </div>

        <div class="event_info">
            <div class="participant_info">
                <div class="participant_name">
                    <span><?php echo __("your_checker") ?>:</span>
                    <span class="checker_name_span"><?php echo $data["event"][0]->checkerName !== null ? $data["event"][0]->checkerName : __("not_available") ?></span>
                </div>
                <div class="additional_info">
                    <a href="/events/information/<?php echo $data["event"][0]->eventID ?>"><?php echo __("event_info") ?></a>
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
            <img src="<?php echo template_url("img/steps/icons/verbalize.png") ?>" width="100px" height="100px">
            <img src="<?php echo template_url("img/steps/big/verbalize.png") ?>" width="280px" height="280px">
            
        </div>

        <div class="tutorial_content">
            <h3><?php echo __("verbalize")?></h3>
            <ul>
                <li><b><?php echo __("purpose") ?></b> <?php echo __("verbalize_purpose") ?></li>
                <li><b><?php echo __("length") ?></b> <?php echo __("verbalize_length") ?></li>
                <li><?php echo __("verbalize_help_1") ?>
                    <ol>
                        <li><?php echo __("verbalize_help_1a") ?></li>
                        <li><?php echo __("verbalize_help_1b") ?></li>
                        <li><?php echo __("verbalize_help_1c") ?></li>
                    </ol>
                </li>
                <li><?php echo __("verbalize_help_2") ?></li>
                <li><?php echo __("verbalize_help_3") ?></li>
                <li><?php echo __("verbalize_help_4") ?></li>
                <li><?php echo __("verbalize_help_5") ?></li>
                <li><?php echo __("move_to_next_step", ["step" => __($data["next_step"])]) ?></li>
            </ul>
        </div>
    </div>
</div>