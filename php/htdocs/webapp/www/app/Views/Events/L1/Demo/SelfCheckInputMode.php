<?php

use Helpers\Constants\EventSteps;
use Helpers\Constants\InputMode;

$addUri = $data["inputMode"] != InputMode::NORMAL ? "-" . $data["inputMode"] : "";

require(app_path() . "Views/Components/CommentEditor.php");
require(app_path() . "Views/Components/FootnotesEditor.php");
require(app_path() . "Views/Components/HelpTools.php");
?>

<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="action_type_container">
            <div class="demo_title"><?php echo __("demo") . " (".__($data["inputMode"]).")" ?></div>
        </div>
        <div class="main_content_title">
            <div><?php echo __("step_num", ["step_number" => 2]) . ": " . __(EventSteps::SELF_CHECK)?></div>
        </div>
    </div>

    <div class="main_content">
        <form action="" method="post" id="main_form">
            <div class="main_content_text row" style="padding-left: 15px">

                <h4>Papuan Malay - <?php echo __("ulb") ?> - <?php echo __("new_test") ?> - <span class='book_name'>2 Timothy 2:1-26</span></h4>

                <div class="source_mode">
                    <label>
                        <?php echo __("show_source") ?>
                        <input type="checkbox" autocomplete="off" checked
                               data-toggle="toggle"
                               data-on="ON"
                               data-off="OFF" />
                    </label>
                </div>

                <div class="flex_container no_padding">
                    <div class="flex_left">
                        <p style="margin: 0 0 10px;" class="verse_p" data-verse="1"><strong><sup>1</sup></strong> You therefore, my child, be strengthened in the grace that is in Christ Jesus.</p>
                        <p style="margin: 0 0 10px;" class="verse_p" data-verse="2"><strong><sup>2</sup></strong> The things you heard from me among many witnesses, entrust them to faithful people who will be able to teach others also.</p>
                        <p style="margin: 0 0 10px;" class="verse_p" data-verse="3"><strong><sup>3</sup></strong> Suffer hardship with me, as a good soldier of Christ Jesus.</p>
                        <p style="margin: 0 0 10px;" class="verse_p" data-verse="4"><strong><sup>4</sup></strong> No soldier serves while entangled in the affairs of this life, so that he may please his superior officer.</p>
                        <p style="margin: 0 0 10px;" class="verse_p" data-verse="5"><strong><sup>5</sup></strong> Also, if someone competes as an athlete, he is not crowned unless he competes by the rules.</p>
                        <p style="margin: 0 0 10px;" class="verse_p" data-verse="6"><strong><sup>6</sup></strong> It is necessary that the hardworking farmer receive his share of the crops first.</p>
                        <p style="margin: 0 0 10px;" class="verse_p" data-verse="7"><strong><sup>7</sup></strong> Think about what I am saying, for the Lord will give you understanding in everything.</p>
                        <p style="margin: 0 0 10px;" class="verse_p" data-verse="8"><strong><sup>8</sup></strong> Remember Jesus Christ, from David's seed, who was raised from the dead. This is according to my gospel message,</p>
                        <p style="margin: 0 0 10px;" class="verse_p" data-verse="9"><strong><sup>9</sup></strong> for which I am suffering to the point of being bound with chains as a criminal. But the word of God is not bound.</p>
                        <p style="margin: 0 0 10px;" class="verse_p" data-verse="10"><strong><sup>10</sup></strong> Therefore I endure all things for those who are chosen, so that they also may obtain the salvation that is in Christ Jesus, with eternal glory.</p>
                        <p style="margin: 0 0 10px;" class="verse_p" data-verse="11"><strong><sup>11</sup></strong> This is a trustworthy saying: "If we have died with him, we will also live with him. </p>
                        <p style="margin: 0 0 10px;" class="verse_p" data-verse="12"><strong><sup>12</sup></strong> If we endure, we will also reign with him. If we deny him, he also will deny us. </p>
                        <p style="margin: 0 0 10px;" class="verse_p" data-verse="13"><strong><sup>13</sup></strong> if we are unfaithful, he remains faithful, for he cannot deny himself." </p>
                        <p style="margin: 0 0 10px;" class="verse_p" data-verse="14"><strong><sup>14</sup></strong> Keep reminding them of these things. Warn them before God against quarreling about words; it is of no value, and only ruins those who listen. <span data-toggle="tooltip" data-placement="auto auto" title="" class="booknote mdi mdi-bookmark" data-original-title=" Some versions read, [ Warn them before the Lord]"></span></p>
                        <p style="margin: 0 0 10px;" class="verse_p" data-verse="15"><strong><sup>15</sup></strong> Do your best to present yourself to God as one approved, a worker who has no reason to be ashamed, who accurately teaches the word of truth.</p>
                        <p style="margin: 0 0 10px;" class="verse_p" data-verse="16"><strong><sup>16</sup></strong> Avoid profane talk, which leads to more and more godlessness.</p>
                        <p style="margin: 0 0 10px;" class="verse_p" data-verse="17"><strong><sup>17</sup></strong> Their talk will spread like cancer. Among them are Hymenaeus and Philetus,</p>
                        <p style="margin: 0 0 10px;" class="verse_p" data-verse="18"><strong><sup>18</sup></strong> who have gone astray from the truth. They say that the resurrection has already happened, and they destroy the faith of some.</p>
                        <p style="margin: 0 0 10px;" class="verse_p" data-verse="19"><strong><sup>19</sup></strong> However, the firm foundation of God stands. It has this inscription: "The Lord knows those who are his" and "Everyone who names the name of the Lord must depart from unrighteousness."</p>
                        <p style="margin: 0 0 10px;" class="verse_p" data-verse="20"><strong><sup>20</sup></strong> In a wealthy home, there are not only containers of gold and silver. There are also containers of wood and clay. Some of these are for honorable use, and some for dishonorable.</p>
                        <p style="margin: 0 0 10px;" class="verse_p" data-verse="21"><strong><sup>21</sup></strong> If someone cleans himself from dishonorable use, he is an honorable container. He is set apart, useful to the Master, and prepared for every good work.</p>
                        <p style="margin: 0 0 10px;" class="verse_p" data-verse="22"><strong><sup>22</sup></strong> Flee youthful lusts. Pursue righteousness, faith, love, and peace with those who call on the Lord out of a clean heart.</p>
                        <p style="margin: 0 0 10px;" class="verse_p" data-verse="23"><strong><sup>23</sup></strong> But refuse foolish and ignorant questions. You know that they give birth to arguments.</p>
                        <p style="margin: 0 0 10px;" class="verse_p" data-verse="24"><strong><sup>24</sup></strong> The Lord's servant must not quarrel. Instead he must be gentle toward all, able to teach, and patient.</p>
                        <p style="margin: 0 0 10px;" class="verse_p" data-verse="25"><strong><sup>25</sup></strong> He must in meekness educate those who oppose him. God may perhaps give them repentance for the knowledge of the truth.</p>
                        <p style="margin: 0 0 10px;" class="verse_p" data-verse="26"><strong><sup>26</sup></strong> They may become sober again and leave the devil's trap, after they have been captured by him for his will.</p>
                    </div>
                    <div class="flex_middle input_mode_list">
                        <div class="input_mode_verse flex_chunk" data-verse="1" data-id="34531">
                            <textarea style="min-height: 120px;" name="verses[1]" class="textarea input_mode_ta peer_verse_ta" >Jadi begitu, anakku kuat sudah dengan anugerah di dalam Kristus Yesus.</textarea>
                            <?php
                            $comments = [$data["comments"][0][0]];
                            $hasComments = !empty($comments);
                            $commentsNumber = sizeof($comments);
                            $myMemberID = 0;
                            require(app_path() . "Views/Components/Comments.php");
                            ?>
                        </div>
                        <div class="input_mode_verse flex_chunk" data-verse="2" data-id="34532">
                            <textarea style="min-height: 120px;" name="verses[2]" class="textarea input_mode_ta peer_verse_ta" >Dan banyak hal yang ko dengar dari sa deng saksi yang banyak itu, beri percaya itu sama orang-orang yang setia, supaya dong dapat mengajar dong yang lain juga.</textarea>
                            <?php $hasComments = false; require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                        <div class="input_mode_verse flex_chunk" data-verse="3" data-id="34533">
                            <textarea style="min-height: 120px;" name="verses[3]" class="textarea input_mode_ta peer_verse_ta" >Mari, tong sama sa dalam penderitaan jadi prajurit Kristus Yesus yang baik.</textarea>
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                        <div class="input_mode_verse flex_chunk" data-verse="4" data-id="34534">
                            <textarea style="min-height: 120px;" name="verses[4]" class="textarea input_mode_ta peer_verse_ta" >Trada satu orang tentara yang kerja sambil sibuk dengan de pu urusan hidup supaya de bisa buat de pu komandan senang.</textarea>
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                        <div class="input_mode_verse flex_chunk" data-verse="5" data-id="34535">
                            <textarea style="min-height: 120px;" name="verses[5]" class="textarea input_mode_ta peer_verse_ta" >Begitu juga dengan atlit , tra akan terima mahkota kalo tra ikut aturan dalam lomba.</textarea>
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                        <div class="input_mode_verse flex_chunk" data-verse="6" data-id="34536">
                            <textarea style="min-height: 120px;" name="verses[6]" class="textarea input_mode_ta peer_verse_ta" >Petani dong yang kerja keras akan terima hasil yang pertama,</textarea>
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                        <div class="input_mode_verse flex_chunk" data-verse="7" data-id="34537">
                            <textarea style="min-height: 120px;" name="verses[7]" class="textarea input_mode_ta peer_verse_ta" >Ingat apa yang sa bilang, karena Tuhan akan kasi ko pengertian untuk mengerti semua ini,</textarea>
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                        <div class="input_mode_verse flex_chunk" data-verse="8" data-id="34538">
                            <textarea style="min-height: 120px;" name="verses[8]" class="textarea input_mode_ta peer_verse_ta" >Ingat: Yesus Kristus, keturunan Daud, su bangkit dari kematian. ini su sesuai dengan pesan Injil yang sa percaya.</textarea>
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                        <div class="input_mode_verse flex_chunk" data-verse="9" data-id="34539">
                            <textarea style="min-height: 120px;" name="verses[9]" class="textarea input_mode_ta peer_verse_ta" >Sampe pada titik penderitaan karna diikat rantai seperti kriminal. tapi firman Allah tra diikat deng rantai.</textarea>
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                        <div class="input_mode_verse flex_chunk" data-verse="10" data-id="34540">
                            <textarea style="min-height: 120px;" name="verses[10]" class="textarea input_mode_ta peer_verse_ta" >Jadi sa bertahan untuk orang-orang yang Tuhan pilih, supaya dong dapat keselamatan yang kekal dalam Kristus Yesus, deng kemuliaan yang abadi..</textarea>
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                        <div class="input_mode_verse flex_chunk" data-verse="11" data-id="34541">
                            <textarea style="min-height: 120px;" name="verses[11]" class="textarea input_mode_ta peer_verse_ta" >Apa yang sa bilang ini, bisa dipercaya: kalo tong mau mati untuk Dia, torang juga akan hidup bersama deng Dia.</textarea>
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                        <div class="input_mode_verse flex_chunk" data-verse="12" data-id="34542">
                            <textarea style="min-height: 120px;" name="verses[12]" class="textarea input_mode_ta peer_verse_ta" >Apalagi kalo tong bertahan , tong juga akan ditinggikan deng Dia. Klo tong menyangkal Dia, Dia juga akan menyangkal ketong,</textarea>
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                        <div class="input_mode_verse flex_chunk" data-verse="13" data-id="34543">
                            <textarea style="min-height: 120px;" name="verses[13]" class="textarea input_mode_ta peer_verse_ta" >Klo tong tra setia, De tetap setia karena De tra bisa menyangkal diri.</textarea>
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                        <div class="input_mode_verse flex_chunk" data-verse="14" data-id="34544">
                            <textarea style="min-height: 120px;" name="verses[14]" class="textarea input_mode_ta peer_verse_ta" >Selalu kasi ingat dong di hadapan Allah, supaya dong jangan berdebat tentang Firman karena itu akan bikin kacau orang yang dengar,</textarea>
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                        <div class="input_mode_verse flex_chunk" data-verse="15" data-id="34545">
                            <textarea style="min-height: 120px;" name="verses[15]" class="textarea input_mode_ta peer_verse_ta" >Lakukan yang paling baik itu adalah persembahan yang Tuhan terima, jadi pekerja trada alasan untuk dapat kasi malu, yang ajar kebeneran Firman dengan pas.</textarea>
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                        <div class="input_mode_verse flex_chunk" data-verse="16" data-id="34546">
                            <textarea style="min-height: 120px;" name="verses[16]" class="textarea input_mode_ta peer_verse_ta" >Hindari omong kosong dan tra bersih yang nanti jadi tra baik.</textarea>
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                        <div class="input_mode_verse flex_chunk" data-verse="17" data-id="34547">
                            <textarea style="min-height: 120px;" name="verses[17]" class="textarea input_mode_ta peer_verse_ta" >Perkataan dong akan menyebar seperti kangker. Diantara dong itu ada Himeneus dan Filetus.</textarea>
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                        <div class="input_mode_verse flex_chunk" data-verse="18" data-id="34548">
                            <textarea style="min-height: 120px;" name="verses[18]" class="textarea input_mode_ta peer_verse_ta" >Dong adalah orang-orang yang sudah tidak benar. dong katakan kebangkitan sudah terjadi, dong putar balik iman dari berapa orang tu.</textarea>
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                        <div class="input_mode_verse flex_chunk" data-verse="19" data-id="34549">
                            <textarea style="min-height: 120px;" name="verses[19]" class="textarea input_mode_ta peer_verse_ta" >Biar begitu, Allah pu fondasi kuat tetap berdiri. ada piagam dengan tulisan yang bilang" Tuhan kenal dong Dia pu milik." . dan orang yang percaya Tuhan harus kasi tinggal yang tra benar.</textarea>
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                        <div class="input_mode_verse flex_chunk" data-verse="20" data-id="34550">
                            <textarea style="min-height: 120px;" name="verses[20]" class="textarea input_mode_ta peer_verse_ta" >Dalam rumah kaya bukan saja ada emas dan perak tapi juga ada kotak-kotak kayu sama tanah liat. barang itu di pake untuk hal-hal yang terhormat, dan ada juga untuk hal-hal tra terhormat.</textarea>
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                        <div class="input_mode_verse flex_chunk" data-verse="21" data-id="34551">
                            <textarea style="min-height: 120px;" name="verses[21]" class="textarea input_mode_ta peer_verse_ta" >Jika satu orang kasi bersih de pu diri dari yang tra terhormat, de itu bejana yang terhormat. de dipilih , dipake untuk tuannya, dan de disiapkan untuk semua perbuatan yang baik.</textarea>
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                        <div class="input_mode_verse flex_chunk" data-verse="22" data-id="34552">
                            <textarea style="min-height: 120px;" name="verses[22]" class="textarea input_mode_ta peer_verse_ta" >Jauhi sudah dari nafsu anak-anak muda, kejar itu kebenaran, iman, kasih, dan damai, sama-sama deng dong yang panggil Tuhan dengan hati yang bersih.</textarea>
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                        <div class="input_mode_verse flex_chunk" data-verse="23" data-id="34553">
                            <textarea style="min-height: 120px;" name="verses[23]" class="textarea input_mode_ta peer_verse_ta" >Tapi tolak sudah pertanyaan-pertanyaan bodok. Kam tahu itu semua nanti jadi sebab baku tengkar.</textarea>
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                        <div class="input_mode_verse flex_chunk" data-verse="24" data-id="34554">
                            <textarea style="min-height: 120px;" name="verses[24]" class="textarea input_mode_ta peer_verse_ta" >Orang yang melayani Tuhan tra boleh bertengkar tapi harus lemah lembut pada semua dong, Dong harus pintar mengajar, sabar</textarea>
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                        <div class="input_mode_verse flex_chunk" data-verse="25" data-id="34555">
                            <textarea style="min-height: 120px;" name="verses[25]" class="textarea input_mode_ta peer_verse_ta" >de kasi ajaran deng lemah lembut sama dong yang melawan dia. mungkin Allah kasi kesempatan untuk dong bertobat pada pengetahuan akan kebenaran.</textarea>
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                        <div class="input_mode_verse flex_chunk" data-verse="26" data-id="34556">
                            <textarea style="min-height: 120px;" name="verses[26]" class="textarea input_mode_ta peer_verse_ta" >mungkin dong sadar kembali dan kasi tinggal jerat iblis setelah selama ini dong ditawan untuk ikut perintahnya.</textarea>
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="main_content_footer row">
                <div class="form-group">
                    <div class="main_content_confirm_desc"><?php echo __("confirm_finished")?></div>
                    <label><input name="confirm_step" id="confirm_step" value="1" type="checkbox"> <?php echo __("confirm_yes")?></label>
                </div>

                <?php if ($data["inputMode"] == InputMode::SCRIPTURE_INPUT): ?>
                    <button id="next_step" class="btn btn-primary" disabled="disabled">
                        <?php echo __($data["next_step"])?>
                    </button>
                <?php else: ?>
                    <button id="next_step" class="btn btn-primary" disabled="disabled">
                        <?php echo __($data["next_step"])?>
                    </button>
                    &nbsp;&nbsp;
                    <button id="next_chapter" class="btn btn-success" disabled>
                        <?php echo __("next_chapter")?>
                    </button>
                <?php endif; ?>
                <img src="<?php echo template_url("img/saving.gif") ?>" class="unsaved_alert">
            </div>
        </form>
        <div class="step_right alt"><?php echo __("step_num", ["step_number" => 2])?></div>
    </div>
