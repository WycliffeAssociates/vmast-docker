<?php
require(app_path() . "Views/Components/CommentEditor.php");
require(app_path() . "Views/Components/HelpTools.php");
?>

<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="action_type_container">
            <div class="demo_title"><?php echo __("demo") . " (" . __("vsail_review") . ")" ?></div>
        </div>
        <div class="main_content_title">
            <div><?php echo __("step_num", ["step_number" => 1]) . ": " . __("peer-review-l3_full") ?></div>
        </div>
    </div>

    <div class="">
        <div class="main_content">
            <div class="main_content_text">
                <h4>SUN - <?php echo __("sun") ?> - <?php echo __("new_test") ?> - <span class="book_name">Mathew 17:1-27</span>
                </h4>

                <div class="no_padding">
                    <div class="sun_mode">
                        <label>
                            <input type="checkbox" autocomplete="off" checked
                                   data-toggle="toggle"
                                   data-on="SUN"
                                   data-off="BACKSUN"/>
                        </label>
                    </div>

                    <div class="row chunk_block">
                        <div class="flex_container">
                            <div class="chunk_verses flex_left" dir="ltr">
                                <p class="verse_text" data-verse="1" style="height: initial;">
                                    <strong class="ltr">
                                        <sup>1</sup>
                                    </strong>
                                    Six days later Jesus took with him Peter, James, and John his brother, and brought
                                    them up a high mountain by themselves. </p>
                                <p class="verse_text" data-verse="2" style="height: initial;">
                                    <strong class="ltr">
                                        <sup>2</sup>
                                    </strong>
                                    He was transfigured before them. His face shone like the sun, and his garments
                                    became as brilliant as the light. </p>
                            </div>
                            <div class="flex_middle editor_area sun_content">
                                <div class="verse_block flex_chunk" data-verse="1">
                                         ,             
                                </div>
                                <div class="verse_block flex_chunk" data-verse="2">
                                                    
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
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row chunk_block">
                        <div class="flex_container">
                            <div class="chunk_verses flex_left" dir="ltr">
                                <p class="verse_text" data-verse="3" style="height: initial;">
                                    <strong class="ltr">
                                        <sup>3</sup>
                                    </strong>
                                    Behold, there appeared to them Moses and Elijah talking with him. </p>
                                <p class="verse_text" data-verse="4" style="height: initial;">
                                    <strong class="ltr">
                                        <sup>4</sup>
                                    </strong>
                                    Peter answered and said to Jesus, "Lord, it is good for us to be here. If you
                                    desire, I will make here three shelters—one for you, and one for Moses, and one for
                                    Elijah." </p>
                            </div>
                            <div class="flex_middle editor_area sun_content">
                                <div class="verse_block flex_chunk" data-verse="3">
                                     ,  ,             
                                </div>
                                <div class="verse_block flex_chunk" data-verse="4">
                                        ”  ,        ,                 
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
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row chunk_block">
                        <div class="flex_container">
                            <div class="chunk_verses flex_left" dir="ltr">
                                <p class="verse_text" data-verse="5" style="height: initial;">
                                    <strong class="ltr">
                                        <sup>5</sup>
                                    </strong>
                                    While he was still speaking, behold, a bright cloud overshadowed them, and behold,
                                    there was a voice out of the cloud, saying, "This is my beloved Son, in whom I am
                                    well pleased. Listen to him." </p>
                                <p class="verse_text" data-verse="6" style="height: initial;">
                                    <strong class="ltr">
                                        <sup>6</sup>
                                    </strong>
                                    When the disciples heard it, they fell on their face and were very afraid. </p>
                                <p class="verse_text" data-verse="7" style="height: initial;">
                                    <strong class="ltr">
                                        <sup>7</sup>
                                    </strong>
                                    Then Jesus came and touched them and said, "Get up and do not be afraid." </p>
                                <p class="verse_text" data-verse="8" style="height: initial;">
                                    <strong class="ltr">
                                        <sup>8</sup>
                                    </strong>
                                    Then they looked up but saw no one except Jesus only. </p>
                            </div>
                            <div class="flex_middle editor_area sun_content">
                                <div class="verse_block flex_chunk" data-verse="5">
                                       ,           ”              
                                </div>
                                <div class="verse_block flex_chunk" data-verse="6">
                                       ,         
                                </div>
                                <div class="verse_block flex_chunk" data-verse="7">
                                            ”      ”
                                </div>
                                <div class="verse_block flex_chunk" data-verse="8">
                                           
                                </div>
                            </div>
                            <div class="flex_right">
                                <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row chunk_block">
                        <div class="flex_container">
                            <div class="chunk_verses flex_left" dir="ltr">
                                <p class="verse_text" data-verse="9" style="height: initial;">
                                    <strong class="ltr">
                                        <sup>9</sup>
                                    </strong>
                                    As they were coming down the mountain, Jesus commanded them, saying, "Report this
                                    vision to no one until the Son of Man has risen from the dead." </p>
                                <p class="verse_text" data-verse="10" style="height: initial;">
                                    <strong class="ltr">
                                        <sup>10</sup>
                                    </strong>
                                    His disciples asked him, saying, "Why then do the scribes say that Elijah must come
                                    first?" </p>
                            </div>
                            <div class="flex_middle editor_area sun_content">
                                <div class="verse_block flex_chunk" data-verse="9">
                                             ”       
                                </div>
                                <div class="verse_block flex_chunk" data-verse="10">
                                       ”       ? “
                                </div>
                            </div>
                            <div class="flex_right">
                                <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row chunk_block">
                        <div class="flex_container">
                            <div class="chunk_verses flex_left" dir="ltr">
                                <p class="verse_text" data-verse="11" style="height: initial;">
                                    <strong class="ltr">
                                        <sup>11</sup>
                                    </strong>
                                    Jesus answered and said, "Elijah will indeed come and restore all things. </p>
                                <p class="verse_text" data-verse="12" style="height: initial;">
                                    <strong class="ltr">
                                        <sup>12</sup>
                                    </strong>
                                    But I tell you, Elijah has already come, but they did not recognize him. Instead,
                                    they did whatever they wanted to him. In the same way, the Son of Man will also
                                    suffer at their hands." </p>
                                <p class="verse_text" data-verse="13" style="height: initial;">
                                    <strong class="ltr">
                                        <sup>13</sup>
                                    </strong>
                                    Then the disciples understood that he was speaking to them about John the Baptist.
                                </p>
                            </div>
                            <div class="flex_middle editor_area sun_content">
                                <div class="verse_block flex_chunk" data-verse="11">
                                       ”       
                                </div>
                                <div class="verse_block flex_chunk" data-verse="12">
                                       ,                  
                                </div>
                                <div class="verse_block flex_chunk" data-verse="13">
                                           
                                </div>
                            </div>
                            <div class="flex_right">
                                <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row chunk_block">
                        <div class="flex_container">
                            <div class="chunk_verses flex_left" dir="ltr">
                                <p class="verse_text" data-verse="14" style="height: initial;">
                                    <strong class="ltr">
                                        <sup>14</sup>
                                    </strong>
                                    When they had come to the crowd, a man came to him, knelt before him, and said, </p>
                                <p class="verse_text" data-verse="15" style="height: initial;">
                                    <strong class="ltr">
                                        <sup>15</sup>
                                    </strong>
                                    "Lord, have mercy on my son, for he is epileptic and suffers severely. For he often
                                    falls into the fire or the water. </p>
                                <p class="verse_text" data-verse="16" style="height: initial;">
                                    <strong class="ltr">
                                        <sup>16</sup>
                                    </strong>
                                    I brought him to your disciples, but they could not cure him." </p>
                            </div>
                            <div class="flex_middle editor_area sun_content">
                                <div class="verse_block flex_chunk" data-verse="14">
                                                       
                                </div>
                                <div class="verse_block flex_chunk" data-verse="15">
                                    ”  ,                    
                                </div>
                                <div class="verse_block flex_chunk" data-verse="16">
                                                 ”
                                </div>
                            </div>
                            <div class="flex_right">
                                <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row chunk_block">
                        <div class="flex_container">
                            <div class="chunk_verses flex_left" dir="ltr">
                                <p class="verse_text" data-verse="17" style="height: initial;">
                                    <strong class="ltr">
                                        <sup>17</sup>
                                    </strong>
                                    Jesus answered and said, "Unbelieving and corrupt generation, how long will I have
                                    to stay with you? How long must I bear with you? Bring him here to me." </p>
                                <p class="verse_text" data-verse="18" style="height: initial;">
                                    <strong class="ltr">
                                        <sup>18</sup>
                                    </strong>
                                    Jesus rebuked the demon, and it came out of him, and the boy was healed from that
                                    hour. </p>
                            </div>
                            <div class="flex_middle editor_area sun_content">
                                <div class="verse_block flex_chunk" data-verse="17">
                                       ”             ?        ”
                                </div>
                                <div class="verse_block flex_chunk" data-verse="18">
                                                  
                                </div>
                            </div>
                            <div class="flex_right">
                                <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row chunk_block">
                        <div class="flex_container">
                            <div class="chunk_verses flex_left" dir="ltr">
                                <p class="verse_text" data-verse="19" style="height: initial;">
                                    <strong class="ltr">
                                        <sup>19</sup>
                                    </strong>
                                    Then the disciples came to Jesus privately and said, "Why could we not cast it out?"
                                </p>
                                <p class="verse_text" data-verse="20" style="height: initial;">
                                    <strong class="ltr">
                                        <sup>20</sup>
                                    </strong>
                                    Jesus said to them, "Because of your small faith. For I truly say to you, if you
                                    have faith even as small as a grain of mustard seed, you can say to this mountain,
                                    'Move from here to there,' and it will move, and nothing will be impossible for you.
                                </p>
                                <p class="verse_text" data-verse="21" style="height: initial;">
                                    <strong class="ltr">
                                        <sup>21</sup>
                                    </strong>
                                    <span data-toggle="tooltip" data-placement="auto auto" title=""
                                          class="booknote mdi mdi-bookmark"
                                          data-original-title="The best ancient copies omit v. 21,  But this kind of demon does not go out except with prayer and fasting  . "></span>
                                </p>
                            </div>
                            <div class="flex_middle editor_area sun_content">
                                <div class="verse_block flex_chunk" data-verse="19">
                                             ”        ? “
                                </div>
                                <div class="verse_block flex_chunk" data-verse="20">
                                      ”                       ’       ’         ”
                                </div>
                                <div class="verse_block flex_chunk" data-verse="21">
                                    Verse removed in ULB.
                                </div>
                            </div>
                            <div class="flex_right">
                                <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row chunk_block">
                        <div class="flex_container">
                            <div class="chunk_verses flex_left" dir="ltr">
                                <p class="verse_text" data-verse="22" style="height: initial;">
                                    <strong class="ltr">
                                        <sup>22</sup>
                                    </strong>
                                    While they stayed in Galilee, Jesus said to his disciples, "The Son of Man will be
                                    delivered into the hands of people, </p>
                                <p class="verse_text" data-verse="23" style="height: initial;">
                                    <strong class="ltr">
                                        <sup>23</sup>
                                    </strong>
                                    and they will kill him, and the third day he will be raised up." The disciples
                                    became very upset. </p>
                            </div>
                            <div class="flex_middle editor_area sun_content">
                                <div class="verse_block flex_chunk" data-verse="22">
                                               ”     
                                </div>
                                <div class="verse_block flex_chunk" data-verse="23">
                                              ”    
                                </div>
                            </div>
                            <div class="flex_right">
                                <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row chunk_block">
                        <div class="flex_container">
                            <div class="chunk_verses flex_left" dir="ltr">
                                <p class="verse_text" data-verse="24" style="height: initial;">
                                    <strong class="ltr">
                                        <sup>24</sup>
                                    </strong>
                                    When they had come to Capernaum, the men who collected the half-shekel tax came to
                                    Peter and said, "Does not your teacher pay the half-shekel tax?" </p>
                                <p class="verse_text" data-verse="25" style="height: initial;">
                                    <strong class="ltr">
                                        <sup>25</sup>
                                    </strong>
                                    He said, "Yes." When Peter came into the house, Jesus spoke to him first and said,
                                    "What do you think, Simon? From whom do the kings of the earth collect tolls or
                                    taxes? From their sons or from others?" </p>
                            </div>
                            <div class="flex_middle editor_area sun_content">
                                <div class="verse_block flex_chunk" data-verse="24">
                                                    ? “
                                </div>
                                <div class="verse_block flex_chunk" data-verse="25">
                                      ”  ”     ,          ?      ?
                                </div>
                            </div>
                            <div class="flex_right">
                                <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row chunk_block">
                        <div class="flex_container">
                            <div class="chunk_verses flex_left" dir="ltr">
                                <p class="verse_text" data-verse="26" style="height: initial;">
                                    <strong class="ltr">
                                        <sup>26</sup>
                                    </strong>
                                    When he said, "From others," Jesus said to him, "Then the sons are free." </p>
                                <p class="verse_text" data-verse="27" style="height: initial;">
                                    <strong class="ltr">
                                        <sup>27</sup>
                                    </strong>
                                    But so that we do not cause the tax collectors to sin, go to the sea, throw in a
                                    hook, and draw in the fish that comes up first. When you have opened its mouth, you
                                    will find a shekel. Take it and give it to the tax collectors for me and you." </p>
                            </div>
                            <div class="flex_middle editor_area sun_content">
                                <div class="verse_block flex_chunk" data-verse="26">
                                      , “  ”    ,    
                                </div>
                                <div class="verse_block flex_chunk" data-verse="27">
                                                                           ”
                                </div>
                            </div>
                            <div class="flex_right">
                                <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
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
                <div class="step_right"></div>
            </div>
            <div class="step_right alt"><?php echo __("step_num", ["step_number" => 1]) ?></div>
        </div>
    </div>
