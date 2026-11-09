<?php
require(app_path() . "Views/Components/CommentEditor.php");
?>

<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="action_type_container">
            <div class="demo_title"><?php echo __("demo") . " (".__("8steps_vmast").")" ?></div>
        </div>
        <div class="main_content_title">
            <div><?php echo __("final-review")?></div>
        </div>
    </div>

    <div class="main_content">
        <div class="main_content_text">
            <h4>English - <?php echo __("ulb") ?> - <?php echo __("new_test") ?> - <span class="book_name">2 Timothy 2:1-26</span></h4>

            <div class="col-sm-12">
                <div class="row chunk_block flex_container">
                    <div style="padding: 0 15px 0 0;" class="chunk_verses flex_left">
                        <strong><sup>1</sup></strong><div class="kwverse_2_0_1"><b data="0">You</b> therefore, my child, be <b data="0">strengthened</b> in the <b data="0">grace</b> that is in <b data="0">Christ Jesus</b>.</div>
                        <strong><sup>2</sup></strong><div class="kwverse_2_0_2">And the things you heard from me among many witnesses, entrust them to faithful people who will be able to teach others also.</div>
                        <strong><sup>3</sup></strong><div class="kwverse_2_0_3"><b data="0">Suffer</b> hardship with me, as a good soldier of Christ Jesus.</div>
                    </div>
                    <div class="input_draft flex_middle">
                        <div class="input_editor textarea"
                             data-initialmarker="1"
                             data-lastmarker="3"
                             data-totalmarkers="3"><div class="bubble">1</div>Demo translation text, Demo translation text, Demo translation text, <div class="bubble">2</div>Demo translation text Demo translation text, Demo translation text, Demo translation text, <div class="bubble">3</div>Demo translation text, Demo translation text, Demo translation text</div>
                    </div>
                    <div class="flex_right">
                        <?php
                        $comments = $data["comments"][0];
                        $hasComments = !empty($comments);
                        $commentsNumber = sizeof($comments);
                        $myMemberID = 0;
                        $enableFootNotes = false;
                        require(app_path() . "Views/Components/Comments.php");
                        ?>
                    </div>
                </div>
                <div class="chunk_divider"></div>
                <div class="row chunk_block flex_container">
                    <div style="padding: 0 15px 0 0;" class="chunk_verses flex_left">
                        <strong><sup>4</sup></strong><div class="kwverse_2_0_4">No soldier serves while entangled in the affairs of this life, so that he may please his superior officer.</div>
                        <strong><sup>5</sup></strong><div class="kwverse_2_0_5">Also, if someone competes as an athlete, he is not crowned unless he competes by the rules.</div>
                        <strong><sup>6</sup></strong><div class="kwverse_2_0_6">It is necessary that the hardworking farmer receive his share of the crops first.</div>
                        <strong><sup>7</sup></strong><div class="kwverse_2_0_7">Think about what I am saying, for the Lord will give you understanding in everything.</div>
                    </div>
                    <div class="input_draft flex_middle">
                        <div class="input_editor textarea"
                             data-initialmarker="4"
                             data-lastmarker="7"
                             data-totalmarkers="4"><div class="bubble">4</div>Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text</div>
                    </div>
                    <div class="flex_right">
                        <?php
                        $comments = [$data["comments"][0][0]];
                        $hasComments = !empty($comments);
                        $commentsNumber = sizeof($comments);
                        $myMemberID = 0;
                        $enableFootNotes = false;
                        require(app_path() . "Views/Components/Comments.php");
                        ?>
                    </div>
                </div>
                <div class="chunk_divider"></div>
                <div class="row chunk_block flex_container">
                    <div style="padding: 0 15px 0 0;" class="chunk_verses flex_left">
                        <strong><sup>8</sup></strong><div class="kwverse_2_1_8">Remember Jesus Christ, from David's seed, who was raised from the dead ones. This is according to my gospel message,</div>
                        <strong><sup>9</sup></strong><div class="kwverse_2_1_9">for which I am suffering to the point of being chained as a criminal. But the word of God is not chained.</div>
                        <strong><sup>10</sup></strong><div class="kwverse_2_1_10">Therefore I endure all things for those who are chosen, so that they also may obtain the salvation that is in Christ Jesus, with eternal glory.</div>
                    </div>
                    <div class="input_draft flex_middle">
                        <div class="input_editor textarea"
                             data-initialmarker="8"
                             data-lastmarker="10"
                             data-totalmarkers="3">Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text</div>
                    </div>
                    <div class="flex_right">
                        <?php
                        $hasComments = false;
                        $enableFootNotes = false;
                        require(app_path() . "Views/Components/Comments.php");
                        ?>
                    </div>
                </div>
                <div class="chunk_divider"></div>
                <div class="row chunk_block flex_container">
                    <div style="padding: 0 15px 0 0;" class="chunk_verses flex_left">
                        <strong><sup>11</sup></strong><div class="kwverse_2_1_11">This saying is trustworthy:   "If we have died with him, we will also live with him.</div>
                        <strong><sup>12</sup></strong><div class="kwverse_2_1_12">If we endure, we will also reign with him. If we deny him, he also will deny us.</div>
                        <strong><sup>13</sup></strong><div class="kwverse_2_1_13">if we are unfaithful, he remains faithful,  for he cannot deny himself."</div>
                        <strong><sup>14</sup></strong><div class="kwverse_2_1_14">Keep reminding them of these things. Warn them before God not to quarrel about words. Because of this there is nothing useful. Because of this there is destruction for those who listen.
                            <note class="mdi mdi-bookmark" title="" data-placement="auto right" data-toggle="tooltip" data-original-title="Some versions read, Warn them before the Lord "></note>
                        </div>
                    </div>
                    <div class="input_draft flex_middle">
                        <div class="input_editor textarea"
                             data-initialmarker="11"
                             data-lastmarker="14"
                             data-totalmarkers="4">Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text</div>
                    </div>
                    <div class="flex_right">
                        <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                    </div>
                </div>
                <div class="chunk_divider"></div>
                <div class="row chunk_block flex_container">
                    <div style="padding: 0 15px 0 0;" class="chunk_verses flex_left">
                        <strong><sup>15</sup></strong><div class="kwverse_2_2_15">Do your best to present yourself to God as one approved, a worker who has no reason to be ashamed, who accurately teaches the word of truth.</div>
                        <strong><sup>16</sup></strong><div class="kwverse_2_2_16">Avoid profane talk, which leads to more and more godlessness.</div>
                        <strong><sup>17</sup></strong><div class="kwverse_2_2_17">Their talk will spread like gangrene. Among whom are Hymenaeus and Philetus.</div>
                    </div>
                    <div class="input_draft flex_middle">
                        <div class="input_editor textarea"
                             data-initialmarker="15"
                             data-lastmarker="17"
                             data-totalmarkers="3">Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text</div>
                    </div>
                    <div class="flex_right">
                        <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                    </div>
                </div>
                <div class="chunk_divider"></div>
                <div class="row chunk_block flex_container">
                    <div style="padding: 0 15px 0 0;" class="chunk_verses flex_left">
                        <strong><sup>18</sup></strong><div class="kwverse_2_2_18">These are men who have missed the truth. They say that the resurrection has already happened. They overturn the faith of some.</div>
                        <strong><sup>19</sup></strong><div class="kwverse_2_2_19">However, the firm foundation of God stands. It has this inscription: "The Lord knows those who are his" and "Everyone who names the name of the Lord must depart from unrighteousness."</div>
                        <strong><sup>20</sup></strong><div class="kwverse_2_2_20">In a wealthy home, there are not only containers of gold and silver. There are also containers of wood and clay. Some of these are for honorable use, and some for dishonorable.</div>
                    </div>
                    <div class="input_draft flex_middle">
                        <div class="input_editor textarea"
                             data-initialmarker="18"
                             data-lastmarker="20"
                             data-totalmarkers="3">Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text</div>
                    </div>
                    <div class="flex_right">
                        <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                    </div>
                </div>
                <div class="chunk_divider"></div>
                <div class="row chunk_block flex_container">
                    <div style="padding: 0 15px 0 0;" class="chunk_verses flex_left">
                        <strong><sup>21</sup></strong><div class="kwverse_2_3_21">If someone cleans himself from dishonorable use, he is an honorable container. He is set apart, useful to the Master, and prepared for every good work.</div>
                        <strong><sup>22</sup></strong><div class="kwverse_2_3_22">Flee youthful lusts. Pursue righteousness, faith, love, and peace with those who call on the Lord out of a clean heart.</div>
                        <strong><sup>23</sup></strong><div class="kwverse_2_3_23">But refuse foolish and ignorant questions. You know that they give birth to arguments.</div>
                    </div>
                    <div class="input_draft flex_middle">
                        <div class="input_editor textarea"
                             data-initialmarker="21"
                             data-lastmarker="23"
                             data-totalmarkers="3">Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text</div>
                    </div>
                    <div class="flex_right">
                        <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                    </div>
                </div>
                <div class="chunk_divider"></div>
                <div class="row chunk_block flex_container">
                    <div style="padding: 0 15px 0 0;" class="chunk_verses flex_left">
                        <strong><sup>24</sup></strong><div class="kwverse_2_3_24">The Lord's servant must not quarrel. Instead he must be gentle toward all, able to teach, and patient.</div>
                        <strong><sup>25</sup></strong><div class="kwverse_2_3_25">He must in meekness educate those who oppose him. God may perhaps give them repentance for the knowledge of the truth.</div>
                        <strong><sup>26</sup></strong><div class="kwverse_2_3_26">They may become sober again and leave the devil's trap, after they have been captured by him for his will.</div>
                    </div>
                    <div class="input_draft flex_middle">
                        <div class="input_editor textarea"
                             data-initialmarker="24"
                             data-lastmarker="26"
                             data-totalmarkers="3">Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text</div>
                    </div>
                    <div class="flex_right">
                        <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                    </div>
                </div>
                <div class="chunk_divider"></div>
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
            </form>
        </div>
    </div>
</div>

<div class="content_help closed">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps">
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

        <div class="event_info">
            <div class="participant_info">
                <div class="additional_info">
                    <a href="/events/demo/information"><?php echo __("event_info") ?></a>
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

        <div class="tutorial_content">
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

<script src="<?php echo template_url("js/markers.js?5") ?>"></script>
<link rel="stylesheet" href="<?php echo template_url("css/markers.css?3") ?>">

<script>
    var isChecker = false;
    $(document).ready(function () {
        $("#next_step").click(function (e) {
            e.preventDefault();
            if(!hasChangesOnPage) window.location.href = '/events/demo/information';
            return false;
        });
        $(".input_editor").markers({
            autoSave: false,
            movableButton: true
        });
    });
</script>