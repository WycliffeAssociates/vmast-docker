<?php
require(app_path() . "Views/Components/CommentEditor.php");
?>

<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="action_type_container">
            <div class="demo_title"><?php echo __("demo") . " (".__("tw").")" ?></div>
        </div>
        <div class="main_content_title">
            <div><?php echo __("step_num", ["step_number" => 1]) . ": " . __("keyword-check") ?></div>
        </div>
    </div>

    <div class="">
        <div class="main_content">
            <form action="" method="post" id="main_form">
                <div class="main_content_text row" style="padding-left: 15px">
                    <h4 dir="ltr">Русский - <?php echo __("tw") ?> -
                        <span class='book_name'>names [aaron...adam]</span></h4>

                    <div class="col-sm-12 no_padding questions_bd">
                        <div class="parent_q questions_chunk" data-question="1" data-chapter="1" data-event="0">
                            <div class="row">
                                <div class="col-md-4"
                                     style="color: #00a74d; font-weight: bold;"> <?php //echo __("verse_number", 1) ?> </div>
                            </div>
                            <div class="flex_container">
                                <div class="question_content flex_left" dir="ltr">
                                    <h1>Aaron</h1>
                                    <h2>Facts:</h2>
                                    <p>Aaron was Moses' older brother. God chose Aaron to be the first high priest for
                                        the people of Israel.</p>
                                    <ul>
                                        <li>Aaron helped Moses speak to Pharaoh about letting the Israelites go free.
                                        </li>
                                        <li>While the Israelites were traveling through the desert, Aaron sinned by
                                            making an idol for the people to worship.
                                        </li>
                                        <li>God also appointed Aaron and his descendants to be the&nbsp;
                                            <a title="../kt/priest.md" href="../kt/priest.md">priest</a>&nbsp;priests
                                            for the people of Israel.
                                        </li>
                                    </ul>
                                    <p>(Translation suggestions:&nbsp;
                                        <a title="rc://en/ta/man/translate/translate-names"
                                           href="rc://en/ta/man/translate/translate-names">How to Translate Names</a>)
                                    </p>
                                    <p>(See also:&nbsp;
                                        <a title="../kt/priest.md" href="../kt/priest.md">priest</a>,&nbsp;
                                        <a title="../names/moses.md" href="../names/moses.md">Moses</a>,&nbsp;
                                        <a title="../kt/israel.md" href="../kt/israel.md">Israel</a>)</p>
                                    <h2>Bible References:</h2>
                                    <ul>
                                        <li><a title="rc://en/tn/help/1ch/23/12" href="rc://en/tn/help/1ch/23/12">1
                                                Chronicles 23:12-14</a></li>
                                        <li><a title="rc://en/tn/help/act/07/38" href="rc://en/tn/help/act/07/38">Acts
                                                07:38-40</a></li>
                                        <li><a title="rc://en/tn/help/exo/28/01" href="rc://en/tn/help/exo/28/01">Exodus
                                                28:1-3</a></li>
                                        <li><a title="rc://en/tn/help/luk/01/05" href="rc://en/tn/help/luk/01/05">Luke
                                                01:5-7</a></li>
                                        <li><a title="rc://en/tn/help/num/16/44" href="rc://en/tn/help/num/16/44">Numbers
                                                16:44-46</a></li>
                                    </ul>
                                    <h2>Examples from the Bible stories:</h2>
                                    <ul>
                                        <li><strong><a title="rc://en/tn/help/obs/09/15"
                                                       href="rc://en/tn/help/obs/09/15">09:15</a>
                                            </strong>&nbsp;God warned Moses and&nbsp;<strong>Aaron</strong>&nbsp;that
                                            Pharaoh would be stubborn.
                                        </li>
                                        <li><strong><a title="rc://en/tn/help/obs/10/05"
                                                       href="rc://en/tn/help/obs/10/05">10:05</a>
                                            </strong>&nbsp;Pharaoh called Moses and&nbsp;<strong>Aaron</strong>&nbsp;and
                                            told them that if they stopped the plague, the Israelites could leave Egypt.
                                        </li>
                                        <li><strong><a title="rc://en/tn/help/obs/13/09"
                                                       href="rc://en/tn/help/obs/13/09">13:09</a>
                                            </strong>&nbsp;God chose Moses' brother,&nbsp;<strong>Aaron</strong>, and
                                            Aaron's descendants to be his priests.
                                        </li>
                                        <li><strong><a title="rc://en/tn/help/obs/13/11"
                                                       href="rc://en/tn/help/obs/13/11">13:11</a>
                                            </strong>&nbsp;So they (the Israelites) brought gold
                                            to&nbsp;<strong>Aaron</strong>and asked him to form it into an idol for
                                            them!
                                        </li>
                                        <li><strong><a title="rc://en/tn/help/obs/14/07"
                                                       href="rc://en/tn/help/obs/14/07">14:07</a>
                                            </strong>&nbsp;They (the Israelites) became angry with Moses
                                            and&nbsp;<strong>Aaron</strong>&nbsp;and said, "Oh, why did you bring us to
                                            this horrible place?"
                                        </li>
                                    </ul>
                                    <h2>Word Data:</h2>
                                    <ul>
                                        <li>Strong's: H175, G2</li>
                                    </ul>
                                </div>
                                <div class="questions_editor font_ru flex_middle" dir="ltr">
                                <textarea name="chunks[0]" class="add_questions_editor blind_ta"><h1>Аарон</h1>
                                    <h2>Факты:</h2>
                                    <p>Аарон был старшим братом Моисея. Бог избрал Аарона первым первосвященником израильского народа.</p>
                                    <ul>
                                        <li>Аарон помогал Моисею обращаться к фараону с требованием отпустить израильтян на свободу.</li>
                                        <li>Во время блуждания израильтян по пустыне, Аарон согрешил, сделав людям золотого идола для поклонения.</li>
                                        <li>Бог также поставил Аарона и его потомков&nbsp;<a href="../kt/priest.md">священниками</a>&nbsp;для израильского народа.</li>
                                    </ul>
                                    <p>(Варианты перевода: Как переводить названия и имена)</p>
                                    <p>(См. также:&nbsp;
                                        <a href="../kt/priest.md">священник</a>,&nbsp;
                                        <a href="../names/moses.md" rel="nofollow">Моисей</a>,&nbsp;
                                        <a href="../kt/israel.md" rel="nofollow">Израиль</a>)</p></textarea>
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
                            <div class="chunk_divider col-sm-12" style="margin-top: 10px"></div>
                        </div>
                        <div class="parent_q questions_chunk" data-question="2" data-chapter="1" data-event="0">
                            <div class="row">
                                <div class="col-md-4"
                                     style="color: #00a74d; font-weight: bold;"> <?php //echo __("verse_number", 2) ?> </div>
                            </div>
                            <div class="flex_container">
                                <div class="question_content flex_left" dir="ltr">
                                    <h1>Abel</h1>
                                    <h2>Facts:</h2>
                                    <p>Abel was Adam and Eve's second son. He was Cain's younger brother.</p>
                                    <ul>
                                        <li>Abel was a shepherd.</li>
                                        <li>Abel sacrificed some of his animals as an offering to God.</li>
                                        <li>God was pleased with Abel and his offerings.</li>
                                        <li>Adam and Eve's firstborn son Cain murdered Abel.</li>
                                    </ul>
                                    <p>(Translation suggestions:&nbsp;
                                        <a title="rc://en/ta/man/translate/translate-names"
                                           href="rc://en/ta/man/translate/translate-names">How to Translate Names</a>)
                                    </p>
                                    <p>(See also:&nbsp;
                                        <a title="../names/cain.md" href="../names/cain.md">Cain</a>,&nbsp;
                                        <a title="../other/sacrifice.md" href="../other/sacrifice.md">sacrifice</a>,&nbsp;
                                        <a title="../other/shepherd.md" href="../other/shepherd.md">shepherd</a>)</p>
                                    <h2>Bible References:</h2>
                                    <ul>
                                        <li><a title="rc://en/tn/help/gen/04/01" href="rc://en/tn/help/gen/04/01">Genesis
                                                04:1-2</a></li>
                                        <li><a title="rc://en/tn/help/gen/04/08" href="rc://en/tn/help/gen/04/08">Genesis
                                                04:8-9</a></li>
                                        <li><a title="rc://en/tn/help/heb/12/22" href="rc://en/tn/help/heb/12/22">Hebrews
                                                12:22-24</a></li>
                                        <li><a title="rc://en/tn/help/luk/11/49" href="rc://en/tn/help/luk/11/49">Luke
                                                11:49-51</a></li>
                                        <li><a title="rc://en/tn/help/mat/23/34" href="rc://en/tn/help/mat/23/34">Matthew
                                                23:34-36</a></li>
                                    </ul>
                                    <h2>Word Data:</h2>
                                    <ul>
                                        <li>Strong's: H01893, G6</li>
                                    </ul>
                                </div>
                                <div class="questions_editor font_ru flex_middle" dir="ltr">
                                <textarea name="chunks[1]" class="add_questions_editor blind_ta"><h1>Авель</h1>
                                    <h2>Факты:</h2>
                                    <p>Авель был вторым сыном Адама и Евы. Он был младшим братом Каина.</p>
                                    <ul>
                                        <li>Авель был пастухом.</li>
                                        <li>Авель принёс некоторых из своих животных в жертву Богу.</li>
                                        <li>Бог был доволен Авелем и его приношениями.</li>
                                        <li>Первородный сын Адама и Евы, Каин, убил Авеля.</li>
                                    </ul>
                                    <p>(Варианты перевода: Как переводить имена)</p>
                                    <p>(См. также:&nbsp;
                                        <a href="../names/cain.md">Каин</a>,&nbsp;
                                        <a href="../other/sacrifice.md">жертвоприношение, дар</a>,&nbsp;
                                        <a href="../other/shepherd.md">пастух, пасти</a>)</p></textarea>


                                </div>
                                <div class="flex_right">
                                    <?php
                                    $hasComments = false;
                                    $enableFootNotes = false;
                                    require(app_path() . "Views/Components/Comments.php");
                                    ?>
                                </div>
                            </div>
                            <div class="chunk_divider col-sm-12" style="margin-top: 10px"></div>
                        </div>
                        <div class="parent_q questions_chunk" data-question="4" data-chapter="1" data-event="0">
                            <div class="row">
                                <div class="col-md-4"
                                     style="color: #00a74d; font-weight: bold;"> <?php //echo __("verse_number", 4) ?> </div>
                            </div>
                            <div class="flex_container">
                                <div class="question_content flex_left" dir="ltr">
                                    <h1>Abiathar</h1>
                                    <h2>Definition:</h2>
                                    <p>Abiathar was a high priest for the nation of Israel during the time of King
                                        David.</p>
                                    <ul>
                                        <li>When King Saul killed the priests, Abiathar escaped and went to David in the
                                            wilderness.
                                        </li>
                                        <li>Abiathar and another high priest named Zadok served David faithfully
                                            throughout his reign.
                                        </li>
                                        <li>After David's death, Abiathar helped Adonijah try to become king instead of
                                            Solomon.
                                        </li>
                                        <li>Because of this, King Solomon removed Abiathar from the priesthood.</li>
                                    </ul>
                                    <p>(See also:&nbsp;
                                        <a title="../names/zadok.md" href="../names/zadok.md">Zadok</a>,&nbsp;
                                        <a title="../names/saul.md" href="../names/saul.md">Saul (OT)</a>,&nbsp;
                                        <a title="../names/david.md" href="../names/david.md">David</a>,&nbsp;
                                        <a title="../names/solomon.md" href="../names/solomon.md">Solomon</a>,&nbsp;
                                        <a title="../names/adonijah.md" href="../names/adonijah.md">Adonijah</a>)</p>
                                    <h2>Bible References:</h2>
                                    <ul>
                                        <li><a title="rc://en/tn/help/1ch/27/32" href="rc://en/tn/help/1ch/27/32">1
                                                Chronicles 27:32-34</a></li>
                                        <li><a title="rc://en/tn/help/1ki/01/07" href="rc://en/tn/help/1ki/01/07">1
                                                Kings 01:7-8</a></li>
                                        <li><a title="rc://en/tn/help/1ki/02/22" href="rc://en/tn/help/1ki/02/22">1
                                                Kings 02:22-23</a></li>
                                        <li><a title="rc://en/tn/help/2sa/17/15" href="rc://en/tn/help/2sa/17/15">2
                                                Samuel 17:15-16</a></li>
                                        <li><a title="rc://en/tn/help/mrk/02/25" href="rc://en/tn/help/mrk/02/25">Mark
                                                02:25-26</a></li>
                                    </ul>
                                    <h2>Word Data:</h2>
                                    <ul>
                                        <li>Strong's: H54, G8</li>
                                    </ul>
                                </div>
                                <div class="questions_editor font_ru flex_middle" dir="ltr">
                                <textarea name="chunks[2]" class="add_questions_editor blind_ta"><h1>Авиафар</h1>
                                    <h2>Определение:</h2>
                                    <p>Авиафар был первосвященником израильского народа во время царя Давида.</p>
                                    <ul>
                                        <li>Когда царь Саул убил священников, Авиафар убежал к Давиду в пустыню.</li>
                                        <li>Авиафар и другой первосвященник по имени Садок на протяжении всего времени царствования Давида верно служили ему.</li>
                                        <li>После смерти Давида Авиафар поддержал Адонию в его попытке стать царём вместо Соломона.</li>
                                        <li>Из-за этого царь Соломон отстранил Авиафара от должности священника.</li>
                                    </ul>
                                    <p>(См. также:&nbsp;
                                        <a href="../names/zadok.md">Садок</a>,&nbsp;
                                        <a href="../names/saul.md">Саул (ВЗ)</a>,&nbsp;
                                        <a href="../names/david.md">Давид</a>,&nbsp;
                                        <a href="../names/solomon.md">Соломон</a>,&nbsp;
                                        <a href="../names/adonijah.md">Адония</a>)</p></textarea>


                                </div>
                                <div class="flex_right">
                                    <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                                </div>
                            </div>
                            <div class="chunk_divider col-sm-12" style="margin-top: 10px"></div>
                        </div>
                        <div class="parent_q questions_chunk" data-question="6" data-chapter="1" data-event="0">
                            <div class="row">
                                <div class="col-md-4"
                                     style="color: #00a74d; font-weight: bold;"> <?php //echo __("verse_number", 6) ?> </div>
                            </div>
                            <div class="flex_container">
                                <div class="question_content flex_left" dir="ltr">
                                    <h1>Abijah</h1>
                                    <h2>Facts:</h2>
                                    <p>Abijah was a king of Judah who reigned from 915 to 913 B.C. He was a son of King
                                        Rehoboam. There were also several other men named Abijah in the Old
                                        Testament:</p>
                                    <ul>
                                        <li>Samuel's sons Abijah and Joel were leaders over the people of Israel at
                                            Beersheba. Because Abijah and his brother were dishonest and greedy, the
                                            people asked Samuel to appoint a king to rule them instead.
                                        </li>
                                        <li>Abijah was one of the temple priests during the time of King David.</li>
                                        <li>Abijah was one of King Jeroboam's sons.</li>
                                        <li>Abijah was also a chief priest who returned with Zerubbabel to Jerusalem
                                            from the Babylonian captivity.
                                        </li>
                                    </ul>
                                    <p>(Translation suggestions:&nbsp;
                                        <a title="rc://en/ta/man/translate/translate-names"
                                           href="rc://en/ta/man/translate/translate-names">Translate Names</a>)</p>
                                    <h2>Bible References:</h2>
                                    <ul>
                                        <li><a title="rc://en/tn/help/1ki/15/01" href="rc://en/tn/help/1ki/15/01">1
                                                Kings 15:1-3</a></li>
                                        <li><a title="rc://en/tn/help/1sa/08/01" href="rc://en/tn/help/1sa/08/01">1
                                                Samuel 08:1-3</a></li>
                                        <li><a title="rc://en/tn/help/2ch/13/01" href="rc://en/tn/help/2ch/13/01">2
                                                Chronicles 13:1-3</a></li>
                                        <li><a title="rc://en/tn/help/2ch/13/19" href="rc://en/tn/help/2ch/13/19">2
                                                Chronicles 13:19-22</a></li>
                                        <li><a title="rc://en/tn/help/luk/01/05" href="rc://en/tn/help/luk/01/05">Luke
                                                01:5-7</a></li>
                                    </ul>
                                    <h2>Word Data:</h2>
                                    <ul>
                                        <li>Strong's: H29, G7</li>
                                    </ul>
                                </div>
                                <div class="questions_editor font_ru flex_middle" dir="ltr">
                                <textarea name="chunks[3]" class="add_questions_editor blind_ta"><h1>Авия</h1>
                                    <h2>Факты:</h2>
                                    <p>Авия был царём, правившим над Иудеей с 915 по 913 гг. до н. э.
                                        Он был сыном царя Иеровоама. В Ветхом Завете встречается ещё несколько человек по имени Авия:</p>
                                    <ul>
                                        <li>Сыновья Самуила Авия и Иоиль правили израильским народом в Вирсавии.
                                            Но из-за того, что Авия и его брат были нечестивыми и жадными,
                                            народ просил Самуила поставить царя, который бы правил страной вместо них.</li>
                                        <li>Другой Авия (по-другому, Авиафор) был одним из священников, служивших в храме во время царя Давида.</li>
                                        <li>Авией звали также одного из сыновей царя Иеровоама.</li>
                                        <li>Авией звали и первосвященника, вернувшегося с Зоровавелем в Иерусалим из вавилонского плена.</li>
                                    </ul>
                                    <p>(Варианты перевода:&nbsp;
                                        <a href="rc://en/ta/man/translate/translate-names" target="_blank" rel="noopener">Как переводить имена</a>)</p></textarea>


                                </div>
                                <div class="flex_right">
                                    <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                                </div>
                            </div>
                            <div class="chunk_divider col-sm-12" style="margin-top: 10px"></div>
                        </div>
                        <div class="parent_q questions_chunk" data-question="7" data-chapter="1" data-event="0">
                            <div class="row">
                                <div class="col-md-4"
                                     style="color: #00a74d; font-weight: bold;"> <?php //echo __("verse_number", 7) ?> </div>
                            </div>
                            <div class="flex_container">
                                <div class="question_content flex_left" dir="ltr">
                                    <h1>Abimelech</h1>
                                    <h2>Facts:</h2>
                                    <p>Abimelech was a Philistine king over the region of Gerar during the time when
                                        Abraham and Isaac were living in the land of Canaan.</p>
                                    <ul>
                                        <li>Abraham deceived King Abimelech by telling him that Sarah was his sister
                                            rather than his wife.
                                        </li>
                                        <li>Abraham and Abimelech made an agreement regarding ownership of wells at
                                            Beersheba.
                                        </li>
                                        <li>Many years later, Isaac also deceived Abimelech and the other men of Gerar
                                            by saying that Rebekah was his sister, not his wife.
                                        </li>
                                        <li>King Abimelech rebuked Abraham, and later Isaac, for lying to him.</li>
                                        <li>Another man by the name of Abimelech was a son of Gideon and a brother of
                                            Jotham.
                                            Some translations may use a slightly different spelling of his name to make
                                            it clear that he is a different person from King Abimelech.
                                        </li>
                                    </ul>
                                    <p>(Translation suggestions:&nbsp;
                                        <a title="rc://en/ta/man/translate/translate-names"
                                           href="rc://en/ta/man/translate/translate-names">How to Translate Names</a>)
                                    </p>
                                    <p>(See also:&nbsp;
                                        <a title="../names/beersheba.md" href="../names/beersheba.md">Beersheba</a>,&nbsp;
                                        <a title="../names/gerar.md" href="../names/gerar.md">Gerar</a>,&nbsp;
                                        <a title="../names/gideon.md" href="../names/gideon.md">Gideon</a>,&nbsp;
                                        <a title="../names/jotham.md" href="../names/jotham.md">Jotham</a>,&nbsp;
                                        <a title="../names/philistines.md"
                                           href="../names/philistines.md">Philistines</a>)</p>
                                    <h2>Bible References:</h2>
                                    <ul>
                                        <li><a title="rc://en/tn/help/2sa/11/21" href="rc://en/tn/help/2sa/11/21">2
                                                Samuel 11:21</a></li>
                                        <li><a title="rc://en/tn/help/gen/20/01" href="rc://en/tn/help/gen/20/01">Genesis
                                                20:1-3</a></li>
                                        <li><a title="rc://en/tn/help/gen/20/04" href="rc://en/tn/help/gen/20/04">Genesis
                                                20:4-5</a></li>
                                        <li><a title="rc://en/tn/help/gen/21/22" href="rc://en/tn/help/gen/21/22">Genesis
                                                21:22-24</a></li>
                                        <li><a title="rc://en/tn/help/gen/26/09" href="rc://en/tn/help/gen/26/09">Genesis
                                                26:9-11</a></li>
                                        <li><a title="rc://en/tn/help/jdg/09/52" href="rc://en/tn/help/jdg/09/52">Judges
                                                09:52-54</a></li>
                                    </ul>
                                    <h2>Word Data:</h2>
                                    <ul>
                                        <li>Strong's: H40</li>
                                    </ul>
                                </div>
                                <div class="questions_editor font_ru flex_middle" dir="ltr">
                                <textarea name="chunks[4]" class="add_questions_editor blind_ta"><h1>Авимелех</h1>
                                    <h2>Факты:</h2>
                                    <p>Авимелех был царём филистимлян и правил в Гераре в то время,
                                        когда Авраам и Исаак странствовали по ханаанской земле.</p>
                                    <ul>
                                        <li>Авраам ввёл Авимелеха в заблуждение, сказав, что Сарра &mdash; его сестра, а не жена.</li>
                                        <li>Авраам и Авимелех заключили союз о владении колодцами в Вирсавии.</li>
                                        <li>Много лет спустя Иссак тоже ввёл в заблуждение Авимелеха и других людей в
                                            Гераре, назвав Ревекку своей сестрой, а не женой.</li>
                                        <li>Царь Авимелех сначала упрекнул Авраама, а позже и Исаака, в том, что они его обманули.</li>
                                        <li>Ещё один человек по имени Авимелех был сыном Гедеона и братом Иофама.
                                            В некоторых переводах может использоваться иное написание этого
                                            имени &mdash; для того, чтобы можно было отличить этого человека от царя Авимелеха.</li>
                                    </ul>
                                    <p>(Варианты перевода: Как переводить имена)</p>
                                    <p>(См. также:&nbsp;
                                        <a href="../names/beersheba.md">Беэр-Шева (Вирсавия)</a>,&nbsp;
                                        <a href="../names/gerar.md">Герар</a>,&nbsp;
                                        <a href="../names/gideon.md">Гедеон</a>,&nbsp;
                                        <a href="../names/jotham.md">Иофам</a>,&nbsp;
                                        <a href="../names/philistines.md">филистимляне</a>)</p></textarea>


                                </div>
                                <div class="flex_right">
                                    <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                                </div>
                            </div>
                            <div class="chunk_divider col-sm-12" style="margin-top: 10px"></div>
                        </div>
                        <div class="parent_q questions_chunk" data-question="8" data-chapter="1" data-event="0">
                            <div class="row">
                                <div class="col-md-4"
                                     style="color: #00a74d; font-weight: bold;"> <?php //echo __("verse_number", 8) ?> </div>
                            </div>
                            <div class="flex_container">
                                <div class="question_content flex_left" dir="ltr">
                                    <h1>Abner</h1>
                                    <h2>Definition:</h2>
                                    <p>Abner was a cousin of King Saul in the Old Testament.</p>
                                    <ul>
                                        <li>Abner was the chief commander of Saul's army, and introduced young David to
                                            Saul
                                            after David killed Goliath the giant.
                                        </li>
                                        <li>After King Saul's death, Abner appointed Saul's son Ishbosheth as king in
                                            Israel, while David was appointed king in Judah.
                                        </li>
                                        <li>Later, Abner was treacherously killed by David's chief commander, Joab.</li>
                                    </ul>
                                    <p>(Translation suggestions:&nbsp;
                                        <a title="rc://en/ta/man/translate/translate-names"
                                           href="rc://en/ta/man/translate/translate-names">How to Translate Names</a>)
                                    </p>
                                    <h2>Bible References:</h2>
                                    <ul>
                                        <li><a title="rc://en/tn/help/1ch/26/26" href="rc://en/tn/help/1ch/26/26">1
                                                Chronicles 26:26-28</a></li>
                                        <li><a title="rc://en/tn/help/1ki/02/05" href="rc://en/tn/help/1ki/02/05">1
                                                Kings 02:5-6</a></li>
                                        <li><a title="rc://en/tn/help/1ki/02/32" href="rc://en/tn/help/1ki/02/32">1
                                                Kings 02:32-33</a></li>
                                        <li><a title="rc://en/tn/help/1sa/17/55" href="rc://en/tn/help/1sa/17/55">1
                                                Samuel 17:55-56</a></li>
                                        <li><a title="rc://en/tn/help/2sa/03/22" href="rc://en/tn/help/2sa/03/22">2
                                                Samuel 03:22-23</a></li>
                                    </ul>
                                    <h2>Word Data:</h2>
                                    <ul>
                                        <li>Strong's: H74</li>
                                    </ul>
                                </div>
                                <div class="questions_editor font_ru flex_middle" dir="ltr">
                                <textarea name="chunks[5]" class="add_questions_editor blind_ta"><h1>Авенир</h1>
                                    <h2>Определение:</h2>
                                    <p>В Ветхом Завете Авенир был двоюродным братом царя Саула.</p>
                                    <ul>
                                        <li>Авенир был военачальником армии Саула. Он привёл к Саулу молодого Давида
                                            после того, как Давид победил Голиафа.</li>
                                        <li>После смерти царя Саула Авенир поставил царём над Израилем Иевосфея,
                                            сына Саула. Давид в это время уже правил Иудеей.</li>
                                        <li>Впоследствии Авенир был коварно убит Иоавом, военачальником Давида.</li>
                                    </ul>
                                    <p>(Варианты перевода:&nbsp;
                                        <a href="rc://en/ta/man/translate/translate-names" target="_blank" rel="noopener">Как переводить имена</a>)</p></textarea>


                                </div>
                                <div class="flex_right">
                                    <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                                </div>
                            </div>
                            <div class="chunk_divider col-sm-12" style="margin-top: 10px"></div>
                        </div>
                        <div class="parent_q questions_chunk" data-question="9" data-chapter="1" data-event="0">
                            <div class="row">
                                <div class="col-md-4"
                                     style="color: #00a74d; font-weight: bold;"> <?php //echo __("verse_number", 9) ?> </div>
                            </div>
                            <div class="flex_container">
                                <div class="question_content flex_left" dir="ltr">
                                    <h1>Abraham, Abram</h1>
                                    <h2>Facts:</h2>
                                    <p>Abram was a Chaldean man from the city of Ur who was chosen by God to be the
                                        forefather of the Israelites. God changed his name to "Abraham."</p>
                                    <ul>
                                        <li>The name "Abram" means "exalted father."</li>
                                        <li>"Abraham" means "father of many."</li>
                                        <li>God promised Abraham that he would have many descendants, who would become a
                                            great nation.
                                        </li>
                                        <li>Abraham believed God and obeyed him. God led Abraham to move from Chaldea to
                                            the land of Canaan.
                                        </li>
                                        <li>Abraham and his wife Sarah, when they were very old and living in the land
                                            of Canaan, had a son, Isaac.
                                        </li>
                                    </ul>
                                    <p>(Translation suggestions:&nbsp;
                                        <a title="rc://en/ta/man/translate/translate-names"
                                           href="rc://en/ta/man/translate/translate-names">Translate Names</a>)</p>
                                    <p>(See also:&nbsp;
                                        <a title="../names/canaan.md" href="../names/canaan.md">Canaan</a>,&nbsp;
                                        <a title="../names/chaldeans.md" href="../names/chaldeans.md">Chaldea</a>,&nbsp;
                                        <a title="../names/sarah.md" href="../names/sarah.md">Sarah</a>,&nbsp;
                                        <a title="../names/isaac.md" href="../names/isaac.md">Isaac</a>)</p>
                                    <h2>Bible References:</h2>
                                    <ul>
                                        <li><a title="rc://en/tn/help/gal/03/06" href="rc://en/tn/help/gal/03/06">Galatians
                                                03:6-9</a></li>
                                        <li><a title="rc://en/tn/help/gen/11/29" href="rc://en/tn/help/gen/11/29">Genesis
                                                11:29-30</a></li>
                                        <li><a title="rc://en/tn/help/gen/21/01" href="rc://en/tn/help/gen/21/01">Genesis
                                                21:1-4</a></li>
                                        <li><a title="rc://en/tn/help/gen/22/01" href="rc://en/tn/help/gen/22/01">Genesis
                                                22:1-3</a></li>
                                        <li><a title="rc://en/tn/help/jas/02/21" href="rc://en/tn/help/jas/02/21">James
                                                02:21-24</a></li>
                                        <li><a title="rc://en/tn/help/mat/01/01" href="rc://en/tn/help/mat/01/01">Matthew
                                                01:1-3</a></li>
                                    </ul>
                                    <h2>Examples from the Bible stories:</h2>
                                    <ul>
                                        <li><strong><a title="rc://en/tn/help/obs/04/06"
                                                       href="rc://en/tn/help/obs/04/06">04:06</a></strong>&nbsp;
                                            When&nbsp;<strong>Abram</strong>&nbsp;arrived in Canaan, God said, "Look all
                                            around you.
                                            I will give to you and your descendants all the land that you can see as an
                                            inheritance."
                                        </li>
                                        <li><strong><a title="rc://en/tn/help/obs/05/04"
                                                       href="rc://en/tn/help/obs/05/04">05:04</a></strong>&nbsp;
                                            Then God changed&nbsp;<strong>Abram</strong>'s name
                                            to&nbsp;<strong>Abraham</strong>, which means "father of many."
                                        </li>
                                        <li><strong><a title="rc://en/tn/help/obs/05/05"
                                                       href="rc://en/tn/help/obs/05/05">05:05</a></strong>&nbsp;
                                            About a year later, when&nbsp;<strong>Abraham</strong>&nbsp;was 100 years
                                            old and Sarah was 90, Sarah gave birth to Abraham's son.
                                        </li>
                                        <li><strong><a title="rc://en/tn/help/obs/05/06"
                                                       href="rc://en/tn/help/obs/05/06">05:06</a></strong>&nbsp;
                                            When Isaac was a young man, God tested&nbsp;<strong>Abraham's</strong>&nbsp;faith
                                            by saying, "Take Isaac,
                                            your only son, and kill him as a sacrifice to me."
                                        </li>
                                        <li><strong><a title="rc://en/tn/help/obs/06/01"
                                                       href="rc://en/tn/help/obs/06/01">06:01</a></strong>&nbsp;
                                            When&nbsp;<strong>Abraham</strong>&nbsp;was very old and his son, Isaac, had
                                            grown to be a man,&nbsp;
                                            <strong>Abraham</strong>&nbsp;sent one of his servants back to the land
                                            where his relatives
                                            lived to find a wife for his son, Isaac.
                                        </li>
                                        <li><strong><a title="rc://en/tn/help/obs/06/04"
                                                       href="rc://en/tn/help/obs/06/04">06:04</a></strong>&nbsp;
                                            After a long time,&nbsp;<strong>Abraham</strong>&nbsp;died and all of the
                                            promises that God had made to him
                                            in the covenant were passed on to Isaac.
                                        </li>
                                        <li><strong><a title="rc://en/tn/help/obs/21/02"
                                                       href="rc://en/tn/help/obs/21/02">21:02</a></strong>&nbsp;
                                            God promised&nbsp;<strong>Abraham</strong>&nbsp;that through him all people
                                            groups of the world would receive a blessing.
                                        </li>
                                    </ul>
                                    <h2>Word Data:</h2>
                                    <ul>
                                        <li>Strong's: H87, H85, G11</li>
                                    </ul>
                                </div>
                                <div class="questions_editor font_ru flex_middle" dir="ltr">
                                <textarea name="chunks[6]" class="add_questions_editor blind_ta"><h1>Авраам, Аврам</h1>
                                    <h2>Факты:</h2>
                                    <p>Аврам был халдеем из города Ур. Бог избрал его быть родоначальником израильского
                                        народа. Бог изменил его имя на "Авраам".</p>
                                    <ul>
                                        <li>Имя "Аврам" означает "достойный отец".</li>
                                        <li>Имя "Авраам" означает "отец многих".</li>
                                        <li>Бог обещал Аврааму произвести от него многочисленных потомков, которые
                                            станут великим народом.</li>
                                        <li>Авраам поверил Богу и был послушен Ему. Бог вывел Авраама из халдейских
                                            земель и повёл его в Ханаан.</li>
                                        <li>Когда Авраам и его жена Сарра состарились и жили в Хараане, у них родился сын Исаак.</li>
                                    </ul>
                                    <p>(Варианты перевода:&nbsp;
                                        <a href="rc://en/ta/man/translate/translate-names" target="_blank" rel="noopener">Как переводить имена</a>)</p></textarea>


                                </div>
                                <div class="flex_right">
                                    <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                                </div>
                            </div>
                            <div class="chunk_divider col-sm-12" style="margin-top: 10px"></div>
                        </div>
                        <div class="parent_q questions_chunk" data-question="10" data-chapter="1" data-event="0">
                            <div class="row">
                                <div class="col-md-4"
                                     style="color: #00a74d; font-weight: bold;"> <?php //echo __("verse_number", 10) ?> </div>
                            </div>
                            <div class="flex_container">
                                <div class="question_content flex_left" dir="ltr">
                                    <h1>Absalom</h1>
                                    <h2>Facts:</h2>
                                    <p>Absalom was the third son of King David. He was known for his handsome appearance
                                        and fiery temperament.</p>
                                    <ul>
                                        <li>When Absalom's sister Tamar was raped by their half-brother, Amnon, Absalom
                                            made a plan to have Amnon killed.
                                        </li>
                                        <li>After the murder of Amnon, Absalom fled to the region of Geshur (where his
                                            mother Maacah was from) and stayed there three years. Then King David sent
                                            for him to come back to Jerusalem, but did not allow Absalom to come into
                                            his presence for two years.
                                        </li>
                                        <li>Absalom turned some of the people against King David and led a revolt
                                            against him.
                                        </li>
                                        <li>David's army fought against Absalom and killed him. David was very grieved
                                            when this happened.
                                        </li>
                                    </ul>
                                    <p>(Translation suggestions:&nbsp;
                                        <a title="rc://en/ta/man/translate/translate-names"
                                           href="rc://en/ta/man/translate/translate-names">How to Translate Names</a>)
                                    </p>
                                    <p>(See also:&nbsp;
                                        <a title="../names/geshur.md" href="../names/geshur.md">Geshur</a>,&nbsp;
                                        <a title="../names/amnon.md" href="../names/amnon.md">Amnon</a>)</p>
                                    <h2>Bible References:</h2>
                                    <ul>
                                        <li><a title="rc://en/tn/help/1ch/03/01" href="rc://en/tn/help/1ch/03/01">1
                                                Chronicles 03:1-3</a></li>
                                        <li><a title="rc://en/tn/help/1ki/01/05" href="rc://en/tn/help/1ki/01/05">1
                                                Kings 01:5-6</a></li>
                                        <li><a title="rc://en/tn/help/2sa/15/01" href="rc://en/tn/help/2sa/15/01">2
                                                Samuel 15:1-2</a></li>
                                        <li><a title="rc://en/tn/help/2sa/17/01" href="rc://en/tn/help/2sa/17/01">2
                                                Samuel 17:1-4</a></li>
                                        <li><a title="rc://en/tn/help/2sa/18/18" href="rc://en/tn/help/2sa/18/18">2
                                                Samuel 18:18</a></li>
                                        <li><a title="rc://en/tn/help/psa/003/001" href="rc://en/tn/help/psa/003/001">Psalm
                                                003:1-2</a></li>
                                    </ul>
                                    <h2>Word Data:</h2>
                                    <ul>
                                        <li>Strong's: H53</li>
                                    </ul>
                                </div>
                                <div class="questions_editor font_ru flex_middle" dir="ltr">
                                <textarea name="chunks[7]" class="add_questions_editor blind_ta"><h1>Авессалом</h1>
                                    <h2>Факты:</h2>
                                    <p>Авесалом был третьим сыном царя Давида. Он прославился своей красотой и вспыльчивым характером.</p>
                                    <ul>
                                        <li>Когда сводный брат Авессалома Амнон изнасиловал его сестру Фамарь, Авессалом замыслил убить Амнона.</li>
                                        <li>После убийства Амнона Авессалом скрылся в Гессуре, откуда была родом его
                                            мать Мааха, и прожил там три года. Потом царь Давид позволил Авессалому
                                            вернуться в Иерусалим, но в течении двух лет не разрешал ему появляться перед ним.</li>
                                        <li>Авессалом настроил часть народа против царя Давида и поднял мятеж против него.</li>
                                        <li>Армия Давида выступила против Авессалома, и он был убит. Давид очень горевал о гибели сына.</li>
                                    </ul>
                                    <p>(Варианты перевода: Как переводить имена)</p>
                                    <p>(См. также:&nbsp;
                                        <a href="../names/geshur.md">Гессур</a>,&nbsp;<a href="../names/amnon.md">Амнон</a>)</p></textarea>


                                </div>
                                <div class="flex_right">
                                    <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                                </div>
                            </div>
                            <div class="chunk_divider col-sm-12" style="margin-top: 10px"></div>
                        </div>
                        <div class="parent_q questions_chunk" data-question="11" data-chapter="1" data-event="0">
                            <div class="row">
                                <div class="col-md-4"
                                     style="color: #00a74d; font-weight: bold;"> <?php //echo __("verse_number", 11) ?> </div>
                            </div>
                            <div class="flex_container">
                                <div class="question_content flex_left" dir="ltr">
                                    <h1>Adam</h1>
                                    <h2>Facts:</h2>
                                    <p>Adam was the first person whom God created. He and his wife Eve were made in the
                                        image of God.</p>
                                    <ul>
                                        <li>God formed Adam from dirt and breathed life into him.</li>
                                        <li>Adam's name sounds similar to the Hebrew word for "red dirt" or "ground."
                                        </li>
                                        <li>The name "Adam" is the same as the Old Testament word for "mankind" or
                                            "human being."
                                        </li>
                                        <li>All people are descendants of Adam and Eve.</li>
                                        <li>Adam and Eve disobeyed God. This separated them from God and caused sin and
                                            death to come into the world.
                                        </li>
                                    </ul>
                                    <p>(Translation suggestions:&nbsp;<a
                                                title="rc://en/ta/man/translate/translate-names"
                                                href="rc://en/ta/man/translate/translate-names">How to Translate
                                            Names</a>)</p>
                                    <p>(See also:&nbsp;<a title="../other/death.md" href="../other/death.md">death</a>,&nbsp;<a
                                                title="../other/descendant.md"
                                                href="../other/descendant.md">descendant</a>,&nbsp;<a
                                                title="../names/eve.md" href="../names/eve.md">Eve</a>,&nbsp;<a
                                                title="../kt/imageofgod.md" href="../kt/imageofgod.md">image of God</a>,&nbsp;<a
                                                title="../kt/life.md" href="../kt/life.md">life</a>)</p>
                                    <h2>Bible References:</h2>
                                    <ul>
                                        <li><a title="rc://en/tn/help/1ti/02/13" href="rc://en/tn/help/1ti/02/13">1
                                                Timothy 02:13-15</a></li>
                                        <li><a title="rc://en/tn/help/gen/03/17" href="rc://en/tn/help/gen/03/17">Genesis
                                                03:17-19</a></li>
                                        <li><a title="rc://en/tn/help/gen/05/01" href="rc://en/tn/help/gen/05/01">Genesis
                                                05:1-2</a></li>
                                        <li><a title="rc://en/tn/help/gen/11/05" href="rc://en/tn/help/gen/11/05">Genesis
                                                11:5-7</a></li>
                                        <li><a title="rc://en/tn/help/luk/03/36" href="rc://en/tn/help/luk/03/36">Luke
                                                03:36-38</a></li>
                                        <li><a title="rc://en/tn/help/rom/05/14" href="rc://en/tn/help/rom/05/14">Romans
                                                05:14-15</a></li>
                                    </ul>
                                    <h2>Examples from the Bible stories:</h2>
                                    <ul>
                                        <li><strong><a title="rc://en/tn/help/obs/01/09"
                                                       href="rc://en/tn/help/obs/01/09">01:09</a></strong>&nbsp;
                                            Then God said, "Let us make human beings in our image to be like us."
                                        </li>
                                        <li><strong><a title="rc://en/tn/help/obs/01/10"
                                                       href="rc://en/tn/help/obs/01/10">01:10</a></strong>&nbsp;
                                            This man's name was&nbsp;<strong>Adam</strong>. God planted a garden where&nbsp;<strong>Adam</strong>&nbsp;could
                                            live, and put him there to care for it.
                                        </li>
                                        <li><strong><a title="rc://en/tn/help/obs/01/12"
                                                       href="rc://en/tn/help/obs/01/12">01:12</a></strong>&nbsp;
                                            Then God said, "It is not good for man to be alone." But none of the animals
                                            could be&nbsp;<strong>Adam's</strong>helper.
                                        </li>
                                        <li><strong><a title="rc://en/tn/help/obs/02/11"
                                                       href="rc://en/tn/help/obs/02/11">02:11</a></strong>&nbsp;
                                            And God clothed&nbsp;<strong>Adam</strong>&nbsp;and Eve with animal skins.
                                        </li>
                                        <li><strong><a title="rc://en/tn/help/obs/02/12"
                                                       href="rc://en/tn/help/obs/02/12">02:12</a></strong>&nbsp;
                                            So God sent&nbsp;<strong>Adam</strong>&nbsp;and Eve away from the beautiful
                                            garden.
                                        </li>
                                        <li><strong><a title="rc://en/tn/help/obs/49/08"
                                                       href="rc://en/tn/help/obs/49/08">49:08</a></strong>&nbsp;
                                            When&nbsp;<strong>Adam</strong>&nbsp;and Eve sinned, it affected all of
                                            their descendants.
                                        </li>
                                        <li><strong><a title="rc://en/tn/help/obs/50/16"
                                                       href="rc://en/tn/help/obs/50/16">50:16</a></strong>&nbsp;
                                            Because&nbsp;<strong>Adam</strong>&nbsp;and Eve disobeyed God and brought
                                            sin into this world, God cursed it and decided to destroy it.
                                        </li>
                                    </ul>
                                    <h2>Word Data:</h2>
                                    <ul>
                                        <li>Strong's: H120, G76</li>
                                    </ul>
                                </div>
                                <div class="questions_editor font_ru flex_middle" dir="ltr">
                                <textarea name="chunks[8]" class="add_questions_editor blind_ta"><h1>Адам</h1>
                                    <h2>Факты:</h2>
                                    <p>Адам был первым человеком, которого создал Бог. Адам и его жена Ева
                                        были сотворены по Божьему образу.</p>
                                    <ul>
                                        <li>Бог создал Адама из пыли и вдохнул в него жизнь.</li>
                                        <li>Имя &laquo;Адам&raquo; созвучно с еврейскими словами &laquo;красная глина&raquo;
                                            или &laquo;земля&raquo;.</li>
                                        <li>Имя &laquo;Адам&raquo; одновременно является ветхозаветным словом,
                                            означающим &laquo;человечество&raquo; или &laquo;человек&raquo;.</li>
                                        <li>Все люди &mdash; потомки Адама и Евы.</li>
                                        <li>Адам и Ева ослушались Бога. Это привело к разделению их с Богом, а также к появлению греха и смерти в мире.</li>
                                    </ul>
                                    <p>(Варианты перевода: Как переводить имена)</p>
                                    <p>(См. Также:&nbsp;
                                        <a href="../other/death.md">смерть, умирать, мертвый</a>,&nbsp;
                                        <a href="../other/descendant.md">потомок</a>,&nbsp;
                                        <a href="../names/eve.md">Ева</a>,&nbsp;
                                        <a href="../kt/imageofgod.md">образ Божий, образ</a>,&nbsp;
                                        <a href="../kt/life.md">жизнь, жить, живой, живущий</a>)</p></textarea>


                                </div>
                                <div class="flex_right">
                                    <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                                </div>
                            </div>
                            <div class="chunk_divider col-sm-12" style="margin-top: 10px"></div>
                        </div>
                    </div>
                </div>

                <div class="main_content_footer row">
                    <div class="form-group">
                        <div class="main_content_confirm_desc"><?php echo __("confirm_finished") ?></div>
                        <label><input name="confirm_step" id="confirm_step" type="checkbox"
                                      value="1"/> <?php echo __("confirm_yes") ?></label>
                    </div>

                    <button id="next_step" type="submit" name="submit" class="btn btn-primary" disabled>
                        <?php echo __($data["next_step"]) ?>
                    </button>
                    <img src="<?php echo template_url("img/saving.gif") ?>" class="unsaved_alert">
                </div>
            </form>
            <div class="step_right alt"><?php echo __("step_num", ["step_number" => 3]) ?></div>
        </div>
    </div>
