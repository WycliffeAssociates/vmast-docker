<?php
use Helpers\Constants\EventMembers;

if(isset($data["error"])) return;

$textDirection = $data["event"][0]->tLangDir;
$fontLanguage = $data["event"][0]->targetLang;
require(app_path() . "Views/Components/CommentEditor.php");
?>

<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="main_content_title">
            <div><?php echo __("step_num", ["step_number" => 4]). ": " . __("peer-review")?></div>
        </div>
    </div>

    <div class="">
        <div class="main_content">
            <form action="" method="post" id="main_form">
                <div class="main_content_text row" style="padding-left: 15px">
                    <h4 dir="<?php echo $data["event"][0]->sLangDir ?>"><?php echo $data["event"][0]->tLang." - "
                            .__($data["event"][0]->bookProject)." - "
                            ."<span class='book_name'>".$data["event"][0]->name." - ".$data["text"][1]."</span>"?></h4>

                    <div class="col-sm-12 no_padding">
                        <?php foreach($data["chunks"] as $key => $chunk) : ?>
                            <div class="row flex_container chunk_block">
                                <div class="chunk_verses flex_left" dir="<?php echo $data["event"][0]->sLangDir ?>">
                                    <?php foreach ($chunk as $verse): ?>
                                        <div class="verse_text" data-verse="<?php echo $verse; ?>">
                                            <?php
                                            $source = __("no_source_error");
                                            if(isset($data["text"][$verse]))
                                            {
                                                if(!is_array($data["text"][$verse]))
                                                {
                                                    $source = "<p class='verse_text_1'>{$data["text"][$verse]}</p>";
                                                }
                                                else
                                                {
                                                    $source = "<p class='verse_text_1'><strong>{$data["text"][$verse]["name"]}</strong></p>";
                                                    $source .= "<p class='verse_text_2'>{$data["text"][$verse]["text"]}</p>";
                                                }
                                            }
                                            ?>
                                            <?php echo $source; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="flex_middle editor_area" dir="<?php echo $data["event"][0]->tLangDir ?>">
                                    <div class="vnote verse_block" data-verse="<?php echo $verse; ?>">
                                        <?php
                                        $text1 = "";
                                        $text2 = "";
                                        $isTitle = $key <= 1;

                                        $translation = isset($data["translation"][$key][EventMembers::CHECKER])
                                        && !empty($data["translation"][$key][EventMembers::CHECKER]["verses"])
                                            ? $data["translation"][$key][EventMembers::CHECKER]["verses"]
                                            : $data["translation"][$key][EventMembers::TRANSLATOR]["verses"];

                                        if(!is_array($translation))
                                        {
                                            $text1 = $translation;
                                        }
                                        else
                                        {
                                            $text1 = $translation["name"];
                                            $text2 = $translation["text"];
                                        }
                                        ?>
                                        <?php if($isTitle): ?>
                                            <textarea name="chunks[<?php echo $key ?>]" class="peer_verse_ta textarea verse_text_1"><?php echo $text1 ?></textarea>
                                        <?php else: ?>
                                            <textarea name="chunks[<?php echo $key ?>][name]" class="peer_verse_ta textarea verse_text_1" rows="1"><?php echo $text1 ?></textarea>
                                            <textarea name="chunks[<?php echo $key ?>][text]" class="peer_verse_ta textarea verse_text_2"><?php echo $text2 ?></textarea>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="flex_right">
                                    <?php
                                    $commentChunk = $data["currentChapter"].":".$key;
                                    $hasComments = array_key_exists($data["currentChapter"], $data["comments"]) && array_key_exists($key, $data["comments"][$data["currentChapter"]]);
                                    if ($hasComments) {
                                        $comments = $data["comments"][$data["currentChapter"]][$key];
                                        $commentsNumber = sizeof(array_filter($comments, function($item) { return $item->saved; }));
                                    }
                                    require(app_path() . "Views/Components/Comments.php");
                                    ?>
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

                    <input type="hidden" name="chk" value="1">
                    <input type="hidden" name="level" value="radContinue">
                    <input type="hidden" name="chapter" value="<?php echo $data["event"][0]->currentChapter ?>">
                    <input type="hidden" name="memberID" value="<?php echo $data["event"][0]->memberID ?>">

                    <button id="next_step" type="submit" name="submit" class="btn btn-primary" disabled>
                        <?php echo __($data["next_step"])?>
                    </button>
                    <img src="<?php echo template_url("img/saving.gif") ?>" class="unsaved_alert">
                </div>
            </form>
            <div class="step_right alt"><?php echo __("step_num", ["step_number" => 4])?></div>
        </div>
    </div>
</div>

<div class="content_help">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps is_checker_page_help">
            <div class="help_name_steps"><span><?php echo __("step_num", ["step_number" => 4])?>:</span> <?php echo __("peer-review")?></div>
            <div class="help_descr_steps">
                <ul>
                    <li><b><?php echo __("purpose") ?></b> <?php echo __("peer-review_rad_purpose") ?></li>
                    <li><?php echo __("self-check_help_2") ?></li>
                    <li><?php echo __("self-check_tn_help_2") ?></li>
                    <li><?php echo __("peer-review_checker_help_6") ?></li>
                    <li><?php echo __("peer-review_rad_help_4", ["icon" => "<span class='mdi mdi-lead-pencil'></span>"]) ?></li>
                    <li><?php echo __("move_to_next_step_alt", ["step" => __($data["next_step"])]) ?></li>
                </ul>
                <div class="show_tutorial_popup"> >>> <?php echo __("show_more")?></div>
            </div>
        </div>

        <div class="event_info is_checker_page_help">
            <div class="participant_info">
                <div class="additional_info">
                    <a href="/events/information-rad/<?php echo $data["event"][0]->eventID ?>"><?php echo __("event_info") ?></a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="tutorial_container">
    <div class="tutorial_popup">
        <div class="tutorial-close glyphicon glyphicon-remove"></div>
        <div class="tutorial_pic">
            <img src="<?php echo template_url("img/steps/icons/peer-review.png") ?>" width="100" height="100">
            <img src="<?php echo template_url("img/steps/big/peer-review.png") ?>" width="280" height="280">
        </div>

        <div class="tutorial_content">
            <h3><?php echo __("peer-review")?></h3>
            <ul>
                <li><b><?php echo __("purpose") ?></b> <?php echo __("peer-review_rad_purpose") ?></li>
                <li><?php echo __("self-check_help_2") ?></li>
                <li><?php echo __("self-check_tn_help_2") ?></li>
                <li><?php echo __("peer-review_checker_help_6") ?></li>
                <li><?php echo __("peer-review_rad_help_4", ["icon" => "<span class='mdi mdi-lead-pencil'></span>"]) ?></li>
                <li><?php echo __("move_to_next_step_alt", ["step" => __($data["next_step"])]) ?></li>
            </ul>
        </div>
    </div>
</div>
