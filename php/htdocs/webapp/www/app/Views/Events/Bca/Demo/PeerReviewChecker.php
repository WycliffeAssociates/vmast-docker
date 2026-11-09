<?php

use Helpers\Constants\EventSteps;

$enableFootNotes = false;
require(app_path() . "Views/Components/CommentEditor.php");
?>

<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="action_type_container">
            <div class="demo_title"><?php echo __("demo") . " (" . __("bca") . ")" ?></div>
            <div class="action_type type_checking isPeer"><?php echo __("type_checking2"); ?></div>
            <div class="action_region"></div>
        </div>
        <div class="main_content_title">
            <div><?php echo __("step_num", ["step_number" => 2]) . ": " . __(EventSteps::PEER_REVIEW . "_bca") ?></div>
        </div>
    </div>

    <div class="">
        <div class="main_content">
            <div class="main_content_text" dir="ltr">
                <h4>Español - <span class='book_name'><?php echo __("bca") ?> - messiahchrist</span></h4>

                <div class="col-sm-12 no_padding">
                    <div class="row chunk_block chunk_block_divider">
                        <div class="flex_container">
                            <div class="chunk_verses flex_left" dir="ltr">
                                <div class="resource_chunk no_margin" data-chunk="0" style="height: 43px;">
                                    <div class="resource_text">Messiah (Christ)</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="0" style="height: 43px;"><span>Mesías (Cristo)</span></div>
                                <div class="chunk_checker" data-chunk="0">
                                    Mesías (Cristo)
                                </div>
                            </div>
                            <div class="flex_right">
                                <?php
                                $comments = $data["comments"][0];
                                $hasComments = !empty($comments);
                                $commentsNumber = sizeof($comments);
                                $myMemberID = 0;
                                require(app_path() . "Views/Components/Comments.php");
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="row chunk_block chunk_block_divider">
                        <div class="flex_container">
                            <div class="chunk_verses flex_left" dir="ltr">
                                <div class="resource_chunk no_margin" data-chunk="1" style="height: 120px;">
                                    <div class="resource_text">“Messiah” (מָשִׁיחַ/h4899) means “anointed one.” The word “Christ” (Χριστός/g5547) also means “anointed one” (see: John 1:41). The messiah was prophesied about in the Old Testament. The Old Testament said that the messiah would rescue the nation of Israel. Jesus fulfilled all of these Old Testament prophecies about the messiah. Jesus was the messiah.</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="1" style="height: 120px;"><span>“Mesías” (מָשִׁיחַ/h4899) significa “ungido”. La palabra “Cristo” (Χριστός/g5547) también significa “ungido” (ver: Juan 1:41). El mesías fue profetizado en el Antiguo Testamento. El Antiguo Testamento decía que el mesías rescataría a la nación de Israel. Jesús cumplió todas estas profecías del Antiguo Testamento acerca del mesías. Jesús era el mesías.</span></div>
                                <div class="chunk_checker" data-chunk="1">
                                    “Mesías” (מָשִׁיחַ/h4899) significa “ungido”. La palabra “Cristo” (Χριστός/g5547) también significa “ungido” (ver: Juan 1:41). El mesías fue profetizado en el Antiguo Testamento. El Antiguo Testamento decía que el mesías rescataría a la nación de Israel. Jesús cumplió todas estas profecías del Antiguo Testamento acerca del mesías. Jesús era el mesías.
                                </div>
                            </div>
                            <div class="flex_right">
                                <?php $hasComments = false; require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row chunk_block chunk_block_divider">
                        <div class="flex_container">
                            <div class="chunk_verses flex_left" dir="ltr">
                                <div class="resource_chunk no_margin" data-chunk="2" style="height: 43px;">
                                    <div class="resource_text">See: Prophesy (Prophecy)</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="2" style="height: 43px;"><del style="background: #ffe6e6;">Wrong text has been replaced by checker</del><ins style="background: #e6ffe6;">Ver: Profecía (Profecía)</ins></div>
                                <div class="chunk_checker" data-chunk="2">
                                    Ver: Profecía (Profecía)
                                </div>
                            </div>
                            <div class="flex_right">
                                <?php require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row chunk_block chunk_block_divider">
                        <div class="flex_container">
                            <div class="chunk_verses flex_left" dir="ltr">
                                <div class="resource_chunk no_margin" data-chunk="3" style="height: 43px;">
                                    <div class="resource_text">More Information About This Topic</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="3" style="height: 43px;"><span>Más información sobre este tema</span></div>
                                <div class="chunk_checker" data-chunk="3">
                                    Más información sobre este tema
                                </div>
                            </div>
                            <div class="flex_right">
                                <?php require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row chunk_block chunk_block_divider">
                        <div class="flex_container">
                            <div class="chunk_verses flex_left" dir="ltr">
                                <div class="resource_chunk no_margin" data-chunk="4" style="height: 120px;">
                                    <div class="resource_text">In the Old Testament, people anointed with oil anything or anyone that was given to God. The word “anointed” means that oil was poured or sprinkled on them. Different people and things were anointed with oil (see: Exodus 28:41; 40:9; 1 Samuel 16:1-13). The king was sometimes called “God’s anointed” (see: 1 Samuel 2:10, 35).</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="4" style="height: 43px;"><span>In the Old Testament, people anointed with oil anything or anyone that was given to God. The word “anointed” means that oil was poured or sprinkled on them. Different people and things were anointed with oil (see: Exodus 28:41; 40:9; 1 Samuel 16:1-13). The king was sometimes called “God’s anointed” (see: 1 Samuel 2:10, 35).</span></div>
                                <div class="chunk_checker" data-chunk="4">
                                    In the Old Testament, people anointed with oil anything or anyone that was given to God. The word “anointed” means that oil was poured or sprinkled on them. Different people and things were anointed with oil (see: Exodus 28:41; 40:9; 1 Samuel 16:1-13). The king was sometimes called “God’s anointed” (see: 1 Samuel 2:10, 35).
                                </div>
                            </div>
                            <div class="flex_right">
                                <?php require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row chunk_block chunk_block_divider">
                        <div class="flex_container">
                            <div class="chunk_verses flex_left" dir="ltr">
                                <div class="resource_chunk no_margin" data-chunk="5" style="height: 210px;">
                                    <div class="resource_text">When Israel and Judah were sent to Assyria and Babylon for their sinning, God began to speak about the messiah whom he would send. He would send this person to save his people and restore the nation of Israel. Prophecy said that the messiah will be a descendant of King David. The messiah will be a righteous and caring king. Also, he will defeat the enemies of God’s people and will rule forever (see: Psalm 2:2-7; 46:6-7; Isaiah 9:2-7; 11:1-16; Ezekiel 34:1-31; Daniel 7:14). Prophecy also said that the messiah will be the son of God (see: Psalm 2:7; 89:27).</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="5" style="height: 43px;"><span>Cuando Israel y Judá fueron enviados a Asiria y Babilonia por su pecado, Dios comenzó a hablar sobre el mesías a quien enviaría. Él enviaría a esta persona para salvar a su pueblo y restaurar la nación de Israel. La profecía decía que el mesías sería descendiente del rey David. El mesías será un rey justo y solidario. Además, vencerá a los enemigos del pueblo de Dios y reinará para siempre (ver: Salmo 2:2-7; 46:6-7; Isaías 9:2-7; 11:1-16; Ezequiel 34:1-31; Daniel 7:14). La profecía también dice que el mesías será el hijo de Dios (ver: Salmo 2:7; 89:27).</span></div>
                                <div class="chunk_checker" data-chunk="5">
                                    Cuando Israel y Judá fueron enviados a Asiria y Babilonia por su pecado, Dios comenzó a hablar sobre el mesías a quien enviaría. Él enviaría a esta persona para salvar a su pueblo y restaurar la nación de Israel. La profecía decía que el mesías sería descendiente del rey David. El mesías será un rey justo y solidario. Además, vencerá a los enemigos del pueblo de Dios y reinará para siempre (ver: Salmo 2:2-7; 46:6-7; Isaías 9:2-7; 11:1-16; Ezequiel 34:1-31; Daniel 7:14). La profecía también dice que el mesías será el hijo de Dios (ver: Salmo 2:7; 89:27).
                                </div>
                            </div>
                            <div class="flex_right">
                                <?php require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row chunk_block chunk_block_divider">
                        <div class="flex_container">
                            <div class="chunk_verses flex_left" dir="ltr">
                                <div class="resource_chunk no_margin" data-chunk="6" style="height: 90px;">
                                    <div class="resource_text">Jesus fulfilled these prophecies. He is truly God’s messiah or christ. His disciples called him the messiah. Martha (see: John 11:27), Peter (see: Matthew 16:16), and even demons called him the messiah (see: Luke 4:31-35, 41).</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="6" style="height: 43px;"><span>Jesús cumplió estas profecías. Él es verdaderamente el mesías o cristo de Dios. Sus discípulos lo llamaron el mesías. Marta (ver: Juan 11:27), Pedro (ver: Mateo 16:16), e incluso los demonios lo llamaron el mesías (ver: Lucas 4:31-35, 41).</span></div>
                                <div class="chunk_checker" data-chunk="6">
                                    Jesús cumplió estas profecías. Él es verdaderamente el mesías o cristo de Dios. Sus discípulos lo llamaron el mesías. Marta (ver: Juan 11:27), Pedro (ver: Mateo 16:16), e incluso los demonios lo llamaron el mesías (ver: Lucas 4:31-35, 41).
                                </div>
                            </div>
                            <div class="flex_right">
                                <?php require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row chunk_block chunk_block_divider">
                        <div class="flex_container">
                            <div class="chunk_verses flex_left" dir="ltr">
                                <div class="resource_chunk no_margin" data-chunk="7" style="height: 89px;">
                                    <div class="resource_text">Jesus rarely used the words “messiah” or “christ” to talk about himself. He did say that God anointed him. He spoke about Old Testament verses about the messiah. He used these verses to talk about his purpose for coming to the earth (see: Luke 4:18; see also: Isaiah 61:1,2). After he was made alive again, Jesus said he fulfilled the Old Testament promises about the messiah (see: Luke 24:27, 44-49).</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="7" style="height: 130px;">
                    <span>Jesús rara vez usó las palabras “mesías” o “cristo” para hablar de sí mismo. Él dijo que Dios lo ungió. Habló de los versículos del Antiguo Testamento sobre el mesías. Usó estos versículos para hablar sobre su propósito al venir a la tierra (ver: Lucas 4:18; ver también: Isaías 61:1,2). Después de resucitar, Jesús dijo que cumplió las promesas del Antiguo Testamento acerca del mesías (ver: Lucas 24:27, 44-49).</span>
                                </div>
                                <div class="chunk_checker" data-chunk="7">
                                    Jesús rara vez usó las palabras “mesías” o “cristo” para hablar de sí mismo. Él dijo que Dios lo ungió. Habló de los versículos del Antiguo Testamento sobre el mesías. Usó estos versículos para hablar sobre su propósito al venir a la tierra (ver: Lucas 4:18; ver también: Isaías 61:1,2). Después de resucitar, Jesús dijo que cumplió las promesas del Antiguo Testamento acerca del mesías (ver: Lucas 24:27, 44-49).
                                </div>
                            </div>
                            <div class="flex_right">
                                <?php require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row chunk_block chunk_block_divider">
                        <div class="flex_container">
                            <div class="chunk_verses flex_left" dir="ltr">
                                <div class="resource_chunk no_margin" data-chunk="8" style="height: 90px;">
                                    <div class="resource_text">Jesus was also called God’s son (see: Matthew 3:17; Mark 1:1-3; Luke 3:21-22), a descendant of David (see: Matthew 1:1-17; Luke 3:23-38; Romans 1:3), and a king who will rule forever and ever (see: Revelation 11:15). All these things fulfilled prophecies about the messiah.</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="8" style="height: 43px;"><span>Jesús también fue llamado hijo de Dios (ver: Mateo 3:17; Marcos 1:1-3; Lucas 3:21-22), descendiente de David (ver: Mateo 1:1-17; Lucas 3:23-38; Romanos 1:3), y un rey que reinará por los siglos de los siglos (ver: Apocalipsis 11:15). Todas estas cosas cumplieron profecías sobre el mesías.</span></div>
                                <div class="chunk_checker" data-chunk="8">
                                    Jesús también fue llamado hijo de Dios (ver: Mateo 3:17; Marcos 1:1-3; Lucas 3:21-22), descendiente de David (ver: Mateo 1:1-17; Lucas 3:23-38; Romanos 1:3), y un rey que reinará por los siglos de los siglos (ver: Apocalipsis 11:15). Todas estas cosas cumplieron profecías sobre el mesías.
                                </div>
                            </div>
                            <div class="flex_right">
                                <?php require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row chunk_block chunk_block_divider">
                        <div class="flex_container">
                            <div class="chunk_verses flex_left" dir="ltr">
                                <div class="resource_chunk no_margin" data-chunk="9" style="height: 43px;">
                                    <div class="resource_text">See: Anoint (Anointing); Sin; Righteous (Righteousness); People of God; Son of God</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="9" style="height: 43px;"><span>Ver: Ungir (Unción); Pecado; Justo (Justicia); Pueblo de Dios; Hijo de Dios</span></div>
                                <div class="chunk_checker" data-chunk="9">
                                    Ver: Ungir (Unción); Pecado; Justo (Justicia); Pueblo de Dios; Hijo de Dios
                                </div>
                            </div>
                            <div class="flex_right">
                                <?php require(app_path() . "Views/Components/Comments.php"); ?>
                            </div>
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

                    <button id="checker_ready" class="btn btn-warning" disabled>
                        <?php echo __("ready_to_check")?>
                    </button>
                    <button id="next_step" class="btn btn-primary" disabled="disabled">
                        <?php echo __($data["next_step"]) ?>
                    </button>
                </form>
                <div class="step_right"><?php echo __("step_num", ["step_number" => 2]) ?></div>
            </div>
        </div>
    </div>
