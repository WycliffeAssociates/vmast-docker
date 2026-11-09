<?php
require(app_path() . "Views/Components/CommentEditor.php");
require(app_path() . "Views/Components/HelpTools.php");
?>

<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="action_type_container">
            <div class="demo_title"><?php echo __("demo") . " (".__("vsail").")" ?></div>
        </div>
        <div class="main_content_title">
            <div><?php echo __("step_num", ["step_number" => 6]) . ": " . __("theo-check") ?></div>
        </div>
    </div>

    <div class="">
        <div class="main_content">
            <div class="main_content_text">
                <h4>English - <?php echo __("sun") ?> - <?php echo __("new_test") ?> - <span class="book_name">Matthew 17:1-27</span>
                </h4>

                <div class="no_padding">
                    <div class="flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div><strong class="ltr"> <sup>1</sup> </strong>
                                <div class="kwverse_2_0_1">Six days later
                                    Jesus took with him Peter, James, and <b data="0">John his brother</b>, and <b
                                            data="0">brought</b> them up a high
                                    mountain by themselves.
                                </div>
                            </div>
                            <div><strong class="ltr"> <sup>2</sup> </strong>
                                <div class="kwverse_2_0_2">He was <b data="0">transfigured</b>
                                    before them. His face shone like the sun, and his garments became as brilliant as
                                    the light.
                                </div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area" dir="ltr">
                            <div class="vnote">
                                <div class="verse_block font_backsun"><p>      ,         
                                                            </p></div>
                            </div>
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
                    <div class="flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div><strong class="ltr"> <sup>3</sup> </strong>
                                <div class="kwverse_2_0_3">Behold, there appeared
                                    to them Moses and Elijah talking with him.
                                </div>
                            </div>
                            <div><strong class="ltr"> <sup>4</sup> </strong>
                                <div class="kwverse_2_0_4">Peter answered and
                                    said to Jesus, "<b data="0">Lord</b>, it is good for us to be here. If you desire, I
                                    will make here
                                    three shelters—one for you, and one for Moses, and one for Elijah."
                                </div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area" dir="ltr">
                            <div class="vnote">
                                <div class="verse_block font_backsun"><p>  ,  ,              
                                           ”  ,        ,                  </p></div>
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php
                            $comments = [$data["comments"][0][1]];
                            $hasComments = !empty($comments);
                            $commentsNumber = sizeof($comments);
                            $myMemberID = 0;
                            $enableFootNotes = false;
                            require(app_path() . "Views/Components/Comments.php");
                            ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div><strong class="ltr"> <sup>5</sup> </strong>
                                <div class="kwverse_2_0_5">While he was still
                                    speaking, behold, a bright cloud overshadowed them, and behold, there was a voice
                                    out of the cloud, saying, "This is my beloved Son, in whom I am well pleased.
                                    Listen to him."
                                </div>
                            </div>
                            <div><strong class="ltr"> <sup>6</sup> </strong>
                                <div class="kwverse_2_0_6">When the disciples
                                    heard it, they fell on their face and were very afraid.
                                </div>
                            </div>
                            <div><strong class="ltr"> <sup>7</sup> </strong>
                                <div class="kwverse_2_0_7">Then Jesus came
                                    and touched them and said, "Get up and do not be afraid."
                                </div>
                            </div>
                            <div><strong class="ltr"> <sup>8</sup> </strong>
                                <div class="kwverse_2_0_8">Then they looked
                                    up but saw no one except Jesus only.
                                </div>
                            </div>
                            <div><strong class="ltr"> <sup>9</sup> </strong>
                                <div class="kwverse_2_0_9">As they were coming
                                    down the mountain, Jesus commanded them, saying, "Report this vision to no one until
                                    the Son of Man has risen from the dead."
                                </div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area" dir="ltr">
                            <div class="vnote">
                                <div class="verse_block font_backsun"><p>    ,           ”      
                                                   ,                  ”   
                                          ”                  ”        </p></div>

                            </div>
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
                    <div class="flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div><strong class="ltr"> <sup>10</sup> </strong>
                                <div class="kwverse_2_0_10">His disciples
                                    asked him, saying, "Why then do the scribes say that Elijah must come first?"
                                </div>
                            </div>
                            <div><strong class="ltr"> <sup>11</sup> </strong>
                                <div class="kwverse_2_0_11">Jesus answered
                                    and said, "Elijah will indeed come and restore all things.
                                </div>
                            </div>
                            <div><strong class="ltr"> <sup>12</sup> </strong>
                                <div class="kwverse_2_0_12">But I tell you,
                                    Elijah has already come, but they did not recognize him. Instead, they did whatever
                                    they wanted to him. In the same way, the Son of Man will also suffer at their
                                    hands."
                                </div>
                            </div>
                            <div><strong class="ltr"> <sup>13</sup> </strong>
                                <div class="kwverse_2_0_13">Then the disciples
                                    understood that he was speaking to them about John the Baptist.
                                </div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area" dir="ltr">
                            <div class="vnote">
                                <div class="verse_block font_backsun"><p>    ”       ? “    ” 
                                                 ,                           </p>
                                </div>

                            </div>
                        </div>
                        <div class="flex_right">
                            <?php $enableFootNotes = false;
                            require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div><strong class="ltr"> <sup>14</sup> </strong>
                                <div class="kwverse_2_0_14">When they had
                                    come to the crowd, a man came to him, knelt before him, and said,
                                </div>
                            </div>
                            <div><strong class="ltr"> <sup>15</sup> </strong>
                                <div class="kwverse_2_0_15">"Lord, have mercy
                                    on my son, for he is epileptic and suffers severely. For he often falls into the
                                    fire or the water.
                                </div>
                            </div>
                            <div><strong class="ltr"> <sup>16</sup> </strong>
                                <div class="kwverse_2_0_16">I brought him
                                    to your disciples, but they could not cure him."
                                </div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area" dir="ltr">
                            <div class="vnote">
                                <div class="verse_block font_backsun"><p>                     ”  ,
                                        
                                                                        ” </p></div>

                            </div>
                        </div>
                        <div class="flex_right">
                            <?php $enableFootNotes = false;
                            require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div><strong class="ltr"> <sup>17</sup> </strong>
                                <div class="kwverse_2_0_17">Jesus answered and said,
                                    "Unbelieving and corrupt generation, how long will I have to stay with you? How long
                                    must I bear with you? Bring him here to me."
                                </div>
                            </div>
                            <div><strong class="ltr"> <sup>18</sup> </strong>
                                <div class="kwverse_2_0_18">Jesus rebuked the demon,
                                    and it came out of him, and the boy was healed from that hour.
                                </div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area" dir="ltr">
                            <div class="vnote">
                                <div class="verse_block font_backsun"><p>    ”             ?  
                                             ”                </p></div>

                            </div>
                        </div>
                        <div class="flex_right">
                            <?php $enableFootNotes = false;
                            require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div><strong class="ltr"> <sup>19</sup> </strong>
                                <div class="kwverse_2_0_19">Then the disciples
                                    came to Jesus privately and said, "Why could we not cast it out?"
                                </div>
                            </div>
                            <div><strong class="ltr"> <sup>20</sup> </strong>
                                <div class="kwverse_2_0_20">Jesus said to them,
                                    "Because of your small faith. For I truly say to you, if you have faith even as
                                    small
                                    as a grain of mustard seed, you can say to this mountain, 'Move from here to there,'
                                    and it will move, and nothing will be impossible for you.
                                </div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area" dir="ltr">
                            <div class="vnote">
                                <div class="verse_block font_backsun"><p>          ”        ? “   ”
                                         
                                                            ’       ’         ” </p>
                                </div>

                            </div>
                        </div>
                        <div class="flex_right">
                            <?php $enableFootNotes = false;
                            require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div><strong class="ltr"> <sup>21</sup> </strong>
                                <div class="kwverse_2_0_21">
                                    <note
                                            data-original-title="The best ancient copies do not have v. 21, 'But
                                                            this kind of demon does not go out except with prayer and fasting'"
                                            data-toggle="tooltip" data-placement="auto right" title=""
                                            class="mdi mdi-bookmark"></note>
                                </div>
                            </div>
                            <div><strong class="ltr"> <sup>22</sup> </strong>
                                <div class="kwverse_2_0_22">While they stayed
                                    in Galilee, Jesus said to his disciples, "The Son of Man will be delivered into the
                                    hands of people,
                                </div>
                            </div>
                            <div><strong class="ltr"> <sup>23</sup> </strong>
                                <div class="kwverse_2_0_23">and they will kill him,
                                    and the third day he will be raised up." The disciples became very upset.
                                </div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area" dir="ltr">
                            <div class="vnote">
                                <div class="verse_block font_backsun"><p> Verse 21 removed in ULB.        
                                           ”                ”     </p></div>

                            </div>
                        </div>
                        <div class="flex_right">
                            <?php $enableFootNotes = false;
                            require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div><strong class="ltr"> <sup>24</sup> </strong>
                                <div class="kwverse_2_0_24">When they had come to
                                    Capernaum, the men who collected the two-drachma tax came to Peter and said,
                                    "Does not your teacher pay the two-drachma tax?"
                                </div>
                            </div>
                            <div><strong class="ltr"> <sup>25</sup> </strong>
                                <div class="kwverse_2_0_25">He said, "Yes."
                                    When Peter came into the house, Jesus spoke to him first and said, "What do you
                                    think,
                                    Simon? From whom do the kings of the earth collect tolls or taxes? From their sons
                                    or from others?"
                                </div>
                            </div>
                            <div><strong class="ltr"> <sup>26</sup> </strong>
                                <div class="kwverse_2_0_26">When he said,
                                    "From others," Jesus said to him, "Then the sons are free..
                                </div>
                            </div>
                            <div><strong class="ltr"> <sup>27</sup> </strong>
                                <div class="kwverse_2_0_27">But so that
                                    we do not cause the tax collectors to sin, go to the sea, throw in a hook, and draw
                                    in the fish that comes up first. When you have opened its mouth, you will find a
                                    shekel.
                                    Take it and give it to the tax collectors for me and you."
                                </div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area" dir="ltr">
                            <div class="vnote">
                                <div class="verse_block font_backsun"><p>                 ? “ 
                                          ”  ”     ,      "    ?      ?  
                                        , “  ”    ,                        
                                                           ” </p></div>

                            </div>
                        </div>
                        <div class="flex_right">
                            <?php $enableFootNotes = false;
                            require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                </div>
            </div>

            <div class="main_content_footer row">
                <form action="" method="post">
                    <div class="form-group">
                        <div class="main_content_confirm_desc"><?php echo __("confirm_finished") ?></div>
                        <label><input name="confirm_step" id="confirm_step" value="1"
                                      type="checkbox"> <?php echo __("confirm_yes") ?></label>
                    </div>

                    <button id="next_step" class="btn btn-primary" disabled="disabled">
                        <?php echo __($data["next_step"]) ?>
                    </button>
                </form>
                <div class="step_right"><?php echo __("step_num", ["step_number" => 6]) ?></div>
            </div>
        </div>
    </div>