</div>

<div class="content_help closed">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps">
            <div class="help_name_steps"><span><?php echo __("step_num", ["step_number" => 2])?>:</span> <?php echo __(EventSteps::SELF_CHECK)?></div>
            <div class="help_descr_steps">
                <ul>
                    <li><b><?php echo __("purpose") ?></b> <?php echo __("self-check_purpose") ?></li>
                    <li><b><?php echo __("length") ?></b> <?php echo __("self-check_length") ?></li>
                    <li><b><?php echo __("self-check_help_1") ?></b></li>
                    <li><?php echo __("self-check_help_2") ?></li>
                    <li><?php echo __("self-check_help_3") ?></li>
                    <li><?php echo __("self-check_help_4") ?></li>
                    <li><?php echo __("self-check_help_5") ?>
                        <ol>
                            <li><?php echo __("self-check_help_5a") ?></li>
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
                    <li><?php echo __("self-check_help_6") ?></li>
                    <li><?php echo __("self-check_help_7", ["icon" => "<span class='mdi mdi-lead-pencil'></span>"]) ?></li>
                    <li><?php echo __("move_to_next_step_alt", ["step" => __($data["next_step"])]) ?></li>
                </ul>
                <div class="show_tutorial_popup"> >>> <?php echo __("show_more")?></div>
            </div>
        </div>

        <div class="event_info">
            <div class="participant_info">
                <div class="additional_info">
                    <a href="/events/demo<?php echo $addUri ?>/information"><?php echo __("event_info") ?></a>
                </div>
            </div>
        </div>

        <div class="tr_tools">
            <?php
            renderTn($data["tnLangID"]);
            renderTq($data["tqLangID"]);
            renderBc($data["bcLangID"]);
            renderRubric();
            ?>
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
            <h3><?php echo __(EventSteps::SELF_CHECK)?></h3>
            <ul>
                <li><b><?php echo __("purpose") ?></b> <?php echo __("self-check_purpose") ?></li>
                <li><b><?php echo __("length") ?></b> <?php echo __("self-check_length") ?></li>
                <li><b><?php echo __("self-check_help_1") ?></b></li>
                <li><?php echo __("self-check_help_2") ?></li>
                <li><?php echo __("self-check_help_3") ?></li>
                <li><?php echo __("self-check_help_4") ?></li>
                <li><?php echo __("self-check_help_5") ?>
                    <ol>
                        <li><?php echo __("self-check_help_5a") ?></li>
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
                <li><?php echo __("self-check_help_6") ?></li>
                <li><?php echo __("self-check_help_7", ["icon" => "<span class='mdi mdi-lead-pencil'></span>"]) ?></li>
                <li><?php echo __("move_to_next_step_alt", ["step" => __($data["next_step"])]) ?></li>
            </ul>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        switch (inputMode) {
            case '<?php echo InputMode::SCRIPTURE_INPUT ?>':
                $("#next_step").click(function (e) {
                    e.preventDefault();
                    if(!hasChangesOnPage) window.location.href = '/events/demo-'+inputMode+'/pray';
                    return false;
                });
                break;
            case '<?php echo InputMode::SPEECH_TO_TEXT ?>':
                $("#next_step").click(function (e) {
                    e.preventDefault();
                    if(!hasChangesOnPage) window.location.href = '/events/demo-'+inputMode+'/peer_review';
                    return false;
                });

                $("#next_chapter").click(function (e) {
                    e.preventDefault();
                    if(!hasChangesOnPage) window.location.href = '/events/demo-'+inputMode+'/pray';
                    return false;
                });
                break;
        }
    });

    $(".source_mode input").change(function () {
        const active = $(this).prop('checked');
        if (active) {
            $(".flex_left").show();
        } else {
            $(".flex_left").hide();
        }
    });
</script>