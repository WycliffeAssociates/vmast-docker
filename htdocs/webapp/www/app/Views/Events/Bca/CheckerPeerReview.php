<?php
/**
 * Created by PhpStorm.
 * User: Maxim
 * Date: 12 Apr 2016
 * Time: 17:30
 */
use Helpers\Constants\EventMembers;
use Helpers\Constants\EventSteps;

if(empty($error) && empty($data["success"])):
?>

<?php
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
            <div><?php echo __("step_num", ["step_number" => 2]). ": " . __(EventSteps::PEER_REVIEW . "_bca")?></div>
            <div class="action_type type_checking <?php echo isset($data["isPeerPage"]) ? "isPeer" : "" ?>">
                <?php echo __("type_checking2"); ?>
            </div>
        </div>
    </div>

    <div class="">
        <div class="main_content">
            <div class="main_content_text">
                <h4><?php echo $data["event"][0]->tLang." - "
                        ."<span class='book_name'>" . __("bca") . " - ".$data["event"][0]->name." - "
                        .$data["word"]."</span>"?></h4>

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

                    <?php foreach($data["chunks"] as $chunkNo => $chunk): ?>
                    <div class="row chunk_block chunk_block_divider">
                        <div class="flex_container">
                            <div class="chunk_verses flex_left" dir="<?php echo $data["event"][0]->resLangDir ?>">
                                <div class="resource_chunk no_margin" data-chunk="<?php echo $chunkNo ?>">
                                    <div class="resource_text"><?php echo $data["bca"]->get($chunkNo)->text ?></div>
                                </div>
                            </div>
                            <div class="flex_middle font_<?php echo $data["event"][0]->targetLang ?>"
                                 dir="<?php echo $data["event"][0]->tLangDir ?>">
                                <?php
                                $translator = $data["translation"][$chunkNo][EventMembers::TRANSLATOR]["verses"];
                                ?>
                                <div class="chunk_translator" data-chunk="<?php echo $chunkNo ?>"><?php echo $translator["text"] ?></div>
                                <div class="chunk_checker" data-chunk="<?php echo $chunkNo ?>">
                                    <?php
                                    $checker = $data["translation"][$chunkNo][EventMembers::CHECKER]["verses"];
                                    echo $checker["text"];
                                    ?>
                                </div>
                            </div>
                            <div class="flex_right">
                                <?php
                                $commentChunk = $data["event"][0]->currentChapter.":".$chunkNo;
                                $hasComments = array_key_exists($data["event"][0]->currentChapter, $data["comments"]) && array_key_exists($chunkNo, $data["comments"][$data["event"][0]->currentChapter]);
                                if ($hasComments) {
                                    $comments = $data["comments"][$data["event"][0]->currentChapter][$chunkNo];
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
                <form action="" method="post" id="checker_submit">
                    <div class="form-group">
                        <div class="main_content_confirm_desc"><?php echo __("confirm_finished")?></div>
                        <label><input name="confirm_step" id="confirm_step" type="checkbox" value="1" /> <?php echo __("confirm_yes")?></label>
                    </div>

                    <button id="checker_ready" class="btn btn-warning" disabled>
                        <?php echo __("ready_to_check")?>
                    </button>
                    <button id="next_step" type="submit" name="submit" class="btn btn-primary checker" disabled>
                        <?php echo __($data["next_step"])?>
                    </button>
                    <img src="<?php echo template_url("img/loader.gif") ?>" class="ready_loader">
                    <input type="hidden" class="event_data_to" value="<?php echo $data["event"][0]->checkerID ?>" />
                    <input type="hidden" class="event_data_step" value="<?php echo $data["event"][0]->step ?>" />
                    <input type="hidden" class="event_data_chapter" value="<?php echo $data["event"][0]->currentChapter ?>" />
                    <input type="hidden" class="event_data_manage" value="<?php echo $data["event"][0]->manageMode ?>" />
                </form>
                <div class="step_right chk"><?php echo __("step_num", ["step_number" => 2])?></div>
            </div>
        </div>
    </div>
</div>

<div class="content_help closed">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps is_checker_page_help isPeer">
            <div class="help_name_steps"><span><?php echo __("step_num", ["step_number" => 2])?>: </span> <?php echo __(EventSteps::PEER_REVIEW . "_bca")?></div>
            <div class="help_descr_steps">
                <ul><?php echo __("peer-review_bc_chk_desc", ["step" => __($data["next_step"])])?></ul>
                <div class="show_tutorial_popup"> >>> <?php echo __("show_more")?></div>
            </div>
        </div>

        <div class="event_info is_checker_page_help isPeer">
            <div class="participant_info">
                <div class="participant_name">
                    <span><?php echo __("your_partner") ?>:</span>
                    <span><?php echo $data["event"][0]->checkerFName . " " . mb_substr($data["event"][0]->checkerLName, 0, 1)."." ?></span>
                </div>
                <div class="additional_info">
                    <a href="/events/information-bca/<?php echo $data["event"][0]->eventID ?>"><?php echo __("event_info") ?></a>
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
            <img src="<?php echo template_url("img/steps/icons/peer-review.png") ?>" width="100" height="100">
            <img src="<?php echo template_url("img/steps/big/peer-review.png") ?>" width="280" height="280">
        </div>

        <div class="tutorial_content<?php echo $data["isCheckerPage"] ? " is_checker_page_help" .
            (isset($data["isPeerPage"]) ? " isPeer" : ""): "" ?>">
            <h3><?php echo __(EventSteps::PEER_REVIEW . "_bca")?></h3>
            <ul><?php echo __("peer-review_bc_chk_desc", ["step" => __($data["next_step"])])?></ul>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo template_url("js/diff_match_patch.js?v=2")?>"></script>
<script type="text/javascript" src="<?php echo template_url("js/diff.js?v=7")?>"></script>
<script>
    var isChecker = true;

    $(document).ready(function() {
        $(".chunk_translator").each(function() {
            const chunk = $(this).data("chunk");
            const chkVersion = $(".chunk_checker[data-chunk='" + chunk + "']");
            diff_plain($(this).text().trim(), unEscapeStr(chkVersion.text().trim()), $(this));
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
        });
    });
</script>
<?php endif; ?>