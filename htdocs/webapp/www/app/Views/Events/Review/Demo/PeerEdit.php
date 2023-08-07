<?php

require(app_path() . "Views/Components/CommentEditor.php");
require(app_path() . "Views/Components/FootnotesEditor.php");
require(app_path() . "Views/Components/HelpTools.php");
?>

<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="main_content_title">
            <div class="demo_title"><?php echo __("demo") . " (".__("review_events").")" ?></div>
            <div><?php echo __("step_num", ["step_number" => 2]) . ": " . __("peer-edit-l3_full")?></div>
        </div>
    </div>

    <div class="">
        <div class="main_content">
            <div class="main_content_text">
                <h4>Papuan Malay - <?php echo __("ulb") ?> - <?php echo __("new_test") ?> - <span class="book_name">2 Timothy 2:1-26</span></h4>

                <div id="my_notes_content" class="my_content shown">
                    <div class="note_chunk l3">
                        <div class="flex_container">
                            <div class="flex_left">
                                <div class="flex_sub_container">
                                    <div class="flex_one scripture_compare_alt" dir="ltr">
                                        <p class="verse_text" data-verse="1">
                                            <strong class="ltr"><sup>1</sup></strong>
                                            <span>You therefore, my child, be strengthened in the grace that is in Christ Jesus.</span>
                                        </p>
                                    </div>
                                    <div class="flex_one vnote l3 font_aaa">
                                        <div class="verse_block flex_chunk" data-verse="1">
                                            <span class="verse_number_l3">1</span>
                                            <textarea name="chunks[0][1]" style="min-width: 400px; flex-grow: 1; min-height: 100px; height: 98px;" class="peer_verse_ta textarea" data-orig-verse="1">jadi begitu, anakku kuat sudah dengan anugerah di dalam Kristus Yesus.</textarea>

                                            <span class="editFootNote mdi mdi-bookmark" style="margin-top: -5px" title="Add a footnote"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex_sub_container">
                                    <div class="flex_one scripture_compare_alt" dir="ltr">
                                        <p class="verse_text" data-verse="2">
                                            <strong class="ltr"><sup>2</sup></strong>
                                            <span>The things you heard from me among many witnesses, entrust them to faithful people who will be able to teach others also.</span>
                                        </p>
                                    </div>
                                    <div class="flex_one vnote l3 font_aaa">
                                        <div class="verse_block flex_chunk" data-verse="2">
                                            <span class="verse_number_l3">2</span>
                                            <textarea name="chunks[0][2]" style="min-width: 400px; flex-grow: 1; min-height: 100px; height: 109px;" class="peer_verse_ta textarea" data-orig-verse="2">dan banyak hal yang ko dengar dari saya dengan saksi yang banyak itu,beri percaya itu sama orang-orang yang setia, supaya dong dapat mengajar orang lain juga.</textarea>

                                            <span class="editFootNote mdi mdi-bookmark" style="margin-top: -5px" title="Add a footnote"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex_sub_container">
                                    <div class="flex_one scripture_compare_alt" dir="ltr">
                                        <p class="verse_text" data-verse="3">
                                            <strong class="ltr"><sup>3</sup></strong>
                                            <span>Suffer hardship with me as a good soldier of Christ Jesus.</span>
                                        </p>
                                    </div>
                                    <div class="flex_one vnote l3 font_aaa">
                                        <div class="verse_block flex_chunk" data-verse="3">
                                            <span class="verse_number_l3">3</span>
                                            <textarea name="chunks[0][3]" style="min-width: 400px; flex-grow: 1; min-height: 100px; height: 98px;" class="peer_verse_ta textarea" data-orig-verse="3">Mari, gabung sama sa dalam penderitaan jadi prajurit Kristus Yesus yang baik.</textarea>

                                            <span class="editFootNote mdi mdi-bookmark" style="margin-top: -5px" title="Add a footnote"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex_sub_container">
                                    <div class="flex_one scripture_compare_alt" dir="ltr">
                                        <p class="verse_text" data-verse="4">
                                            <strong class="ltr"><sup>4</sup></strong>
                                            <span>No soldier serves while entangled in the affairs of this life, so that he may please his superior officer.</span>
                                        </p>
                                    </div>
                                    <div class="flex_one vnote l3 font_aaa">
                                        <div class="verse_block flex_chunk" data-verse="4">
                                            <span class="verse_number_l3">4</span>
                                            <textarea name="chunks[0][4]" style="min-width: 400px; flex-grow: 1; min-height: 100px; height: 109px;" class="peer_verse_ta textarea" data-orig-verse="4">Trada satu orang tentara yang kerja sambil sibuk dengan de pu urusan hidup supaya de bisa buat de pu komandan senang.</textarea>

                                            <span class="editFootNote mdi mdi-bookmark" style="margin-top: -5px" title="Add a footnote"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex_sub_container">
                                    <div class="flex_one scripture_compare_alt" dir="ltr">
                                        <p class="verse_text" data-verse="5">
                                            <strong class="ltr"><sup>5</sup></strong>
                                            <span>Also, if someone competes as an athlete, he is not crowned unless he competes by the rules.</span>
                                        </p>
                                    </div>
                                    <div class="flex_one vnote l3 font_aaa">
                                        <div class="verse_block flex_chunk" data-verse="5">
                                            <span class="verse_number_l3">5</span>
                                            <textarea name="chunks[0][5]" style="min-width: 400px; flex-grow: 1; min-height: 100px; height: 98px;" class="peer_verse_ta textarea" data-orig-verse="5">begitu juga dengan atlit , tidak akan terima mahkota kalo tidak ikut aturan dalam lomba</textarea>

                                            <span class="editFootNote mdi mdi-bookmark" style="margin-top: -5px" title="Add a footnote"></span>
                                        </div>
                                    </div>
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
                    <div class="note_chunk l3">
                        <div class="flex_container">
                            <div class="flex_left">
                                <div class="flex_sub_container">
                                    <div class="flex_one scripture_compare_alt" dir="ltr">
                                        <p class="verse_text" data-verse="6">
                                            <strong class="ltr"><sup>6</sup></strong>
                                            <span>It is necessary that the hard-working farmer receive his share of the crops first.</span>
                                        </p>
                                    </div>
                                    <div class="flex_one vnote l3 font_aaa">
                                        <div class="verse_block flex_chunk" data-verse="6">
                                            <span class="verse_number_l3">6</span>
                                            <textarea name="chunks[1][6]" style="min-width: 400px; flex-grow: 1; min-height: 100px; height: 98px;" class="peer_verse_ta textarea" data-orig-verse="6">lebih baik petani yang kerja keras terima hasil yang pertama,</textarea>

                                            <span class="editFootNote mdi mdi-bookmark" style="margin-top: -5px" title="Add a footnote"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex_sub_container">
                                    <div class="flex_one scripture_compare_alt" dir="ltr">
                                        <p class="verse_text" data-verse="7">
                                            <strong class="ltr"><sup>7</sup></strong>
                                            <span>Think about what I am saying, for the Lord will give you understanding in everything.</span>
                                        </p>
                                    </div>
                                    <div class="flex_one vnote l3 font_aaa">
                                        <div class="verse_block flex_chunk" data-verse="7">
                                            <span class="verse_number_l3">7</span>
                                            <textarea name="chunks[1][7]" style="min-width: 400px; flex-grow: 1; min-height: 100px; height: 98px;" class="peer_verse_ta textarea" data-orig-verse="7">ingat apa yang sa bilang, karena Tuhan akan kasi ko pengertian untuk mengerti semua ini,</textarea>

                                            <span class="editFootNote mdi mdi-bookmark" style="margin-top: -5px" title="Add a footnote"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex_sub_container">
                                    <div class="flex_one scripture_compare_alt" dir="ltr">
                                        <p class="verse_text" data-verse="8">
                                            <strong class="ltr"><sup>8</sup></strong>
                                            <span>Remember Jesus Christ, a descendant of David, who was raised from the dead. This is according to my gospel message,</span>
                                        </p>
                                    </div>
                                    <div class="flex_one vnote l3 font_aaa">
                                        <div class="verse_block flex_chunk" data-verse="8">
                                            <span class="verse_number_l3">8</span>
                                            <textarea name="chunks[1][8]" style="min-width: 400px; flex-grow: 1; min-height: 100px; height: 98px;" class="peer_verse_ta textarea" data-orig-verse="8">ingat Yesus Kristus, keturunan Daud, sudah bangkit dari kematian. ini su sesuai dengan pesan injil yang sa percaya.</textarea>

                                            <span class="editFootNote mdi mdi-bookmark" style="margin-top: -5px" title="Add a footnote"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex_sub_container">
                                    <div class="flex_one scripture_compare_alt" dir="ltr">
                                        <p class="verse_text" data-verse="9">
                                            <strong class="ltr"><sup>9</sup></strong>
                                            <span>for which I am suffering to the point of being bound with chains as a criminal. But the word of God is not bound.</span>
                                        </p>
                                    </div>
                                    <div class="flex_one vnote l3 font_aaa">
                                        <div class="verse_block flex_chunk" data-verse="9">
                                            <span class="verse_number_l3">9</span>
                                            <textarea name="chunks[1][9]" style="min-width: 400px; flex-grow: 1; min-height: 100px; height: 98px;" class="peer_verse_ta textarea" data-orig-verse="9">sampe pada titik penderitaan karna diikat rantai seperti kriminal. tapi firman Allah tidak diikat dengan rantai.</textarea>

                                            <span class="editFootNote mdi mdi-bookmark" style="margin-top: -5px" title="Add a footnote"></span>
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
                    <div class="note_chunk l3">
                        <div class="flex_container">
                            <div class="flex_left">
                                <div class="flex_sub_container">
                                    <div class="flex_one scripture_compare_alt" dir="ltr">
                                        <p class="verse_text" data-verse="10">
                                            <strong class="ltr"><sup>10</sup></strong>
                                            <span>Therefore I endure all things for those who are chosen, so that they also may obtain the salvation that is in Christ Jesus, with eternal glory.</span>
                                        </p>
                                    </div>
                                    <div class="flex_one vnote l3 font_aaa">
                                        <div class="verse_block flex_chunk" data-verse="10">
                                            <span class="verse_number_l3">10</span>
                                            <textarea name="chunks[2][10]" style="min-width: 400px; flex-grow: 1; min-height: 100px; height: 109px;" class="peer_verse_ta textarea" data-orig-verse="10">jadi sa bertahan untuk orang-orang yang Tuhan pilih, supaya dong dapat keselamatan yang kekal dalam Kristus Yesus, dengan kemuliaan yang abadi..</textarea>

                                            <span class="editFootNote mdi mdi-bookmark" style="margin-top: -5px" title="Add a footnote"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex_sub_container">
                                    <div class="flex_one scripture_compare_alt" dir="ltr">
                                        <p class="verse_text" data-verse="11">
                                            <strong class="ltr"><sup>11</sup></strong>
                                            <span>This is a trustworthy saying:  "If we have died with him, we will also live with him. </span>
                                        </p>
                                    </div>
                                    <div class="flex_one vnote l3 font_aaa">
                                        <div class="verse_block flex_chunk" data-verse="11">
                                            <span class="verse_number_l3">11</span>
                                            <textarea name="chunks[2][11]" style="min-width: 400px; flex-grow: 1; min-height: 100px; height: 98px;" class="peer_verse_ta textarea" data-orig-verse="11">apa yang sa bilang ini, bisa dipercaya: kalo ketong mau mati untuk Dia, torang juga akan hidup bersama dengan Dia.</textarea>

                                            <span class="editFootNote mdi mdi-bookmark" style="margin-top: -5px" title="Add a footnote"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex_sub_container">
                                    <div class="flex_one scripture_compare_alt" dir="ltr">
                                        <p class="verse_text" data-verse="12">
                                            <strong class="ltr"><sup>12</sup></strong>
                                            <span>If we endure, we will also reign with him.  If we deny him, he also will deny us. </span>
                                        </p>
                                    </div>
                                    <div class="flex_one vnote l3 font_aaa">
                                        <div class="verse_block flex_chunk" data-verse="12">
                                            <span class="verse_number_l3">12</span>
                                            <textarea name="chunks[2][12]" style="min-width: 400px; flex-grow: 1; min-height: 100px; height: 109px;" class="peer_verse_ta textarea" data-orig-verse="12">apalagi kalo tong bertahan , tong juga akan ditinggikan bersama Dia. klo tong menyangkal Dia, Dia juga akan menyangkal ketong,</textarea>

                                            <span class="editFootNote mdi mdi-bookmark" style="margin-top: -5px" title="Add a footnote"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex_sub_container">
                                    <div class="flex_one scripture_compare_alt" dir="ltr">
                                        <p class="verse_text" data-verse="13">
                                            <strong class="ltr"><sup>13</sup></strong>
                                            <span>if we are unfaithful, he remains faithful,  for he cannot deny himself."</span>
                                        </p>
                                    </div>
                                    <div class="flex_one vnote l3 font_aaa">
                                        <div class="verse_block flex_chunk" data-verse="13">
                                            <span class="verse_number_l3">13</span>
                                            <textarea name="chunks[2][13]" style="min-width: 400px; flex-grow: 1; min-height: 100px; height: 98px;" class="peer_verse_ta textarea" data-orig-verse="13">klo tong tra setia, De tetap setia karena de tra bisa menyangkal diri.</textarea>

                                            <span class="editFootNote mdi mdi-bookmark" style="margin-top: -5px" title="Add a footnote"></span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="flex_right">
                                <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                    </div>
                    <div class="note_chunk l3">
                        <div class="flex_container">
                            <div class="flex_left">
                                <div class="flex_sub_container">
                                    <div class="flex_one scripture_compare_alt" dir="ltr">
                                        <p class="verse_text" data-verse="14">
                                            <strong class="ltr"><sup>14</sup></strong>
                                            <span>Keep reminding them of these things. Command them before God not to quarrel about words; it is of no value and only ruins those who listen.  <span data-toggle="tooltip" data-placement="auto auto" title="Some important and ancient Greek copies read,  Warn them before the Lord  . " class="booknote mdi mdi-bookmark"></span> </span>
                                        </p>
                                    </div>
                                    <div class="flex_one vnote l3 font_aaa">
                                        <div class="verse_block flex_chunk" data-verse="14">
                                            <span class="verse_number_l3">14</span>
                                            <textarea name="chunks[3][14]" style="min-width: 400px; flex-grow: 1; min-height: 100px; height: 109px;" class="peer_verse_ta textarea" data-orig-verse="14">selalu kasi ingat dong di hadapan Allah, supaya dong jangan berdebat tentang firman karena itu akan bikin kacau orang yang dengar,</textarea>

                                            <span class="editFootNote mdi mdi-bookmark" style="margin-top: -5px" title="Add a footnote"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex_sub_container">
                                    <div class="flex_one scripture_compare_alt" dir="ltr">
                                        <p class="verse_text" data-verse="15">
                                            <strong class="ltr"><sup>15</sup></strong>
                                            <span>Do your best to present yourself to God as one approved, a laborer who has no reason to be ashamed, who accurately teaches the word of truth.</span>
                                        </p>
                                    </div>
                                    <div class="flex_one vnote l3 font_aaa">
                                        <div class="verse_block flex_chunk" data-verse="15">
                                            <span class="verse_number_l3">15</span>
                                            <textarea name="chunks[3][15]" style="min-width: 400px; flex-grow: 1; min-height: 100px; height: 109px;" class="peer_verse_ta textarea" data-orig-verse="15">lakukan yang paling baik itu adalah persembahan yang Tuhan terima, jadi pekerja trada alasan untuk dapat kasi malu, yang ajar kebeneran firman dengan pas.</textarea>

                                            <span class="editFootNote mdi mdi-bookmark" style="margin-top: -5px" title="Add a footnote"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex_sub_container">
                                    <div class="flex_one scripture_compare_alt" dir="ltr">
                                        <p class="verse_text" data-verse="16">
                                            <strong class="ltr"><sup>16</sup></strong>
                                            <span>Avoid profane and empty talk, which leads to more and more godlessness.</span>
                                        </p>
                                    </div>
                                    <div class="flex_one vnote l3 font_aaa">
                                        <div class="verse_block flex_chunk" data-verse="16">
                                            <span class="verse_number_l3">16</span>
                                            <textarea name="chunks[3][16]" style="min-width: 400px; flex-grow: 1; min-height: 100px; height: 98px;" class="peer_verse_ta textarea" data-orig-verse="16">pindah dari kata-kata kotor, yang nanti jadi tidak baik.</textarea>

                                            <span class="editFootNote mdi mdi-bookmark" style="margin-top: -5px" title="Add a footnote"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex_sub_container">
                                    <div class="flex_one scripture_compare_alt" dir="ltr">
                                        <p class="verse_text" data-verse="17">
                                            <strong class="ltr"><sup>17</sup></strong>
                                            <span>Their talk will spread like cancer. Among them are Hymenaeus and Philetus,</span>
                                        </p>
                                    </div>
                                    <div class="flex_one vnote l3 font_aaa">
                                        <div class="verse_block flex_chunk" data-verse="17">
                                            <span class="verse_number_l3">17</span>
                                            <textarea name="chunks[3][17]" style="min-width: 400px; flex-grow: 1; min-height: 100px; height: 98px;" class="peer_verse_ta textarea" data-orig-verse="17">kata kotor akan tersebar seperti jamur. Diantara itu ada Himeneus dan Filetus.</textarea>

                                            <span class="editFootNote mdi mdi-bookmark" style="margin-top: -5px" title="Add a footnote"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex_sub_container">
                                    <div class="flex_one scripture_compare_alt" dir="ltr">
                                        <p class="verse_text" data-verse="18">
                                            <strong class="ltr"><sup>18</sup></strong>
                                            <span>who have gone astray from the truth. They say that the resurrection has already happened, and they destroy the faith of some.</span>
                                        </p>
                                    </div>
                                    <div class="flex_one vnote l3 font_aaa">
                                        <div class="verse_block flex_chunk" data-verse="18">
                                            <span class="verse_number_l3">18</span>
                                            <textarea name="chunks[3][18]" style="min-width: 400px; flex-grow: 1; min-height: 100px; height: 109px;" class="peer_verse_ta textarea" data-orig-verse="18">dong adalah orang-orang yang sudah tidak benar. dong katakan kebangkitan sudah terjadi, dong putar balik iman dari berapa orang tu.</textarea>

                                            <span class="editFootNote mdi mdi-bookmark" style="margin-top: -5px" title="Add a footnote"></span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="flex_right">
                                <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                    </div>
                    <div class="note_chunk l3">
                        <div class="flex_container">
                            <div class="flex_left">
                                <div class="flex_sub_container">
                                    <div class="flex_one scripture_compare_alt" dir="ltr">
                                        <p class="verse_text" data-verse="19">
                                            <strong class="ltr"><sup>19</sup></strong>
                                            <span>However, the firm foundation of God stands. It has this inscription: "The Lord knows those who are his" and "Everyone who names the name of the Lord must depart from unrighteousness."</span>
                                        </p>
                                    </div>
                                    <div class="flex_one vnote l3 font_aaa">
                                        <div class="verse_block flex_chunk" data-verse="19">
                                            <span class="verse_number_l3">19</span>
                                            <textarea name="chunks[4][19]" style="min-width: 400px; flex-grow: 1; min-height: 100px; height: 140px;" class="peer_verse_ta textarea" data-orig-verse="19">biar begitu, Allah pu fondasi kuat tetap berdiri. ada piagam dengan tulisan yang bilang Tuhan kenal dong yang su jadi milik Dia. dan orang yang percaya Tuhan harus kasi tinggal yang tidak benar.</textarea>

                                            <span class="editFootNote mdi mdi-bookmark" style="margin-top: -5px" title="Add a footnote"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex_sub_container">
                                    <div class="flex_one scripture_compare_alt" dir="ltr">
                                        <p class="verse_text" data-verse="20">
                                            <strong class="ltr"><sup>20</sup></strong>
                                            <span>In a wealthy home there are not only containers of gold and silver. There are also containers of wood and clay. Some of these are for honorable use, and some for dishonorable.</span>
                                        </p>
                                    </div>
                                    <div class="flex_one vnote l3 font_aaa">
                                        <div class="verse_block flex_chunk" data-verse="20">
                                            <span class="verse_number_l3">20</span>
                                            <textarea name="chunks[4][20]" style="min-width: 400px; flex-grow: 1; min-height: 100px; height: 140px;" class="peer_verse_ta textarea" data-orig-verse="20">dalam rumah kaya bukan saja ada emas dan perak tapi juga ada kotak-kotak kayu sama tanah liat. barang itu di pake untuk hal-hal yang terhormat, dan ada juga untuk hal-hal tidak terhormat.</textarea>

                                            <span class="editFootNote mdi mdi-bookmark" style="margin-top: -5px" title="Add a footnote"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex_sub_container">
                                    <div class="flex_one scripture_compare_alt" dir="ltr">
                                        <p class="verse_text" data-verse="21">
                                            <strong class="ltr"><sup>21</sup></strong>
                                            <span>If someone cleans himself from dishonorable use, he is an honorable container. He is set apart, useful to the Master, and prepared for every good work.</span>
                                        </p>
                                    </div>
                                    <div class="flex_one vnote l3 font_aaa">
                                        <div class="verse_block flex_chunk" data-verse="21">
                                            <span class="verse_number_l3">21</span>
                                            <textarea name="chunks[4][21]" style="min-width: 400px; flex-grow: 1; min-height: 100px; height: 140px;" class="peer_verse_ta textarea" data-orig-verse="21">jika satu orang kasi bersih de pu diri dari yang tidak terhormat, de itu bejana yang terhormat. de dipilih , dipake untuk tuannya, dan de disiapkan untuk semua perbuatan yang baik.</textarea>

                                            <span class="editFootNote mdi mdi-bookmark" style="margin-top: -5px" title="Add a footnote"></span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="flex_right">
                                <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                    </div>
                    <div class="note_chunk l3">
                        <div class="flex_container">
                            <div class="flex_left">
                                <div class="flex_sub_container">
                                    <div class="flex_one scripture_compare_alt" dir="ltr">
                                        <p class="verse_text" data-verse="22">
                                            <strong class="ltr"><sup>22</sup></strong>
                                            <span>Flee youthful lusts. Pursue righteousness, faith, love, and peace with those who call on the Lord out of a clean heart.</span>
                                        </p>
                                    </div>
                                    <div class="flex_one vnote l3 font_aaa">
                                        <div class="verse_block flex_chunk" data-verse="22">
                                            <span class="verse_number_l3">22</span>
                                            <textarea name="chunks[5][22]" style="min-width: 400px; flex-grow: 1; min-height: 100px; height: 109px;" class="peer_verse_ta textarea" data-orig-verse="22">jauh sudah dari nafsu anak-anak muda,kejar itu kebenaran, iman, kasih, dan damai, sama-sama dengan dong yang panggil Tuhan dengan hati yang bersih.</textarea>

                                            <span class="editFootNote mdi mdi-bookmark" style="margin-top: -5px" title="Add a footnote"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex_sub_container">
                                    <div class="flex_one scripture_compare_alt" dir="ltr">
                                        <p class="verse_text" data-verse="23">
                                            <strong class="ltr"><sup>23</sup></strong>
                                            <span>But refuse foolish and ignorant questions. You know that they give birth to quarrels.</span>
                                        </p>
                                    </div>
                                    <div class="flex_one vnote l3 font_aaa">
                                        <div class="verse_block flex_chunk" data-verse="23">
                                            <span class="verse_number_l3">23</span>
                                            <textarea name="chunks[5][23]" style="min-width: 400px; flex-grow: 1; min-height: 100px; height: 98px;" class="peer_verse_ta textarea" data-orig-verse="23">tapi tolak sudah pertanyaan-pertanyaan bodok. kamu tahu itu semua nanti jadi sebab baku tengkar.</textarea>

                                            <span class="editFootNote mdi mdi-bookmark" style="margin-top: -5px" title="Add a footnote"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex_sub_container">
                                    <div class="flex_one scripture_compare_alt" dir="ltr">
                                        <p class="verse_text" data-verse="24">
                                            <strong class="ltr"><sup>24</sup></strong>
                                            <span>The Lord's servant must not quarrel. Instead he must be gentle toward all, able to teach, and patient,</span>
                                        </p>
                                    </div>
                                    <div class="flex_one vnote l3 font_aaa">
                                        <div class="verse_block flex_chunk" data-verse="24">
                                            <span class="verse_number_l3">24</span>
                                            <textarea name="chunks[5][24]" style="min-width: 400px; flex-grow: 1; min-height: 100px; height: 109px;" class="peer_verse_ta textarea" data-orig-verse="24">orang yang melayani Tuhan tra boleh bertengkar tapi harus lemah lembut kepada semua orang, bisa mengajar, dan sabar.</textarea>

                                            <span class="editFootNote mdi mdi-bookmark" style="margin-top: -5px" title="Add a footnote"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex_sub_container">
                                    <div class="flex_one scripture_compare_alt" dir="ltr">
                                        <p class="verse_text" data-verse="25">
                                            <strong class="ltr"><sup>25</sup></strong>
                                            <span>correcting his opponents with gentleness. Perhaps God may give them repentance for the knowledge of the truth.</span>
                                        </p>
                                    </div>
                                    <div class="flex_one vnote l3 font_aaa">
                                        <div class="verse_block flex_chunk" data-verse="25">
                                            <span class="verse_number_l3">25</span>
                                            <textarea name="chunks[5][25]" style="min-width: 400px; flex-grow: 1; min-height: 100px; height: 109px;" class="peer_verse_ta textarea" data-orig-verse="25">de harus kasi ajaran dengan lemah lembut sama dong yang melawan dia. mungkin Allah kasi kesempatan untuk dong bertobat pada pengetahuan akan kebenaran.</textarea>

                                            <span class="editFootNote mdi mdi-bookmark" style="margin-top: -5px" title="Add a footnote"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex_sub_container">
                                    <div class="flex_one scripture_compare_alt" dir="ltr">
                                        <p class="verse_text" data-verse="26">
                                            <strong class="ltr"><sup>26</sup></strong>
                                            <span>They may become sober again and leave the devil's trap, after they have been captured by him for his will.</span>
                                        </p>
                                    </div>
                                    <div class="flex_one vnote l3 font_aaa">
                                        <div class="verse_block flex_chunk" data-verse="26">
                                            <span class="verse_number_l3">26</span>
                                            <textarea name="chunks[5][26]" style="min-width: 400px; flex-grow: 1; min-height: 100px; height: 98px;" class="peer_verse_ta textarea" data-orig-verse="26">mungkin dong sadar kembali dan kasi tinggal jerat iblis setalah selama ini dong ditawan untuk ikut perintahnya.</textarea>

                                            <span class="editFootNote mdi mdi-bookmark" style="margin-top: -5px" title="Add a footnote"></span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="flex_right">
                                <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                    </div>
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
                <div class="step_right"></div>
            </div>
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
                <?php echo __("peer-edit-l3")?>
            </div>
            <div class="help_descr_steps">
                <ul>
                    <li><b><?php echo __("purpose") ?></b> <?php echo __("self-check_l2_purpose") ?></li>
                    <li><?php echo __("peer-edit-l3_help_1") ?></li>
                    <li><?php echo __("peer-edit-l3_help_2") ?></li>
                    <li><?php echo __("peer-edit-l3_help_3", ["icon" => "<span class='mdi mdi-lead-pencil'></span>"]) ?></li>
                    <li><?php echo __("peer-edit-l3_help_4") ?></li>
                    <li><?php echo __("peer-edit-l3_help_5") ?></li>
                    <li><?php echo __("peer-edit-l3_help_6") ?></li>
                    <li><?php echo __("peer-review_help_5") ?>
                        <ol>
                            <li><?php echo __("self-check_help_5a") ?></li>
                            <li><?php echo __("keyword-check_help_4a") ?></li>
                            <li><?php echo __("self-check_help_5b") ?></li>
                            <li><?php echo __("self-check_help_5c") ?></li>
                        </ol>
                    </li>
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
                    <li><b><?php echo __("peer-edit-l3_help_8") ?></b></li>
                </ul>
                <div class="show_tutorial_popup"> >>> <?php echo __("show_more")?></div>
            </div>
        </div>

        <div class="event_info is_checker_page_help">
            <div class="participant_info">
                <div class="participant_name">
                    <span><?php echo __("your_checker") ?>:</span>
                    <span class="checker_name_span">
                                Marge S.
                            </span>
                </div>
                <div class="additional_info">
                    <a href="/events/demo-review/information"><?php echo __("event_info") ?></a>
                </div>
            </div>
        </div>

        <div class="tr_tools">
            <?php
            renderTn($data["tnLangID"]);
            renderTq($data["tqLangID"]);
            renderTw($data["twLangID"]);
            renderBc($data["bcLangID"]);
            renderRubric();
            ?>
        </div>

        <div class="checker_view">
            <a href="/events/demo-review/peer_edit_l3_checker"><?php echo __("checker_other_view", [2]) ?></a>
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
            <h3><?php echo __("peer-edit-l3_full")?></h3>
            <ul>
                <li><b><?php echo __("purpose") ?></b> <?php echo __("self-check_l2_purpose") ?></li>
                <li><?php echo __("peer-edit-l3_help_1") ?></li>
                <li><?php echo __("peer-edit-l3_help_2") ?></li>
                <li><?php echo __("peer-edit-l3_help_3", ["icon" => "<span class='mdi mdi-lead-pencil'></span>"]) ?></li>
                <li><?php echo __("peer-edit-l3_help_4") ?></li>
                <li><?php echo __("peer-edit-l3_help_5") ?></li>
                <li><?php echo __("peer-edit-l3_help_6") ?></li>
                <li><?php echo __("peer-review_help_5") ?>
                    <ol>
                        <li><?php echo __("self-check_help_5a") ?></li>
                        <li><?php echo __("keyword-check_help_4a") ?></li>
                        <li><?php echo __("self-check_help_5b") ?></li>
                        <li><?php echo __("self-check_help_5c") ?></li>
                    </ol>
                </li>
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
                <li><b><?php echo __("peer-edit-l3_help_8") ?></b></li>
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
            if(!hasChangesOnPage) window.location.href = '/events/demo-review/pray';
            return false;
        });

        $(".ttools_panel .word_def").each(function() {
            let html = convertRcLinks($(this).html());
            $(this).html(html);
        });
    });
</script>