</div>

<div class="content_help closed">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps is_checker_page_help">
            <div class="help_name_steps">
                <span><?php echo __("step_num", ["step_number" => 6]) ?>:</span> <?php echo __("theo-check") ?></div>
            <div class="help_descr_steps">
                <ul>
                    <li><b><?php echo __("purpose") ?></b> <?php echo __("theo-check_purpose") ?></li>
                    <li><?php echo __("theo-check_help_1") ?></li>
                    <li><?php echo __("move_to_next_step", ["step" => __($data["next_step"])]) ?></li>
                </ul>
                <div class="show_tutorial_popup"> >>> <?php echo __("show_more") ?></div>
            </div>
        </div>

        <div class="event_info is_checker_page_help">
            <div class="participant_info">
                <div class="additional_info">
                    <a href="/events/demo-sun/information"><?php echo __("event_info") ?></a>
                </div>
            </div>
        </div>

        <div class="tr_tools">
            <?php
            renderSailDict();
            renderTn($data["tnLangID"]);
            renderTw($data["twLangID"]);
            ?>
        </div>
    </div>
</div>

<div class="tutorial_container">
    <div class="tutorial_popup">
        <div class="tutorial-close glyphicon glyphicon-remove"></div>
        <div class="tutorial_pic">
            <img src="<?php echo template_url("img/steps/icons/keyword-check.png") ?>" height="100px" width="100px">
            <img src="<?php echo template_url("img/steps/big/keyword-check.png") ?>" height="280px" width="280px">

        </div>

        <div class="tutorial_content is_checker_page_help">
            <h3><?php echo __("theo-check") ?></h3>
            <ul>
                <li><b><?php echo __("purpose") ?></b> <?php echo __("theo-check_purpose") ?></li>
                <li><?php echo __("theo-check_help_1") ?></li>
                <li><?php echo __("move_to_next_step", ["step" => __($data["next_step"])]) ?></li>
            </ul>
        </div>
    </div>
</div>

<script>
    isChecker = true;
    $(document).ready(function () {
        $("#next_step").click(function (e) {
            e.preventDefault();
            if (!hasChangesOnPage) window.location.href = '/events/demo-sun/content_review_checker';
            return false;
        });

        $(".ttools_panel .word_def").each(function () {
            let html = convertRcLinks($(this).html());
            $(this).html(html);
        });
    });
</script>