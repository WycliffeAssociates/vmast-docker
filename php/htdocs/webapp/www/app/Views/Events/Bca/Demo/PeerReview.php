<?php

use Helpers\Constants\EventSteps;

$enableFootNotes = false;
require(app_path() . "Views/Components/CommentEditor.php");
?>

<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="action_type_container">
            <div class="demo_title"><?php echo __("demo") . " (" . __("bca") . ")" ?></div>
            <div class="action_type type_checking"><?php echo __("type_checking1"); ?></div>
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
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="0" style="height: 80px;">
                                <div class="resource_text">Messiah (Christ)</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="0">
                                <textarea name="chunks[0][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; min-height: 90px; height: 90px;">Mesías (Cristo)</textarea>
                                <input name="chunks[0][meta]" type="hidden" value="# {}" />
                                <input name="chunks[0][type]" type="hidden" value="heading_1" />
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
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="1" style="height: 80px;">
                                <div class="resource_text">“Messiah” (מָשִׁיחַ/h4899) means “anointed one.” The word “Christ” (Χριστός/g5547) also means “anointed one” (see: John 1:41). The messiah was prophesied about in the Old Testament. The Old Testament said that the messiah would rescue the nation of Israel. Jesus fulfilled all of these Old Testament prophecies about the messiah. Jesus was the messiah.</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="1">
                                <textarea name="chunks[1][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; min-height: 90px; height: 90px;">“Mesías” (מָשִׁיחַ/h4899) significa “ungido”. La palabra “Cristo” (Χριστός/g5547) también significa “ungido” (ver: Juan 1:41). El mesías fue profetizado en el Antiguo Testamento. El Antiguo Testamento decía que el mesías rescataría a la nación de Israel. Jesús cumplió todas estas profecías del Antiguo Testamento acerca del mesías. Jesús era el mesías.</textarea>
                                <input name="chunks[1][meta]" type="hidden" value="## {}" />
                                <input name="chunks[1][type]" type="hidden" value="heading_2" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <div class="notes_tools">
                                <div class="comments_number"></div>

                                <span class="editComment mdi mdi-lead-pencil" data-chunk="28:1" title="Write a note to the chunk"></span>

                                <div class="comments"></div>
                            </div>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="2" style="height: 80px;">
                                <div class="resource_text">See: Prophesy (Prophecy)</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="2">
                                <textarea name="chunks[2][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; min-height: 90px; height: 90px;">Ver: Profecía (Profecía)</textarea>
                                <input name="chunks[2][meta]" type="hidden" value="### {}" />
                                <input name="chunks[2][type]" type="hidden" value="heading_3" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <div class="notes_tools">
                                <div class="comments_number"></div>

                                <span class="editComment mdi mdi-lead-pencil" data-chunk="28:2" title="Write a note to the chunk"></span>

                                <div class="comments"></div>
                            </div>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="3" style="height: 80px;">
                                <div class="resource_text">More Information About This Topic</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="3">
                                <textarea name="chunks[3][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; min-height: 90px; height: 90px;">Más información sobre este tema</textarea>
                                <input name="chunks[3][meta]" type="hidden" value="" />
                                <input name="chunks[3][type]" type="hidden" value="text" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <div class="notes_tools">
                                <div class="comments_number"></div>

                                <span class="editComment mdi mdi-lead-pencil" data-chunk="28:3" title="Write a note to the chunk"></span>

                                <div class="comments"></div>
                            </div>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="4" style="height: 80px;">
                                <div class="resource_text">In the Old Testament, people anointed with oil anything or anyone that was given to God. The word “anointed” means that oil was poured or sprinkled on them. Different people and things were anointed with oil (see: Exodus 28:41; 40:9; 1 Samuel 16:1-13). The king was sometimes called “God’s anointed” (see: 1 Samuel 2:10, 35).</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="4">
                                <textarea name="chunks[4][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; min-height: 90px; height: 90px;">En el Antiguo Testamento, la gente ungía con aceite cualquier cosa o persona que le fuera dada a Dios. La palabra “ungidos” significa que se les derramó o roció aceite. Diferentes personas y cosas fueron ungidas con aceite (ver: Éxodo 28:41; 40:9; 1 Samuel 16:1-13). A veces se llamaba al rey “el ungido de Dios” (ver: 1 Samuel 2:10, 35).</textarea>
                                <input name="chunks[4][meta]" type="hidden" value="See: [Sabbath](../articles/sabbath.md)" />
                                <input name="chunks[4][type]" type="hidden" value="link" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <div class="notes_tools">
                                <div class="comments_number"></div>

                                <span class="editComment mdi mdi-lead-pencil" data-chunk="28:4" title="Write a note to the chunk"></span>

                                <div class="comments"></div>
                            </div>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="5" style="height: 80px;">
                                <div class="resource_text">When Israel and Judah were sent to Assyria and Babylon for their sinning, God began to speak about the messiah whom he would send. He would send this person to save his people and restore the nation of Israel. Prophecy said that the messiah will be a descendant of King David. The messiah will be a righteous and caring king. Also, he will defeat the enemies of God’s people and will rule forever (see: Psalm 2:2-7; 46:6-7; Isaiah 9:2-7; 11:1-16; Ezekiel 34:1-31; Daniel 7:14). Prophecy also said that the messiah will be the son of God (see: Psalm 2:7; 89:27).</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="5">
                                <textarea name="chunks[5][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; min-height: 90px; height: 90px;">Cuando Israel y Judá fueron enviados a Asiria y Babilonia por su pecado, Dios comenzó a hablar sobre el mesías a quien enviaría. Él enviaría a esta persona para salvar a su pueblo y restaurar la nación de Israel. La profecía decía que el mesías sería descendiente del rey David. El mesías será un rey justo y solidario. Además, vencerá a los enemigos del pueblo de Dios y reinará para siempre (ver: Salmo 2:2-7; 46:6-7; Isaías 9:2-7; 11:1-16; Ezequiel 34:1-31; Daniel 7:14). La profecía también dice que el mesías será el hijo de Dios (ver: Salmo 2:7; 89:27).</textarea>
                                <input name="chunks[5][meta]" type="hidden" value="### {}" />
                                <input name="chunks[5][type]" type="hidden" value="heading_3" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <div class="notes_tools">
                                <div class="comments_number"></div>

                                <span class="editComment mdi mdi-lead-pencil" data-chunk="28:5" title="Write a note to the chunk"></span>

                                <div class="comments"></div>
                            </div>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="6" style="height: 80px;">
                                <div class="resource_text">Jesus fulfilled these prophecies. He is truly God’s messiah or christ. His disciples called him the messiah. Martha (see: John 11:27), Peter (see: Matthew 16:16), and even demons called him the messiah (see: Luke 4:31-35, 41).</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="6">
                                <textarea name="chunks[6][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; min-height: 90px; height: 90px;">Jesús cumplió estas profecías. Él es verdaderamente el mesías o cristo de Dios. Sus discípulos lo llamaron el mesías. Marta (ver: Juan 11:27), Pedro (ver: Mateo 16:16), e incluso los demonios lo llamaron el mesías (ver: Lucas 4:31-35, 41).</textarea>
                                <input name="chunks[6][meta]" type="hidden" value="" />
                                <input name="chunks[6][type]" type="hidden" value="text" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <div class="notes_tools">
                                <div class="comments_number"></div>

                                <span class="editComment mdi mdi-lead-pencil" data-chunk="28:6" title="Write a note to the chunk"></span>

                                <div class="comments"></div>
                            </div>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="7" style="height: 111px;">
                                <div class="resource_text">Jesus rarely used the words “messiah” or “christ” to talk about himself. He did say that God anointed him. He spoke about Old Testament verses about the messiah. He used these verses to talk about his purpose for coming to the earth (see: Luke 4:18; see also: Isaiah 61:1,2). After he was made alive again, Jesus said he fulfilled the Old Testament promises about the messiah (see: Luke 24:27, 44-49).</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="7">
                                <textarea name="chunks[7][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; min-height: 121px; height: 142px;">Jesús rara vez usó las palabras “mesías” o “cristo” para hablar de sí mismo. Él dijo que Dios lo ungió. Habló de los versículos del Antiguo Testamento sobre el mesías. Usó estos versículos para hablar sobre su propósito al venir a la tierra (ver: Lucas 4:18; ver también: Isaías 61:1,2). Después de resucitar, Jesús dijo que cumplió las promesas del Antiguo Testamento acerca del mesías (ver: Lucas 24:27, 44-49).</textarea>
                                <input name="chunks[7][meta]" type="hidden" value="" />
                                <input name="chunks[7][type]" type="hidden" value="text" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <div class="notes_tools">
                                <div class="comments_number"></div>

                                <span class="editComment mdi mdi-lead-pencil" data-chunk="28:7" title="Write a note to the chunk"></span>

                                <div class="comments"></div>
                            </div>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="8" style="height: 80px;">
                                <div class="resource_text">Jesus was also called God’s son (see: Matthew 3:17; Mark 1:1-3; Luke 3:21-22), a descendant of David (see: Matthew 1:1-17; Luke 3:23-38; Romans 1:3), and a king who will rule forever and ever (see: Revelation 11:15). All these things fulfilled prophecies about the messiah.</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="8">
                                <textarea name="chunks[8][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; min-height: 90px; height: 90px;">Jesús también fue llamado hijo de Dios (ver: Mateo 3:17; Marcos 1:1-3; Lucas 3:21-22), descendiente de David (ver: Mateo 1:1-17; Lucas 3:23-38; Romanos 1:3), y un rey que reinará por los siglos de los siglos (ver: Apocalipsis 11:15). Todas estas cosas cumplieron profecías sobre el mesías.</textarea>
                                <input name="chunks[8][meta]" type="hidden" value="See: [Sabbath](../articles/sabbath.md)" />
                                <input name="chunks[8][type]" type="hidden" value="link" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <div class="notes_tools">
                                <div class="comments_number"></div>

                                <span class="editComment mdi mdi-lead-pencil" data-chunk="28:8" title="Write a note to the chunk"></span>

                                <div class="comments"></div>
                            </div>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="9" style="height: 80px;">
                                <div class="resource_text">See: Anoint (Anointing); Sin; Righteous (Righteousness); People of God; Son of God</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="9">
                                <textarea name="chunks[9][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; min-height: 90px; height: 90px;">Ver: Ungir (Unción); Pecado; Justo (Justicia); Pueblo de Dios; Hijo de Dios</textarea>
                                <input name="chunks[9][meta]" type="hidden" value="### {}" />
                                <input name="chunks[9][type]" type="hidden" value="heading_3" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <div class="notes_tools">
                                <div class="comments_number"></div>

                                <span class="editComment mdi mdi-lead-pencil" data-chunk="28:9" title="Write a note to the chunk"></span>

                                <div class="comments"></div>
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
                <div class="step_right"><?php echo __("step_num", ["step_number" => 2]) ?></div>
            </div>
        </div>
    </div>
