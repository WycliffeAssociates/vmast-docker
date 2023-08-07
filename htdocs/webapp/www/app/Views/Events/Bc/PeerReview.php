<?php
use Helpers\Constants\EventMembers;
use Helpers\Constants\EventSteps;

if(isset($data["error"])) return;

$textDirection = $data["event"][0]->tLangDir;
$fontLanguage = $data["event"][0]->targetLang;
$level = 2;
$enableFootNotes = false;
require(app_path() . "Views/Components/CommentEditor.php");
require(app_path() . "Views/Components/HelpTools.php");
?>

<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="main_content_title">
            <?php echo __("step_num", ["step_number" => 2]) . ": " . __(EventSteps::PEER_REVIEW . "_bc")?>
            <div class="action_type type_checking"><?php echo __("type_checking1"); ?></div>
        </div>
    </div>

    <div class="" style="position: relative">
        <div class="main_content">
            <form action="" id="main_form" method="post">
            <div class="main_content_text">
            
                <?php if($data["event"][0]->checkerID == 0): ?>
                    <div class="alert alert-success check_request"><?php echo __("check_request_sent_success") ?></div>
                <?php endif; ?>

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
                        <div class="row flex_container chunk_block chunk_block_divider">
                            <div class="chunk_verses flex_left" dir="<?php echo $data["event"][0]->sLangDir ?>">
                                <div class="resource_chunk no_margin" data-chunk="<?php echo $key ?>">
                                    <div class="resource_text"><?php echo $data["bc"]->get($key)->text ?></div>
                                </div>
                            </div>
                            <div class="flex_middle editor_area font_<?php echo $data["event"][0]->targetLang ?>" dir="<?php echo $data["event"][0]->tLangDir ?>">
                                <?php
                                $translation = isset($data["translation"][$key]) && isset($data["translation"][$key][EventMembers::CHECKER])
                                && !empty($data["translation"][$key][EventMembers::CHECKER]["verses"])
                                    ? $data["translation"][$key][EventMembers::CHECKER]["verses"]
                                    : $data["translation"][$key][EventMembers::TRANSLATOR]["verses"];
                                ?>
                                <div class="vnote" data-chunk="<?php echo $key ?>">
                                    <textarea name="chunks[<?php echo $key ?>][text]" class="col-sm-6 peer_verse_ta textarea"><?php echo $translation["text"] ?? "" ?></textarea>
                                    <input name="chunks[<?php echo $key ?>][meta]" type="hidden" value="<?php echo $translation["meta"] ?? "" ?>" />
                                    <input name="chunks[<?php echo $key ?>][type]" type="hidden" value="<?php echo $translation["type"] ?? "" ?>" />
                                </div>
                            </div>
                            <div class="flex_right">
                                <?php
                                $commentChunk = $data["event"][0]->currentChapter.":".$key;
                                $hasComments = array_key_exists($data["event"][0]->currentChapter, $data["comments"]) && array_key_exists($key, $data["comments"][$data["event"][0]->currentChapter]);
                                if ($hasComments) {
                                    $comments = $data["comments"][$data["event"][0]->currentChapter][$key];
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
                    <div class="main_content_confirm_desc"><?php echo __("confirm_finished")?></div>
                    <label><input name="confirm_step" id="confirm_step" type="checkbox" value="1" /> <?php echo __("confirm_yes")?></label>
                </div>

                <input type="hidden" name="chk" value="1">
                <input type="hidden" name="level" value="bcContinue">
                <input type="hidden" name="chapter" value="<?php echo $data["event"][0]->currentChapter ?>">
                <input type="hidden" name="memberID" value="<?php echo $data["event"][0]->memberID ?>">

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
        <div class="help_info_steps is_checker_page_help">
            <div class="help_name_steps">
                <span><?php echo __("step_num", ["step_number" => 2])?>: </span>
                <?php echo __(EventSteps::PEER_REVIEW . "_bc")?>
            </div>
            <div class="help_descr_steps">
                <ul><?php echo __("peer-review_bc_desc", ["step" => __($data["next_step"])])?></ul>
                <div class="show_tutorial_popup"> >>> <?php echo __("show_more")?></div>
            </div>
        </div>

        <div class="event_info is_checker_page_help">
            <div class="participant_name">
                <span><?php echo __("your_checker") ?>:</span>
                <span class="checker_name_span"><?php echo $data["event"][0]->checkerFName !== null ? $data["event"][0]->checkerFName . " " . mb_substr($data["event"][0]->checkerLName, 0, 1)."." : __("not_available") ?></span>
            </div>
            <div class="participant_info">
                <div class="additional_info">
                    <a href="/events/information-bc/<?php echo $data["event"][0]->eventID ?>"><?php echo __("event_info") ?></a>
                </div>
            </div>
        </div>

        <div class="tr_tools">
            <?php renderSailDict($data["event"][0]->targetLang, false); ?>
        </div>
    </div>
</div>

<div class="tutorial_container">
    <div class="tutorial_popup">
        <div class="tutorial-close glyphicon glyphicon-remove"></div>
        <div class="tutorial_pic">
            <img src="<?php echo template_url("img/steps/icons/peer-review.png") ?>" width="100px" height="100px">
            <img src="<?php echo template_url("img/steps/big/peer-review.png") ?>" width="280px" height="280px">
        </div>

        <div class="tutorial_content<?php echo $data["isCheckerPage"] ? " is_checker_page_help" : "" ?>">
            <h3><?php echo __(EventSteps::PEER_REVIEW . "_bc")?></h3>
            <ul><?php echo __("peer-review_bc_desc", ["step" => __($data["next_step"])])?></ul>
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