</div>

<div class="content_help closed">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps is_checker_page_help">
            <div class="help_name_steps">
                <span><?php echo __("step_num", ["step_number" => 1]) ?>: </span>
                <?php echo __("peer-review-l3") ?>
            </div>
            <div class="help_descr_steps">
                <ul>
                    <li><b><?php echo __("purpose") ?></b> <?php echo __("self-check_l2_purpose") ?></li>
                    <li><?php echo __("peer-review-l3_help_1") ?></li>
                    <li><?php echo __("peer-review-l3_help_2") ?></li>
                    <li><?php echo __("peer-review_checker_help_2") ?></li>
                    <li><?php echo __("peer-review-l3_help_4") ?></li>
                    <li><?php echo __("peer-review_checker_help_6") ?></li>
                    <li><?php echo __("peer-review-l3_help_6") ?></li>
                    <li><?php echo __("peer-review-l3_help_7") ?>
                        <ol>
                            <li><?php echo __("peer-review-l3_help_7a", ["icon" => "<span class='mdi mdi-lead-pencil'></span>"]) ?></li>
                            <li><?php echo __("peer-review-l3_help_7b", ["icon" => "<span class='mdi mdi-lead-pencil'></span>"]) ?></li>
                            <li><?php echo __("peer-review-l3_help_7c") ?></li>
                        </ol>
                    </li>
                    <li><?php echo __("peer-review_help_5") ?>
                        <ol>
                            <li><?php echo __("self-check_help_5a") ?></li>
                            <li><?php echo __("keyword-check_help_4a") ?></li>
                            <li><?php echo __("self-check_help_5b") ?></li>
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
                    <li><?php echo __("move_to_next_step_alt", ["step" => __($data["next_step"])]) ?></li>
                </ul>
                <div class="show_tutorial_popup"> >>> <?php echo __("show_more") ?></div>
            </div>
        </div>

        <div class="event_info is_checker_page_help">
            <div class="participant_info">
                <div class="participant_name">
                    <span><?php echo __("your_checker") ?>:</span>
                    <span class="checker_name_span">
                                Henry M.
                            </span>
                </div>
                <div class="additional_info">
                    <a href="/events/demo-sun-review/information"><?php echo __("event_info") ?></a>
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

        <div class="checker_view">
            <a href="/events/demo-sun-review/peer_review_l3_checker"><?php echo __("checker_other_view", [2]) ?></a>
        </div>
    </div>
