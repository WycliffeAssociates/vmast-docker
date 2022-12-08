<?php
use Helpers\Constants\EventCheckSteps;

require(app_path() . "Views/Components/CommentEditor.php");
require(app_path() . "Views/Components/FootnotesEditor.php");
?>

<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="main_content_title">
            <div class="demo_title"><?php echo __("demo") . " (".__("revision_events").")" ?></div>
            <div><?php echo __("step_num", ["step_number" => 2]) . ": " . __(EventCheckSteps::SELF_CHECK)?></div>
        </div>
    </div>

    <div class="">
        <div class="main_content">
            <div class="main_content_text">
                <h4>Papuan Malay - <?php echo __("ulb") ?> - <?php echo __("new_test") ?> - <span class="book_name">2 Timothy 2:1-26</span></h4>

                <div class="no_padding">
                    <div class="source_mode">
                        <label>
                            <?php echo __("show_source") ?>
                            <input type="checkbox" autocomplete="off"
                                   data-toggle="toggle"
                                   data-on="ON"
                                   data-off="OFF" />
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
                                            <span class="verse_text_source" style="display: none;">You therefore, my child, be strengthened in the grace that is in Christ Jesus.</span>
                                            <span class="verse_text_original" style="display: inline;">jadi begitu, anakku kuat sudah dengan anugerah di dalam Kristus Yesus.</span>
                                        </p>
                                    </div>
                                    <div class="flex_one editor_area font_aaa" dir="ltr">
                                        <div class="vnote">
                                            <div class="verse_block flex_chunk" data-verse="1">
                                                <textarea name="chunks[0][1]" class="peer_verse_ta textarea" style="min-width: 400px; min-height: 100px; height: 98px;">jadi begitu, anakku kuat sudah dengan anugerah di dalam Kristus Yesus.</textarea>

                                                <span class="editFootNote mdi mdi-bookmark" style="margin-top: -5px" title="Add a footnote"></span>
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
                                            <span class="verse_text_source" style="display: none;">The things you heard from me among many witnesses, entrust them to faithful people who will be able to teach others also.</span>
                                            <span class="verse_text_original" style="display: inline;">dan banyak hal yang ko dengar dari saya dengan saksi yang banyak itu,beri percaya itu sama orang-orang yang setia, supaya dong dapat mengajar orang lain juga.</span>
                                        </p>
                                    </div>
                                    <div class="flex_one editor_area font_aaa" dir="ltr">
                                        <div class="vnote">
                                            <div class="verse_block flex_chunk" data-verse="2">
                                                <textarea name="chunks[0][2]" class="peer_verse_ta textarea" style="min-width: 400px; min-height: 100px; height: 109px;">dan banyak hal yang ko dengar dari saya dengan saksi yang banyak itu,beri percaya itu sama orang-orang yang setia, supaya dong dapat mengajar orang lain juga.</textarea>

                                                <span class="editFootNote mdi mdi-bookmark" style="margin-top: -5px" title="Add a footnote"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex_sub_container">
                                    <div class="flex_one chunk_verses font_en" dir="ltr">
                                        <p class="verse_text kwverse_2_0_3" data-verse="3">
                                            <strong class="ltr">
                                                <sup>3</sup>
                                            </strong>
                                            <span class="verse_text_source" style="display: none;">Suffer hardship with me as a good soldier of Christ Jesus.</span>
                                            <span class="verse_text_original" style="display: inline;">Mari, gabung sama sa dalam penderitaan jadi prajurit Kristus Yesus yang baik.</span>
                                        </p>
                                    </div>
                                    <div class="flex_one editor_area font_aaa" dir="ltr">
                                        <div class="vnote">
                                            <div class="verse_block flex_chunk" data-verse="3">
                                                <textarea name="chunks[0][3]" class="peer_verse_ta textarea" style="min-width: 400px; min-height: 100px; height: 98px;">Mari, gabung sama sa dalam penderitaan jadi prajurit Kristus Yesus yang baik.</textarea>

                                                <span class="editFootNote mdi mdi-bookmark" style="margin-top: -5px" title="Add a footnote"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex_sub_container">
                                    <div class="flex_one chunk_verses font_en" dir="ltr">
                                        <p class="verse_text kwverse_2_0_4" data-verse="4">
                                            <strong class="ltr">
                                                <sup>4</sup>
                                            </strong>
                                            <span class="verse_text_source" style="display: none;">No soldier serves while entangled in the affairs of this life, so that he may please his superior officer.</span>
                                            <span class="verse_text_original" style="display: inline;">Trada satu orang tentara yang kerja sambil sibuk dengan de pu urusan hidup supaya de bisa buat de pu komandan senang.</span>
                                        </p>
                                    </div>
                                    <div class="flex_one editor_area font_aaa" dir="ltr">
                                        <div class="vnote">
                                            <div class="verse_block flex_chunk" data-verse="4">
                                                <textarea name="chunks[0][4]" class="peer_verse_ta textarea" style="min-width: 400px; min-height: 100px; height: 109px;">Trada satu orang tentara yang kerja sambil sibuk dengan de pu urusan hidup supaya de bisa buat de pu komandan senang.</textarea>

                                                <span class="editFootNote mdi mdi-bookmark" style="margin-top: -5px" title="Add a footnote"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex_sub_container">
                                    <div class="flex_one chunk_verses font_en" dir="ltr">
                                        <p class="verse_text kwverse_2_0_5" data-verse="5">
                                            <strong class="ltr">
                                                <sup>5</sup>
                                            </strong>
                                            <span class="verse_text_source" style="display: none;">Also, if someone competes as an athlete, he is not crowned unless he competes by the rules.</span>
                                            <span class="verse_text_original" style="display: inline;">begitu juga dengan atlit , tidak akan terima mahkota kalo tidak ikut aturan dalam lomba</span>
                                        </p>
                                    </div>
                                    <div class="flex_one editor_area font_aaa" dir="ltr">
                                        <div class="vnote">
                                            <div class="verse_block flex_chunk" data-verse="5">
                                                <textarea name="chunks[0][5]" class="peer_verse_ta textarea" style="min-width: 400px; min-height: 100px; height: 98px;">begitu juga dengan atlit , tidak akan terima mahkota kalo tidak ikut aturan dalam lomba</textarea>

                                                <span class="editFootNote mdi mdi-bookmark" style="margin-top: -5px" title="Add a footnote"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row chunk_block">
                        <div class="flex_container">
                            <div class="flex_left flex_column">
                                <div class="flex_sub_container">
                                    <div class="flex_one chunk_verses font_en" dir="ltr">
                                        <p class="verse_text kwverse_2_1_6" data-verse="6">
                                            <strong class="ltr">
                                                <sup>6</sup>
                                            </strong>
                                            <span class="verse_text_source" style="display: none;">It is necessary that the hard-working farmer receive his share of the crops first.</span>
                                            <span class="verse_text_original" style="display: inline;">lebih baik petani yang kerja keras terima hasil yang pertama,</span>
                                        </p>
                                    </div>
                                    <div class="flex_one editor_area font_aaa" dir="ltr">
                                        <div class="vnote">
                                            <div class="verse_block flex_chunk" data-verse="6">
                                                <textarea name="chunks[1][6]" class="peer_verse_ta textarea" style="min-width: 400px; min-height: 100px; height: 98px;">lebih baik petani yang kerja keras terima hasil yang pertama,</textarea>

                                                <span class="editFootNote mdi mdi-bookmark" style="margin-top: -5px" title="Add a footnote"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex_sub_container">
                                    <div class="flex_one chunk_verses font_en" dir="ltr">
                                        <p class="verse_text kwverse_2_1_7" data-verse="7">
                                            <strong class="ltr">
                                                <sup>7</sup>
                                            </strong>
                                            <span class="verse_text_source" style="display: none;">Think about what I am saying, for the Lord will give you understanding in everything.</span>
                                            <span class="verse_text_original" style="display: inline;">ingat apa yang sa bilang, karena Tuhan akan kasi ko pengertian untuk mengerti semua ini,</span>
                                        </p>
                                    </div>
                                    <div class="flex_one editor_area font_aaa" dir="ltr">
                                        <div class="vnote">
                                            <div class="verse_block flex_chunk" data-verse="7">
                                                <textarea name="chunks[1][7]" class="peer_verse_ta textarea" style="min-width: 400px; min-height: 100px; height: 98px;">ingat apa yang sa bilang, karena Tuhan akan kasi ko pengertian untuk mengerti semua ini,</textarea>

                                                <span class="editFootNote mdi mdi-bookmark" style="margin-top: -5px" title="Add a footnote"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex_sub_container">
                                    <div class="flex_one chunk_verses font_en" dir="ltr">
                                        <p class="verse_text kwverse_2_1_8" data-verse="8">
                                            <strong class="ltr">
                                                <sup>8</sup>
                                            </strong>
                                            <span class="verse_text_source" style="display: none;">Remember Jesus Christ, a descendant of David, who was raised from the dead. This is according to my gospel message,</span>
                                            <span class="verse_text_original" style="display: inline;">ingat Yesus Kristus, keturunan Daud, sudah bangkit dari kematian. ini su sesuai dengan pesan injil yang sa percaya.</span>
                                        </p>
                                    </div>
                                    <div class="flex_one editor_area font_aaa" dir="ltr">
                                        <div class="vnote">
                                            <div class="verse_block flex_chunk" data-verse="8">
                                                <textarea name="chunks[1][8]" class="peer_verse_ta textarea" style="min-width: 400px; min-height: 100px; height: 98px;">ingat Yesus Kristus, keturunan Daud, sudah bangkit dari kematian. ini su sesuai dengan pesan injil yang sa percaya.</textarea>

                                                <span class="editFootNote mdi mdi-bookmark" style="margin-top: -5px" title="Add a footnote"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex_sub_container">
                                    <div class="flex_one chunk_verses font_en" dir="ltr">
                                        <p class="verse_text kwverse_2_1_9" data-verse="9">
                                            <strong class="ltr">
                                                <sup>9</sup>
                                            </strong>
                                            <span class="verse_text_source" style="display: none;">for which I am suffering to the point of being bound with chains as a criminal. But the word of God is not bound.</span>
                                            <span class="verse_text_original" style="display: inline;">sampe pada titik penderitaan karna diikat rantai seperti kriminal. tapi firman Allah tidak diikat dengan rantai.</span>
                                        </p>
                                    </div>
                                    <div class="flex_one editor_area font_aaa" dir="ltr">
                                        <div class="vnote">
                                            <div class="verse_block flex_chunk" data-verse="9">
                                                <textarea name="chunks[1][9]" class="peer_verse_ta textarea" style="min-width: 400px; min-height: 100px; height: 98px;">sampe pada titik penderitaan karna diikat rantai seperti kriminal. tapi firman Allah tidak diikat dengan rantai.</textarea>

                                                <span class="editFootNote mdi mdi-bookmark" style="margin-top: -5px" title="Add a footnote"></span>
                                            </div>
                                        </div>
                                    </div>
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
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row chunk_block">
                        <div class="flex_container">
                            <div class="flex_left flex_column">
                                <div class="flex_sub_container">
                                    <div class="flex_one chunk_verses font_en" dir="ltr">
                                        <p class="verse_text kwverse_2_2_10" data-verse="10">
                                            <strong class="ltr">
                                                <sup>10</sup>
                                            </strong>
                                            <span class="verse_text_source" style="display: none;">Therefore I endure all things for those who are chosen, so that they also may obtain the salvation that is in Christ Jesus, with eternal glory.</span>
                                            <span class="verse_text_original" style="display: inline;">jadi sa bertahan untuk orang-orang yang Tuhan pilih, supaya dong dapat keselamatan yang kekal dalam Kristus Yesus, dengan kemuliaan yang abadi..</span>
                                        </p>
                                    </div>
                                    <div class="flex_one editor_area font_aaa" dir="ltr">
                                        <div class="vnote">
                                            <div class="verse_block flex_chunk" data-verse="10">
                                                <textarea name="chunks[2][10]" class="peer_verse_ta textarea" style="min-width: 400px; min-height: 100px; height: 109px;">jadi sa bertahan untuk orang-orang yang Tuhan pilih, supaya dong dapat keselamatan yang kekal dalam Kristus Yesus, dengan kemuliaan yang abadi..</textarea>

                                                <span class="editFootNote mdi mdi-bookmark" style="margin-top: -5px" title="Add a footnote"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex_sub_container">
                                    <div class="flex_one chunk_verses font_en" dir="ltr">
                                        <p class="verse_text kwverse_2_2_11" data-verse="11">
                                            <strong class="ltr">
                                                <sup>11</sup>
                                            </strong>
                                            <span class="verse_text_source" style="display: none;">This is a trustworthy saying:  "If we have died with him, we will also live with him. </span>
                                            <span class="verse_text_original" style="display: inline;">apa yang sa bilang ini, bisa dipercaya: kalo ketong mau mati untuk Dia, torang juga akan hidup bersama dengan Dia.</span>
                                        </p>
                                    </div>
                                    <div class="flex_one editor_area font_aaa" dir="ltr">
                                        <div class="vnote">
                                            <div class="verse_block flex_chunk" data-verse="11">
                                                <textarea name="chunks[2][11]" class="peer_verse_ta textarea" style="min-width: 400px; min-height: 100px; height: 98px;">apa yang sa bilang ini, bisa dipercaya: kalo ketong mau mati untuk Dia, torang juga akan hidup bersama dengan Dia.</textarea>

                                                <span class="editFootNote mdi mdi-bookmark" style="margin-top: -5px" title="Add a footnote"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex_sub_container">
                                    <div class="flex_one chunk_verses font_en" dir="ltr">
                                        <p class="verse_text kwverse_2_2_12" data-verse="12">
                                            <strong class="ltr">
                                                <sup>12</sup>
                                            </strong>
                                            <span class="verse_text_source" style="display: none;">If we endure, we will also reign with him.  If we deny him, he also will deny us. </span>
                                            <span class="verse_text_original" style="display: inline;">apalagi kalo tong bertahan , tong juga akan ditinggikan bersama Dia. klo tong menyangkal Dia, Dia juga akan menyangkal ketong,</span>
                                        </p>
                                    </div>
                                    <div class="flex_one editor_area font_aaa" dir="ltr">
                                        <div class="vnote">
                                            <div class="verse_block flex_chunk" data-verse="12">
                                                <textarea name="chunks[2][12]" class="peer_verse_ta textarea" style="min-width: 400px; min-height: 100px; height: 109px;">apalagi kalo tong bertahan , tong juga akan ditinggikan bersama Dia. klo tong menyangkal Dia, Dia juga akan menyangkal ketong,</textarea>

                                                <span class="editFootNote mdi mdi-bookmark" style="margin-top: -5px" title="Add a footnote"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex_sub_container">
                                    <div class="flex_one chunk_verses font_en" dir="ltr">
                                        <p class="verse_text kwverse_2_2_13" data-verse="13">
                                            <strong class="ltr">
                                                <sup>13</sup>
                                            </strong>
                                            <span class="verse_text_source" style="display: none;">if we are unfaithful, he remains faithful,  for he cannot deny himself."</span>
                                            <span class="verse_text_original" style="display: inline;">klo tong tra setia, De tetap setia karena de tra bisa menyangkal diri.</span>
                                        </p>
                                    </div>
                                    <div class="flex_one editor_area font_aaa" dir="ltr">
                                        <div class="vnote">
                                            <div class="verse_block flex_chunk" data-verse="13">
                                                <textarea name="chunks[2][13]" class="peer_verse_ta textarea" style="min-width: 400px; min-height: 100px; height: 98px;">klo tong tra setia, De tetap setia karena de tra bisa menyangkal diri.</textarea>

                                                <span class="editFootNote mdi mdi-bookmark" style="margin-top: -5px" title="Add a footnote"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex_right">
                                <div class="flex_right">
                                    <?php
                                    $hasComments = false;
                                    $enableFootNotes = false;
                                    require(app_path() . "Views/Components/Comments.php");
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row chunk_block">
                        <div class="flex_container">
                            <div class="flex_left flex_column">
                                <div class="flex_sub_container">
                                    <div class="flex_one chunk_verses font_en" dir="ltr">
                                        <p class="verse_text kwverse_2_3_14" data-verse="14">
                                            <strong class="ltr">
                                                <sup>14</sup>
                                            </strong>
                                            <span class="verse_text_source" style="display: none;">Keep reminding them of these things. Command them before God not to quarrel about words; it is of no value and only ruins those who listen.  <span data-toggle="tooltip" data-placement="auto auto" title="Some important and ancient Greek copies read,  Warn them before the Lord  . " class="booknote mdi mdi-bookmark"></span> </span>
                                            <span class="verse_text_original" style="display: inline;">selalu kasi ingat dong di hadapan Allah, supaya dong jangan berdebat tentang firman karena itu akan bikin kacau orang yang dengar,</span>
                                        </p>
                                    </div>
                                    <div class="flex_one editor_area font_aaa" dir="ltr">
                                        <div class="vnote">
                                            <div class="verse_block flex_chunk" data-verse="14">
                                                <textarea name="chunks[3][14]" class="peer_verse_ta textarea" style="min-width: 400px; min-height: 100px; height: 109px;">selalu kasi ingat dong di hadapan Allah, supaya dong jangan berdebat tentang firman karena itu akan bikin kacau orang yang dengar,</textarea>

                                                <span class="editFootNote mdi mdi-bookmark" style="margin-top: -5px" title="Add a footnote"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex_sub_container">
                                    <div class="flex_one chunk_verses font_en" dir="ltr">
                                        <p class="verse_text kwverse_2_3_15" data-verse="15">
                                            <strong class="ltr">
                                                <sup>15</sup>
                                            </strong>
                                            <span class="verse_text_source" style="display: none;">Do your best to present yourself to God as one approved, a laborer who has no reason to be ashamed, who accurately teaches the word of truth.</span>
                                            <span class="verse_text_original" style="display: inline;">lakukan yang paling baik itu adalah persembahan yang Tuhan terima, jadi pekerja trada alasan untuk dapat kasi malu, yang ajar kebeneran firman dengan pas.</span>
                                        </p>
                                    </div>
                                    <div class="flex_one editor_area font_aaa" dir="ltr">
                                        <div class="vnote">
                                            <div class="verse_block flex_chunk" data-verse="15">
                                                <textarea name="chunks[3][15]" class="peer_verse_ta textarea" style="min-width: 400px; min-height: 100px; height: 109px;">lakukan yang paling baik itu adalah persembahan yang Tuhan terima, jadi pekerja trada alasan untuk dapat kasi malu, yang ajar kebeneran firman dengan pas.</textarea>

                                                <span class="editFootNote mdi mdi-bookmark" style="margin-top: -5px" title="Add a footnote"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex_sub_container">
                                    <div class="flex_one chunk_verses font_en" dir="ltr">
                                        <p class="verse_text kwverse_2_3_16" data-verse="16">
                                            <strong class="ltr">
                                                <sup>16</sup>
                                            </strong>
                                            <span class="verse_text_source" style="display: none;">Avoid profane and empty talk, which leads to more and more godlessness.</span>
                                            <span class="verse_text_original" style="display: inline;">pindah dari kata-kata kotor, yang nanti jadi tidak baik.</span>
                                        </p>
                                    </div>
                                    <div class="flex_one editor_area font_aaa" dir="ltr">
                                        <div class="vnote">
                                            <div class="verse_block flex_chunk" data-verse="16">
                                                <textarea name="chunks[3][16]" class="peer_verse_ta textarea" style="min-width: 400px; min-height: 100px; height: 98px;">pindah dari kata-kata kotor, yang nanti jadi tidak baik.</textarea>

                                                <span class="editFootNote mdi mdi-bookmark" style="margin-top: -5px" title="Add a footnote"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex_sub_container">
                                    <div class="flex_one chunk_verses font_en" dir="ltr">
                                        <p class="verse_text kwverse_2_3_17" data-verse="17">
                                            <strong class="ltr">
                                                <sup>17</sup>
                                            </strong>
                                            <span class="verse_text_source" style="display: none;">Their talk will spread like cancer. Among them are Hymenaeus and Philetus,</span>
                                            <span class="verse_text_original" style="display: inline;">kata kotor akan tersebar seperti jamur. Diantara itu ada Himeneus dan Filetus.</span>
                                        </p>
                                    </div>
                                    <div class="flex_one editor_area font_aaa" dir="ltr">
                                        <div class="vnote">
                                            <div class="verse_block flex_chunk" data-verse="17">
                                                <textarea name="chunks[3][17]" class="peer_verse_ta textarea" style="min-width: 400px; min-height: 100px; height: 98px;">kata kotor akan tersebar seperti jamur. Diantara itu ada Himeneus dan Filetus.</textarea>

                                                <span class="editFootNote mdi mdi-bookmark" style="margin-top: -5px" title="Add a footnote"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex_sub_container">
                                    <div class="flex_one chunk_verses font_en" dir="ltr">
                                        <p class="verse_text kwverse_2_3_18" data-verse="18">
                                            <strong class="ltr">
                                                <sup>18</sup>
                                            </strong>
                                            <span class="verse_text_source" style="display: none;">who have gone astray from the truth. They say that the resurrection has already happened, and they destroy the faith of some.</span>
                                            <span class="verse_text_original" style="display: inline;">dong adalah orang-orang yang sudah tidak benar. dong katakan kebangkitan sudah terjadi, dong putar balik iman dari berapa orang tu.</span>
                                        </p>
                                    </div>
                                    <div class="flex_one editor_area font_aaa" dir="ltr">
                                        <div class="vnote">
                                            <div class="verse_block flex_chunk" data-verse="18">
                                                <textarea name="chunks[3][18]" class="peer_verse_ta textarea" style="min-width: 400px; min-height: 100px; height: 109px;">dong adalah orang-orang yang sudah tidak benar. dong katakan kebangkitan sudah terjadi, dong putar balik iman dari berapa orang tu.</textarea>

                                                <span class="editFootNote mdi mdi-bookmark" style="margin-top: -5px" title="Add a footnote"></span>
                                            </div>
                                        </div>
                                    </div>
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
                            <div class="flex_left flex_column">
                                <div class="flex_sub_container">
                                    <div class="flex_one chunk_verses font_en" dir="ltr">
                                        <p class="verse_text kwverse_2_4_19" data-verse="19">
                                            <strong class="ltr">
                                                <sup>19</sup>
                                            </strong>
                                            <span class="verse_text_source" style="display: none;">However, the firm foundation of God stands. It has this inscription: "The Lord knows those who are his" and "Everyone who names the name of the Lord must depart from unrighteousness."</span>
                                            <span class="verse_text_original" style="display: inline;">biar begitu, Allah pu fondasi kuat tetap berdiri. ada piagam dengan tulisan yang bilang Tuhan kenal dong yang su jadi milik Dia. dan orang yang percaya Tuhan harus kasi tinggal yang tidak benar.</span>
                                        </p>
                                    </div>
                                    <div class="flex_one editor_area font_aaa" dir="ltr">
                                        <div class="vnote">
                                            <div class="verse_block flex_chunk" data-verse="19">
                                                <textarea name="chunks[4][19]" class="peer_verse_ta textarea" style="min-width: 400px; min-height: 100px; height: 140px;">biar begitu, Allah pu fondasi kuat tetap berdiri. ada piagam dengan tulisan yang bilang Tuhan kenal dong yang su jadi milik Dia. dan orang yang percaya Tuhan harus kasi tinggal yang tidak benar.</textarea>

                                                <span class="editFootNote mdi mdi-bookmark" style="margin-top: -5px" title="Add a footnote"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex_sub_container">
                                    <div class="flex_one chunk_verses font_en" dir="ltr">
                                        <p class="verse_text kwverse_2_4_20" data-verse="20">
                                            <strong class="ltr">
                                                <sup>20</sup>
                                            </strong>
                                            <span class="verse_text_source" style="display: none;">In a wealthy home there are not only containers of gold and silver. There are also containers of wood and clay. Some of these are for honorable use, and some for dishonorable.</span>
                                            <span class="verse_text_original" style="display: inline;">dalam rumah kaya bukan saja ada emas dan perak tapi juga ada kotak-kotak kayu sama tanah liat. barang itu di pake untuk hal-hal yang terhormat, dan ada juga untuk hal-hal tidak terhormat.</span>
                                        </p>
                                    </div>
                                    <div class="flex_one editor_area font_aaa" dir="ltr">
                                        <div class="vnote">
                                            <div class="verse_block flex_chunk" data-verse="20">
                                                <textarea name="chunks[4][20]" class="peer_verse_ta textarea" style="min-width: 400px; min-height: 100px; height: 140px;">dalam rumah kaya bukan saja ada emas dan perak tapi juga ada kotak-kotak kayu sama tanah liat. barang itu di pake untuk hal-hal yang terhormat, dan ada juga untuk hal-hal tidak terhormat.</textarea>

                                                <span class="editFootNote mdi mdi-bookmark" style="margin-top: -5px" title="Add a footnote"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex_sub_container">
                                    <div class="flex_one chunk_verses font_en" dir="ltr">
                                        <p class="verse_text kwverse_2_4_21" data-verse="21">
                                            <strong class="ltr">
                                                <sup>21</sup>
                                            </strong>
                                            <span class="verse_text_source" style="display: none;">If someone cleans himself from dishonorable use, he is an honorable container. He is set apart, useful to the Master, and prepared for every good work.</span>
                                            <span class="verse_text_original" style="display: inline;">jika satu orang kasi bersih de pu diri dari yang tidak terhormat, de itu bejana yang terhormat. de dipilih , dipake untuk tuannya, dan de disiapkan untuk semua perbuatan yang baik.</span>
                                        </p>
                                    </div>
                                    <div class="flex_one editor_area font_aaa" dir="ltr">
                                        <div class="vnote">
                                            <div class="verse_block flex_chunk" data-verse="21">
                                                <textarea name="chunks[4][21]" class="peer_verse_ta textarea" style="min-width: 400px; min-height: 100px; height: 140px;">jika satu orang kasi bersih de pu diri dari yang tidak terhormat, de itu bejana yang terhormat. de dipilih , dipake untuk tuannya, dan de disiapkan untuk semua perbuatan yang baik.</textarea>

                                                <span class="editFootNote mdi mdi-bookmark" style="margin-top: -5px" title="Add a footnote"></span>
                                            </div>
                                        </div>
                                    </div>
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
                            <div class="flex_left flex_column">
                                <div class="flex_sub_container">
                                    <div class="flex_one chunk_verses font_en" dir="ltr">
                                        <p class="verse_text kwverse_2_5_22" data-verse="22">
                                            <strong class="ltr">
                                                <sup>22</sup>
                                            </strong>
                                            <span class="verse_text_source" style="display: none;">Flee youthful lusts. Pursue righteousness, faith, love, and peace with those who call on the Lord out of a clean heart.</span>
                                            <span class="verse_text_original" style="display: inline;">jauh sudah dari nafsu anak-anak muda,kejar itu kebenaran, iman, kasih, dan damai, sama-sama dengan dong yang panggil Tuhan dengan hati yang bersih.</span>
                                        </p>
                                    </div>
                                    <div class="flex_one editor_area font_aaa" dir="ltr">
                                        <div class="vnote">
                                            <div class="verse_block flex_chunk" data-verse="22">
                                                <textarea name="chunks[5][22]" class="peer_verse_ta textarea" style="min-width: 400px; min-height: 100px; height: 109px;">jauh sudah dari nafsu anak-anak muda,kejar itu kebenaran, iman, kasih, dan damai, sama-sama dengan dong yang panggil Tuhan dengan hati yang bersih.</textarea>

                                                <span class="editFootNote mdi mdi-bookmark" style="margin-top: -5px" title="Add a footnote"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex_sub_container">
                                    <div class="flex_one chunk_verses font_en" dir="ltr">
                                        <p class="verse_text kwverse_2_5_23" data-verse="23">
                                            <strong class="ltr">
                                                <sup>23</sup>
                                            </strong>
                                            <span class="verse_text_source" style="display: none;">But refuse foolish and ignorant questions. You know that they give birth to quarrels.</span>
                                            <span class="verse_text_original" style="display: inline;">tapi tolak sudah pertanyaan-pertanyaan bodok. kamu tahu itu semua nanti jadi sebab baku tengkar.</span>
                                        </p>
                                    </div>
                                    <div class="flex_one editor_area font_aaa" dir="ltr">
                                        <div class="vnote">
                                            <div class="verse_block flex_chunk" data-verse="23">
                                                <textarea name="chunks[5][23]" class="peer_verse_ta textarea" style="min-width: 400px; min-height: 100px; height: 98px;">tapi tolak sudah pertanyaan-pertanyaan bodok. kamu tahu itu semua nanti jadi sebab baku tengkar.</textarea>

                                                <span class="editFootNote mdi mdi-bookmark" style="margin-top: -5px" title="Add a footnote"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex_sub_container">
                                    <div class="flex_one chunk_verses font_en" dir="ltr">
                                        <p class="verse_text kwverse_2_5_24" data-verse="24">
                                            <strong class="ltr">
                                                <sup>24</sup>
                                            </strong>
                                            <span class="verse_text_source" style="display: none;">The Lord's servant must not quarrel. Instead he must be gentle toward all, able to teach, and patient,</span>
                                            <span class="verse_text_original" style="display: inline;">orang yang melayani Tuhan tra boleh bertengkar tapi harus lemah lembut kepada semua orang, bisa mengajar, dan sabar.</span>
                                        </p>
                                    </div>
                                    <div class="flex_one editor_area font_aaa" dir="ltr">
                                        <div class="vnote">
                                            <div class="verse_block flex_chunk" data-verse="24">
                                                <textarea name="chunks[5][24]" class="peer_verse_ta textarea" style="min-width: 400px; min-height: 100px; height: 109px;">orang yang melayani Tuhan tra boleh bertengkar tapi harus lemah lembut kepada semua orang, bisa mengajar, dan sabar.</textarea>

                                                <span class="editFootNote mdi mdi-bookmark" style="margin-top: -5px" title="Add a footnote"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex_sub_container">
                                    <div class="flex_one chunk_verses font_en" dir="ltr">
                                        <p class="verse_text kwverse_2_5_25" data-verse="25">
                                            <strong class="ltr">
                                                <sup>25</sup>
                                            </strong>
                                            <span class="verse_text_source" style="display: none;">correcting his opponents with gentleness. Perhaps God may give them repentance for the knowledge of the truth.</span>
                                            <span class="verse_text_original" style="display: inline;">de harus kasi ajaran dengan lemah lembut sama dong yang melawan dia. mungkin Allah kasi kesempatan untuk dong bertobat pada pengetahuan akan kebenaran.</span>
                                        </p>
                                    </div>
                                    <div class="flex_one editor_area font_aaa" dir="ltr">
                                        <div class="vnote">
                                            <div class="verse_block flex_chunk" data-verse="25">
                                                <textarea name="chunks[5][25]" class="peer_verse_ta textarea" style="min-width: 400px; min-height: 100px; height: 109px;">de harus kasi ajaran dengan lemah lembut sama dong yang melawan dia. mungkin Allah kasi kesempatan untuk dong bertobat pada pengetahuan akan kebenaran.</textarea>

                                                <span class="editFootNote mdi mdi-bookmark" style="margin-top: -5px" title="Add a footnote"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex_sub_container">
                                    <div class="flex_one chunk_verses font_en" dir="ltr">
                                        <p class="verse_text kwverse_2_5_26" data-verse="26">
                                            <strong class="ltr">
                                                <sup>26</sup>
                                            </strong>
                                            <span class="verse_text_source" style="display: none;">They may become sober again and leave the devil's trap, after they have been captured by him for his will.</span>
                                            <span class="verse_text_original" style="display: inline;">mungkin dong sadar kembali dan kasi tinggal jerat iblis setalah selama ini dong ditawan untuk ikut perintahnya.</span>
                                        </p>
                                    </div>
                                    <div class="flex_one editor_area font_aaa" dir="ltr">
                                        <div class="vnote">
                                            <div class="verse_block flex_chunk" data-verse="26">
                                                <textarea name="chunks[5][26]" class="peer_verse_ta textarea" style="min-width: 400px; min-height: 100px; height: 98px;">mungkin dong sadar kembali dan kasi tinggal jerat iblis setalah selama ini dong ditawan untuk ikut perintahnya.</textarea>

                                                <span class="editFootNote mdi mdi-bookmark" style="margin-top: -5px" title="Add a footnote"></span>
                                            </div>
                                        </div>
                                    </div>
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
                        <div class="main_content_confirm_desc"><?php echo __("confirm_finished")?></div>
                        <label><input name="confirm_step" id="confirm_step" value="1" type="checkbox"> <?php echo __("confirm_yes")?></label>
                    </div>

                    <button id="next_step" class="btn btn-primary" disabled="disabled">
                        <?php echo __($data["next_step"])?>
                    </button>
                    <img src="<?php echo template_url("img/saving.gif") ?>" class="unsaved_alert">
                </form>
                <div class="step_right alt"><?php echo __("step_num", ["step_number" => 2])?></div>
            </div>
        </div>
    </div>
