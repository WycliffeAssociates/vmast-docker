<?php
use Helpers\Constants\EventMembers;
use Helpers\Parsedown;

if (isset($error)) return;

$parsedown = new Parsedown();
$textDirection = $data["event"][0]->tLangDir;
$fontLanguage = $data["event"][0]->targetLang;
$level = 3;
$enableFootNotes = false;
require(app_path() . "Views/Components/CommentEditor.php");
?>

<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="main_content_title"><?php echo __("step_num", ["step_number" => 2]) . ": " . __("peer-edit-l3_full")?></div>
    </div>

    <div class="">
        <div class="main_content">
            <form action="" id="main_form" method="post">
                <div class="main_content_text">
                    <?php if($data["event"][0]->checkerFName == null): ?>
                        <div class="alert alert-success check_request"><?php echo __("check_request_sent_success") ?></div>
                    <?php endif; ?>

                    <h4><?php echo $data["event"][0]->tLang." - "
                            .__($data["event"][0]->bookProject)." - "
                            .($data["event"][0]->sort <= 39 ? __("old_test") : __("new_test"))." - "
                            ."<span class='book_name'>".$data["event"][0]->name." ".
                            ($data["currentChapter"] > 0
                                ? $data["currentChapter"].":1-".$data["totalVerses"]
                                : __("front"))."</span>"?></h4>

                        <div id="my_notes_content" class="my_content">
                        <?php foreach($data["chunks"] as $chunkNo => $chunk): $fv = $chunk[0]; ?>
                            <div class="note_chunk l3">
                                <?php if($fv > 0): ?>
                                    <div class="compare_scripture">
                                        <label>
                                            <input type="checkbox" autocomplete="off" checked
                                                   data-toggle="toggle"
                                                   data-on="<?php echo __("on") ?>"
                                                   data-off="<?php echo __("off") ?>" />
                                            <?php echo __("compare"); ?>
                                        </label>
                                    </div>
                                <?php endif; ?>
                                <div class="flex_container">
                                    <div class="scripture_l3 flex_left">
                                        <?php if(!empty($data["ulb_translation"]["l3"])): ?>
                                            <?php foreach(array_values($chunk) as $verse): ?>
                                                <?php if($verse <= 0) continue; ?>
                                                <?php echo isset($data["ulb_translation"]["l3"][$verse])
                                                    ? $verse . ". <span data-verse=\"$verse\">" . $data["ulb_translation"]["l3"][$verse]."</span>" : ""; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="scripture_l2 flex_left">
                                        <?php if(!empty($data["ulb_translation"]["l2"])): ?>
                                            <?php foreach(array_values($chunk) as $verse): ?>
                                                <?php if($verse <= 0) continue; ?>
                                                <?php echo isset($data["ulb_translation"]["l2"][$verse])
                                                    ? $verse . ". <span data-verse=\"$verse\">" . $data["ulb_translation"]["l2"][$verse]."</span>" : ""; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="scripture_compare flex_left">
                                        <?php if(!empty($data["ulb_translation"]["l3"])): ?>
                                            <?php foreach(array_values($chunk) as $verse): ?>
                                                <?php if($verse <= 0) continue; ?>
                                                <?php echo isset($data["ulb_translation"]["l3"][$verse])
                                                    ? $verse . ". <span data-verse=\"$verse\"></span>" : ""; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="vnote l3 flex_middle notes_editor font_<?php echo $data["event"][0]->targetLang ?>"
                                         dir="<?php echo $data["event"][0]->tLangDir ?>">
                                        <?php
                                        $text = empty($data["translation"][$chunkNo][EventMembers::L3_CHECKER]["verses"]) ?
                                            $data["translation"][$chunkNo][EventMembers::CHECKER]["verses"] :
                                            $data["translation"][$chunkNo][EventMembers::L3_CHECKER]["verses"];
                                        $text = $parsedown->text($text);

                                        $text = preg_replace(
                                            "/(\[\[[0-9a-z:\/\-]+\]\])/",
                                            "<span class='uwlink' title='".__("leaveit")."'>$1</span>",
                                            $text);
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

                    <input type="hidden" name="step" value="<?php echo $data["event"][0]->step ?>">
                    <input type="hidden" name="level" value="l3">

                    <button id="next_step" type="submit" name="submit" class="btn btn-primary" disabled>
                        <?php echo __($data["next_step"])?>
                    </button>
                    <img src="<?php echo template_url("img/saving.gif") ?>" class="unsaved_alert">
                </div>
            </form>
            <div class="step_right alt"><?php echo __("step_num", ["step_number" => 2])?></div>
        </div>
    </div>
</div>

