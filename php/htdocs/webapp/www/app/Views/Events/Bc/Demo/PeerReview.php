<?php

use Helpers\Constants\EventSteps;

$enableFootNotes = false;
require(app_path() . "Views/Components/CommentEditor.php");
?>

<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="action_type_container">
            <div class="demo_title"><?php echo __("demo") . " (" . __("bc") . ")" ?></div>
            <div class="action_type type_checking"><?php echo __("type_checking1"); ?></div>
            <div class="action_region"></div>
        </div>
        <div class="main_content_title">
            <div><?php echo __("step_num", ["step_number" => 2]) . ": " . __(EventSteps::PEER_REVIEW . "_bc") ?></div>
        </div>
    </div>

    <div class="">
        <div class="main_content">
            <div class="main_content_text" dir="ltr">
                <h4>Español - <span class='book_name'><?php echo __("bc") ?> - Matthew 4</span></h4>

                <div class="col-sm-12 no_padding">
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="0" style="height: 80px;">
                                <div class="resource_text">Matthew 28</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="0">
                                <textarea name="chunks[0][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 80px; min-height: 90px;">Mateo 28</textarea>
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
                                <div class="resource_text">28:1-10</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="1">
                                <textarea name="chunks[1][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 80px; min-height: 90px;">28:1-10</textarea>
                                <input name="chunks[1][meta]" type="hidden" value="## {}" />
                                <input name="chunks[1][type]" type="hidden" value="heading_2" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php $hasComments = false; require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="2" style="height: 80px;">
                                <div class="resource_text">What was the Sabbath?</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="2">
                                <textarea name="chunks[2][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 80px; min-height: 90px;">¿Qué era el sábado?</textarea>
                                <input name="chunks[2][meta]" type="hidden" value="### {}" />
                                <input name="chunks[2][type]" type="hidden" value="heading_3" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="3" style="height: 80px;">
                                <div class="resource_text">[28:1]</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="3">
                                <textarea name="chunks[3][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 80px; min-height: 90px;">[28:1]</textarea>
                                <input name="chunks[3][meta]" type="hidden" value="" />
                                <input name="chunks[3][type]" type="hidden" value="text" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="4" style="height: 80px;">
                                <div class="resource_text">See: Sabbath</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="4">
                                <textarea name="chunks[4][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 80px; min-height: 90px;">Ver: Sábado</textarea>
                                <input name="chunks[4][meta]" type="hidden" value="See: [Sabbath](../articles/sabbath.md)" />
                                <input name="chunks[4][type]" type="hidden" value="link" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="5" style="height: 80px;">
                                <div class="resource_text">What day was the first day of the week?</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="5">
                                <textarea name="chunks[5][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 80px; min-height: 90px;">¿Qué día fue el primer día de la semana?</textarea>
                                <input name="chunks[5][meta]" type="hidden" value="### {}" />
                                <input name="chunks[5][type]" type="hidden" value="heading_3" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="6" style="height: 80px;">
                                <div class="resource_text">[28:1]</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="6">
                                <textarea name="chunks[6][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 80px; min-height: 90px;">[28:1]</textarea>
                                <input name="chunks[6][meta]" type="hidden" value="" />
                                <input name="chunks[6][type]" type="hidden" value="text" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="7" style="height: 111px;">
                                <div class="resource_text">
                                    The Sabbath began on Friday at sunset and ended Saturday at sunset. The day after the Sabbath was the first day of the week. This day began on Saturday at sunset and ended on Sunday at sunset.
                                </div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="7">
                <textarea name="chunks[7][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 111px; min-height: 121px;">