</div>

<div class="content_help closed">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps is_checker_page_help">
            <div class="help_name_steps">
                <span><?php echo __("step_num", ["step_number" => 1]) ?>:</span> <?php echo __("keyword-check") ?></div>
            <div class="help_descr_steps">
                <ul>
                    <li><b><?php echo __("purpose") ?></b> <?php echo __("keyword-check_tw_purpose") ?></li>
                    <li><?php echo __("keyword-check_tw_help_1") ?></li>
                    <li><?php echo __("keyword-check_tq_help_2") ?></li>
                    <li><?php echo __("keyword-check_tq_help_4") ?></li>
                    <li><?php echo __("keyword-check_tw_help_4", ["icon" => "<span class='mdi mdi-lead-pencil'></span>"]) ?></li>
                    <li><?php echo __("keyword-check_tq_help_6") ?></li>
                    <li><?php echo __("move_to_next_step_alt", ["step" => __($data["next_step"])]) ?></li>
                </ul>
                <div class="show_tutorial_popup"> >>> <?php echo __("show_more") ?></div>
            </div>
        </div>

        <div class="event_info is_checker_page_help">
            <div class="participant_info">
                <div class="additional_info">
                    <a href="/events/demo-tw/information"><?php echo __("event_info") ?></a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="tutorial_container">
    <div class="tutorial_popup">
        <div class="tutorial-close glyphicon glyphicon-remove"></div>
        <div class="tutorial_pic">
            <img src="<?php echo template_url("img/steps/icons/keyword-check.png") ?>" width="100" height="100">
            <img src="<?php echo template_url("img/steps/big/keyword-check.png") ?>" width="280" height="280">

        </div>

        <div class="tutorial_content">
            <h3><?php echo __("keyword-check") ?></h3>
            <ul>
                <li><b><?php echo __("purpose") ?></b> <?php echo __("keyword-check_tw_purpose") ?></li>
                <li><?php echo __("keyword-check_tw_help_1") ?></li>
                <li><?php echo __("keyword-check_tq_help_2") ?></li>
                <li><?php echo __("keyword-check_tq_help_4") ?></li>
                <li><?php echo __("keyword-check_tw_help_4", ["icon" => "<span class='mdi mdi-lead-pencil'></span>"]) ?></li>
                <li><?php echo __("keyword-check_tq_help_6") ?></li>
                <li><?php echo __("move_to_next_step_alt", ["step" => __($data["next_step"])]) ?></li>
            </ul>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#next_step").click(function (e) {
            e.preventDefault();
            if (!hasChangesOnPage) window.location.href = '/events/demo-tw/peer_review';
            return false;
        });
    });
</script>