<div class="content_help closed">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps <?php echo isset($data["isCheckerPage"]) ? "is_checker_page_help" : "is_checker_page_help isPeer" ?>">
            <div class="help_name_steps">
                <span><?php echo __("step_num", ["step_number" => 2])?>: </span>
                <?php echo __("peer-edit-l3")?>
            </div>
            <div class="help_descr_steps">
                <ul>
                    <li><b><?php echo __("purpose") ?></b> <?php echo __("peer-review-l3_tn_purpose") ?></li>
                    <li><?php echo __("peer-edit-l3_help_1") ?></li>
                    <li><?php echo __("peer-edit-l3_help_2") ?></li>
                    <li><?php echo __("peer-edit-l3_help_3", ["icon" => "<span class='mdi mdi-lead-pencil'></span>"]) ?></li>
                    <li><?php echo __("peer-edit-l3_tn_help_4") ?></li>
                    <li><?php echo __("peer-edit-l3_help_5") ?></li>
                    <li><?php echo __("peer-review-l3_tn_help_5") ?></li>
                    <li><?php echo __("peer-review-l3_tn_help_6") ?></li>
                    <li><?php echo __("peer-edit-l3_tn_help_8") ?></li>
                    <li><?php echo __("peer-edit-l3_tn_help_9") ?>
                        <ol>
                            <li><?php echo __("peer-edit-l3_tn_help_9a", ["icon" => "<i class='note-icon-magic'></i>"]) ?></li>
                            <li><?php echo __("self-check_tn_chk_help_9a") ?></li>
                            <li><?php echo __("self-check_tn_help_6a") ?></li>
                            <li><?php echo __("blind-draft_tn_help_8c") ?></li>
                            <li><?php echo __("blind-draft_tn_help_9") ?></li>
                        </ol>
                    </li>
                    <li><?php echo __("move_to_next_step_alt", ["step" => __($data["next_step"])]) ?></li>
                    <li><b><?php echo __("peer-edit-l3_help_8") ?></b></li>
                </ul>
                <div class="show_tutorial_popup"> >>> <?php echo __("show_more")?></div>
            </div>
        </div>

        <div class="event_info <?php echo isset($data["isCheckerPage"]) ? "is_checker_page_help" : "is_checker_page_help isPeer" ?>">
            <div class="participant_info">
                <div class="participant_name">
                    <span><?php echo __("your_checker") ?>:</span>
                    <span class="checker_name_span">
                                <?php echo $data["event"][0]->checkerFName !== null
                                    ? $data["event"][0]->checkerFName . " "
                                    . mb_substr($data["event"][0]->checkerLName, 0, 1)."."
                                    : __("not_available") ?>
                            </span>
                </div>
                <div class="additional_info">
                    <a href="/events/information-tn-review/<?php echo $data["event"][0]->eventID ?>"><?php echo __("event_info") ?></a>
                </div>
            </div>
        </div>

        <div class="tr_tools">
            <button class="btn btn-primary ttools" data-tool="tn"><?php echo __("show_notes") ?></button>
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

        <div class="tutorial_content <?php echo "is_checker_page_help" ?>">
            <h3><?php echo __("peer-edit-l3_full")?></h3>
            <ul>
                <li><b><?php echo __("purpose") ?></b> <?php echo __("peer-review-l3_tn_purpose") ?></li>
                <li><?php echo __("peer-edit-l3_help_1") ?></li>
                <li><?php echo __("peer-edit-l3_help_2") ?></li>
                <li><?php echo __("peer-edit-l3_help_3", ["icon" => "<span class='mdi mdi-lead-pencil'></span>"]) ?></li>
                <li><?php echo __("peer-edit-l3_tn_help_4") ?></li>
                <li><?php echo __("peer-edit-l3_help_5") ?></li>
                <li><?php echo __("peer-review-l3_tn_help_5") ?></li>
                <li><?php echo __("peer-review-l3_tn_help_6") ?></li>
                <li><?php echo __("peer-edit-l3_tn_help_8") ?></li>
                <li><?php echo __("peer-edit-l3_tn_help_9") ?>
                    <ol>
                        <li><?php echo __("peer-edit-l3_tn_help_9a", ["icon" => "<i class='note-icon-magic'></i>"]) ?></li>
                        <li><?php echo __("self-check_tn_chk_help_9a") ?></li>
                        <li><?php echo __("self-check_tn_help_6a") ?></li>
                        <li><?php echo __("blind-draft_tn_help_8c") ?></li>
                        <li><?php echo __("blind-draft_tn_help_9") ?></li>
                    </ol>
                </li>
                <li><?php echo __("move_to_next_step_alt", ["step" => __($data["next_step"])]) ?></li>
                <li><b><?php echo __("peer-edit-l3_help_8") ?></b></li>
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

<script type="text/javascript" src="<?php echo template_url("js/diff_match_patch.js?v=2")?>"></script>
<script type="text/javascript" src="<?php echo template_url("js/diff.js?v=7")?>"></script>
<script>
    $(document).ready(function() {
        $(".note_chunk").each(function(i, v) {
            $(".scripture_l2 span", this).each(function (i, v) {
                var verse = $(v).data("verse");

                var elm1 = $(v).text();
                var elm2 = $(".scripture_l3 span[data-verse="+verse+"]").text();
                var out = $(".scripture_compare span[data-verse="+verse+"]");

                if(typeof elm1 == "undefined") return true;

                diff_plain(unEscapeStr(elm1), unEscapeStr(elm2), out);
            });
        });

        $(".compare_scripture input").change(function () {
            var parent = $(this).parents(".note_chunk");
            var active = $(this).prop('checked');

            if (active) {
                $(".scripture_l3", parent).hide();
                $(".scripture_compare", parent).show();
            } else {
                $(".scripture_compare", parent).hide();
                $(".scripture_l3", parent).show();
            }
        });
    });
</script>