</div>

<!-- Data for tools -->
<input type="hidden" id="targetLang" value="<?php echo $data["targetLang"] ?>">

<div class="content_help closed">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps is_checker_page_help">
            <div class="help_name_steps"><span><?php echo __("step_num", ["step_number" => 2])?>:</span> <?php echo __("self-check")?></div>
            <div class="help_descr_steps">
                <ul>
                    <li><b><?php echo __("purpose") ?></b> <?php echo __("self-check_l2_purpose") ?></li>
                    <li><b><?php echo __("length") ?></b> <?php echo __("self-check_l2_length") ?></li>
                    <li><?php echo __("peer-review_checker_help_2") ?></li>
                    <li><?php echo __("peer-review_checker_help_6") ?></li>
                    <li><?php echo __("self-check_l2_help_3") ?></li>
                    <li><?php echo __("self-check_l2_help_4") ?>
                        <ol>
                            <li><?php echo __("self-check_l2_help_4a") ?></li>
                            <li><?php echo __("self-check_l2_help_4b") ?></li>
                        </ol>
                    </li>
                    <li><?php echo __("self-check_l2_help_5") ?></li>
                    <li><?php echo __("self-check_l2_help_6") ?>
                        <ol>
                            <li><?php echo __("self-check_l2_help_6a", ["icon" => "<span class='mdi mdi-lead-pencil'></span>"]) ?></li>
                            <li><?php echo __("self-check_l2_help_6b") ?></li>
                            <li><?php echo __("self-check_l2_help_6c") ?></li>
                        </ol>
                    </li>
                    <li><?php echo __("self-check_l2_help_7") ?></li>
                    <li><?php echo __("edit_footnote_help_1") ?>
                        <ol>
                            <li><?php echo __("edit_footnote_help_1a") ?></li>
                            <li><?php echo __("edit_footnote_help_1b", ["icon" => "<i class='mdi mdi-bookmark'></i>"]) ?></li>
                            <li><?php echo __("edit_footnote_help_1c") ?></li>
                            <li><?php echo __("edit_footnote_help_1d") ?></li>
                            <li><?php echo __("edit_footnote_help_1e") ?></li>
                            <li><?php echo __("edit_footnote_help_1f") ?></li>
                            <li><?php echo __("edit_footnote_help_1g", ["icon" => "<i class='mdi mdi-bookmark'></i>"]) ?></li>
                            <li><?php echo __("edit_footnote_help_1h") ?></li>
                        </ol>
                    </li>
                    <li><?php echo __("move_to_next_step_alt", ["step" => __($data["next_step"])]) ?></li>
                </ul>
                <div class="show_tutorial_popup"> >>> <?php echo __("show_more")?></div>
            </div>
        </div>

        <div class="event_info is_checker_page_help">
            <div class="participant_info">
                <div class="additional_info">
                    <a href="/events/demo-revision/information"><?php echo __("event_info") ?></a>
                </div>
            </div>
        </div>

        <div class="tr_tools">
            <button class="btn btn-primary ttools" data-tool="tn"><?php echo __("show_notes") ?></button>
            <button class="btn btn-primary ttools" data-tool="tq"><?php echo __("show_questions") ?></button>
            <button class="btn btn-primary ttools" data-tool="tw"><?php echo __("show_keywords") ?></button>
            <button class="btn btn-warning ttools" data-tool="rubric"><?php echo __("show_rubric") ?></button>
        </div>
    </div>