El sábado comenzaba el viernes al atardecer y terminaba el sábado al atardecer. El día después del sábado era el primer día de la semana. Este día comenzaba el sábado al atardecer y terminaba el domingo al atardecer.
                </textarea>
                                <input name="chunks[7][meta]" type="hidden" value="" />
                                <input name="chunks[7][type]" type="hidden" value="text" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="8" style="height: 80px;">
                                <div class="resource_text">See: Sabbath</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="8">
                                <textarea name="chunks[8][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 80px; min-height: 90px;">Ver: Sábado</textarea>
                                <input name="chunks[8][meta]" type="hidden" value="See: [Sabbath](../articles/sabbath.md)" />
                                <input name="chunks[8][type]" type="hidden" value="link" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="9" style="height: 80px;">
                                <div class="resource_text">Why did the angel roll away the stone?</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="9">
                                <textarea name="chunks[9][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 80px; min-height: 90px;">¿Por qué el ángel hizo rodar la piedra?</textarea>
                                <input name="chunks[9][meta]" type="hidden" value="### {}" />
                                <input name="chunks[9][type]" type="hidden" value="heading_3" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="10" style="height: 80px;">
                                <div class="resource_text">[28:2]</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="10">
                                <textarea name="chunks[10][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 80px; min-height: 90px;">[28:2]</textarea>
                                <input name="chunks[10][meta]" type="hidden" value="" />
                                <input name="chunks[10][type]" type="hidden" value="text" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="11" style="height: 80px;">
                                <div class="resource_text">The angel rolled away the stone because it was very large.</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="11">
                                <textarea name="chunks[11][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 80px; min-height: 90px;">El ángel hizo rodar la piedra porque era muy grande.</textarea>
                                <input name="chunks[11][meta]" type="hidden" value="" />
                                <input name="chunks[11][type]" type="hidden" value="text" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="12" style="height: 80px;">
                                <div class="resource_text">See: Angel; Heaven</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="12">
                                <textarea name="chunks[12][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 80px; min-height: 90px;">Ver: Ver: Ángel; Cielo; Cielo</textarea>
                                <input name="chunks[12][meta]" type="hidden" value="See: [Angel](../articles/angel.md); [Heaven](../articles/heaven.md)" />
                                <input name="chunks[12][type]" type="hidden" value="link" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="13" style="height: 80px;">
                                <div class="resource_text">Why did the angel look the way he did?</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="13">
                                <textarea name="chunks[13][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 80px; min-height: 90px;">¿Por qué el ángel se veía de la manera que lo hizo?</textarea>
                                <input name="chunks[13][meta]" type="hidden" value="### {}" />
                                <input name="chunks[13][type]" type="hidden" value="heading_3" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="14" style="height: 80px;">
                                <div class="resource_text">[28:3]</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="14">
                                <textarea name="chunks[14][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 80px; min-height: 90px;">[28:3]</textarea>
                                <input name="chunks[14][meta]" type="hidden" value="" />
                                <input name="chunks[14][type]" type="hidden" value="text" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="15" style="height: 80px;">
                                <div class="resource_text">The angel looked the way he did because he was holy. White was a symbol of someone or something being holy.</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="15">
                <textarea name="chunks[15][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 80px; min-height: 90px;">
El ángel tenía el aspecto que tenía porque era santo. El blanco era un símbolo de que alguien o algo era santo.
                </textarea>
                                <input name="chunks[15][meta]" type="hidden" value="" />
                                <input name="chunks[15][type]" type="hidden" value="text" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="16" style="height: 80px;">
                                <div class="resource_text">See: Angel; Holy (Holiness, Set Apart); White (symbol)</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="16">
                <textarea name="chunks[16][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 80px; min-height: 90px;">
