<?php
require(app_path() . "Views/Components/CommentEditor.php");
require(app_path() . "Views/Components/HelpTools.php");
?>

<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="action_type_container">
            <div class="demo_title"><?php echo __("demo") . " (".__("8steps_vmast").")" ?></div>
            <div class="action_type type_checking"><?php echo __("type_checking"); ?></div>
            <div class="action_region"></div>
        </div>
        <div class="main_content_title">
            <div><?php echo __("step_num", ["step_number" => 7]) . ": " . __("keyword-check")?></div>
        </div>
    </div>

    <div class="main_content">
        <div class="main_content_text row">
            <h4>English - <?php echo __("ulb") ?> - <?php echo __("new_test") ?> - <span class='book_name'>2 Timothy 2:1-26</span></h4>

            <div class="col-sm-12 one_side_content">
                <div class="row flex_container chunk_block">
                    <div class="chunk_verses flex_left" style="padding: 0 15px 0 0;">
                        <strong><sup>1</sup></strong><div class="kwverse_2_0_1"><b data="0">You</b> therefore, my child, be <b data="0">strengthened</b> in the <b data="0">grace</b> that is in <b data="0">Christ Jesus</b>.</div>
                        <strong><sup>2</sup></strong><div class="kwverse_2_0_2">And the things you heard from me among many witnesses, entrust them to faithful people who will be able to teach others also.</div>
                        <strong><sup>3</sup></strong><div class="kwverse_2_0_3"><b data="0">Suffer</b> hardship with me, as a good soldier of Christ Jesus.</div>
                    </div>
                    <div class="editor_area flex_middle" style="padding: 0;">
                        <div class="vnote">
                            Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text
                        </div>
                    </div>
                    <div class="flex_right">
                        <?php
                        $comments = $data["comments"][0];
                        $hasComments = !empty($comments);
                        $commentsNumber = sizeof($comments);
                        $myMemberID = 0;
                        require(app_path() . "Views/Components/Comments.php");
                        ?>
                    </div>
                </div>
                <div class="chunk_divider"></div>
                <div class="row flex_container chunk_block">
                    <div class="chunk_verses flex_left" style="padding: 0 15px 0 0;">
                        <strong><sup>4</sup></strong><div class="kwverse_2_0_4">No soldier serves while entangled in the affairs of this life, so that he may please his superior officer.</div>
                        <strong><sup>5</sup></strong><div class="kwverse_2_0_5">Also, if someone competes as an athlete, he is not crowned unless he competes by the rules.</div>
                        <strong><sup>6</sup></strong><div class="kwverse_2_0_6">It is necessary that the hardworking farmer receive his share of the crops first.</div>
                        <strong><sup>7</sup></strong><div class="kwverse_2_0_7">Think about what I am saying, for the Lord will give you understanding in everything.</div>
                    </div>
                    <div class="editor_area flex_middle" style="padding: 0;">
                        <div class="vnote">
                            Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text
                        </div>
                    </div>
                    <div class="flex_right">
                        <?php
                        $comments = [$data["comments"][0][0]];
                        $hasComments = !empty($comments);
                        $commentsNumber = sizeof($comments);
                        $myMemberID = 0;
                        require(app_path() . "Views/Components/Comments.php");
                        ?>
                    </div>
                </div>
                <div class="chunk_divider"></div>
                <div class="row flex_container chunk_block">
                    <div class="chunk_verses flex_left" style="padding: 0 15px 0 0;">
                        <strong><sup>8</sup></strong><div class="kwverse_2_1_8">Remember Jesus Christ, from David's seed, who was raised from the dead ones. This is according to my gospel message,</div>
                        <strong><sup>9</sup></strong><div class="kwverse_2_1_9">for which I am suffering to the point of being chained as a criminal. But the word of God is not chained.</div>
                        <strong><sup>10</sup></strong><div class="kwverse_2_1_10">Therefore I endure all things for those who are chosen, so that they also may obtain the salvation that is in Christ Jesus, with eternal glory.</div>
                    </div>
                    <div class="editor_area flex_middle" style="padding: 0;">
                        <div class="vnote">
                            Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text
                        </div>
                    </div>
                    <div class="flex_right">
                        <?php $hasComments = false; require(app_path() . "Views/Components/Comments.php"); ?>
                    </div>
                </div>
                <div class="chunk_divider"></div>
                <div class="row flex_container chunk_block">
                    <div class="chunk_verses flex_left" style="padding: 0 15px 0 0;">
                        <strong><sup>11</sup></strong><div class="kwverse_2_1_11">This saying is trustworthy:   "If we have died with him, we will also live with him.</div>
                        <strong><sup>12</sup></strong><div class="kwverse_2_1_12">If we endure, we will also reign with him. If we deny him, he also will deny us.</div>
                        <strong><sup>13</sup></strong><div class="kwverse_2_1_13">if we are unfaithful, he remains faithful,  for he cannot deny himself."</div>
                        <strong><sup>14</sup></strong><div class="kwverse_2_1_14">Keep reminding them of these things. Warn them before God not to quarrel about words. Because of this there is nothing useful. Because of this there is destruction for those who listen.
                            <note class="mdi mdi-bookmark" title="" data-placement="auto right" data-toggle="tooltip" data-original-title="Some versions read, Warn them before the Lord "></note>
                        </div>
                    </div>
                    <div class="editor_area flex_middle" style="padding: 0;">
                        <div class="vnote">
                            Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text
                        </div>
                    </div>
                    <div class="flex_right">
                        <?php require(app_path() . "Views/Components/Comments.php"); ?>
                    </div>
                </div>
                <div class="chunk_divider"></div>
                <div class="row flex_container chunk_block">
                    <div class="chunk_verses flex_left" style="padding: 0 15px 0 0;">
                        <strong><sup>15</sup></strong><div class="kwverse_2_2_15">Do your best to present yourself to God as one approved, a worker who has no reason to be ashamed, who accurately teaches the word of truth.</div>
                        <strong><sup>16</sup></strong><div class="kwverse_2_2_16">Avoid profane talk, which leads to more and more godlessness.</div>
                        <strong><sup>17</sup></strong><div class="kwverse_2_2_17">Their talk will spread like gangrene. Among whom are Hymenaeus and Philetus.</div>
                    </div>
                    <div class="editor_area flex_middle" style="padding: 0;">
                        <div class="vnote">
                            Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text
                        </div>
                    </div>
                    <div class="flex_right">
                        <?php require(app_path() . "Views/Components/Comments.php"); ?>
                    </div>
                </div>
                <div class="chunk_divider"></div>
                <div class="row flex_container chunk_block">
                    <div class="chunk_verses flex_left" style="padding: 0 15px 0 0;">
                        <strong><sup>18</sup></strong><div class="kwverse_2_2_18">These are men who have missed the truth. They say that the resurrection has already happened. They overturn the faith of some.</div>
                        <strong><sup>19</sup></strong><div class="kwverse_2_2_19">However, the firm foundation of God stands. It has this inscription: "The Lord knows those who are his" and "Everyone who names the name of the Lord must depart from unrighteousness."</div>
                        <strong><sup>20</sup></strong><div class="kwverse_2_2_20">In a wealthy home, there are not only containers of gold and silver. There are also containers of wood and clay. Some of these are for honorable use, and some for dishonorable.</div>
                    </div>
                    <div class="editor_area flex_middle" style="padding: 0;">
                        <div class="vnote">
                            Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text
                        </div>
                    </div>
                    <div class="flex_right">
                        <?php require(app_path() . "Views/Components/Comments.php"); ?>
                    </div>
                </div>
                <div class="chunk_divider"></div>
                <div class="row flex_container chunk_block">
                    <div class="chunk_verses flex_left" style="padding: 0 15px 0 0;">
                        <strong><sup>21</sup></strong><div class="kwverse_2_3_21">If someone cleans himself from dishonorable use, he is an honorable container. He is set apart, useful to the Master, and prepared for every good work.</div>
                        <strong><sup>22</sup></strong><div class="kwverse_2_3_22">Flee youthful lusts. Pursue righteousness, faith, love, and peace with those who call on the Lord out of a clean heart.</div>
                        <strong><sup>23</sup></strong><div class="kwverse_2_3_23">But refuse foolish and ignorant questions. You know that they give birth to arguments.</div>
                    </div>
                    <div class="editor_area flex_middle" style="padding: 0;">
                        <div class="vnote">
                            Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text
                        </div>
                    </div>
                    <div class="flex_right">
                        <?php require(app_path() . "Views/Components/Comments.php"); ?>
                    </div>
                </div>
                <div class="chunk_divider"></div>
                <div class="row flex_container chunk_block">
                    <div class="chunk_verses flex_left" style="padding: 0 15px 0 0;">
                        <strong><sup>24</sup></strong><div class="kwverse_2_3_24">The Lord's servant must not quarrel. Instead he must be gentle toward all, able to teach, and patient.</div>
                        <strong><sup>25</sup></strong><div class="kwverse_2_3_25">He must in meekness educate those who oppose him. God may perhaps give them repentance for the knowledge of the truth.</div>
                        <strong><sup>26</sup></strong><div class="kwverse_2_3_26">They may become sober again and leave the devil's trap, after they have been captured by him for his will.</div>
                    </div>
                    <div class="editor_area flex_middle" style="padding: 0;">
                        <div class="vnote">
                            Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text, Demo translation text
                        </div>
                    </div>
                    <div class="flex_right">
                        <?php require(app_path() . "Views/Components/Comments.php"); ?>
                    </div>
                </div>
                <div class="chunk_divider"></div>
            </div>
        </div>

        <div class="main_content_footer row">
            <form action="" method="post" id="checker_submit">
                <div class="form-group">
                    <div class="main_content_confirm_desc"><?php echo __("confirm_finished")?></div>
                    <label><input name="confirm_step" id="confirm_step" value="1" type="checkbox"> <?php echo __("confirm_yes")?></label>
                </div>

                <button id="checker_ready" class="btn btn-warning" disabled>
                    <?php echo __("ready_to_check")?>
                </button>
                <button id="next_step" class="btn btn-primary" disabled="">
                    <?php echo __($data["next_step"])?>
                </button>
            </form>
            <div class="step_right chk"><?php echo __("step_num", ["step_number" => 7])?></div>
        </div>
    </div>
