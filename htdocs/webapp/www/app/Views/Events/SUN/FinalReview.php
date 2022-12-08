<?php
use Helpers\Constants\EventMembers;

if(isset($data["error"])) return;

$textDirection = $data["event"][0]->tLangDir;
$fontLanguage = $data["event"][0]->targetLang;
$enableFootNotes = false;
require(app_path() . "Views/Components/CommentEditor.php");
?>

<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="main_content_title"><?php echo __("final-review")?></div>
    </div>

    <div class="">
        <div class="main_content">
            <form action="" method="post" id="finalReview">
                <div class="main_content_text">
                    <h4 dir="<?php echo $data["event"][0]->sLangDir ?>"><?php echo $data["event"][0]->tLang." - "
                            .__($data["event"][0]->bookProject)." - "
                            .($data["event"][0]->sort <= 39 ? __("old_test") : __("new_test"))." - "
                            ."<span class='book_name'>".$data["event"][0]->name." ".$data["currentChapter"].":1-".$data["totalVerses"]."</span>"?></h4>

                    <div class="col-sm-12">
                        <?php foreach($data["chunks"] as $key => $chunk) : ?>
                            <div class="chunk_block">
                                <div class="flex_container">
                                    <div class="chunk_verses flex_left" style="padding: 0 15px 0 0;" dir="<?php echo $data["event"][0]->sLangDir ?>">
                                        <?php $firstVerse = 0; ?>
                                        <?php foreach ($chunk as $verse): ?>
                                            <?php
                                            // process combined verses
                                            if (!isset($data["text"][$verse]))
                                            {
                                                if($firstVerse == 0)
                                                {
                                                    $firstVerse = $verse;
                                                    continue;
                                                }
                                                $combinedVerse = $firstVerse . "-" . $verse;

                                                if(!isset($data["text"][$combinedVerse]))
                                                    continue;
                                                $verse = $combinedVerse;
                                            }
                                            ?>
                                            <strong dir="<?php echo $data["event"][0]->sLangDir ?>" class="<?php echo $data["event"][0]->sLangDir ?>"><sup><?php echo $verse; ?></sup></strong><div class="<?php echo "kwverse_".$data["currentChapter"]."_".$key."_".$verse ?>" dir="<?php echo $data["event"][0]->sLangDir ?>"><?php echo $data["text"][$verse]; ?></div>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="flex_middle input_draft" dir="<?php echo $data["event"][0]->tLangDir ?>">
                                        <?php
                                        $text = "";
                                        if (isset($_POST["chunks"][$key])) {
                                            $text = preg_replace(
                                                "/\|(\d+)\|/",
                                                "<div class='bubble'>$1</div>",
                                                $_POST["chunks"][$key]
                                            );
                                        } elseif (isset($data["translation"][$key])) {
                                            $translation = $data["translation"][$key];
                                            if (!empty($translation[EventMembers::TRANSLATOR]["verses"])) {
                                                foreach ($translation[EventMembers::TRANSLATOR]["verses"] as $v => $verse) {
                                                    $text .= "<div class='bubble'>$v</div>$verse";
                                                }
                                            } else {
                                                $text = $translation[EventMembers::TRANSLATOR]["symbols"];
                                            }
                                        }
                                        ?>
                                        <div class="input_editor textarea sun_content"
                                             data-initialmarker="<?php echo $chunk[0] ?>"
                                             data-lastmarker="<?php echo $chunk[sizeof($chunk)-1] ?>"
                                             data-totalmarkers="<?php echo sizeof($chunk) ?>"><?php echo $text ?></div>
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
                            </div>
                            <div class="chunk_divider"></div>
                        <?php endforeach; ?>
                    </div>
                    <div class="clear"></div>
                </div>

                <div class="main_content_footer row">
                    <div class="form-group">
                        <div class="main_content_confirm_desc"><?php echo __("confirm_finished")?></div>
                        <label><input name="confirm_step" id="confirm_step" type="checkbox" value="1" /> <?php echo __("confirm_yes")?></label>
                    </div>

                    <button id="next_step" type="submit" name="submit" class="btn btn-primary" disabled>
                        <?php echo __($data["next_step"])?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="content_help closed">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps is_checker_page_help">
            <div class="help_name_steps"><span><?php echo __("final-review")?></span></div>
            <div class="help_descr_steps">
                <ul>
                    <li><b><?php echo __("purpose") ?></b> <?php echo __("final-review_purpose") ?></li>
                    <li><?php echo __("final-review_help_1") ?></li>
                    <li><?php echo __("verse_markers_help_2") ?></li>
                    <li><?php echo __("verse_markers_help_3") ?></li>
                    <li><?php echo __("verse_markers_help_4") ?></li>
                    <li><?php echo __("verse_markers_help_5", ["step" => __($data["next_step"])]) ?></li>
                </ul>
                <div class="show_tutorial_popup"> >>> <?php echo __("show_more")?></div>
            </div>
        </div>

        <div class="event_info is_checker_page_help">
            <div class="participant_info">
                <div class="additional_info">
                    <a href="/events/information-sun/<?php echo $data["event"][0]->eventID ?>"><?php echo __("event_info") ?></a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="tutorial_container">
    <div class="tutorial_popup">
        <div class="tutorial-close glyphicon glyphicon-remove"></div>
        <div class="tutorial_pic">
            <img src="<?php echo template_url("img/steps/icons/final-review.png") ?>" height="100px" width="100px">
            <img src="<?php echo template_url("img/steps/big/final-review.png") ?>" height="280px" width="280px">
        </div>

        <div class="tutorial_content is_checker_page_help">
            <h3><?php echo __("final-review")?></h3>
            <ul>
                <li><b><?php echo __("purpose") ?></b> <?php echo __("final-review_purpose") ?></li>
                <li><?php echo __("final-review_help_1") ?></li>
                <li><?php echo __("verse_markers_help_2") ?></li>
                <li><?php echo __("verse_markers_help_3") ?></li>
                <li><?php echo __("verse_markers_help_4") ?></li>
                <li><?php echo __("verse_markers_help_5", ["step" => __($data["next_step"])]) ?></li>
            </ul>
        </div>
    </div>
</div>

<script src="<?php echo template_url("js/markers.js?4") ?>"></script>
<link rel="stylesheet" href="<?php echo template_url("css/markers.css?3") ?>">

<script>
    $(document).ready(function() {
        $(".input_editor").markers({
            inputName: "chunks[]",
            autoSave: false,
            movableButton: true
        });
    });
</script>