Ver: Ángel; Santo (Santidad, Apartado); Blanco (símbolo)
                </textarea>
                                <input name="chunks[16][meta]" type="hidden" value="See: [Angel](../articles/angel.md); [Holy (Holiness, Set Apart)](../articles/holy.md); [White (symbol)](../articles/white.md)" />
                                <input name="chunks[16][type]" type="hidden" value="link" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="17" style="height: 80px;">
                                <div class="resource_text">How was Jesus crucified?</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="17">
                                <textarea name="chunks[17][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 80px; min-height: 90px;">¿Cómo fue crucificado Jesús?</textarea>
                                <input name="chunks[17][meta]" type="hidden" value="### {}" />
                                <input name="chunks[17][type]" type="hidden" value="heading_3" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="18" style="height: 80px;">
                                <div class="resource_text">[28:5]</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="18">
                                <textarea name="chunks[18][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 80px; min-height: 90px;">[28:5]</textarea>
                                <input name="chunks[18][meta]" type="hidden" value="" />
                                <input name="chunks[18][type]" type="hidden" value="text" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="19" style="height: 80px;">
                                <div class="resource_text">See: Crucify (Crucifixion)</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="19">
                                <textarea name="chunks[19][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 80px; min-height: 90px;">Ver: Crucificar (Crucifixión)</textarea>
                                <input name="chunks[19][meta]" type="hidden" value="See: [Crucify (Crucifixion)](../articles/crucify.md)" />
                                <input name="chunks[19][type]" type="hidden" value="link" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="20" style="height: 80px;">
                                <div class="resource_text">How was Jesus resurrected?</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="20">
                                <textarea name="chunks[20][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 80px; min-height: 90px;">¿Cómo resucitó Jesús?</textarea>
                                <input name="chunks[20][meta]" type="hidden" value="### {}" />
                                <input name="chunks[20][type]" type="hidden" value="heading_3" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="21" style="height: 80px;">
                                <div class="resource_text">[28:5, 28:6]</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="21">
                                <textarea name="chunks[21][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 80px; min-height: 90px;">[28:5, 28:6]</textarea>
                                <input name="chunks[21][meta]" type="hidden" value="" />
                                <input name="chunks[21][type]" type="hidden" value="text" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="22" style="height: 80px;">
                                <div class="resource_text">The man in the tomb said that Jesus was risen. That is, Jesus was resurrected.</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="22">
                <textarea name="chunks[22][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 80px; min-height: 90px;">
El hombre en la tumba dijo que Jesús había resucitado. Es decir, Jesús resucitó.
                </textarea>
                                <input name="chunks[22][meta]" type="hidden" value="" />
                                <input name="chunks[22][type]" type="hidden" value="text" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="23" style="height: 80px;">
                                <div class="resource_text">See: Resurrect (Resurrection)</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="23">
                                <textarea name="chunks[23][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 80px; min-height: 90px;">Ver: Resucitar (Resurrección)</textarea>
                                <input name="chunks[23][meta]" type="hidden" value="See: [Resurrect (Resurrection)](../articles/resurrect.md)" />
                                <input name="chunks[23][type]" type="hidden" value="link" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="24" style="height: 80px;">
                                <div class="resource_text">Where was Galilee?</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="24">
                                <textarea name="chunks[24][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 80px; min-height: 90px;">¿Dónde estaba Galilea?</textarea>
                                <input name="chunks[24][meta]" type="hidden" value="### {}" />
                                <input name="chunks[24][type]" type="hidden" value="heading_3" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="25" style="height: 80px;">
                                <div class="resource_text">[28:7]</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="25">
                                <textarea name="chunks[25][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 80px; min-height: 90px;">[28:7]</textarea>
                                <input name="chunks[25][meta]" type="hidden" value="" />
                                <input name="chunks[25][type]" type="hidden" value="text" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="26" style="height: 80px;">
                                <div class="resource_text">See Map: Galilee</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="26">
                                <textarea name="chunks[26][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 80px; min-height: 90px;">Ver Mapa: Galilea</textarea>
                                <input name="chunks[26][meta]" type="hidden" value="" />
                                <input name="chunks[26][type]" type="hidden" value="text" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="27" style="height: 80px;">
                                <div class="resource_text">What was worship?</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="27">
                                <textarea name="chunks[27][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 80px; min-height: 90px;">¿Qué era la adoración?</textarea>
                                <input name="chunks[27][meta]" type="hidden" value="### {}" />
                                <input name="chunks[27][type]" type="hidden" value="heading_3" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="28" style="height: 80px;">
                                <div class="resource_text">[28:9]</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="28">
                                <textarea name="chunks[28][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 80px; min-height: 90px;">[28:9]</textarea>
                                <input name="chunks[28][meta]" type="hidden" value="" />
                                <input name="chunks[28][type]" type="hidden" value="text" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="29" style="height: 80px;">
                                <div class="resource_text">See: Worship</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="29">
                                <textarea name="chunks[29][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 80px; min-height: 90px;">Ver: Adoración</textarea>
                                <input name="chunks[29][meta]" type="hidden" value="See: [Worship](../articles/worship.md)" />
                                <input name="chunks[29][type]" type="hidden" value="link" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="30" style="height: 80px;">
                                <div class="resource_text">28:11-20</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="30">
                                <textarea name="chunks[30][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 80px; min-height: 90px;">28:11-20</textarea>
                                <input name="chunks[30][meta]" type="hidden" value="## {}" />
                                <input name="chunks[30][type]" type="hidden" value="heading_2" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="31" style="height: 80px;">
                                <div class="resource_text">Who were the chief priests and elders?</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="31">
                <textarea name="chunks[31][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 80px; min-height: 90px;">
