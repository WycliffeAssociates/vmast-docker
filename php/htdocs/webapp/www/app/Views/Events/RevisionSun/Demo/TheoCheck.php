<?php

use Helpers\Constants\EventCheckSteps;

require(app_path() . "Views/Components/CommentEditor.php");
require(app_path() . "Views/Components/HelpTools.php");
?>

<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="action_type_container">
            <div class="demo_title"><?php echo __("demo") . " (".__("vsail_revision").")" ?></div>
        </div>
        <div class="main_content_title">
            <div><?php echo __("step_num", ["step_number" => 3]) . ": " . __(EventCheckSteps::PEER_REVIEW . "_sun") ?></div>
        </div>
    </div>

    <div class="">
        <div class="main_content">
            <div class="main_content_text">
                <h4>SUN - <?php echo __("sun") ?> - <?php echo __("new_test") ?> - <span class="book_name">2 Timothy 2:1-26</span>
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
                            <div class="flex_left flex_column">
                                <div class="flex_sub_container">
                                    <div class="flex_one chunk_verses font_en" dir="ltr">
                                        <p class="verse_text kwverse_2_0_1" data-verse="1">
                                            <strong class="ltr">
                                                <sup>1</sup>
                                            </strong>
                                            You therefore, my child, be strengthened in the grace that is in Christ Jesus.                                                    </p>
                                    </div>
                                    <div class="flex_one editor_area sun_content font_sgn-US-symbunot" dir="ltr">
                                        <div class="vnote">
                                            <div class="verse_block flex_chunk" data-verse="1">
                                                <textarea name="chunks[0][1]" class="peer_verse_ta narrow textarea" style="min-width: 400px; min-height: 100px; height: 98px;">         </textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex_sub_container">
                                    <div class="flex_one chunk_verses font_en" dir="ltr">
                                        <p class="verse_text kwverse_2_0_2" data-verse="2">
                                            <strong class="ltr">
                                                <sup>2</sup>
                                            </strong>
                                            The things you heard from me among many witnesses, entrust them to faithful people who will be able to teach others also.                                                    </p>
                                    </div>
                                    <div class="flex_one editor_area sun_content font_sgn-US-symbunot" dir="ltr">
                                        <div class="vnote">
                                            <div class="verse_block flex_chunk" data-verse="2">
                                                <textarea name="chunks[0][2]" class="peer_verse_ta narrow textarea" style="min-width: 400px; min-height: 100px; height: 98px;">                      </textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex_right">
                                <?php
                                $comments = [$data["comments"][0][0], $data["comments"][0][1]];
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
                            <div class="flex_left flex_column">
                                <div class="flex_sub_container">
                                    <div class="flex_one chunk_verses font_en" dir="ltr">
                                        <p class="verse_text kwverse_2_1_3" data-verse="3">
                                            <strong class="ltr">
                                                <sup>3</sup>
                                            </strong>
                                            Suffer hardship with me as a good soldier of Christ Jesus.                                                    </p>
                                    </div>
                                    <div class="flex_one editor_area sun_content font_sgn-US-symbunot" dir="ltr">
                                        <div class="vnote">
                                            <div class="verse_block flex_chunk" data-verse="3">
                                                <textarea name="chunks[1][3]" class="peer_verse_ta narrow textarea" style="min-width: 400px; min-height: 100px; height: 98px;">           </textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex_sub_container">
                                    <div class="flex_one chunk_verses font_en" dir="ltr">
                                        <p class="verse_text kwverse_2_1_4" data-verse="4">
                                            <strong class="ltr">
                                                <sup>4</sup>
                                            </strong>
                                            No soldier serves while entangled in the affairs of this life, so that he may please his superior officer.                                                    </p>
                                    </div>
                                    <div class="flex_one editor_area sun_content font_sgn-US-symbunot" dir="ltr">
                                        <div class="vnote">
                                            <div class="verse_block flex_chunk" data-verse="4">
                                                <textarea name="chunks[1][4]" class="peer_verse_ta narrow textarea" style="min-width: 400px; min-height: 100px; height: 98px;">              </textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex_sub_container">
                                    <div class="flex_one chunk_verses font_en" dir="ltr">
                                        <p class="verse_text kwverse_2_1_5" data-verse="5">
                                            <strong class="ltr">
                                                <sup>5</sup>
                                            </strong>
                                            Also, if someone competes as an athlete, he is not crowned unless he competes by the rules.                                                    </p>
                                    </div>
                                    <div class="flex_one editor_area sun_content font_sgn-US-symbunot" dir="ltr">
                                        <div class="vnote">
                                            <div class="verse_block flex_chunk" data-verse="5">
                                                <textarea name="chunks[1][5]" class="peer_verse_ta narrow textarea" style="min-width: 400px; min-height: 100px; height: 98px;">             </textarea>
                                            </div>
                                        </div>
                                    </div>
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
                            <div class="flex_left flex_column">
                                <div class="flex_sub_container">
                                    <div class="flex_one chunk_verses font_en" dir="ltr">
                                        <p class="verse_text kwverse_2_2_6" data-verse="6">
                                            <strong class="ltr">
                                                <sup>6</sup>
                                            </strong>
                                            It is necessary that the hard-working farmer receive his share of the crops first.                                                    </p>
                                    </div>
                                    <div class="flex_one editor_area sun_content font_sgn-US-symbunot" dir="ltr">
                                        <div class="vnote">
                                            <div class="verse_block flex_chunk" data-verse="6">
                                                <textarea name="chunks[2][6]" class="peer_verse_ta narrow textarea" style="min-width: 400px; min-height: 100px; height: 98px;">           </textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex_sub_container">
                                    <div class="flex_one chunk_verses font_en" dir="ltr">
                                        <p class="verse_text kwverse_2_2_7" data-verse="7">
                                            <strong class="ltr">
                                                <sup>7</sup>
                                            </strong>
                                            Think about what I am saying, for the Lord will give you understanding in everything.                                                    </p>
                                    </div>
                                    <div class="flex_one editor_area sun_content font_sgn-US-symbunot" dir="ltr">
                                        <div class="vnote">
                                            <div class="verse_block flex_chunk" data-verse="7">
                                                <textarea name="chunks[2][7]" class="peer_verse_ta narrow textarea" style="min-width: 400px; min-height: 100px; height: 98px;">          </textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex_right">
                                <?php $enableFootNotes = false;
                                require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row chunk_block">
                        <div class="flex_container">
                            <div class="flex_left flex_column">
                                <div class="flex_sub_container">
                                    <div class="flex_one chunk_verses font_en" dir="ltr">
                                        <p class="verse_text kwverse_2_3_8" data-verse="8">
                                            <strong class="ltr">
                                                <sup>8</sup>
                                            </strong>
                                            Remember Jesus Christ, a descendant of David, who was raised from the dead. This is according to my gospel message,                                                    </p>
                                    </div>
                                    <div class="flex_one editor_area sun_content font_sgn-US-symbunot" dir="ltr">
                                        <div class="vnote">
                                            <div class="verse_block flex_chunk" data-verse="8">
                                                <textarea name="chunks[3][8]" class="peer_verse_ta narrow textarea" style="min-width: 400px; min-height: 100px; height: 98px;">   ,           </textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex_sub_container">
                                    <div class="flex_one chunk_verses font_en" dir="ltr">
                                        <p class="verse_text kwverse_2_3_9" data-verse="9">
                                            <strong class="ltr">
                                                <sup>9</sup>
                                            </strong>
                                            for which I am suffering to the point of being bound with chains as a criminal. But the word of God is not bound.                                                    </p>
                                    </div>
                                    <div class="flex_one editor_area sun_content font_sgn-US-symbunot" dir="ltr">
                                        <div class="vnote">
                                            <div class="verse_block flex_chunk" data-verse="9">
                                                <textarea name="chunks[3][9]" class="peer_verse_ta narrow textarea" style="min-width: 400px; min-height: 100px; height: 98px;">                  </textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex_right">
                                <?php $enableFootNotes = false;
                                require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row chunk_block">
                        <div class="flex_container">
                            <div class="flex_left flex_column">
                                <div class="flex_sub_container">
                                    <div class="flex_one chunk_verses font_en" dir="ltr">
                                        <p class="verse_text kwverse_2_4_10" data-verse="10">
                                            <strong class="ltr">
                                                <sup>10</sup>
                                            </strong>
                                            Therefore I endure all things for those who are chosen, so that they also may obtain the salvation that is in Christ Jesus, with eternal glory.                                                    </p>
                                    </div>
                                    <div class="flex_one editor_area sun_content font_sgn-US-symbunot" dir="ltr">
                                        <div class="vnote">
                                            <div class="verse_block flex_chunk" data-verse="10">
                                                <textarea name="chunks[4][10]" class="peer_verse_ta narrow textarea" style="min-width: 400px; min-height: 100px; height: 103px;">                      </textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex_right">
                                <?php $enableFootNotes = false;
                                require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row chunk_block">
                        <div class="flex_container">
                            <div class="flex_left flex_column">
                                <div class="flex_sub_container">
                                    <div class="flex_one chunk_verses font_en" dir="ltr">
                                        <p class="verse_text kwverse_2_5_11" data-verse="11">
                                            <strong class="ltr">
                                                <sup>11</sup>
                                            </strong>
                                            This is a trustworthy saying:  "If we have died with him, we will also live with him.                                                     </p>
                                    </div>
                                    <div class="flex_one editor_area sun_content font_sgn-US-symbunot" dir="ltr">
                                        <div class="vnote">
                                            <div class="verse_block flex_chunk" data-verse="11">
                                                <textarea name="chunks[5][11]" class="peer_verse_ta narrow textarea" style="min-width: 400px; min-height: 100px; height: 98px;">   "           </textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex_sub_container">
                                    <div class="flex_one chunk_verses font_en" dir="ltr">
                                        <p class="verse_text kwverse_2_5_12" data-verse="12">
                                            <strong class="ltr">
                                                <sup>12</sup>
                                            </strong>
                                            If we endure, we will also reign with him.  If we deny him, he also will deny us.                                                     </p>
                                    </div>
                                    <div class="flex_one editor_area sun_content font_sgn-US-symbunot" dir="ltr">
                                        <div class="vnote">
                                            <div class="verse_block flex_chunk" data-verse="12">
                                                <textarea name="chunks[5][12]" class="peer_verse_ta narrow textarea" style="min-width: 400px; min-height: 100px; height: 98px;">   ,          ,    </textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex_sub_container">
                                    <div class="flex_one chunk_verses font_en" dir="ltr">
                                        <p class="verse_text kwverse_2_5_13" data-verse="13">
                                            <strong class="ltr">
                                                <sup>13</sup>
                                            </strong>
                                            if we are unfaithful, he remains faithful,  for he cannot deny himself."                                                    </p>
                                    </div>
                                    <div class="flex_one editor_area sun_content font_sgn-US-symbunot" dir="ltr">
                                        <div class="vnote">
                                            <div class="verse_block flex_chunk" data-verse="13">
                                                <textarea name="chunks[5][13]" class="peer_verse_ta narrow textarea" style="min-width: 400px; min-height: 100px; height: 98px;">    ,          "</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex_right">
                                <?php $enableFootNotes = false;
                                require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row chunk_block">
                        <div class="flex_container">
                            <div class="flex_left flex_column">
                                <div class="flex_sub_container">
                                    <div class="flex_one chunk_verses font_en" dir="ltr">
                                        <p class="verse_text kwverse_2_6_14" data-verse="14">
                                            <strong class="ltr">
                                                <sup>14</sup>
                                            </strong>
                                            Keep reminding them of these things. Command them before God not to quarrel about words; it is of no value and only ruins those who listen.  <span data-toggle="tooltip" data-placement="auto auto" title="Some important and ancient Greek copies read,  Warn them before the Lord  . " class="booknote mdi mdi-bookmark"></span>                                                     </p>
                                    </div>
                                    <div class="flex_one editor_area sun_content font_sgn-US-symbunot" dir="ltr">
                                        <div class="vnote">
                                            <div class="verse_block flex_chunk" data-verse="14">
                                                <textarea name="chunks[6][14]" class="peer_verse_ta narrow textarea" style="min-width: 400px; min-height: 100px; height: 103px;">                         </textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex_right">
                                <?php $enableFootNotes = false;
                                require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row chunk_block">
                        <div class="flex_container">
                            <div class="flex_left flex_column">
                                <div class="flex_sub_container">
                                    <div class="flex_one chunk_verses font_en" dir="ltr">
                                        <p class="verse_text kwverse_2_7_15" data-verse="15">
                                            <strong class="ltr">
                                                <sup>15</sup>
                                            </strong>
                                            Do your best to present yourself to God as one approved, a laborer who has no reason to be ashamed, who accurately teaches the word of truth.                                                    </p>
                                    </div>
                                    <div class="flex_one editor_area sun_content font_sgn-US-symbunot" dir="ltr">
                                        <div class="vnote">
                                            <div class="verse_block flex_chunk" data-verse="15">
                                                <textarea name="chunks[7][15]" class="peer_verse_ta narrow textarea" style="min-width: 400px; min-height: 100px; height: 98px;">                     </textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex_right">
                                <?php $enableFootNotes = false;
                                require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row chunk_block">
                        <div class="flex_container">
                            <div class="flex_left flex_column">
                                <div class="flex_sub_container">
                                    <div class="flex_one chunk_verses font_en" dir="ltr">
                                        <p class="verse_text kwverse_2_8_16" data-verse="16">
                                            <strong class="ltr">
                                                <sup>16</sup>
                                            </strong>
                                            Avoid profane and empty talk, which leads to more and more godlessness.                                                    </p>
                                    </div>
                                    <div class="flex_one editor_area sun_content font_sgn-US-symbunot" dir="ltr">
                                        <div class="vnote">
                                            <div class="verse_block flex_chunk" data-verse="16">
                                                <textarea name="chunks[8][16]" class="peer_verse_ta narrow textarea" style="min-width: 400px; min-height: 100px; height: 98px;">    ,          </textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex_sub_container">
                                    <div class="flex_one chunk_verses font_en" dir="ltr">
                                        <p class="verse_text kwverse_2_8_17" data-verse="17">
                                            <strong class="ltr">
                                                <sup>17</sup>
                                            </strong>
                                            Their talk will spread like cancer. Among them are Hymenaeus and Philetus,                                                    </p>
                                    </div>
                                    <div class="flex_one editor_area sun_content font_sgn-US-symbunot" dir="ltr">
                                        <div class="vnote">
                                            <div class="verse_block flex_chunk" data-verse="17">
                                                <textarea name="chunks[8][17]" class="peer_verse_ta narrow textarea" style="min-width: 400px; min-height: 100px; height: 98px;">           </textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex_sub_container">
                                    <div class="flex_one chunk_verses font_en" dir="ltr">
                                        <p class="verse_text kwverse_2_8_18" data-verse="18">
                                            <strong class="ltr">
                                                <sup>18</sup>
                                            </strong>
                                            who have gone astray from the truth. They say that the resurrection has already happened, and they destroy the faith of some.                                                    </p>
                                    </div>
                                    <div class="flex_one editor_area sun_content font_sgn-US-symbunot" dir="ltr">
                                        <div class="vnote">
                                            <div class="verse_block flex_chunk" data-verse="18">
                                                <textarea name="chunks[8][18]" class="peer_verse_ta narrow textarea" style="min-width: 400px; min-height: 100px; height: 98px;">               </textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex_right">
                                <?php $enableFootNotes = false;
                                require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row chunk_block">
                        <div class="flex_container">
                            <div class="flex_left flex_column">
                                <div class="flex_sub_container">
                                    <div class="flex_one chunk_verses font_en" dir="ltr">
                                        <p class="verse_text kwverse_2_9_19" data-verse="19">
                                            <strong class="ltr">
                                                <sup>19</sup>
                                            </strong>
                                            However, the firm foundation of God stands. It has this inscription: "The Lord knows those who are his" and "Everyone who names the name of the Lord must depart from unrighteousness."                                                    </p>
                                    </div>
                                    <div class="flex_one editor_area sun_content font_sgn-US-symbunot" dir="ltr">
                                        <div class="vnote">
                                            <div class="verse_block flex_chunk" data-verse="19">
                                                <textarea name="chunks[9][19]" class="peer_verse_ta narrow textarea" style="min-width: 400px; min-height: 100px; height: 103px;">       , "      " "         "</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex_right">
                                <?php $enableFootNotes = false;
                                require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row chunk_block">
                        <div class="flex_container">
                            <div class="flex_left flex_column">
                                <div class="flex_sub_container">
                                    <div class="flex_one chunk_verses font_en" dir="ltr">
                                        <p class="verse_text kwverse_2_10_20" data-verse="20">
                                            <strong class="ltr">
                                                <sup>20</sup>
                                            </strong>
                                            In a wealthy home there are not only containers of gold and silver. There are also containers of wood and clay. Some of these are for honorable use, and some for dishonorable.                                                    </p>
                                    </div>
                                    <div class="flex_one editor_area sun_content font_sgn-US-symbunot" dir="ltr">
                                        <div class="vnote">
                                            <div class="verse_block flex_chunk" data-verse="20">
                                                <textarea name="chunks[10][20]" class="peer_verse_ta narrow textarea" style="min-width: 400px; min-height: 100px; height: 103px;">                          </textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex_right">
                                <?php $enableFootNotes = false;
                                require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row chunk_block">
                        <div class="flex_container">
                            <div class="flex_left flex_column">
                                <div class="flex_sub_container">
                                    <div class="flex_one chunk_verses font_en" dir="ltr">
                                        <p class="verse_text kwverse_2_11_21" data-verse="21">
                                            <strong class="ltr">
                                                <sup>21</sup>
                                            </strong>
                                            If someone cleans himself from dishonorable use, he is an honorable container. He is set apart, useful to the Master, and prepared for every good work.                                                    </p>
                                    </div>
                                    <div class="flex_one editor_area sun_content font_sgn-US-symbunot" dir="ltr">
                                        <div class="vnote">
                                            <div class="verse_block flex_chunk" data-verse="21">
                                                <textarea name="chunks[11][21]" class="peer_verse_ta narrow textarea" style="min-width: 400px; min-height: 100px; height: 98px;">                       </textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex_right">
                                <?php $enableFootNotes = false;
                                require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row chunk_block">
                        <div class="flex_container">
                            <div class="flex_left flex_column">
                                <div class="flex_sub_container">
                                    <div class="flex_one chunk_verses font_en" dir="ltr">
                                        <p class="verse_text kwverse_2_12_22" data-verse="22">
                                            <strong class="ltr">
                                                <sup>22</sup>
                                            </strong>
                                            Flee youthful lusts. Pursue righteousness, faith, love, and peace with those who call on the Lord out of a clean heart.                                                    </p>
                                    </div>
                                    <div class="flex_one editor_area sun_content font_sgn-US-symbunot" dir="ltr">
                                        <div class="vnote">
                                            <div class="verse_block flex_chunk" data-verse="22">
                                                <textarea name="chunks[12][22]" class="peer_verse_ta narrow textarea" style="min-width: 400px; min-height: 100px; height: 103px;">        ,  ,                </textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex_sub_container">
                                    <div class="flex_one chunk_verses font_en" dir="ltr">
                                        <p class="verse_text kwverse_2_12_23" data-verse="23">
                                            <strong class="ltr">
                                                <sup>23</sup>
                                            </strong>
                                            But refuse foolish and ignorant questions. You know that they give birth to quarrels.                                                    </p>
                                    </div>
                                    <div class="flex_one editor_area sun_content font_sgn-US-symbunot" dir="ltr">
                                        <div class="vnote">
                                            <div class="verse_block flex_chunk" data-verse="23">
                                                <textarea name="chunks[12][23]" class="peer_verse_ta narrow textarea" style="min-width: 400px; min-height: 100px; height: 98px;">            </textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex_right">
                                <?php $enableFootNotes = false;
                                require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row chunk_block">
                        <div class="flex_container">
                            <div class="flex_left flex_column">
                                <div class="flex_sub_container">
                                    <div class="flex_one chunk_verses font_en" dir="ltr">
                                        <p class="verse_text kwverse_2_13_24" data-verse="24">
                                            <strong class="ltr">
                                                <sup>24</sup>
                                            </strong>
                                            The Lord's servant must not quarrel. Instead he must be gentle toward all, able to teach, and patient,                                                    </p>
                                    </div>
                                    <div class="flex_one editor_area sun_content font_sgn-US-symbunot" dir="ltr">
                                        <div class="vnote">
                                            <div class="verse_block flex_chunk" data-verse="24">
                                                <textarea name="chunks[13][24]" class="peer_verse_ta narrow textarea" style="min-width: 400px; min-height: 100px; height: 98px;">                 </textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex_sub_container">
                                    <div class="flex_one chunk_verses font_en" dir="ltr">
                                        <p class="verse_text kwverse_2_13_25" data-verse="25">
                                            <strong class="ltr">
                                                <sup>25</sup>
                                            </strong>
                                            correcting his opponents with gentleness. Perhaps God may give them repentance for the knowledge of the truth.                                                    </p>
                                    </div>
                                    <div class="flex_one editor_area sun_content font_sgn-US-symbunot" dir="ltr">
                                        <div class="vnote">
                                            <div class="verse_block flex_chunk" data-verse="25">
                                                <textarea name="chunks[13][25]" class="peer_verse_ta narrow textarea" style="min-width: 400px; min-height: 100px; height: 98px;">                  </textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex_sub_container">
                                    <div class="flex_one chunk_verses font_en" dir="ltr">
                                        <p class="verse_text kwverse_2_13_26" data-verse="26">
                                            <strong class="ltr">
                                                <sup>26</sup>
                                            </strong>
                                            They may become sober again and leave the devil's trap, after they have been captured by him for his will.                                                    </p>
                                    </div>
                                    <div class="flex_one editor_area sun_content font_sgn-US-symbunot" dir="ltr">
                                        <div class="vnote">
                                            <div class="verse_block flex_chunk" data-verse="26">
                                                <textarea name="chunks[13][26]" class="peer_verse_ta narrow textarea" style="min-width: 400px; min-height: 100px; height: 98px;">             </textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex_right">
                                <?php $enableFootNotes = false;
                                require(app_path() . "Views/Components/Comments.php"); ?>
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
                    <img src="<?php echo template_url("img/saving.gif") ?>" class="unsaved_alert">
                </form>
                <div class="step_right"><?php echo __("step_num", ["step_number" => 3]) ?></div>
            </div>
        </div>
    </div>
