<?php
use \Helpers\Constants\EventMembers;
use \Helpers\Parsedown;


if(isset($data["error"])) return;

$parsedown = new Parsedown();
$textDirection = $data["event"][0]->tLangDir;
$fontLanguage = $data["event"][0]->targetLang;
$enableFootNotes = false;
require(app_path() . "Views/Components/CommentEditor.php");
?>

<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="main_content_title"><?php echo __("step_num", ["step_number" => 2]) . ": " . __("self-check")?></div>
    </div>

    <div class="" style="position: relative">
        <div class="main_content">
            <form action="" id="main_form" method="post">
                <div class="main_content_text">
                    <h4><?php echo $data["event"][0]->tLang." - "
                            .__($data["event"][0]->bookProject)." - "
                        ."<span class='book_name'>".$data["event"][0]->name
                        ." [".$data["group"][0]."...".$data["group"][sizeof($data["group"])-1]."]</span>"?></h4>

                    <div id="my_notes_content" class="my_content">
                        <?php foreach($data["chunks"] as $chunkNo => $chunk): ?>
                            <div class="row note_chunk">
                                <div class="row">
                                    <div class="col-md-4" style="color: #00a74d; font-weight: bold;">
                                        <?php //echo $data["words"][$chunkNo]["word"] ?>
                                    </div>
                                </div>
                                <div class="flex_container">
                                    <div class="flex_left" dir="<?php echo $data["event"][0]->resLangDir ?>">
                                        <div class="note_content">
                                            <?php
                                            $source = $data["words"][$chunkNo]["text"];
                                            $source = preg_replace("/(title=\"([^\"]+)\")/", "", $source);
                                            $source = preg_replace("/(href=\"([^\"]+)\")/", "$1 title='$2'", $source);
                                            echo $source
                                            ?>
                                        </div>
                                    </div>
                                    <div class="flex_middle notes_editor font_<?php echo $data["event"][0]->targetLang ?>"
                                         dir="<?php echo $data["event"][0]->tLangDir ?>"
                                         data-chunkno="<?php echo $chunkNo ?>">
                                        <?php
                                        $text = isset($data["translation"][$chunkNo])
                                            ? $parsedown->text($data["translation"][$chunkNo][EventMembers::TRANSLATOR]["verses"])
                                            : "";
                                        ?>
                                        <textarea
                                                name="chunks[<?php echo $chunkNo ?>]"
                                                class="add_notes_editor"><?php echo htmlentities($text) ?></textarea>
                                    </div>
                                    <div class="flex_right">
                                        <?php
                                        $commentChunk = $data["currentChapter"].":".$chunkNo;
                                        $hasComments = array_key_exists($data["currentChapter"], $data["comments"]) && array_key_exists($chunkNo, $data["comments"][$data["currentChapter"]]);
                                        if ($hasComments) {
                                            $comments = $data["comments"][$data["currentChapter"]][$chunkNo];
                                            $commentsNumber = sizeof(array_filter($comments, function($item) { return $item->saved; }));
                                        }
                                        require(app_path() . "Views/Components/Comments.php");
                                        ?>
                                    </div>
                                </div>
                            </div>
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
            <div class="step_right alt"><?php echo __("step_num", ["step_number" => 2])?></div>
        </div>
    </div>
</div>

<div class="content_help closed">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps">
            <div class="help_name_steps"><span><?php echo __("step_num", ["step_number" => 2])?>: </span><?php echo __("self-check")?></div>
            <div class="help_descr_steps">
                <ul>
                    <li><b><?php echo __("purpose") ?></b> <?php echo __("self-check_tw_purpose") ?></li>
                    <li><?php echo __("self-check_tn_help_1") ?></li>
                    <li><?php echo __("self-check_tn_help_2") ?></li>
                    <li><?php echo __("self-check_tn_help_3") ?></li>
                    <li><?php echo __("blind-draft_tn_help_8", ["icon" => "<i class='note-icon-magic'></i>"]) ?></li>
                    <li><?php echo __("self-check_tw_help_5") ?></li>
                    <li><?php echo __("self-check_tw_help_6") ?></li>
                    <li><?php echo __("multi-draft_tw_help_5e") ?></li>
                    <li><?php echo __("self-check_tw_help_8", ["icon" => "<span class='mdi mdi-lead-pencil'></span>"]) ?></li>
                    <li><?php echo __("move_to_next_step_alt", ["step" => __($data["next_step"])]) ?></li>
                </ul>
                <div class="show_tutorial_popup"> >>> <?php echo __("show_more")?></div>
            </div>
        </div>

        <div class="event_info">
            <div class="participant_info">
                <div class="additional_info">
                    <a href="/events/information-tw/<?php echo $data["event"][0]->eventID ?>"><?php echo __("event_info") ?></a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="tutorial_container">
    <div class="tutorial_popup">
        <div class="tutorial-close glyphicon glyphicon-remove"></div>
        <div class="tutorial_pic">
            <img src="<?php echo template_url("img/steps/icons/self-check.png") ?>" width="100px" height="100px">
            <img src="<?php echo template_url("img/steps/big/self-check.png") ?>" width="280px" height="280px">
        </div>

        <div class="tutorial_content">
            <h3><?php echo __("self-check")?></h3>
            <ul>
                <li><b><?php echo __("purpose") ?></b> <?php echo __("self-check_tw_purpose") ?></li>
                <li><?php echo __("self-check_tn_help_1") ?></li>
                <li><?php echo __("self-check_tn_help_2") ?></li>
                <li><?php echo __("self-check_tn_help_3") ?></li>
                <li><?php echo __("blind-draft_tn_help_8", ["icon" => "<i class='note-icon-magic'></i>"]) ?></li>
                <li><?php echo __("self-check_tw_help_5") ?></li>
                <li><?php echo __("self-check_tw_help_6") ?></li>
                <li><?php echo __("multi-draft_tw_help_5e") ?></li>
                <li><?php echo __("self-check_tw_help_8", ["icon" => "<span class='mdi mdi-lead-pencil'></span>"]) ?></li>
                <li><?php echo __("move_to_next_step_alt", ["step" => __($data["next_step"])]) ?></li>
            </ul>
        </div>
    </div>
</div>