<?php
require(app_path() . "Views/Components/CommentEditor.php");
require(app_path() . "Views/Components/HelpTools.php");
?>

<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="action_type_container">
            <div class="demo_title"><?php echo __("demo") . " (".__("review_events").")" ?></div>
        </div>
        <div class="main_content_title">
            <div><?php echo __("step_num", ["step_number" => 1]) . ": " . __("peer-review-l3_full")?></div>
        </div>
    </div>

    <div class="">
        <div class="main_content">
            <div class="main_content_text">
                <h4>Papuan Malay - <?php echo __("ulb") ?> - <?php echo __("new_test") ?> - <span class="book_name">2 Timothy 2:1-26</span></h4>

                <div id="my_notes_content" class="my_content">
                    <div class="note_chunk l3 flex_container">
                        <div class="scripture_compare_alt flex_left" dir="ltr">
                            <p>
                                <strong class="ltr"><sup>1</sup></strong>
                                <span>You therefore, my child, be strengthened in the grace that is in Christ Jesus.</span>
                            </p>
                            <p>
                                <strong class="ltr"><sup>2</sup></strong>
                                <span>The things you heard from me among many witnesses, entrust them to faithful people
                                    who will be able to teach others also.</span>
                            </p>
                        </div>
                        <div class="vnote l3 font_en flex_middle" dir="ltr" style="padding-right: 20px">
                            <div class="verse_block">
                                <p>
                                    <strong><sup>1</sup></strong>
                                    <span class="targetVerse" data-orig-verse="1">Jadi begitu, anakku kuat sudah dengan
                                        anugerah di dalam Kristus Yesus.</span>
                                </p>
                            </div>
                            <div class="verse_block">
                                <p>
                                    <strong><sup>2</sup></strong>
                                    <span class="targetVerse" data-orig-verse="2">Dan banyak hal yang ko dengar dari sa
                                        deng saksi yang banyak itu, beri percaya itu sama orang-orang yang setia, supaya
                                        dong dapat mengajar dong yang lain juga.</span>
                                </p>
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
                    <div class="note_chunk l3 flex_container">
                        <div class="scripture_compare_alt flex_left" dir="ltr">
                            <p>
                                <strong class="ltr"><sup>3</sup></strong>
                                <span>Suffer hardship with me, as a good soldier of Christ Jesus.</span>
                            </p>
                            <p>
                                <strong class="ltr"><sup>4</sup></strong>
                                <span>No soldier serves while entangled in the affairs of this life, so that he may
                                    please his superior officer.</span>
                            </p>
                            <p>
                                <strong class="ltr"><sup>5</sup></strong>
                                <span>Also, if someone competes as an athlete, he is not crowned unless he competes by the rules.</span>
                            </p>
                        </div>
                        <div class="vnote l3 font_en flex_middle" dir="ltr" style="padding-right: 20px">
                            <div class="verse_block">
                                <p>
                                    <strong><sup>3</sup></strong>
                                    <span class="targetVerse" data-orig-verse="3">Mari, tong sama sa dalam penderitaan
                                        jadi prajurit Kristus Yesus yang baik.</span>
                                </p>
                            </div>
                            <div class="verse_block">
                                <p>
                                    <strong><sup>4</sup></strong>
                                    <span class="targetVerse" data-orig-verse="4">Trada satu orang tentara yang kerja
                                        sambil sibuk dengan de pu urusan hidup supaya de bisa buat de pu komandan senang.</span>
                                </p>
                            </div>
                            <div class="verse_block">
                                <p>
                                    <strong><sup>5</sup></strong>
                                    <span class="targetVerse" data-orig-verse="5">Begitu juga dengan atlit , tra akan
                                        terima mahkota kalo tra ikut aturan dalam lomba.</span>
                                </p>
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
                    <div class="note_chunk l3 flex_container">
                        <div class="scripture_compare_alt flex_left" dir="ltr">
                            <p>
                                <strong class="ltr"><sup>6</sup></strong>
                                <span>It is necessary that the hardworking farmer receive his share of the crops first.</span>
                            </p>
                            <p>
                                <strong class="ltr"><sup>7</sup></strong>
                                <span>Think about what I am saying, for the Lord will give you understanding in everything.</span>
                            </p>
                        </div>
                        <div class="vnote l3 font_en flex_middle" dir="ltr" style="padding-right: 20px">
                            <div class="verse_block">
                                <p>
                                    <strong><sup>6</sup></strong>
                                    <span class="targetVerse" data-orig-verse="6">Petani dong yang kerja keras akan terima
                                        hasil yang pertama,</span>
                                </p>
                            </div>
                            <div class="verse_block">
                                <p>
                                    <strong><sup>7</sup></strong>
                                    <span class="targetVerse" data-orig-verse="7">Ingat apa yang sa bilang, karena Tuhan
                                        akan kasi ko pengertian untuk mengerti semua ini,</span>
                                </p>
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="note_chunk l3 flex_container">
                        <div class="scripture_compare_alt flex_left" dir="ltr">
                            <p>
                                <strong class="ltr"><sup>8</sup></strong>
                                <span>Remember Jesus Christ, from David's seed, who was raised from the dead. This is
                                    according to my gospel message,</span>
                            </p>
                            <p>
                                <strong class="ltr"><sup>9</sup></strong>
                                <span>for which I am suffering to the point of being bound with chains as a criminal.
                                    But the word of God is not bound.</span>
                            </p>
                            <p>
                                <strong class="ltr"><sup>10</sup></strong>
                                <span>Therefore I endure all things for those who are chosen, so that they also may obtain
                                    the salvation that is in Christ Jesus, with eternal glory.</span>
                            </p>
                        </div>
                        <div class="vnote l3 font_en flex_middle" dir="ltr" style="padding-right: 20px">
                            <div class="verse_block">
                                <p>
                                    <strong><sup>8</sup></strong>
                                    <span class="targetVerse" data-orig-verse="8">Ingat: Yesus Kristus, keturunan Daud,
                                        su bangkit dari kematian. ini su sesuai dengan pesan Injil yang sa percaya.</span>
                                </p>
                            </div>
                            <div class="verse_block">
                                <p>
                                    <strong><sup>9</sup></strong>
                                    <span class="targetVerse" data-orig-verse="9">Sampe pada titik penderitaan karna diikat
                                        rantai seperti kriminal. tapi firman Allah tra diikat deng rantai.</span>
                                </p>
                            </div>
                            <div class="verse_block">
                                <p>
                                    <strong><sup>10</sup></strong>
                                    <span class="targetVerse" data-orig-verse="10">Jadi sa bertahan untuk orang-orang yang
                                        Tuhan pilih, supaya dong dapat keselamatan yang kekal dalam Kristus Yesus, deng
                                        kemuliaan yang abadi..</span>
                                </p>
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="note_chunk l3 flex_container">
                        <div class="scripture_compare_alt flex_left" dir="ltr">
                            <p>
                                <strong class="ltr"><sup>11</sup></strong>
                                <span>This is a trustworthy saying: "If we have died with him, we will also live with him. </span>
                            </p>
                            <p>
                                <strong class="ltr"><sup>12</sup></strong>
                                <span>If we endure, we will also reign with him. If we deny him, he also will deny us. </span>
                            </p>
                            <p>
                                <strong class="ltr"><sup>13</sup></strong>
                                <span>if we are unfaithful, he remains faithful, for he cannot deny himself." </span>
                            </p>
                        </div>
                        <div class="vnote l3 font_en flex_middle" dir="ltr" style="padding-right: 20px">
                            <div class="verse_block">
                                <p>
                                    <strong><sup>11</sup></strong>
                                    <span class="targetVerse" data-orig-verse="11">Apa yang sa bilang ini, bisa dipercaya:
                                        kalo tong mau mati untuk Dia, torang juga akan hidup bersama deng Dia.</span>
                                </p>
                            </div>
                            <div class="verse_block">
                                <p>
                                    <strong><sup>12</sup></strong>
                                    <span class="targetVerse" data-orig-verse="12">Apalagi kalo tong bertahan, tong juga
                                        akan ditinggikan deng Dia. Klo tong menyangkal Dia, Dia juga akan menyangkal ketong,</span>
                                </p>
                            </div>
                            <div class="verse_block">
                                <p>
                                    <strong><sup>13</sup></strong>
                                    <span class="targetVerse" data-orig-verse="13">Klo tong tra setia, De tetap setia karena
                                        De tra bisa menyangkal diri.</span>
                                </p>
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="note_chunk l3 flex_container">
                        <div class="scripture_compare_alt flex_left" dir="ltr">
                            <p>
                                <strong class="ltr"><sup>14</sup></strong>
                                <span>Keep reminding them of these things. Warn them before God against quarreling
                                    about words; it is of no value, and only ruins those who listen.
                                    <span data-toggle="tooltip" data-placement="auto right" title=""
                                          class="booknote mdi mdi-bookmark" data-original-title=" Some versions read,
                                          Warn them before the Lord . "></span>
                                </span>
                            </p>
                            <p>
                                <strong class="ltr"><sup>15</sup></strong>
                                <span>Do your best to present yourself to God as one approved, a worker who has no
                                    reason to be ashamed, who accurately teaches the word of truth.</span>
                            </p>
                        </div>
                        <div class="vnote l3 font_en flex_middle" dir="ltr" style="padding-right: 20px">
                            <div class="verse_block">
                                <p>
                                    <strong><sup>14</sup></strong>
                                    <span class="targetVerse" data-orig-verse="14">Selalu kasi ingat dong di hadapan Allah,
                                        supaya dong jangan berdebat tentang Firman karena itu akan bikin kacau orang yang dengar,</span>
                                </p>
                            </div>
                            <div class="verse_block">
                                <p>
                                    <strong><sup>15</sup></strong>
                                    <span class="targetVerse" data-orig-verse="15">Lakukan yang paling baik itu adalah
                                        persembahan yang Tuhan terima, jadi pekerja trada alasan untuk dapat kasi malu,
                                        yang ajar kebeneran Firman dengan pas.</span>
                                </p>
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="note_chunk l3 flex_container">
                        <div class="scripture_compare_alt flex_left" dir="ltr">
                            <p>
                                <strong class="ltr"><sup>16</sup></strong>
                                <span>Avoid profane talk, which leads to more and more godlessness.</span>
                            </p>
                            <p>
                                <strong class="ltr"><sup>17</sup></strong>
                                <span>Their talk will spread like cancer. Among them are Hymenaeus and Philetus,</span>
                            </p>
                            <p>
                                <strong class="ltr"><sup>18</sup></strong>
                                <span>who have gone astray from the truth. They say that the resurrection has already
                                    happened, and they destroy the faith of some.</span>
                            </p>
                        </div>
                        <div class="vnote l3 font_en flex_middle" dir="ltr" style="padding-right: 20px">
                            <div class="verse_block">
                                <p>
                                    <strong><sup>16</sup></strong>
                                    <span class="targetVerse" data-orig-verse="16">Hindari omong kosong dan tra bersih
                                        yang nanti jadi tra baik.</span>
                                </p>
                            </div>
                            <div class="verse_block">
                                <p>
                                    <strong><sup>17</sup></strong>
                                    <span class="targetVerse" data-orig-verse="17">Perkataan dong akan menyebar seperti
                                        kangker. Diantara dong itu ada Himeneus dan Filetus.</span>
                                </p>
                            </div>
                            <div class="verse_block">
                                <p>
                                    <strong><sup>18</sup></strong>
                                    <span class="targetVerse" data-orig-verse="18">Dong adalah orang-orang yang sudah
                                        tidak benar. dong katakan kebangkitan sudah terjadi, dong putar balik iman dari
                                        berapa orang tu.</span>
                                </p>
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="note_chunk l3 flex_container">
                        <div class="scripture_compare_alt flex_left" dir="ltr">
                            <p>
                                <strong class="ltr"><sup>19</sup></strong> <span>However, the firm foundation of God stands.
                                    It has this inscription: "The Lord knows those who are his" and "Everyone who names the
                                    name of the Lord must depart from unrighteousness."</span>
                            </p>
                            <p>
                                <strong class="ltr"><sup>20</sup></strong> <span>In a wealthy home, there are not only
                                    containers of gold and silver. There are also containers of wood and clay. Some of
                                    these are for honorable use, and some for dishonorable.</span>
                            </p>
                            <p>
                                <strong class="ltr"><sup>21</sup></strong> <span>If someone cleans himself from dishonorable
                                    use, he is an honorable container. He is set apart, useful to the Master, and prepared
                                    for every good work.</span>
                            </p>
                        </div>
                        <div class="vnote l3 font_en flex_middle" dir="ltr" style="padding-right: 20px">
                            <div class="verse_block">
                                <p>
                                    <strong><sup>19</sup></strong>
                                    <span class="targetVerse" data-orig-verse="19">Biar begitu, Allah pu fondasi kuat
                                        tetap berdiri. ada piagam dengan tulisan yang bilang" Tuhan kenal dong Dia pu milik." .
                                        dan orang yang percaya Tuhan harus kasi tinggal yang tra benar.</span>
                                </p>
                            </div>
                            <div class="verse_block">
                                <p>
                                    <strong><sup>20</sup></strong>
                                    <span class="targetVerse" data-orig-verse="20">Dalam rumah kaya bukan saja ada emas
                                        dan perak tapi juga ada kotak-kotak kayu sama tanah liat. barang itu di pake
                                        untuk hal-hal yang terhormat, dan ada juga untuk hal-hal tra terhormat.</span>
                                </p>
                            </div>
                            <div class="verse_block">
                                <p>
                                    <strong><sup>21</sup></strong>
                                    <span class="targetVerse" data-orig-verse="21">Jika satu orang kasi bersih de pu
                                        diri dari yang tra terhormat, de itu bejana yang terhormat. de dipilih , dipake
                                        untuk tuannya, dan de disiapkan untuk semua perbuatan yang baik.</span>
                                </p>
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="note_chunk l3 flex_container">
                        <div class="scripture_compare_alt flex_left" dir="ltr">
                            <p>
                                <strong class="ltr"><sup>22</sup></strong>
                                <span>Flee youthful lusts. Pursue righteousness, faith, love, and peace with those who
                                    call on the Lord out of a clean heart.</span>
                            </p>
                            <p>
                                <strong class="ltr"><sup>23</sup></strong>
                                <span>But refuse foolish and ignorant questions. You know that they give birth to arguments.</span>
                            </p>
                        </div>
                        <div class="vnote l3 font_en flex_middle" dir="ltr" style="padding-right: 20px">
                            <div class="verse_block">
                                <p>
                                    <strong><sup>22</sup></strong>
                                    <span class="targetVerse" data-orig-verse="22">Jauhi sudah dari nafsu anak-anak muda,
                                        kejar itu kebenaran, iman, kasih, dan damai, sama-sama deng dong yang panggil
                                        Tuhan dengan hati yang bersih.</span>
                                </p>
                            </div>
                            <div class="verse_block">
                                <p>
                                    <strong><sup>23</sup></strong>
                                    <span class="targetVerse" data-orig-verse="23">Tapi tolak sudah pertanyaan-pertanyaan
                                        bodok. Kam tahu itu semua nanti jadi sebab baku tengkar.</span>
                                </p>
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="note_chunk l3 flex_container">
                        <div class="scripture_compare_alt flex_left" dir="ltr">
                            <p>
                                <strong class="ltr"><sup>24</sup></strong>
                                <span>The Lord's servant must not quarrel. Instead he must be gentle toward all, able to
                                    teach, and patient.</span>
                            </p>
                            <p>
                                <strong class="ltr"><sup>25</sup></strong>
                                <span>He must in meekness educate those who oppose him. God may perhaps give them
                                    repentance for the knowledge of the truth.</span>
                            </p>
                            <p>
                                <strong class="ltr"><sup>26</sup></strong>
                                <span>They may become sober again and leave the devil's trap, after they have been
                                    captured by him for his will.</span>
                            </p>
                        </div>
                        <div class="vnote l3 font_en flex_middle" dir="ltr" style="padding-right: 20px">
                            <div class="verse_block">
                                <p>
                                    <strong><sup>24</sup></strong>
                                    <span class="targetVerse" data-orig-verse="24">Orang yang melayani Tuhan tra boleh
                                        bertengkar tapi harus lemah lembut pada semua dong, Dong harus pintar mengajar, sabar</span>
                                </p>
                            </div>
                            <div class="verse_block">
                                <p>
                                    <strong><sup>25</sup></strong>
                                    <span class="targetVerse" data-orig-verse="25">de kasi ajaran deng lemah lembut sama
                                        dong yang melawan dia. mungkin Allah kasi kesempatan untuk dong bertobat pada
                                        pengetahuan akan kebenaran.</span>
                                </p>
                            </div>
                            <div class="verse_block">
                                <p>
                                    <strong><sup>26</sup></strong>
                                    <span class="targetVerse" data-orig-verse="26">mungkin dong sadar kembali dan kasi
                                        tinggal jerat iblis setelah selama ini dong ditawan untuk ikut perintahnya.</span>
                                </p>
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
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

                    <button id="checker_ready" class="btn btn-warning" disabled>
                        <?php echo __("ready_to_check")?>
                    </button>
                    <button id="next_step" class="btn btn-primary" disabled="disabled">
                        <?php echo __($data["next_step"])?>
                    </button>
                </form>
                <div class="step_right"></div>
            </div>
            <div class="step_right alt"><?php echo __("step_num", ["step_number" => 1])?></div>
        </div>
    </div>
</div>

<div class="content_help closed">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps is_checker_page_help isPeer">
            <div class="help_name_steps">
                <span><?php echo __("step_num", ["step_number" => 1])?>: </span>
                <?php echo __("peer-review-l3")?>
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
                <div class="show_tutorial_popup"> >>> <?php echo __("show_more")?></div>
            </div>
        </div>

        <div class="event_info is_checker_page_help isPeer">
            <div class="participant_info">
                <div class="participant_name">
                    <span><?php echo __("your_checker") ?>:</span>
                    <span class="checker_name_span">
                                Mark P.
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
            <a href="/events/demo-review/peer_review_l3"><?php echo __("checker_other_view", [1]) ?></a>
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
            <h3><?php echo __("peer-review-l3_full")?></h3>
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
            window.location.href = '/events/demo-review/peer_edit_l3';
            return false;
        });

        $(".ttools_panel .word_def").each(function() {
            let html = convertRcLinks($(this).html());
            $(this).html(html);
        });
    });
</script>