¿Quiénes eran los principales sacerdotes y los ancianos?
                </textarea>
                                <input name="chunks[31][meta]" type="hidden" value="### {}" />
                                <input name="chunks[31][type]" type="hidden" value="heading_3" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="32" style="height: 80px;">
                                <div class="resource_text">[28:11]</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="32">
                                <textarea name="chunks[32][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 80px; min-height: 90px;">[28:11]</textarea>
                                <input name="chunks[32][meta]" type="hidden" value="" />
                                <input name="chunks[32][type]" type="hidden" value="text" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="33" style="height: 80px;">
                                <div class="resource_text">The chief priests and elders were Jewish leaders.</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="33">
                <textarea name="chunks[33][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 80px; min-height: 90px;">
Los principales sacerdotes y los ancianos eran líderes judíos.
                </textarea>
                                <input name="chunks[33][meta]" type="hidden" value="" />
                                <input name="chunks[33][type]" type="hidden" value="text" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="34" style="height: 80px;">
                                <div class="resource_text">See: Chief Priest; Elder</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="34">
                                <textarea name="chunks[34][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 80px; min-height: 90px;">Ver: Sumo Sacerdote; Mayor</textarea>
                                <input name="chunks[34][meta]" type="hidden" value="See: [Chief Priest](../articles/chiefpriest.md); [Elder](../articles/elder.md)" />
                                <input name="chunks[34][type]" type="hidden" value="link" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="35" style="height: 80px;">
                                <div class="resource_text">Why did the Jewish leaders give money to the Roman soldiers?</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="35">
                <textarea name="chunks[35][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 80px; min-height: 90px;">
¿Por qué los líderes judíos dieron dinero a los soldados romanos?
                </textarea>
                                <input name="chunks[35][meta]" type="hidden" value="### {}" />
                                <input name="chunks[35][type]" type="hidden" value="heading_3" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="36" style="height: 80px;">
                                <div class="resource_text">[28:12]</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="36">
                                <textarea name="chunks[36][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 80px; min-height: 90px;">[28:12]</textarea>
                                <input name="chunks[36][meta]" type="hidden" value="" />
                                <input name="chunks[36][type]" type="hidden" value="text" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="37" style="height: 89px;">
                                <div class="resource_text">The Jewish leaders gave money to the Roman soldiers to lie about what happened. They did not want people to know about what happened at the tomb.</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="37">
                <textarea name="chunks[37][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 89px; min-height: 99px;">
Los líderes judíos dieron dinero a los soldados romanos para que mintieran sobre lo sucedido. No querían que la gente supiera lo que pasó en la tumba.
                </textarea>
                                <input name="chunks[37][meta]" type="hidden" value="" />
                                <input name="chunks[37][type]" type="hidden" value="text" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="38" style="height: 80px;">
                                <div class="resource_text">Who were the eleven disciples?</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="38">
                                <textarea name="chunks[38][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 80px; min-height: 90px;">¿Quiénes eran los once discípulos?</textarea>
                                <input name="chunks[38][meta]" type="hidden" value="### {}" />
                                <input name="chunks[38][type]" type="hidden" value="heading_3" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="39" style="height: 80px;">
                                <div class="resource_text">[28:16]</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="39">
                                <textarea name="chunks[39][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 80px; min-height: 90px;">[28:16]</textarea>
                                <input name="chunks[39][meta]" type="hidden" value="" />
                                <input name="chunks[39][type]" type="hidden" value="text" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="40" style="height: 80px;">
                                <div class="resource_text">Normally, there were twelve disciples. At this time, Judas was not a disciple anymore.</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="40">
                <textarea name="chunks[40][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 80px; min-height: 90px;">