</div>

<div class="tutorial_container">
    <div class="tutorial_popup">
        <div class="tutorial-close glyphicon glyphicon-remove"></div>
        <div class="tutorial_pic">
            <img src="<?php echo template_url("img/steps/icons/self-check.png") ?>" height="100px" width="100px">
            <img src="<?php echo template_url("img/steps/big/self-check.png") ?>" height="280px" width="280px">
        </div>

        <div class="tutorial_content">
            <h3><?php echo __(EventCheckSteps::SELF_CHECK)?></h3>
            <ul>
                <li><b><?php echo __("purpose") ?></b> <?php echo __("self-check_l2_purpose") ?></li>
                <li><b><?php echo __("length") ?></b> <?php echo __("self-check_l2_length") ?></li>
                <li><?php echo __("peer-review_checker_help_2") ?></li>
                <li><?php echo __("peer-review_checker_help_6") ?></li>
                <li><?php echo __("self-check_l2_help_3") ?></li>
                <li><?php echo __("self-check_l2_help_4") ?>
                    <ol>
                        <li><?php echo __("self-check_l2_help_4a") ?></li>
                        <li><?php echo __("self-check_l2_help_4b") ?></li>
                    </ol>
                </li>
                <li><?php echo __("self-check_l2_help_5") ?></li>
                <li><?php echo __("self-check_l2_help_6") ?>
                    <ol>
                        <li><?php echo __("self-check_l2_help_6a", ["icon" => "<span class='mdi mdi-lead-pencil'></span>"]) ?></li>
                        <li><?php echo __("self-check_l2_help_6b") ?></li>
                        <li><?php echo __("self-check_l2_help_6c") ?></li>
                    </ol>
                </li>
                <li><?php echo __("self-check_l2_help_7") ?></li>
                <li><?php echo __("edit_footnote_help_1") ?>
                    <ol>
                        <li><?php echo __("edit_footnote_help_1a") ?></li>
                        <li><?php echo __("edit_footnote_help_1b", ["icon" => "<i class='mdi mdi-bookmark'></i>"]) ?></li>
                        <li><?php echo __("edit_footnote_help_1c") ?></li>
                        <li><?php echo __("edit_footnote_help_1d") ?></li>
                        <li><?php echo __("edit_footnote_help_1e") ?></li>
                        <li><?php echo __("edit_footnote_help_1f") ?></li>
                        <li><?php echo __("edit_footnote_help_1g", ["icon" => "<i class='mdi mdi-bookmark'></i>"]) ?></li>
                        <li><?php echo __("edit_footnote_help_1h") ?></li>
                    </ol>
                </li>
                <li><?php echo __("move_to_next_step_alt", ["step" => __($data["next_step"])]) ?></li>
            </ul>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#next_step").click(function (e) {
            e.preventDefault();
            if(!hasChangesOnPage) window.location.href = '/events/demo-revision/peer_review';
            return false;
        });

        $(".source_mode input").change(function () {
            const active = $(this).prop('checked');
            if (active) {
                $(".verse_text_source").show();
                $(".verse_text_original").hide();
            } else {
                $(".verse_text_source").hide();
                $(".verse_text_original").show();
            }
        });

        $(".ttools_panel .word_def").each(function() {
            let html = convertRcLinks($(this).html());
            $(this).html(html);
        });
    });
</script>