</div>

<div class="content_help closed">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps is_checker_page_help">
            <div class="help_name_steps"><span><?php echo __("step_num", ["step_number" => 7])?>:</span> <?php echo __("keyword-check")?></div>
            <div class="help_descr_steps">
                <ul>
                    <li><b><?php echo __("purpose") ?></b> <?php echo __("keyword-check_checker_purpose") ?></li>
                    <li><b><?php echo __("length") ?></b> <?php echo __("keyword-check_checker_length") ?></li>
                    <li><?php echo __("keyword-check_checker_help_1") ?></li>
                    <li><b><?php echo __("self-check_help_1") ?></b></li>
                    <li><?php echo __("keyword-check_checker_help_3") ?></li>
                    <li><?php echo __("keyword-check_checker_help_4") ?></li>
                    <li><?php echo __("keyword-check_checker_help_5") ?></li>
                    <li><?php echo __("peer-review_help_5") ?>
                        <ol>
                            <li><?php echo __("keyword-check_help_4a") ?></li>
                            <li><?php echo __("self-check_help_5c") ?></li>
                        </ol>
                    </li>
                    <li><?php echo __("check_footnote_help_1") ?>
                        <ol>
                            <li><?php echo __("check_footnote_help_1a") ?></li>
                            <li><?php echo __("check_footnote_help_1b") ?></li>
                            <li><?php echo __("check_footnote_help_1c") ?></li>
                            <li><?php echo __("check_footnote_help_1d") ?></li>
                            <li><?php echo __("check_footnote_help_1e") ?></li>
                        </ol>
                    </li>
                    <li><?php echo __("keyword-check_help_5") ?></li>
                    <li><b><?php echo __("keyword-check_help_6") ?></b></li>
                    <li><?php echo __("keyword-check_checker_help_9") ?></li>
                    <li><?php echo __("keyword-check_checker_help_10") ?></li>
                    <li><?php echo __("keyword-check_checker_help_11") ?></li>
                    <li><?php echo __("keyword-check_checker_help_12", ["icon" => "<span class='mdi mdi-lead-pencil'></span>"]) ?></li>
                    <li><?php echo __("move_to_next_step_alt", ["step" => __($data["next_step"])]) ?></li>
                </ul>
                <div class="show_tutorial_popup"> >>> <?php echo __("show_more")?></div>
            </div>
        </div>

        <div class="event_info is_checker_page_help">
            <div class="participant_info">
                <div class="participant_name">
                    <span><?php echo __("your_translator") ?>:</span>
                    <span>Mark P.</span>
                </div>
                <div class="additional_info">
                    <a href="/events/demo/information"><?php echo __("event_info") ?></a>
                </div>
            </div>
        </div>

        <div class="tr_tools">
            <?php
            renderTn($data["tnLangID"]);
            renderTw($data["twLangID"]);
            renderBc($data["bcLangID"]);
            renderRubric();
            ?>
        </div>

        <div class="checker_view">
            <a href="/events/demo/keyword_check"><?php echo __("translator_view") ?></a>
        </div>
    </div>