</div>

<div class="tutorial_container">
    <div class="tutorial_popup">
        <div class="tutorial-close glyphicon glyphicon-remove"></div>
        <div class="tutorial_pic">
            <img src="<?php echo template_url("img/steps/icons/peer-review.png") ?>" height="100px" width="100px">
            <img src="<?php echo template_url("img/steps/big/peer-review.png") ?>" height="280px" width="280px">
        </div>

        <div class="tutorial_content">
            <h3><?php echo __("peer-review-l3_full") ?></h3>
            <ul>
                <li><b><?php echo __("purpose") ?></b> <?php echo __("self-check_l2_purpose") ?></li>
                <li><?php echo __("peer-review-l3_help_1") ?></li>
                <li><?php echo __("peer-review-l3_help_2") ?></li>
                <li><?php echo __("peer-review_checker_help_2") ?></li>
                <li><?php echo __("peer-review-l3_help_4") ?></li>
                <li><?php echo __("peer-review_checker_help_6") ?></li>
                <li><?php echo __("peer-review-l3_help_6") ?></li>
                <li><?php echo __("peer-review-l3_help_7") ?>
                    <ol>
                        <li><?php echo __("peer-review-l3_help_7a", ["icon" => "<span class='mdi mdi-lead-pencil'></span>"]) ?></li>
                        <li><?php echo __("peer-review-l3_help_7b", ["icon" => "<span class='mdi mdi-lead-pencil'></span>"]) ?></li>
                        <li><?php echo __("peer-review-l3_help_7c") ?></li>
                    </ol>
                </li>
                <li><?php echo __("peer-review_help_5") ?>
                    <ol>
                        <li><?php echo __("self-check_help_5a") ?></li>
                        <li><?php echo __("keyword-check_help_4a") ?></li>
                        <li><?php echo __("self-check_help_5b") ?></li>
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
                <li><?php echo __("move_to_next_step_alt", ["step" => __($data["next_step"])]) ?></li>
            </ul>
        </div>
    </div>
</div>

<script>
    isChecker = true;
    isLevel3 = true;
    $(document).ready(function () {
        $("#next_step").click(function (e) {
            e.preventDefault();
            window.location.href = '/events/demo-sun-review/peer_edit_l3';
            return false;
        });

        $(".ttools_panel .word_def").each(function () {
            let html = convertRcLinks($(this).html());
            $(this).html(html);
        });

        $(".sun_mode input").change(function () {
            const active = $(this).prop('checked');

            if (active) {
                $(".flex_middle").removeClass("backsun_content");
                $(".flex_middle").addClass("sun_content");
            } else {
                $(".flex_middle").removeClass("sun_content");
                $(".flex_middle").addClass("backsun_content");
            }

            $(".verse_text").removeAttr("style");
            $(".verse_block").removeAttr("style");
        });
    });
</script>