Normalmente, había doce discípulos. En ese momento, Judas ya no era un discípulo.
                </textarea>
                                <input name="chunks[40][meta]" type="hidden" value="" />
                                <input name="chunks[40][type]" type="hidden" value="text" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="41" style="height: 80px;">
                                <div class="resource_text">See: Disciple</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="41">
                                <textarea name="chunks[41][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 80px; min-height: 90px;">Ver: Discípulo</textarea>
                                <input name="chunks[41][meta]" type="hidden" value="See: [Disciple](../articles/disciple.md)" />
                                <input name="chunks[41][type]" type="hidden" value="link" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="42" style="height: 80px;">
                                <div class="resource_text">What did God give to Jesus?</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="42">
                                <textarea name="chunks[42][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 80px; min-height: 90px;">¿Qué le dio Dios a Jesús?</textarea>
                                <input name="chunks[42][meta]" type="hidden" value="### {}" />
                                <input name="chunks[42][type]" type="hidden" value="heading_3" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="43" style="height: 80px;">
                                <div class="resource_text">[28:18]</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="43">
                                <textarea name="chunks[43][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 80px; min-height: 90px;">[28:18]</textarea>
                                <input name="chunks[43][meta]" type="hidden" value="" />
                                <input name="chunks[43][type]" type="hidden" value="text" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="44" style="height: 80px;">
                                <div class="resource_text">Jesus said that God gave him permission to do something. He gave him power to do things in heaven and on earth.</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="44">
                <textarea name="chunks[44][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 80px; min-height: 90px;">
Jesús dijo que Dios le dio permiso para hacer algo. Le dio poder para hacer cosas en el cielo y en la tierra.
                </textarea>
                                <input name="chunks[44][meta]" type="hidden" value="" />
                                <input name="chunks[44][type]" type="hidden" value="text" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="45" style="height: 80px;">
                                <div class="resource_text">See: Heaven</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="45">
                                <textarea name="chunks[45][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 80px; min-height: 90px;">Ver: Cielo</textarea>
                                <input name="chunks[45][meta]" type="hidden" value="See: [Heaven](../articles/heaven.md)" />
                                <input name="chunks[45][type]" type="hidden" value="link" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="46" style="height: 80px;">
                                <div class="resource_text">How were the disciples to make disciples?</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="46">
                                <textarea name="chunks[46][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 80px; min-height: 90px;">¿Cómo iban a hacer discípulos los discípulos?</textarea>
                                <input name="chunks[46][meta]" type="hidden" value="### {}" />
                                <input name="chunks[46][type]" type="hidden" value="heading_3" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="47" style="height: 80px;">
                                <div class="resource_text">[28:19]</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="47">
                                <textarea name="chunks[47][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 80px; min-height: 90px;">[28:19]</textarea>
                                <input name="chunks[47][meta]" type="hidden" value="" />
                                <input name="chunks[47][type]" type="hidden" value="text" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="48" style="height: 173px;">
                                <div class="resource_text">
                                    Jesus wanted the disciples to make other disciples. That is, he wanted them to tell people about Jesus and help them to believe in Jesus and to do things that honored God. They did this by going to different places,
                                    baptizing people who believed in Jesus, and teaching them how to live in a way that honored God.
                                </div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="48">
                <textarea name="chunks[48][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 173px; min-height: 183px;">