</div>

<div class="content_help closed">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps is_checker_page_help isPeer">
            <div class="help_name_steps">
                <span><?php echo __("step_num", ["step_number" => 2]) ?>:</span> <?php echo __(EventSteps::PEER_REVIEW . "_bca") ?>
            </div>
            <div class="help_descr_steps">
                <ul><?php echo __("peer-review_bc_chk_desc", ["step" => __($data["next_step"])]) ?></ul>
                <div class="show_tutorial_popup"> >>> <?php echo __("show_more") ?></div>
            </div>
        </div>

        <div class="event_info is_checker_page_help isPeer">
            <div class="participant_info">
                <div class="participant_name">
                    <span><?php echo __("your_partner") ?>:</span>
                    <span class="checker_name_span">
                                Ketut S.
                            </span>
                </div>
                <div class="additional_info">
                    <a href="/events/demo-bca/information"><?php echo __("event_info") ?></a>
                </div>
            </div>
        </div>

        <div class="checker_view">
            <a href="/events/demo-bca/peer_review"><?php echo __("checker_other_view", [1]) ?></a>
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

        <div class="tutorial_content is_checker_page_help isPeer">
            <h3><?php echo __(EventSteps::PEER_REVIEW . "_bc") ?></h3>
            <ul><?php echo __("peer-review_bc_chk_desc", ["step" => __($data["next_step"])]) ?></ul>
        </div>
    </div>
</div>

<script>
    var isChecker = true;

    $(document).ready(function () {
        $("#next_step").click(function (e) {
            e.preventDefault();
            window.location.href = '/events/demo-bca/peer_review';
            return false;
        });
    });
</script>