</div>

<div class="content_help closed">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps is_checker_page_help">
            <div class="help_name_steps">
                <span><?php echo __("step_num", ["step_number" => 2]) ?>:</span> <?php echo __(EventSteps::PEER_REVIEW . "_bca") ?>
            </div>
            <div class="help_descr_steps">
                <ul><?php echo __("peer-review_bc_desc", ["step" => __($data["next_step"])]) ?></ul>
                <div class="show_tutorial_popup"> >>> <?php echo __("show_more") ?></div>
            </div>
        </div>

        <div class="event_info is_checker_page_help">
            <div class="participant_info">
                <div class="participant_name">
                    <span><?php echo __("your_checker") ?>:</span>
                    <span class="checker_name_span">
                                John C.
                            </span>
                </div>
                <div class="additional_info">
                    <a href="/events/demo-bca/information"><?php echo __("event_info") ?></a>
                </div>
            </div>
        </div>

        <div class="checker_view">
            <a href="/events/demo-bca/peer_review_checker"><?php echo __("checker_other_view", [2]) ?></a>
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

        <div class="tutorial_content is_checker_page_help">
            <h3><?php echo __(EventSteps::PEER_REVIEW . "_bca") ?></h3>
            <ul><?php echo __("peer-review_bc_desc", ["step" => __($data["next_step"])]) ?></ul>
        </div>
    </div>
</div>

<script>
    var isChecker = true;

    $(document).ready(function () {
        $("#next_step").click(function (e) {
            e.preventDefault();
            if (!hasChangesOnPage) window.location.href = '/events/demo-bca/pray';
            return false;
        });
    });
</script>