Jesús quería que los discípulos hicieran otros discípulos. Es decir, quería que le hablaran a la gente acerca de Jesús y les ayudaran a creer en Jesús ya hacer cosas que honraran a Dios. Hicieron esto yendo a diferentes lugares, bautizando a personas que creían en Jesús y enseñándoles cómo vivir de una manera que honrara a Dios.
                </textarea>
                                <input name="chunks[48][meta]" type="hidden" value="" />
                                <input name="chunks[48][type]" type="hidden" value="text" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="49" style="height: 80px;">
                                <div class="resource_text">See: Disciple; Baptize (Baptism)</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="49">
                                <textarea name="chunks[49][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 80px; min-height: 90px;">See: Disciple; Baptize (Baptism)</textarea>
                                <input name="chunks[49][meta]" type="hidden" value="See: [Disciple](../articles/disciple.md); [Baptize (Baptism)](../articles/baptize.md)" />
                                <input name="chunks[49][type]" type="hidden" value="link" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="50" style="height: 80px;">
                                <div class="resource_text">How was Jesus with the disciples until the end of the age?</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="50">
                <textarea name="chunks[50][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 80px; min-height: 90px;">
¿Cómo fue Jesús con los discípulos hasta el final de la era?
                </textarea>
                                <input name="chunks[50][meta]" type="hidden" value="### {}" />
                                <input name="chunks[50][type]" type="hidden" value="heading_3" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="51" style="height: 80px;">
                                <div class="resource_text">[28:20]</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="51">
                                <textarea name="chunks[51][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 80px; min-height: 90px;">[28:20]</textarea>
                                <input name="chunks[51][meta]" type="hidden" value="" />
                                <input name="chunks[51][type]" type="hidden" value="text" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="52" style="height: 173px;">
                                <div class="resource_text">
                                    Jesus said that he would be with the disciples until the end of the age. Some scholars think Jesus would remain with them for a time on the earth after he was resurrected. He will help them to do the things he wanted
                                    them to do. Other scholars think Jesus wanted to say that he would be with every generation of Christians to help them do the things he wanted them to do.
                                </div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="52">
                <textarea name="chunks[52][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 173px; min-height: 183px;">
Jesús dijo que estaría con los discípulos hasta el final de la era. Algunos eruditos piensan que Jesús permanecería con ellos por un tiempo en la tierra después de que resucitara. Él los ayudará a hacer las cosas que él quería que hicieran. Otros eruditos creen que Jesús quería decir que estaría con cada generación de cristianos para ayudarlos a hacer las cosas que él quería que hicieran.
                </textarea>
                                <input name="chunks[52][meta]" type="hidden" value="" />
                                <input name="chunks[52][type]" type="hidden" value="text" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="row flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div class="resource_chunk no_margin" data-chunk="53" style="height: 80px;">
                                <div class="resource_text">See: Disciple; Resurrect (Resurrection); Generation</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area font_aaa" dir="ltr">
                            <div class="vnote" data-chunk="53">
                                <textarea name="chunks[53][text]" class="col-sm-6 peer_verse_ta textarea" style="overflow-x: hidden; overflow-wrap: break-word; height: 80px; min-height: 90px;">Ver: discípulo; Resucitar (Resurrección); Generación</textarea>
                                <input name="chunks[53][meta]" type="hidden" value="See: [Disciple](../articles/disciple.md); [Resurrect (Resurrection)](../articles/resurrect.md) ; [Generation](../articles/generation.md)" />
                                <input name="chunks[53][type]" type="hidden" value="link" />
                            </div>
                        </div>
                        <div class="flex_right">
                            <?php require(app_path() . "Views/Components/Comments.php"); ?>
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
                <span><?php echo __("step_num", ["step_number" => 2]) ?>:</span> <?php echo __(EventSteps::PEER_REVIEW . "_bc") ?>
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
                    <a href="/events/demo-bc/information"><?php echo __("event_info") ?></a>
                </div>
            </div>
        </div>

        <div class="checker_view">
            <a href="/events/demo-bc/peer_review_checker"><?php echo __("checker_other_view", [2]) ?></a>
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
            <h3><?php echo __(EventSteps::PEER_REVIEW . "_bc") ?></h3>
            <ul><?php echo __("peer-review_bc_desc", ["step" => __($data["next_step"])]) ?></ul>
        </div>
    </div>
</div>

<script>
    var isChecker = true;

    $(document).ready(function () {
        $("#next_step").click(function (e) {
            e.preventDefault();
            if (!hasChangesOnPage) window.location.href = '/events/demo-bc/pray';
            return false;
        });
    });
</script>