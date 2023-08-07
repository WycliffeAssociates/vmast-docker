<?php
require(app_path() . "Views/Components/CommentEditor.php");
require(app_path() . "Views/Components/HelpTools.php");
?>

<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="main_content_title">
            <div class="demo_title"><?php echo __("demo") . " (" . __("tn_review") . ")" ?></div>
            <div><?php echo __("step_num", ["step_number" => 2]) . ": " . __("peer-edit-l3_full") ?></div>
        </div>
    </div>

    <div class="">
        <div class="main_content">
            <div class="main_content_text">
                <h4>Bahasa Indonesia - <?php echo __("tn") ?> - <?php echo __("new_test") ?> - <span class="book_name">Mark 16:1-20</span>
                </h4>

                <div id="my_notes_content" class="my_content">
                    <div class="note_chunk l3 flex_container">
                        <div class="scripture_l3 flex_left"></div>
                        <div class="scripture_l2 flex_left"></div>
                        <div class="scripture_compare flex_left"></div>
                        <div class="vnote l3 notes_editor font_id flex_middle" dir="ltr">
                            <textarea name="chunks[0]" class="add_notes_editor"><h1>Pendahuluan</h1>
                                <h1>Catatan Umum</h1>
                                <h1>Markus 16</h1>
                                <h4>Konsep Khusus dalam Pasal ini</h4>
                                <h5>Tradisi Penguburan</h5>
                                <p>Hal itu adalah sebuah adat dalam bangsa Israel kuno untuk menempatkan orang-orang penting atau orang-orang kaya dalam kuburan dan menutup kuburan itu dengan batu yang besar. Hanya keluarga orang-orang kaya yang dapat membayar tempat pemakaman semacam ini.</p>
                                <h3>Beberapa kesulitan terjemahan dalam pasal ini</h3>
                                <h5>Seorang laki-laki muda berpakaian dalam jubah yang putih.</h5>
                                <p>Matius, Markus, Lukas, dan Yohanes semua menulis tentang malaikat-malaikat yang berjubah putih dengan wanita dalam makam Yesus. Dua dari penulis itu menyebut mereka pria, tetapi hal itu karena malaikat dalam bentuk manusia. Dua dari penulis itu menulis tentang dua malaikat, namun dua penulis yang lain hanya menulis tentang satu dari mereka. Lebih baik menerjemahkan setiap bagian ini seperti yang terlihat dalam ULB tanpa mencoba untuk membuat semua bagian mengatakan hal yang sama persis.&nbsp; (Lihat: <a href="../../mat/28/01.md">Matius 28:1-2</a>, <a href="../../mrk/16/05.md">Markus 16:5</a>&nbsp;dan&nbsp;<a href="../../luk/24/04.md">Lukas 24:4</a>&nbsp;dan&nbsp;<a href="../../jhn/20/11.md">Yohanes 20:12</a>)</p>
                                <h2>Tautan :</h2>
                                <ul>
                                    <li><strong><a href="./01.md">Catatan Markus 16:01</a></strong></li>
                                </ul>
                                <p><strong><a href="../15/intro.md">&lt;&lt;</a> |</strong></p></textarea>

                        </div>
                        <div class="flex_right">
                            <?php
                            $comment = new stdClass();
                            $comment->memberID = 1;
                            $comment->level = 2;
                            $comment->text = "A note from revision checker";
                            $comment->firstName = "James";
                            $comment->lastName = "S";
                            $comment->cID = 0;

                            $comment2 = new stdClass();
                            $comment2->memberID = 0;
                            $comment2->level = 3;
                            $comment2->text = "A note from Review checker";
                            $comment2->firstName = "Anna";
                            $comment2->lastName = "S";
                            $comment2->cID = 0;

                            $hasComments = true;
                            $commentsNumber = 2;
                            $comments = [$comment, $comment2];
                            $myMemberID = 0;
                            $enableFootNotes = false;
                            require(app_path() . "Views/Components/Comments.php");
                            ?>
                        </div>
                    </div>
                    <div class="note_chunk l3 flex_container">
                        <div class="compare_scripture">
                            <label>
                                <input type="checkbox" autocomplete="off" checked
                                       data-toggle="toggle"
                                       data-on="<?php echo __("on") ?>"
                                       data-off="<?php echo __("off") ?>"/>
                                <?php echo __("compare"); ?>
                            </label>
                        </div>
                        <div class="scripture_l3 flex_left"> 1. <span data-verse="1">Ketika hari Sabat telah lewat, Maria Magdalena dan Maria, ibu
                                Yakobus, dan Salome, membeli rempah-rempah harum, dengan maksud untuk pergi dan mengurapi tubuh
                                Yesus ke tempat pemakaman.</span> 2. <span data-verse="2">Pagi-pagi benar setelah matahari terbit,
                                pada hari pertama di minggu itu, pergilah mereka ke kuburan.</span></div>
                        <div class="scripture_l2 flex_left"> 1. <span data-verse="1">Dan, ketika hari Sabat berlalu, Maria Magdalena, Maria ibu
                                Yakobus, serta Salome, telah membeli rempah-rempah sehingga mereka dapat pergi dan meminyaki Yesus.</span>
                            2. <span data-verse="2">Pagi-pagi sekali, pada hari pertama minggu itu, ketika matahari terbit, mereka datang ke kuburan.</span>
                        </div>
                        <div class="scripture_compare flex_left"> 1. <span data-verse="1"><del
                                        style="background:#ffe6e6;">Dan, k</del><ins style="background:#e6ffe6;">K</ins>
                                <span>etika hari Sabat </span><del style="background:#ffe6e6;">berlalu</del><ins
                                        style="background:#e6ffe6;">telah lewat</ins>
                                <span>, Maria Magdalena</span><del style="background:#ffe6e6;">,</del><ins
                                        style="background:#e6ffe6;"> dan</ins><span> Maria</span>
                                <ins style="background:#e6ffe6;">,</ins><span> ibu Yakobus, </span><del
                                        style="background:#ffe6e6;">serta</del><ins
                                        style="background:#e6ffe6;">dan</ins>
                                <span> Salome,</span><del style="background:#ffe6e6;"> telah</del><span> membeli rempah-rempah </span>
                                <del style="background:#ffe6e6;">sehingga mereka dapat pergi dan meminyaki Yesus</del>
                                <ins style="background:#e6ffe6;">harum, dengan maksud untuk pergi dan mengurapi tubuh Yesus ke tempat pemakaman</ins>
                                <span>.</span></span> 2. <span data-verse="2"><span>Pagi-pagi </span><del
                                        style="background:#ffe6e6;">sekali</del>
                                <ins style="background:#e6ffe6;">benar setelah matahari terbit</ins><span>, pada hari pertama </span><ins
                                        style="background:#e6ffe6;">di </ins>
                                <span>minggu itu, </span><del style="background:#ffe6e6;">ketika matahari terbit, mereka datang</del><ins
                                        style="background:#e6ffe6;">pergilah mereka</ins>
                                <span> ke kuburan.</span></span>
                        </div>
                        <div class="vnote l3 font_id flex_middle" dir="ltr">
                            <textarea name="chunks[0]" class="add_notes_editor"><h1>Ayat: 1-2</h1>
                            <h1>Pernyataan Terkait:</h1>
                            <p>Pada hari pertama minggu itu, wanita-wanita datang lebih awal karena mereka mengharapkan untuk menggunakan rempah-rempah untuk mengurapi tubuh Yesus. Mereka terkejut melihat seorang laki-laki yang mengatakan kepada mereka bahwa Yesus telah hidup, namun mereka takut dan tidak menceritakan kepada siapapun.</p>
                            <h1>Ketika hari Sabat telah berlalu</h1>
                            <p>Yaitu, setelah hari Sabat, hari ketujuh minggu itu, telah usai dan hari pertama minggu itu telah dimulai.</p>
                            <h1>Kata-Kata Terjemahan</h1>
                            <ul>
                                <li>[[rc://en/tw/dict/bible/kt/sabbath]]</li>
                                <li>[[rc://en/tw/dict/bible/names/marymagdalene]]</li>
                                <li>[[rc://en/tw/dict/bible/kt/jesus]]</li>
                                <li>[[rc://en/tw/dict/bible/other/tomb]]</li>
                            </ul></textarea>
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
                        <div class="compare_scripture">
                            <label>
                                <input type="checkbox" autocomplete="off" checked
                                       data-toggle="toggle"
                                       data-on="<?php echo __("on") ?>"
                                       data-off="<?php echo __("off") ?>"/>
                                <?php echo __("compare"); ?>
                            </label>
                        </div>
                        <div class="scripture_l3 flex_left"> 3. <span data-verse="3"><span>Lalu mereka berkata satu kepada yang lain,
                                    "Siapa yang akan menggulingkan batu di depan pintu kuburan untuk kami?"</span></span>
                            4. <span data-verse="4">
                                    <span>Ketika mereka lihat, mereka mendapati bahwa seseorang telah menggulingkan batu yang sangat besar itu.</span></span>
                        </div>
                        <div class="scripture_l2 flex_left"> 3. <span data-verse="3">Lalu mereka berkata satu kepada yang lain, "Siapa
                                    yang akan menggulingkan batu di depan pintu kuburan untuk kami?"</span> 4.
                            <span data-verse="4">Ketika mereka lihat, mereka mendapati bahwa seseorang telah menggulingkan batu yang sangat besar itu.</span>
                        </div>
                        <div class="scripture_compare flex_left"> 3. <span data-verse="3"><span>Lalu mereka berkata satu kepada yang lain,
                                    "Siapa yang akan menggulingkan batu di depan pintu kuburan untuk kami?"</span></span>
                            4. <span data-verse="4">
                                    <span>Ketika mereka lihat, mereka mendapati bahwa seseorang telah menggulingkan batu yang sangat besar itu.</span></span>
                        </div>
                        <div class="vnote l3 font_id flex_middle" dir="ltr">
                            <textarea name="chunks[0]" class="add_notes_editor"><h1>Ayat: 3-4</h1>
                            <h1>batu itu telah digulingkan</h1>
                            <p>Ini dapat dinyatakan dalam bentuk yang aktif. AT: "seseorang telah menggulingkan batu itu" (Lihat:&nbsp;[[rc://en/ta/man/translate/figs-activepassive]])</p>
                            <h1>Kata-kata Terjemahan</h1>
                            <ul>
                                <li>[[rc://en/tw/dict/bible/other/tomb]]</li>
                            </ul></textarea>
                        </div>
                        <div class="flex_right">
                            <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="note_chunk l3 flex_container">
                        <div class="compare_scripture">
                            <label>
                                <input type="checkbox" autocomplete="off" checked
                                       data-toggle="toggle"
                                       data-on="<?php echo __("on") ?>"
                                       data-off="<?php echo __("off") ?>"/>
                                <?php echo __("compare"); ?>
                            </label>
                        </div>
                        <div class="scripture_l3 flex_left"> 5. <span data-verse="5"><span>Ketika mereka masuk ke kuburan, mereka melihat
                                    seorang muda berpakaian jubah putih, duduk di sebelah kanan, dan mereka sangat
                                    tercengang.</span></span> 6. <span data-verse="6">Lalu orang itu berkata
                                    kepada mereka, "Jangan takut. Kamu mencari Yesus, orang Nazaret, yang telah disalibkan itu.
                                    Ia telah bangkit! Ia tidak ada di sini. Lihatlah tempat mereka telah membaringkanNya.
                                    </span> 7. <span data-verse="7"><span>Tetapi pergilah dan katakan kepada murid-muridNya dan Petrus,
                                    bahwa Ia akan pergi mendahului kamu ke Galilea. Di sana kamu akan menemui Dia, seperti yang
                                    telah Ia katakan kepadamu.</span></span>
                        </div>
                        <div class="scripture_l2 flex_left"> 5. <span data-verse="5">Ketika mereka masuk ke kuburan, mereka melihat
                                    seorang muda berpakaian jubah putih, duduk di sebelah kanan, dan mereka sangat tercengang.</span>
                            6.
                            <span data-verse="6">Lalu orang itu berkata kepada mereka, "Jangan takut. Kamu mencari Yesus, orang Nazaret,
                                    yang telah disalibkan itu. Ia telah bangkit! Ia tidak ada di sini. Lihatlah tempat mereka telah membaringkanNya.</span>
                            7. <span data-verse="7">Tetapi pergilah dan katakan kepada murid-muridNya dan Petrus, bahwa Ia akan pergi mendahului
                                    kamu ke Galilea. Di sana kamu akan menemui Dia, seperti yang telah Ia katakan kepadamu.</span>
                        </div>
                        <div class="scripture_compare flex_left"> 5. <span data-verse="5"><span>Ketika mereka masuk ke kuburan, mereka melihat
                                    seorang muda berpakaian jubah putih, duduk di sebelah kanan, dan mereka sangat
                                    tercengang.</span></span> 6. <span data-verse="6">Lalu orang itu berkata
                                    kepada mereka, "Jangan takut. Kamu mencari Yesus, orang Nazaret, yang telah disalibkan itu.
                                    Ia telah bangkit! Ia tidak ada di sini. Lihatlah tempat mereka telah membaringkanNya.
                                    </span> 7. <span data-verse="7"><span>Tetapi pergilah dan katakan kepada murid-muridNya dan Petrus,
                                    bahwa Ia akan pergi mendahului kamu ke Galilea. Di sana kamu akan menemui Dia, seperti yang
                                    telah Ia katakan kepadamu.</span></span>
                        </div>
                        <div class="vnote l3 font_id flex_middle" dir="ltr">
                            <textarea name="chunks[0]" class="add_notes_editor"><h1>Ayat: 5-7</h1>
                            <h1>Ia telah bangkit!</h1>
                            <p>Malaikat itu dengan tegas menyatakan bahwa Yesus telah bangkit dari kematian. Ini dapat diterjemahkan dalam bentuk yang aktif. AT: "Ia bangkit" atau "Allah telah membangkitkan Dia dari kematian!" atau "Ia membangkitkan dirinya sendiri dari kematian!" (Lihat: [[rc://en/ta/man/translate/figs-activepassive]])</p>
                            <h1>Kata-Kata Terjemahan</h1>
                            <ul>
                                <li>[[rc://en/tw/dict/bible/names/nazareth]]</li>
                                <li>[[rc://en/tw/dict/bible/kt/crucify]]</li>
                                <li>[[rc://en/tw/dict/bible/other/raise]]</li>
                                <li>[[rc://en/tw/dict/bible/kt/disciple]]</li>
                                <li>[[rc://en/tw/dict/bible/names/peter]]</li>
                                <li>[[rc://en/tw/dict/bible/names/galilee]]</li>
                            </ul></textarea>
                        </div>
                        <div class="flex_right">
                            <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="note_chunk l3 flex_container">
                        <div class="compare_scripture">
                            <label>
                                <input type="checkbox" autocomplete="off" checked
                                       data-toggle="toggle"
                                       data-on="<?php echo __("on") ?>"
                                       data-off="<?php echo __("off") ?>"/>
                                <?php echo __("compare"); ?>
                            </label>
                        </div>
                        <div class="scripture_l3 flex_left"> 8. <span data-verse="8">Lalu mereka bergegas pergi keluar dari
                                    kuburan, dalam keadaan gemetar dan takjub. Mereka tidak memberitahu apapun kepada siapapun,
                                    karena mereka sangat takut.</span>
                        </div>
                        <div class="scripture_l2 flex_left"> 8. <span data-verse="8">Lalu mereka bergegas pergi keluar dari kuburan,
                                    dalam keadaan gemetar dan takjub. Mereka tidak memberitahu apapun kepada siapapun,
                                    karena mereka sangat takut.</span>
                        </div>
                        <div class="scripture_compare flex_left"> 8. <span data-verse="8">Lalu mereka bergegas pergi keluar dari
                                    kuburan, dalam keadaan gemetar dan takjub. Mereka tidak memberitahu apapun kepada siapapun,
                                    karena mereka sangat takut.</span>
                        </div>
                        <div class="vnote l3 font_id flex_middle" dir="ltr">
                            <textarea name="chunks[0]" class="add_notes_editor"><h1>Ayat: 8</h1>
                            <h1>Kata-Kata Terjemahan</h1>
                            <ul>
                                <li>[[rc://en/tw/dict/bible/other/tomb]]</li>
                                <li>[[rc://en/tw/dict/bible/other/amazed]]</li>
                            </ul></textarea>
                        </div>
                        <div class="flex_right">
                            <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="note_chunk l3 flex_container">
                        <div class="compare_scripture">
                            <label>
                                <input type="checkbox" autocomplete="off" checked
                                       data-toggle="toggle"
                                       data-on="<?php echo __("on") ?>"
                                       data-off="<?php echo __("off") ?>"/>
                                <?php echo __("compare"); ?>
                            </label>
                        </div>
                        <div class="scripture_l3 flex_left"> 9. <span data-verse="9">Pagi-pagi benar pada hari pertama minggu itu,
                                    setelah Yesus bangkit, Ia pertama kali menampakkan diriNya kepada Maria Magdalena,
                                    yang dari padanya Yesus pernah mengusir keluar tujuh roh jahat.</span> 10.
                            <span data-verse="10">Lalu ia pergi dan memberitahukan kepada mereka yang bersama-sama
                                    dengan dia, ketika mereka sedang berkabung dan menangis.</span> 11. <span
                                    data-verse="11">
                                    Mereka mendengar bahwa Ia hidup dan telah menampakkan diri kepadanya, tetapi mereka tidak percaya.</span>
                        </div>
                        <div class="scripture_l2 flex_left"> 9. <span data-verse="9"> Pagi-pagi benar pada hari pertama minggu itu,
                                    setelah Yesus bangkit, Ia pertama kali menampakkan diriNya kepada Maria Magdalena,
                                    yang dari padanya Yesus pernah mengusir keluar tujuh roh jahat.</span> 10.
                            <span data-verse="10">Lalu ia pergi dan memberitahukan kepada mereka yang bersama-sama dengan dia,
                                    ketika mereka sedang berkabung dan menangis.</span> 11. <span data-verse="11">Mereka mendengar
                                    bahwa Ia hidup dan telah menampakkan diri kepadanya, tetapi mereka tidak percaya.</span>
                        </div>
                        <div class="scripture_compare flex_left"> 9. <span data-verse="9">Pagi-pagi benar pada hari pertama minggu itu,
                                    setelah Yesus bangkit, Ia pertama kali menampakkan diriNya kepada Maria Magdalena,
                                    yang dari padanya Yesus pernah mengusir keluar tujuh roh jahat.</span> 10.
                            <span data-verse="10">Lalu ia pergi dan memberitahukan kepada mereka yang bersama-sama
                                    dengan dia, ketika mereka sedang berkabung dan menangis.</span> 11. <span
                                    data-verse="11">
                                    Mereka mendengar bahwa Ia hidup dan telah menampakkan diri kepadanya, tetapi mereka tidak percaya.</span>
                        </div>
                        <div class="vnote l3 font_id flex_middle" dir="ltr">
                            <textarea name="chunks[0]" class="add_notes_editor"><h1>Ayat: 9-11</h1>
                            <h1>Pernyataan Terkait:</h1>
                            <p>Yesus pertama-tama muncul kepada Maria Magdalena, yang memberitahukan kepada murid-murid Yesus, kemudian Ia muncul kepada dua orang yang lainnya saat dia berjalan di negeri itu, dan kemudian Ia muncul kepada kesebelas murid-Nya.</p>
                            <h1>pada hari pertama minggu itu</h1>
                            <p>"pada hari Minggu"</p>
                            <h1>Mereka mendengar</h1>
                            <p>"Mereka mendengar Maria Magdalena mengatakan"</p>
                            <h1>Kata-Kata Terjemahan</h1>
                            <ul>
                                <li>[[rc://en/tw/dict/bible/names/marymagdalene]</li>
                                <li>[[rc://en/tw/dict/bible/kt/demon]]</li>
                                <li>[[rc://en/tw/dict/bible/kt/believe]]</li>
                            </ul></textarea>
                        </div>
                        <div class="flex_right">
                            <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="note_chunk l3 flex_container">
                        <div class="compare_scripture">
                            <label>
                                <input type="checkbox" autocomplete="off" checked
                                       data-toggle="toggle"
                                       data-on="<?php echo __("on") ?>"
                                       data-off="<?php echo __("off") ?>"/>
                                <?php echo __("compare"); ?>
                            </label>
                        </div>
                        <div class="scripture_l3 flex_left"> 12. <span data-verse="12"> Sesudah itu, Ia menampakkan dalam
                                    rupa yang lain kepada dua orang yang lain, ketika mereka sedang berjalan keluar kota.
                                    </span> 13. <span data-verse="13">Lalu keduanya pergi dan memberitahukan kepada
                                    murid-murid lainnya, tetapi mereka tidak percaya kepada kedua orang itu.</span>
                        </div>
                        <div class="scripture_l2 flex_left"> 12. <span data-verse="12"> Sesudah itu, Ia menampakkan dalam rupa
                                    yang lain kepada dua orang yang lain, ketika mereka sedang berjalan keluar kota.</span>
                            13. <span data-verse="13">Lalu keduanya pergi dan memberitahukan kepada murid-murid lainnya,
                                    tetapi mereka tidak percaya kepada kedua orang itu.</span>
                        </div>
                        <div class="scripture_compare flex_left"> 12. <span data-verse="12"> Sesudah itu, Ia menampakkan dalam
                                    rupa yang lain kepada dua orang yang lain, ketika mereka sedang berjalan keluar kota.
                                    </span> 13. <span data-verse="13">Lalu keduanya pergi dan memberitahukan kepada
                                    murid-murid lainnya, tetapi mereka tidak percaya kepada kedua orang itu.</span>
                        </div>
                        <div class="vnote l3 font_id flex_middle" dir="ltr">
                            <textarea name="chunks[0]" class="add_notes_editor"><h1>Ayat: 12-13</h1>
                            <h1>Ia muncul dalam bentuk yang lain</h1>
                            <p>"dua dari mereka" melihat Yesus, namun Ia terlihat berbeda dari penampilan Ia sebelumnya.</p>
                            <h1>dua dari mereka</h1>
                            <p>dua dari "mereka yang bersama dengan Dia" (<a href="./09.md">Markus 16:10</a>)</p>
                            <h1>mereka tidak mempercayainya</h1>
                            <p>Beberapa murid lainnya tidak percaya apa yang mereka berdua katakan saat berjalan di negeri itu.</p>
                            <h1>Kata-Kata Terjemahan</h1>
                            <ul>
                                <li>[[rc://en/tw/dict/bible/kt/disciple]]</li>
                            </ul></textarea>
                        </div>
                        <div class="flex_right">
                            <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="note_chunk l3 flex_container">
                        <div class="compare_scripture">
                            <label>
                                <input type="checkbox" autocomplete="off" checked
                                       data-toggle="toggle"
                                       data-on="<?php echo __("on") ?>"
                                       data-off="<?php echo __("off") ?>"/>
                                <?php echo __("compare"); ?>
                            </label>
                        </div>
                        <div class="scripture_l3 flex_left"> 14. <span data-verse="14"> Yesus kemudian menampakkan diri
                                    kepada sebelas muridNya, ketika mereka sedang makan, dan Ia pun menegur ketidakpercayaan
                                    dan kekerasan hati mereka, sebab mereka tidak mempercayai mereka yang telah melihatNya
                                    setelah Ia telah bangkit dari kematian.</span> 15. <span data-verse="15">
                                    Ia berkata kepada mereka,"Pergilah ke seluruh dunia, beritakan injil kepada segala makhluk ciptaan.</span>
                            16. <span data-verse="16">Siapa yang percaya dan dibaptis akan selamat, dan siapa yang tidak
                                    percaya akan dihukum.</span>
                        </div>
                        <div class="scripture_l2 flex_left"> 14. <span data-verse="14"> Yesus kemudian menampakkan diri kepada sebelas
                                    muridNya, ketika mereka sedang makan, dan Ia pun menegur ketidakpercayaan dan kekerasan
                                    hati mereka, sebab mereka tidak mempercayai mereka yang telah melihatNya setelah Ia telah
                                    bangkit dari kematian.</span> 15. <span data-verse="15">Ia berkata kepada mereka,"Pergilah
                                    ke seluruh dunia, beritakan injil kepada segala makhluk ciptaan.</span> 16.
                            <span data-verse="16">Siapa yang percaya dan dibaptis akan selamat, dan siapa yang tidak
                                    percaya akan dihukum.</span>
                        </div>
                        <div class="scripture_compare flex_left"> 14. <span data-verse="14"> Yesus kemudian menampakkan diri
                                    kepada sebelas muridNya, ketika mereka sedang makan, dan Ia pun menegur ketidakpercayaan
                                    dan kekerasan hati mereka, sebab mereka tidak mempercayai mereka yang telah melihatNya
                                    setelah Ia telah bangkit dari kematian.</span> 15. <span data-verse="15">
                                    Ia berkata kepada mereka,"Pergilah ke seluruh dunia, beritakan injil kepada segala makhluk ciptaan.</span>
                            16. <span data-verse="16">Siapa yang percaya dan dibaptis akan selamat, dan siapa yang tidak
                                    percaya akan dihukum.</span>
                        </div>
                        <div class="vnote l3 font_id flex_middle" dir="ltr">
                            <textarea name="chunks[0]" class="add_notes_editor"><h1>Ayat: 14-16</h1>
                            <h1>Pernyataan Terkait:</h1>
                            <p>Ketika Yesus bertemu dengan kesebelas murid, Ia menegur mereka karena ketidakpercayaan mereka dan mengatakan kepada mereka untuk pergi ke seluruh dunia dan beritakanlah Injil.</p>
                            <h1>kesebelas</h1>
                            <p>Mereka adalah sebelas rasul yang tetap ada setelah Yudas meninggalkan mereka.</p>
                            <h1>mereka bersandar di meja</h1>
                            <p>Ini merupakan sebuah metonimia untuk makan, yang merupakan sebuah cara yang biasa untuk orang-orang pada saat itu memakan makanan. AT: "mereka sedang menyantap makanan" (Lihat:[[rc://en/ta/man/translate/figs-metonymy]])</p>
                            <h1>bersandar</h1>
                            <p>Dalam tradisi Yesus, ketika orang-orang berkumpul bersama untuk makan, mereka berbaring di sisi mereka, menyangga diri mereka sendiri diatas bantal disamping meja yang rendah ketinggiannya.</p>
                            <h1>kekerasan hati</h1>
                            <p>Yesus sedang menegur murid-murid-Nya karena mereka tidak akan percaya kepadaNya. Terjemahkan ungkapan ini sehingga dapat dipahami bahwa murid-murid tidak mempercayai Yesus. AT: "penolakan untuk percaya" (Lihat:[[rc://en/ta/man/translate/figs-idiom]])</p>
                            <h1>Pergi ke seluruh dunia</h1>
                            <p>Disini "dunia" merupakan sebuah metonimia untuk orang-orang di dunia. AT: "Pergi kemanapun tempat yang ada banyak orang-orang" (Lihat:[[rc://en/ta/man/translate/figs-metonymy]])</p>
                            <h1>kepada semua ciptaan</h1>
                            <p>Ini adalah sesuatu yang melebih-lebihkan dan suatu metonimia untuk dimanapun orang-orang berada. AT: "semua orang sepenuhnya" (Lihat:[[rc://en/ta/man/translate/figs-metonymy]]&nbsp;dan&nbsp;[[rc://en/ta/man/translate/figs-hyperbole]])</p>
                            <h1>Siapa yang percaya dan dibaptis akan diselamatkan</h1>
                            <p>Kata "Ia" mengarah kepada siapapun. Kalimat ini dapat dibuat menjadi kalimat aktif. AT: "Allah akan menyelamatkan semua orang yang percaya dan memperbolehkan kamu untuk membaptis mereka" (Lihat: [[rc://en/ta/man/translate/figs-activepassive]])</p>
                            <h1>orang yang tidak percaya akan dihukum</h1>
                            <p>Kata "orang" disini mengarah kepada siapapun. Klausa ini dapat dibuat menjadi aktif. AT: "Allah akan menghukum semua orang yang tidak percaya" (Lihat:[rc://en/ta/man/translate/figs-activepassive]])</p>
                            <h1>Kata-Kata Terjemahan</h1>
                            <ul>
                                <li>[[rc://en/tw/dict/bible/kt/jesus]]</li>
                                <li>[[rc://en/tw/dict/bible/kt/thetwelve]]</li>
                                <li>[[rc://en/tw/dict/bible/other/rebuke]]</li>
                                <li>[[rc://en/tw/dict/bible/kt/believe]]</li>
                                <li>[[rc://en/tw/dict/bible/kt/believe]]</li>
                                <li>[[rc://en/tw/dict/bible/other/preach]]</li>
                                <li>[[rc://en/tw/dict/bible/kt/goodnews]]</li>
                                <li>[[rc://en/tw/dict/bible/kt/baptize]]</li>
                                <li>[[rc://en/tw/dict/bible/kt/save]]</li>
                                <li>[[rc://en/tw/dict/bible/kt/condemn]]</li>
                            </ul></textarea>
                        </div>
                        <div class="flex_right">
                            <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="note_chunk l3 flex_container">
                        <div class="compare_scripture">
                            <label>
                                <input type="checkbox" autocomplete="off" checked
                                       data-toggle="toggle"
                                       data-on="<?php echo __("on") ?>"
                                       data-off="<?php echo __("off") ?>"/>
                                <?php echo __("compare"); ?>
                            </label>
                        </div>
                        <div class="scripture_l3 flex_left"> 17. <span data-verse="17">Tanda-tanda ini akan menyertai mereka yang percaya.
                                    Mereka akan mengusir setan dalam namaKu. Mereka akan berbicara dalam bahasa yang baru,</span>
                            18.
                            <span data-verse="18">Mereka akan mengangkat ular dengan tangan mereka, dan sekalipun mereka
                                    minum racun yang mematikan, mereka tidak akan mendapat celaka. Mereka akan
                                    menumpangkan tangan kepada orang sakit dan orang sakit itu akan sembuh.</span>
                        </div>
                        <div class="scripture_l2 flex_left"> 17. <span data-verse="17">Tanda-tanda ini akan menyertai mereka yang
                                    percaya. Mereka akan mengusir setan dalam namaKu. Mereka akan berbicara dalam bahasa
                                    yang baru,</span> 18. <span data-verse="18">Mereka akan mengangkat ular dengan tangan
                                    mereka, dan sekalipun mereka minum racun yang mematikan, mereka tidak akan mendapat celaka.
                                    Mereka akan menumpangkan tangan kepada orang sakit dan orang sakit itu akan sembuh.</span>
                        </div>
                        <div class="scripture_compare flex_left"> 17. <span data-verse="17">Tanda-tanda ini akan menyertai mereka yang percaya.
                                    Mereka akan mengusir setan dalam namaKu. Mereka akan berbicara dalam bahasa yang baru,</span>
                            18.
                            <span data-verse="18">Mereka akan mengangkat ular dengan tangan mereka, dan sekalipun mereka
                                    minum racun yang mematikan, mereka tidak akan mendapat celaka. Mereka akan
                                    menumpangkan tangan kepada orang sakit dan orang sakit itu akan sembuh.</span>
                        </div>
                        <div class="vnote l3 font_id flex_middle" dir="ltr">
                            <textarea name="chunks[0]" class="add_notes_editor"><h1>Ayat: 17-18</h1>
                            <h1>Tanda-tanda ini akan menyertai mereka yang percaya</h1>
                            <p>Markus berbicara tentang mujizat seolah-olah mereka berjalan bersama dengan orang-orang percaya. AT: "Orang-orang melihat mereka yang percaya akan melihat hal-hal ini terjadi dan mengetahui bahwa Aku bersama dengan orang-orang percaya" (Lihat:&nbsp;[[rc://en/ta/man/translate/figs-personification]])</p>
                            <h1>Dalam nama-Ku mereka</h1>
                            <p>Arti kemungkinan lainnya adalah 1) Yesus sedang memberikan daftar umum: "Dalam nama-Ku mereka akan melakukan hal-hal yang seperti ini: Mereka" atau 2) Yesus sedang memberikan daftar yang tepat: "Ini adalah hal-hal yang mereka akan lakukan dalam nama-Ku: Mereka."</p>
                            <h1>Dalam nama-Ku</h1>
                            <p>Disini "nama" dihubungkan dengan kekuasaan Yesus dan kuasa-Nya. Lihat bagaimana "dalam namaMu" diterjemahkan dalam&nbsp;<a href="../09/38.md">Markus 9:38</a>. AT: "Dengan kekuasaan atas nama-Ku" atau "Dengan kuasa dalam nama-Ku" (Lihat: [[rc://en/ta/man/translate/figs-metonymy]])</p>
                            <h1>Kata-Kata Terjemahan</h1>
                            <ul>
                                <li>[[rc://en/tw/dict/bible/kt/demon]]</li>
                                <li>[[rc://en/tw/dict/bible/other/serpent]]</li>
                            </ul></textarea>
                        </div>
                        <div class="flex_right">
                            <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="note_chunk l3 flex_container">
                        <div class="compare_scripture">
                            <label>
                                <input type="checkbox" autocomplete="off" checked
                                       data-toggle="toggle"
                                       data-on="<?php echo __("on") ?>"
                                       data-off="<?php echo __("off") ?>"/>
                                <?php echo __("compare"); ?>
                            </label>
                        </div>
                        <div class="scripture_l3 flex_left">19. <span data-verse="19"> Setelah Tuhan berbicara kepada mereka,
                                    terangkatlah Ia ke surga, dan Ia duduk di sebelah kanan Allah.</span> 20.
                            <span data-verse="20">Murid-muridNya pun pergi dan memberitakan injil ke mana-mana,
                                    sementara Tuhan bekerja dengan mereka dan meneguhkan Firman itu dengan tanda-tanda
                                    yang menyertainya.</span>
                        </div>
                        <div class="scripture_l2 flex_left"> 19. <span data-verse="19">Setelah Tuhan berbicara kepada mereka,
                                    terangkatlah Ia ke surga, dan Ia duduk di sebelah kanan Allah.</span> 20.
                            <span data-verse="20">Murid-muridNya pun pergi dan memberitakan injil kemana-mana,
                                    dan Tuhan turut bekerja di antara mereka, dan meneguhkan Firman itu dengan tanda-tanda
                                    yang menyertainya.</span>
                        </div>
                        <div class="scripture_compare flex_left"> 19. <span data-verse="19"><ins
                                        style="background:#e6ffe6;"> </ins><span>Setelah Tuhan berbicara
                                    kepada mereka, terangkatlah Ia ke surga, dan Ia duduk di sebelah kanan Allah.</span>
                                    <del style="background:#ffe6e6;"></del></span> 20. <span data-verse="20">Murid-muridNya pun pergi dan
                                    memberitakan injil ke<ins
                                        style="background:#e6ffe6;"> </ins><span>mana-mana, </span><del
                                        style="background:#ffe6e6;">dan</del>
                                    <ins style="background:#e6ffe6;">sementara</ins><span> Tuhan </span><del
                                        style="background:#ffe6e6;">turut </del>
                                    <span>bekerja d</span><del style="background:#ffe6e6;">i antara</del><ins
                                        style="background:#e6ffe6;">engan</ins>
                                    <span> mereka</span><del style="background:#ffe6e6;">,</del><span> dan meneguhkan Firman itu dengan
                                    tanda-tanda yang menyertainya.</span></span>
                        </div>
                        <div class="vnote l3 font_id flex_middle" dir="ltr">
                            <textarea name="chunks[0]" class="add_notes_editor"><h1>Ayat: 19-20</h1>
                            <h1>Ia terangkat ke Surga dan duduk</h1>
                            <p>Ini dapat dinyatakan dalam bentuk yang aktif. AT: "Allah mengangkat Dia ke Surga, dan Ia duduk" (Lihat:&nbsp;[[rc://en/ta/man/translate/figs-activepassive]])</p>
                            <h1>duduk di sebelah kanan Allah</h1>
                            <p>Untuk duduk "di sebelah kanan Allah" merupakan sebuah simbol penerimaan kehormatan dan kekuasaan yang besar dari Allah. AT: "duduk di tempat yang maha tinggi disamping Allah" (Lihat:&nbsp;[[rc://en/ta/man/translate/translate-symaction]])</p>
                            <h1>meneguhkan firman</h1>
                            <p>Ungkapan ini berarti mereka membuktikkan bahwa pesan mereka adalah benar. AT: "menunjukkan bahwa pesannya, yang dikatakan oleh mereka, adalah benar" (Lihat:[[rc://en/ta/man/translate/figs-idiom]])</p>
                            <h1>Kata-Kata Terjemahan</h1>
                            <ul>
                                <li>[[rc://en/tw/dict/bible/kt/lord]]</li>
                                <li>[[rc://en/tw/dict/bible/kt/heaven]]</li>
                                <li>[[rc://en/tw/dict/bible/kt/god]]</li>
                                <li>[[rc://en/tw/dict/bible/kt/disciple]]</li>
                                <li>[[rc://en/tw/dict/bible/other/preach]]</li>
                                <li>[[rc://en/tw/dict/bible/kt/miracle]]</li>
                            </ul></textarea>
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
                        <div class="main_content_confirm_desc"><?php echo __("confirm_finished") ?></div>
                        <label><input name="confirm_step" id="confirm_step" value="1"
                                      type="checkbox"> <?php echo __("confirm_yes") ?></label>
                    </div>

                    <button id="next_step" class="btn btn-primary" disabled="disabled">
                        <?php echo __($data["next_step"]) ?>
                    </button>
                    <img src="<?php echo template_url("img/saving.gif") ?>" class="unsaved_alert">
                </form>
                <div class="step_right"></div>
            </div>
            <div class="step_right alt"><?php echo __("step_num", ["step_number" => 2]) ?></div>
        </div>
    </div>
</div>

<div class="content_help closed">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps is_checker_page_help">
            <div class="help_name_steps">
                <span><?php echo __("step_num", ["step_number" => 2]) ?>: </span>
                <?php echo __("peer-edit-l3") ?>
            </div>
            <div class="help_descr_steps">
                <ul>
                    <li><b><?php echo __("purpose") ?></b> <?php echo __("peer-review-l3_tn_purpose") ?></li>
                    <li><?php echo __("peer-edit-l3_help_1") ?></li>
                    <li><?php echo __("peer-edit-l3_help_2") ?></li>
                    <li><?php echo __("peer-edit-l3_help_3", ["icon" => "<span class='mdi mdi-lead-pencil'></span>"]) ?></li>
                    <li><?php echo __("peer-edit-l3_tn_help_4") ?></li>
                    <li><?php echo __("peer-edit-l3_help_5") ?></li>
                    <li><?php echo __("peer-review-l3_tn_help_5") ?></li>
                    <li><?php echo __("peer-review-l3_tn_help_6") ?></li>
                    <li><?php echo __("peer-edit-l3_tn_help_8") ?></li>
                    <li><?php echo __("peer-edit-l3_tn_help_9") ?>
                        <ol>
                            <li><?php echo __("peer-edit-l3_tn_help_9a", ["icon" => "<i class='note-icon-magic'></i>"]) ?></li>
                            <li><?php echo __("self-check_tn_chk_help_9a") ?></li>
                            <li><?php echo __("self-check_tn_help_6a") ?></li>
                            <li><?php echo __("blind-draft_tn_help_8c") ?></li>
                            <li><?php echo __("blind-draft_tn_help_9") ?></li>
                        </ol>
                    </li>
                    <li><?php echo __("move_to_next_step_alt", ["step" => __($data["next_step"])]) ?></li>
                    <li><b><?php echo __("peer-edit-l3_help_8") ?></b></li>
                </ul>
                <div class="show_tutorial_popup"> >>> <?php echo __("show_more") ?></div>
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
                    <a href="/events/demo-tn-review/information"><?php echo __("event_info") ?></a>
                </div>
            </div>
        </div>

        <div class="tr_tools">
            <?php renderTn($data["tnLangID"]); ?>
        </div>

        <div class="checker_view">
            <a href="/events/demo-tn-review/peer_edit_l3_checker"><?php echo __("checker_other_view", [2]) ?></a>
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
            <h3><?php echo __("peer-edit-l3_full") ?></h3>
            <ul>
                <li><b><?php echo __("purpose") ?></b> <?php echo __("peer-review-l3_tn_purpose") ?></li>
                <li><?php echo __("peer-edit-l3_help_1") ?></li>
                <li><?php echo __("peer-edit-l3_help_2") ?></li>
                <li><?php echo __("peer-edit-l3_help_3", ["icon" => "<span class='mdi mdi-lead-pencil'></span>"]) ?></li>
                <li><?php echo __("peer-edit-l3_tn_help_4") ?></li>
                <li><?php echo __("peer-edit-l3_help_5") ?></li>
                <li><?php echo __("peer-review-l3_tn_help_5") ?></li>
                <li><?php echo __("peer-review-l3_tn_help_6") ?></li>
                <li><?php echo __("peer-edit-l3_tn_help_8") ?></li>
                <li><?php echo __("peer-edit-l3_tn_help_9") ?>
                    <ol>
                        <li><?php echo __("peer-edit-l3_tn_help_9a", ["icon" => "<i class='note-icon-magic'></i>"]) ?></li>
                        <li><?php echo __("self-check_tn_chk_help_9a") ?></li>
                        <li><?php echo __("self-check_tn_help_6a") ?></li>
                        <li><?php echo __("blind-draft_tn_help_8c") ?></li>
                        <li><?php echo __("blind-draft_tn_help_9") ?></li>
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
            if (!hasChangesOnPage) window.location.href = '/events/demo-tn-review/pray';
            return false;
        });

        $(".compare_scripture input").change(function () {
            var parent = $(this).parents(".note_chunk");
            var active = $(this).prop('checked');

            if (active) {
                $(".scripture_l3", parent).hide();
                $(".scripture_compare", parent).show();
            } else {
                $(".scripture_compare", parent).hide();
                $(".scripture_l3", parent).show();
            }
        });

        $(".ttools_panel .word_def").each(function () {
            let html = convertRcLinks($(this).html());
            $(this).html(html);
        });
    });
</script>
