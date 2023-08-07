<?php
use Helpers\Constants\EventCheckSteps;

require(app_path() . "Views/Components/CommentEditor.php");
require(app_path() . "Views/Components/HelpTools.php");
?>

<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="main_content_title">
            <div class="demo_title"><?php echo __("demo") . " (".__("revision_events").")" ?></div>
            <div><?php echo __("step_num", ["step_number" => 4]) . ": " . __(EventCheckSteps::KEYWORD_CHECK)?></div>
        </div>
    </div>

    <div class="">
        <div class="main_content">
            <div class="main_content_text">
                <h4>Papuan Malay - <?php echo __("ulb") ?> - <?php echo __("new_test") ?> - <span class="book_name">2 Timothy 2:1-26</span></h4>

                <div class="my_content">
                    <div class="no_padding">
                        <div class="compare_flex_container">
                            <div class="source_mode">
                                <label>
                                    <?php echo __("show_source") ?>
                                    <input type="checkbox" autocomplete="off"
                                           data-toggle="toggle"
                                           data-on="ON"
                                           data-off="OFF" />
                                </label>
                            </div>
                            <div class="compare_notes revision">
                                <label>
                                    <?php echo __("compare"); ?>
                                    <input type="checkbox" autocomplete="off" data-toggle="toggle"
                                           data-on="<?php echo __("on") ?>"
                                           data-off="<?php echo __("off") ?>" />
                                </label>
                            </div>
                        </div>

                        <div class="row chunk_block">
                            <div class="flex_container">
                                <div class="chunk_verses flex_left" dir="ltr">
                                    <p class="verse_text" data-verse="1"> <strong class="ltr"> <sup>1</sup> </strong>
                                        <span class="verse_text_source"><b data="0">You</b> therefore, my child, be <b data="0">strengthened</b> in the <b data="0">grace</b> that is in <b data="0">Christ Jesus</b>.</span>
                                        <span class="verse_text_original">Jadi begitu, anakku kuat sudah dengan anugerah di dalam Kristus Yesus.</span>
                                    </p>
                                    <p class="verse_text" data-verse="2"> <strong class="ltr"> <sup>2</sup> </strong>
                                        <span class="verse_text_source">And the things you heard from me among many witnesses, entrust them to faithful people who will be able to teach others also.</span>
                                        <span class="verse_text_original">Dan banyak hal yang ko dengar dari sa deng saksi yang banyak itu, beri percaya itu sama orang-orang yang setia, supaya dong dapat this is the removed text.</span>
                                    </p>
                                    <p class="verse_text" data-verse="3"> <strong class="ltr"> <sup>3</sup> </strong>
                                        <span class="verse_text_source"><b data="0">Suffer</b> hardship with me, as a good soldier of Christ Jesus.</span>
                                        <span class="verse_text_original">Mari, tong sama sa dalam penderitaan jadi prajurit Kristus Yesus yang baik.</span>
                                    </p>
                                </div>
                                <div class="editor_area flex_middle" dir="ltr">
                                    <p class="original_verse" data-verse="1">Jadi begitu, anakku kuat sudah dengan anugerah di dalam Kristus Yesus.</p>
                                    <p class="original_verse" data-verse="2">Dan banyak hal yang ko dengar dari sa deng saksi yang banyak itu, beri percaya itu sama orang-orang yang setia, supaya dong dapat mengajar dong yang lain juga.</p>
                                    <p class="original_verse" data-verse="3">Mari, tong sama sa dalam penderitaan jadi prajurit Kristus Yesus yang baik.</p>

                                    <div class="vnote">
                                        <div class="verse_block flex_chunk" data-verse="1"> <p class="target_verse" data-verse="1">Jadi begitu, The text that was added will be marked green. anakku kuat sudah dengan anugerah di dalam Kristus Yesus.</p> </div>
                                        <div class="verse_block flex_chunk" data-verse="2"> <p class="target_verse" data-verse="2">Dan banyak hal yang ko dengar dari sa deng saksi yang banyak itu, beri percaya itu sama orang-orang yang setia, supaya dong dapat mengajar dong yang lain juga.</p></div>
                                        <div class="verse_block flex_chunk" data-verse="3"> <p class="target_verse" data-verse="3">Mari, tong sama sa dalam penderitaan jadi (This is an example of replaced text) Kristus Yesus yang baik.</p></div>
                                    </div>
                                </div>
                                <div class="flex_right">
                                    <div class="flex_right">
                                        <?php
                                        $comments = [$data["comments"][0][0], $data["comments"][0][1]];
                                        $hasComments = !empty($comments);
                                        $commentsNumber = sizeof($comments);
                                        $myMemberID = 0;
                                        $enableFootNotes = false;
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
                                <div class="chunk_verses flex_left" dir="ltr">
                                    <p class="verse_text" data-verse="4"> <strong class="ltr"> <sup>4</sup> </strong>
                                        <span class="verse_text_source">No soldier serves while entangled in the affairs of this life, so that he may please his superior officer.</span>
                                        <span class="verse_text_original">Trada satu orang tentara yang kerja sambil sibuk dengan de pu urusan hidup supaya de bisa buat de pu komandan senang.</span>
                                    </p>
                                    <p class="verse_text" data-verse="5"> <strong class="ltr"> <sup>5</sup> </strong>
                                        <span class="verse_text_source">Also, if someone competes as an athlete, he is not crowned unless he competes by the rules.</span>
                                        <span class="verse_text_original">Begitu juga dengan atlit , tra akan terima mahkota kalo tra ikut aturan dalam lomba.</span>
                                    </p>
                                    <p class="verse_text" data-verse="6"> <strong class="ltr"> <sup>6</sup> </strong>
                                        <span class="verse_text_source">It is necessary that the hardworking farmer receive his share of the crops first.</span>
                                        <span class="verse_text_original">Petani dong yang kerja keras akan terima hasil yang pertama,</span>
                                    </p>
                                    <p class="verse_text" data-verse="7"> <strong class="ltr"> <sup>7</sup> </strong>
                                        <span class="verse_text_source">Think about what I am saying, for the Lord will give you understanding in everything.</span>
                                        <span class="verse_text_original">Ingat apa yang sa bilang, karena Tuhan akan kasi ko pengertian untuk mengerti semua ini,</span>
                                    </p>
                                </div>
                                <div class="editor_area flex_middle" dir="ltr">
                                    <p class="original_verse" data-verse="4">Trada satu orang tentara yang kerja sambil sibuk dengan de pu urusan hidup supaya de bisa buat de pu komandan senang.</p>
                                    <p class="original_verse" data-verse="5">Begitu juga dengan atlit , tra akan terima mahkota kalo tra ikut aturan dalam lomba.</p>
                                    <p class="original_verse" data-verse="6">Petani dong yang kerja keras akan (Deleted text will be marked red) terima hasil yang pertama,</p>
                                    <p class="original_verse" data-verse="7">Ingat apa yang sa bilang, karena Tuhan akan kasi ko pengertian untuk mengerti semua ini,</p>

                                    <div class="vnote">
                                        <div class="verse_block flex_chunk" data-verse="4"> <p class="target_verse" data-verse="4">Trada satu orang tentara yang kerja sambil sibuk dengan de pu urusan hidup supaya de bisa buat de pu komandan senang.</p> </div>
                                        <div class="verse_block flex_chunk" data-verse="5"> <p class="target_verse" data-verse="5">Begitu juga dengan atlit , tra akan terima mahkota kalo tra ikut aturan dalam lomba.</p> </div>
                                        <div class="verse_block flex_chunk" data-verse="6"> <p class="target_verse" data-verse="6">Petani dong yang kerja keras akan terima hasil yang pertama,</p> </div>
                                        <div class="verse_block flex_chunk" data-verse="7"> <p class="target_verse" data-verse="7">Ingat apa yang sa bilang, karena Tuhan akan kasi ko pengertian untuk mengerti semua ini,</p> </div>
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
                                    <p class="verse_text" data-verse="8"> <strong class="ltr"> <sup>8</sup> </strong>
                                        <span class="verse_text_source">Remember Jesus Christ, from David's seed, who was raised from the dead ones. This is according to my gospel message,</span>
                                        <span class="verse_text_original">Ingat: Yesus Kristus, keturunan Daud, su bangkit dari kematian. ini su sesuai dengan pesan Injil yang sa percaya.</span>
                                    </p>
                                    <p class="verse_text" data-verse="9"> <strong class="ltr"> <sup>9</sup> </strong>
                                        <span class="verse_text_source">for which I am suffering to the point of being chained as a criminal. But the word of God is not chained.</span>
                                        <span class="verse_text_original">Sampe pada titik penderitaan karna diikat rantai seperti kriminal. tapi firman Allah tra diikat deng rantai.</span>

                                    </p>
                                    <p class="verse_text" data-verse="10"> <strong class="ltr"> <sup>10</sup> </strong>
                                        <span class="verse_text_source">Therefore I endure all things for those who are chosen, so that they also may obtain the salvation that is in Christ Jesus, with eternal glory.</span>
                                        <span class="verse_text_original">Jadi sa bertahan untuk orang-orang yang Tuhan pilih, supaya dong dapat keselamatan yang kekal dalam Kristus Yesus, deng kemuliaan yang abadi.</span>
                                    </p>
                                </div>
                                <div class="editor_area flex_middle" dir="ltr">
                                    <p class="original_verse" data-verse="8">Ingat: Yesus Kristus, keturunan Daud, su bangkit dari kematian. ini su sesuai dengan pesan Injil yang sa percaya.</p>
                                    <p class="original_verse" data-verse="9">Sampe pada titik penderitaan karna diikat rantai seperti kriminal. tapi firman Allah tra diikat deng rantai.</p>
                                    <p class="original_verse" data-verse="10">Jadi sa bertahan untuk orang-orang yang Tuhan pilih, supaya dong dapat keselamatan yang kekal dalam Kristus Yesus, deng kemuliaan yang abadi.</p>

                                    <div class="vnote">
                                        <div class="verse_block flex_chunk" data-verse="8"> <p class="target_verse" data-verse="8">Ingat: Yesus Kristus, keturunan Daud, su bangkit dari kematian. ini su sesuai dengan pesan Injil yang sa percaya.</p> </div>
                                        <div class="verse_block flex_chunk" data-verse="9"> <p class="target_verse" data-verse="9">Sampe pada titik penderitaan karna diikat rantai seperti kriminal. tapi firman Allah tra diikat deng rantai.</p> </div>
                                        <div class="verse_block flex_chunk" data-verse="10"> <p class="target_verse" data-verse="10">Jadi sa bertahan untuk orang-orang yang Tuhan pilih, supaya dong dapat keselamatan yang kekal dalam Kristus Yesus, deng kemuliaan yang abadi.</p> </div>
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
                                    <p class="verse_text" data-verse="11"> <strong class="ltr"> <sup>11</sup> </strong>
                                        <span class="verse_text_source">This saying is trustworthy: "If we have died with him, we will also live with him.</span>
                                        <span class="verse_text_original">Apa yang sa bilang ini, bisa dipercaya: kalo tong mau mati untuk Dia, torang juga akan hidup bersama deng Dia.</span>
                                    </p>
                                    <p class="verse_text" data-verse="12"> <strong class="ltr"> <sup>12</sup> </strong>
                                        <span class="verse_text_source">If we endure, we will also reign with him. If we deny him, he also will deny us.</span>
                                        <span class="verse_text_original">Apalagi kalo tong bertahan , tong juga akan ditinggikan deng Dia. Klo tong menyangkal Dia, Dia juga akan menyangkal ketong,</span>
                                    </p>
                                    <p class="verse_text" data-verse="13"> <strong class="ltr"> <sup>13</sup> </strong>
                                        <span class="verse_text_source">if we are unfaithful, he remains faithful, for he cannot deny himself."</span>
                                        <span class="verse_text_original">Klo tong tra setia, De tetap setia karena De tra bisa menyangkal diri.</span>
                                    </p>
                                    <p class="verse_text" data-verse="14"> <strong class="ltr"> <sup>14</sup> </strong>
                                        <span class="verse_text_source">Keep reminding them of these things. Warn them before God not to quarrel about words. Because of this there is nothing useful. Because of this there is destruction for those who listen.</span>
                                        <span class="verse_text_original">Selalu kasi ingat dong di hadapan Allah, supaya dong jangan berdebat tentang Firman karena itu akan bikin kacau orang yang dengar,</span>
                                    </p>
                                </div>
                                <div class="editor_area flex_middle" dir="ltr">
                                    <p class="original_verse" data-verse="11">Apa yang sa bilang ini, bisa dipercaya: kalo tong mau mati untuk Dia, torang juga akan hidup bersama deng Dia.</p>
                                    <p class="original_verse" data-verse="12">Apalagi kalo tong bertahan , tong juga akan ditinggikan deng Dia. Klo tong menyangkal Dia, Dia juga akan menyangkal ketong,</p>
                                    <p class="original_verse" data-verse="13">Klo tong tra setia, De tetap setia karena De tra bisa menyangkal diri.</p>
                                    <p class="original_verse" data-verse="14">Selalu kasi ingat dong di hadapan Allah, supaya dong jangan berdebat tentang Firman karena itu akan bikin kacau orang yang dengar,</p>

                                    <div class="vnote">
                                        <div class="verse_block flex_chunk" data-verse="11"> <p class="target_verse" data-verse="11">Apa yang sa bilang ini, bisa dipercaya: kalo tong mau mati untuk Dia, torang juga akan hidup bersama deng Dia.</p> </div>
                                        <div class="verse_block flex_chunk" data-verse="12"> <p class="target_verse" data-verse="12">Apalagi kalo tong bertahan , tong juga akan ditinggikan deng Dia. Klo tong menyangkal Dia, Dia juga akan menyangkal ketong,</p> </div>
                                        <div class="verse_block flex_chunk" data-verse="13"> <p class="target_verse" data-verse="13">Klo tong tra setia, De tetap setia karena De tra bisa menyangkal diri.</p> </div>
                                        <div class="verse_block flex_chunk" data-verse="14"> <p class="target_verse" data-verse="14">Selalu kasi ingat dong di hadapan Allah, supaya dong jangan berdebat tentang Firman karena itu akan bikin kacau orang yang dengar,</p> </div>
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
                                    <p class="verse_text" data-verse="15"> <strong class="ltr"> <sup>15</sup> </strong>
                                        <span class="verse_text_source">Do your best to present yourself to God as one approved, a worker who has no reason to be ashamed, who accurately teaches the word of truth.</span>
                                        <span class="verse_text_original">Lakukan yang paling baik itu adalah persembahan yang Tuhan terima, jadi pekerja trada alasan untuk dapat kasi malu, yang ajar kebeneran Firman dengan pas.</span>
                                    </p>
                                    <p class="verse_text" data-verse="16"> <strong class="ltr"> <sup>16</sup> </strong>
                                        <span class="verse_text_source">Avoid profane talk, which leads to more and more godlessness.</span>
                                        <span class="verse_text_original">Hindari omong kosong dan tra bersih yang nanti jadi tra baik.</span>
                                    </p>
                                    <p class="verse_text" data-verse="17"> <strong class="ltr"> <sup>17</sup> </strong>
                                        <span class="verse_text_source">Their talk will spread like gangrene. Among whom are Hymenaeus and Philetus.</span>
                                        <span class="verse_text_original">Perkataan dong akan menyebar seperti kangker. Diantara dong itu ada Himeneus dan Filetus.</span>
                                    </p>
                                </div>
                                <div class="editor_area flex_middle" dir="ltr">
                                    <p class="original_verse" data-verse="15">Lakukan yang paling baik itu adalah persembahan yang Tuhan terima, jadi pekerja trada alasan untuk dapat kasi malu, yang ajar kebeneran Firman dengan pas.</p>
                                    <p class="original_verse" data-verse="16">Hindari omong kosong dan tra bersih yang nanti jadi tra baik.</p>
                                    <p class="original_verse" data-verse="17">Perkataan dong akan menyebar seperti kangker. Diantara dong itu ada Himeneus dan Filetus.</p>

                                    <div class="vnote">
                                        <div class="verse_block flex_chunk" data-verse="15"> <p class="target_verse" data-verse="15">Lakukan yang paling baik itu adalah persembahan yang Tuhan terima, jadi pekerja trada alasan untuk dapat kasi malu, yang ajar kebeneran Firman dengan pas.</p> </div>
                                        <div class="verse_block flex_chunk" data-verse="16"> <p class="target_verse" data-verse="16">Hindari omong kosong dan tra bersih yang nanti jadi tra baik.</p> </div>
                                        <div class="verse_block flex_chunk" data-verse="17"> <p class="target_verse" data-verse="17">Perkataan dong akan menyebar seperti kangker. Diantara dong itu ada Himeneus dan Filetus.</p> </div>
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
                                    <p class="verse_text" data-verse="18"> <strong class="ltr"> <sup>18</sup> </strong>
                                        <span class="verse_text_source">These are men who have missed the truth. They say that the resurrection has already happened. They overturn the faith of some.</span>
                                        <span class="verse_text_original">Dong adalah orang-orang yang sudah tidak benar. dong katakan kebangkitan sudah terjadi, dong putar balik iman dari berapa orang tu.</span>
                                    </p>
                                    <p class="verse_text" data-verse="19"> <strong class="ltr"> <sup>19</sup> </strong>
                                        <span class="verse_text_source">However, the firm foundation of God stands. It has this inscription: "The Lord knows those who are his" and "Everyone who names the name of the Lord must depart from unrighteousness."</span>
                                        <span class="verse_text_original">Biar begitu, Allah pu fondasi kuat tetap berdiri. ada piagam dengan tulisan yang bilang" Tuhan kenal dong Dia pu milik." . dan orang yang percaya Tuhan harus kasi tinggal yang tra benar.</span>
                                    </p>
                                    <p class="verse_text" data-verse="20"> <strong class="ltr"> <sup>20</sup> </strong>
                                        <span class="verse_text_source">In a wealthy home, there are not only containers of gold and silver. There are also containers of wood and clay. Some of these are for honorable use, and some for dishonorable.</span>
                                        <span class="verse_text_original">Dalam rumah kaya bukan saja ada emas dan perak tapi juga ada kotak-kotak kayu sama tanah liat. barang itu di pake untuk hal-hal yang terhormat, dan ada juga untuk hal-hal tra terhormat.</span>
                                    </p>
                                </div>
                                <div class="editor_area flex_middle" dir="ltr">
                                    <p class="original_verse" data-verse="18">Dong adalah orang-orang yang sudah tidak benar. dong katakan kebangkitan sudah terjadi, dong putar balik iman dari berapa orang tu.</p>
                                    <p class="original_verse" data-verse="19">Biar begitu, Allah pu fondasi kuat tetap berdiri. ada piagam dengan tulisan yang bilang" Tuhan kenal dong Dia pu milik." . dan orang yang percaya Tuhan harus kasi tinggal yang tra benar.</p>
                                    <p class="original_verse" data-verse="20">Dalam rumah kaya bukan saja ada emas dan perak tapi juga ada kotak-kotak kayu sama tanah liat. barang itu di pake untuk hal-hal yang terhormat, dan ada juga untuk hal-hal tra terhormat.</p>

                                    <div class="vnote">
                                        <div class="verse_block flex_chunk" data-verse="18"> <p class="target_verse" data-verse="18">Dong adalah orang-orang yang sudah tidak benar. dong katakan kebangkitan sudah terjadi, dong putar balik iman dari berapa orang tu.</p> </div>
                                        <div class="verse_block flex_chunk" data-verse="19"> <p class="target_verse" data-verse="19">Biar begitu, Allah pu fondasi kuat tetap berdiri. ada piagam dengan tulisan yang bilang" Tuhan kenal dong Dia pu milik." . dan orang yang percaya Tuhan harus kasi tinggal yang tra benar.</p></div>
                                        <div class="verse_block flex_chunk" data-verse="20"> <p class="target_verse" data-verse="20">Dalam rumah kaya bukan saja ada emas dan perak tapi juga ada kotak-kotak kayu sama tanah liat. barang itu di pake untuk hal-hal yang terhormat, dan ada juga untuk hal-hal tra terhormat.</p> </div>
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
                                    <p class="verse_text" data-verse="21"> <strong class="ltr"> <sup>21</sup> </strong>
                                        <span class="verse_text_source">If someone cleans himself from dishonorable use, he is an honorable container. He is set apart, useful to the Master, and prepared for every good work.</span>
                                        <span class="verse_text_original">Jika satu orang kasi bersih de pu diri dari yang tra terhormat, de itu bejana yang terhormat. de dipilih , dipake untuk tuannya, dan de disiapkan untuk semua perbuatan yang baik.</span>
                                    </p>
                                    <p class="verse_text" data-verse="22"> <strong class="ltr"> <sup>22</sup> </strong>
                                        <span class="verse_text_source">Flee youthful lusts. Pursue righteousness, faith, love, and peace with those who call on the Lord out of a clean heart.</span>
                                        <span class="verse_text_original">Jauhi sudah dari nafsu anak-anak muda, kejar itu kebenaran, iman, kasih, dan damai, sama-sama deng dong yang panggil Tuhan dengan hati yang bersih.</span>
                                    </p>
                                    <p class="verse_text" data-verse="23"> <strong class="ltr"> <sup>23</sup> </strong>
                                        <span class="verse_text_source">But refuse foolish and ignorant questions. You know that they give birth to arguments.</span>
                                        <span class="verse_text_original">Tapi tolak sudah pertanyaan-pertanyaan bodok. Kam tahu itu semua nanti jadi sebab baku tengkar.</span>
                                    </p>
                                </div>
                                <div class="editor_area flex_middle" dir="ltr">
                                    <p class="original_verse" data-verse="21">Jika satu orang kasi bersih de pu diri dari yang tra terhormat, de itu bejana yang terhormat. de dipilih , dipake untuk tuannya, dan de disiapkan untuk semua perbuatan yang baik.</p>
                                    <p class="original_verse" data-verse="22">Jauhi sudah dari nafsu anak-anak muda, kejar itu kebenaran, iman, kasih, dan damai, sama-sama deng dong yang panggil Tuhan dengan hati yang bersih.</p>
                                    <p class="original_verse" data-verse="23">Tapi tolak sudah pertanyaan-pertanyaan bodok. Kam tahu itu semua nanti jadi sebab baku tengkar.</p>

                                    <div class="vnote">
                                        <div class="verse_block flex_chunk" data-verse="21"> <p class="target_verse" data-verse="21">Jika satu orang kasi bersih de pu diri dari yang tra terhormat, de itu bejana yang terhormat. de dipilih , dipake untuk tuannya, dan de disiapkan untuk semua perbuatan yang baik.</p> </div>
                                        <div class="verse_block flex_chunk" data-verse="22"> <p class="target_verse" data-verse="22">Jauhi sudah dari nafsu anak-anak muda, kejar itu kebenaran, iman, kasih, dan damai, sama-sama deng dong yang panggil Tuhan dengan hati yang bersih.</p> </div>
                                        <div class="verse_block flex_chunk" data-verse="23"> <p class="target_verse" data-verse="23">Tapi tolak sudah pertanyaan-pertanyaan bodok. Kam tahu itu semua nanti jadi sebab baku tengkar.</p> </div>
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
                                    <p class="verse_text" data-verse="24"> <strong class="ltr"> <sup>24</sup> </strong>
                                        <span class="verse_text_source">The Lord's servant must not quarrel. Instead he must be gentle toward all, able to teach, and patient.</span>
                                        <span class="verse_text_original">Orang yang melayani Tuhan tra boleh bertengkar tapi harus lemah lembut pada semua dong, Dong harus pintar mengajar, sabar</span>
                                    </p>
                                    <p class="verse_text" data-verse="25"> <strong class="ltr"> <sup>25</sup> </strong>
                                        <span class="verse_text_source">He must in meekness educate those who oppose him. God may perhaps give them repentance for the knowledge of the truth.</span>
                                        <span class="verse_text_original">de kasi ajaran deng lemah lembut sama dong yang melawan dia. mungkin Allah kasi kesempatan untuk dong bertobat pada pengetahuan akan kebenaran.</span>
                                    </p>
                                    <p class="verse_text" data-verse="26"> <strong class="ltr"> <sup>26</sup> </strong>
                                        <span class="verse_text_source">They may become sober again and leave the devil's trap, after they have been captured by him for his will.</span>
                                        <span class="verse_text_original">mungkin dong sadar kembali dan kasi tinggal jerat iblis setelah selama ini dong ditawan untuk ikut perintahnya.</span>
                                    </p>
                                </div>
                                <div class="editor_area flex_middle" dir="ltr">
                                    <p class="original_verse" data-verse="24">Orang yang melayani Tuhan tra boleh bertengkar tapi harus lemah lembut pada semua dong, Dong harus pintar mengajar, sabar</p>
                                    <p class="original_verse" data-verse="25">de kasi ajaran deng lemah lembut sama dong yang melawan dia. mungkin Allah kasi kesempatan untuk dong bertobat pada pengetahuan akan kebenaran.</p>
                                    <p class="original_verse" data-verse="26">mungkin dong sadar kembali dan kasi tinggal jerat iblis setelah selama ini dong ditawan untuk ikut perintahnya.</p>

                                    <div class="vnote">
                                        <div class="verse_block flex_chunk" data-verse="24"> <p class="target_verse" data-verse="24">Orang yang melayani Tuhan tra boleh bertengkar tapi harus lemah lembut pada semua dong, Dong harus pintar mengajar, sabar</p> </div>
                                        <div class="verse_block flex_chunk" data-verse="25"> <p class="target_verse" data-verse="25">de kasi ajaran deng lemah lembut sama dong yang melawan dia. mungkin Allah kasi kesempatan untuk dong bertobat pada pengetahuan akan kebenaran.</p></div>
                                        <div class="verse_block flex_chunk" data-verse="26"> <p class="target_verse" data-verse="26">mungkin dong sadar kembali dan kasi tinggal jerat iblis setelah selama ini dong ditawan untuk ikut perintahnya.</p> </div>
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
            </div>

            <div class="main_content_footer row">
                <form action="" method="post">
                    <div class="form-group">
                        <div class="main_content_confirm_desc"><?php echo __("confirm_finished")?></div>
                        <label><input name="confirm_step" id="confirm_step" value="1" type="checkbox"> <?php echo __("confirm_yes")?></label>
                    </div>

                    <button id="checker_ready" class="btn btn-warning" disabled>
                        <?php echo __("ready_to_check")?>
                    </button>
                    <button id="next_step" class="btn btn-primary" disabled="disabled">
                        <?php echo __($data["next_step"])?>
                    </button>
                </form>
                <div class="step_right"></div>
            </div>
        </div>
    </div>