</div>

<div class="tutorial_container">
    <div class="tutorial_popup">
        <div class="tutorial-close glyphicon glyphicon-remove"></div>
        <div class="tutorial_pic">
            <img src="<?php echo template_url("img/steps/icons/keyword-check.png") ?>" width="100px" height="100px">
            <img src="<?php echo template_url("img/steps/big/keyword-check.png") ?>" width="280px" height="280px">
        </div>

        <div class="tutorial_content is_checker_page_help">
            <h3><?php echo __("keyword-check")?></h3>
            <ul>
                <li><b><?php echo __("purpose") ?></b> <?php echo __("keyword-check_checker_purpose") ?></li>
                <li><b><?php echo __("length") ?></b> <?php echo __("keyword-check_checker_length") ?></li>
                <li><?php echo __("keyword-check_checker_help_1") ?></li>
                <li><b><?php echo __("self-check_help_1") ?></b></li>
                <li><?php echo __("keyword-check_checker_help_3") ?></li>
                <li><?php echo __("keyword-check_checker_help_4") ?></li>
                <li><?php echo __("keyword-check_checker_help_5") ?></li>
                <li><?php echo __("peer-review_help_5") ?>
                    <ol>
                        <li><?php echo __("keyword-check_help_4a") ?></li>
                        <li><?php echo __("self-check_help_5c") ?></li>
                    </ol>
                </li>
                <li><?php echo __("check_footnote_help_1") ?>
                    <ol>
                        <li><?php echo __("check_footnote_help_1a") ?></li>
                        <li><?php echo __("check_footnote_help_1b") ?></li>
                        <li><?php echo __("check_footnote_help_1c") ?></li>
                        <li><?php echo __("check_footnote_help_1d") ?></li>
                        <li><?php echo __("check_footnote_help_1e") ?></li>
                    </ol>
                </li>
                <li><?php echo __("keyword-check_help_5") ?></li>
                <li><b><?php echo __("keyword-check_help_6") ?></b></li>
                <li><?php echo __("keyword-check_checker_help_9") ?></li>
                <li><?php echo __("keyword-check_checker_help_10") ?></li>
                <li><?php echo __("keyword-check_checker_help_11") ?></li>
                <li><?php echo __("keyword-check_checker_help_12", ["icon" => "<span class='mdi mdi-lead-pencil'></span>"]) ?></li>
                <li><?php echo __("move_to_next_step_alt", ["step" => __($data["next_step"])]) ?></li>
            </ul>
        </div>
    </div>
</div>

<script>
    var isChecker = true;
    $(document).ready(function () {
        $("#next_step").click(function (e) {
            renderConfirmPopup(Language.checkerConfirmTitle, Language.checkerConfirm,
                function () {
                    
                    window.location.href = '/events/demo/content_review';
                },
                function () {
                    $("#confirm_step").prop("checked", false);
                    $("#next_step").prop("disabled", true);
                    $("#checker_ready").text(Language.ready_to_check);
                    $( this ).dialog("close");
                },
                function () {
                    $("#confirm_step").prop("checked", false);
                    $("#next_step").prop("disabled", true);
                    $("#checker_ready").text(Language.ready_to_check);
                    $( this ).dialog("close");
                });

            e.preventDefault();
            return false;
        });

        $(".ttools_panel .word_def").each(function() {
            let html = convertRcLinks($(this).html());
            $(this).html(html);
        });
    });
</script>