</div>

<div class="content_help closed">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps is_checker_page_help">
            <div class="help_name_steps">
                <span><?php echo __("step_num", ["step_number" => 3]) ?>:</span> <?php echo __(EventCheckSteps::PEER_REVIEW . "_sun") ?>
            </div>
            <div class="help_descr_steps">
                <ul>
                    <li><b><?php echo __("purpose") ?></b> <?php echo __("theo-check_sun_l2_purpose") ?></li>
                    <li><?php echo __("theo-check_sun_l2_help_1") ?></li>
                    <li><?php echo __("theo-check_sun_l2_help_2") ?></li>
                    <li><?php echo __("theo-check_sun_l2_help_3") ?></li>
                    <li><?php echo __("peer-review_checker_help_2") ?></li>
                    <li><?php echo __("theo-check_sun_l2_help_5") ?></li>
                    <li><?php echo __("theo-check_sun_l2_help_6") ?></li>
                    <li><?php echo __("theo-check_sun_l2_help_7") ?></li>
                    <li><?php echo __("move_to_next_step_alt", ["step" => __($data["next_step"])]) ?></li>
                </ul>
                <div class="show_tutorial_popup"> >>> <?php echo __("show_more") ?></div>
            </div>
        </div>

        <div class="event_info is_checker_page_help">
            <div class="participant_info">
                <div class="additional_info">
                    <a href="/events/demo-sun-revision/information"><?php echo __("event_info") ?></a>
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
            <img src="<?php echo template_url("img/steps/icons/theo-check-gray.png") ?>" height="100px" width="100px">
            <img src="<?php echo template_url("img/steps/big/keyword-check.png") ?>" height="280px" width="280px">
        </div>

        <div class="tutorial_content">
            <h3><?php echo __(EventCheckSteps::PEER_REVIEW . "_sun") ?></h3>
            <ul>
                <li><b><?php echo __("purpose") ?></b> <?php echo __("theo-check_sun_l2_purpose") ?></li>
                <li><?php echo __("theo-check_sun_l2_help_1") ?></li>
                <li><?php echo __("theo-check_sun_l2_help_2") ?></li>
                <li><?php echo __("theo-check_sun_l2_help_3") ?></li>
                <li><?php echo __("peer-review_checker_help_2") ?></li>
                <li><?php echo __("theo-check_sun_l2_help_5") ?></li>
                <li><?php echo __("theo-check_sun_l2_help_6") ?></li>
                <li><?php echo __("theo-check_sun_l2_help_7") ?></li>
                <li><?php echo __("move_to_next_step_alt", ["step" => __($data["next_step"])]) ?></li>
            </ul>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#next_step").click(function (e) {
            e.preventDefault();
            if (!hasChangesOnPage) window.location.href = '/events/demo-sun-revision/pray';
            return false;
        });

        $(".sun_mode input").change(function () {
            const active = $(this).prop('checked');
            if (active) {
                $(".editor_area").removeClass("font_backsun");
                $(".editor_area").addClass("sun_content");
                $(".editor_area .textarea").removeClass("wide");
                $(".editor_area .textarea").addClass("narrow");
            } else {
                $(".editor_area").removeClass("sun_content");
                $(".editor_area").addClass("font_backsun");
                $(".editor_area .textarea").addClass("wide");
                $(".editor_area .textarea").removeClass("narrow");
            }
        });

        $(".ttools_panel .word_def").each(function () {
            let html = convertRcLinks($(this).html());
            $(this).html(html);
        });
    });
</script>