</div>

<div class="content_help closed">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps is_checker_page_help isPeer">
            <div class="help_name_steps"><span><?php echo __("step_num", ["step_number" => 4])?>:</span> <?php echo __(EventCheckSteps::KEYWORD_CHECK)?></div>
            <div class="help_descr_steps">
                <ul></ul>
                <div class="show_tutorial_popup"> >>> <?php echo __("show_more")?></div>
            </div>
        </div>

        <div class="event_info is_checker_page_help isPeer">
            <div class="participant_info">
                <div class="participant_name">
                    <span><?php echo __("your_checker") ?>:</span>
                    <span class="checker_name_span">
                                Genry M.
                            </span>
                </div>
                <div class="additional_info">
                    <a href="/events/demo-revision/information"><?php echo __("event_info") ?></a>
                </div>
            </div>
        </div>

        <div class="tr_tools">
            <?php
            renderTw($data["twLangID"]);
            renderRubric();
            ?>
        </div>

        <div class="checker_view">
            <a href="/events/demo-revision/keyword_check"><?php echo __("checker_other_view", [1]) ?></a>
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
            <h3><?php echo __(EventCheckSteps::KEYWORD_CHECK)?></h3>
            <ul></ul>
        </div>
    </div>
</div>

<!-- Data for tools -->
<input type="hidden" id="bookCode" value="2ti">
<input type="hidden" id="chapter" value="2">
<input type="hidden" id="tw_lang" value="en">
<input type="hidden" id="totalVerses" value="26">
<input type="hidden" id="targetLang" value="en">

<script type="text/javascript" src="<?php echo template_url("js/diff_match_patch.js?v=2")?>"></script>
<script type="text/javascript" src="<?php echo template_url("js/diff.js?v=7")?>"></script>
<script>
    isLevel2 = true;
    isChecker = true;

    $(document).ready(function () {
        $(".original_verse").each(function() {
            const verse = $(this).data("verse");
            const chkVersion = $(".target_verse[data-verse='"+verse+"']");
            const chkText = chkVersion.text();

            diff_plain($(this).text(), unEscapeStr(chkText), $(this));
        });

        $(".compare_notes input").change(function () {
            const active = $(this).prop('checked');

            if (active) {
                $(".vnote").hide();
                $(".original_verse").show();
            } else {
                $(".original_verse").hide();
                $(".vnote").show();
            }
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

        $("#next_step").click(function (e) {
            renderConfirmPopup(Language.checkerConfirmTitle, Language.checkerConfirm,
                function () {
                    window.location.href = '/events/demo-revision/content_review';
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
