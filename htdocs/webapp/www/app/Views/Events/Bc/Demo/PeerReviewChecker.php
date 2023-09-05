<?php

use Helpers\Constants\EventSteps;

$enableFootNotes = false;
require(app_path() . "Views/Components/CommentEditor.php");
?>

<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="action_type_container">
            <div class="demo_title"><?php echo __("demo") . " (" . __("bc") . ")" ?></div>
            <div class="action_type type_checking isPeer"><?php echo __("type_checking2"); ?></div>
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
                    <div class="row chunk_block chunk_block_divider">
                        <div class="flex_container">
                            <div class="chunk_verses flex_left" dir="ltr">
                                <div class="resource_chunk no_margin" data-chunk="0" style="height: 43px;">
                                    <div class="resource_text">Matthew 28</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="0" style="height: 43px;"><span>Mateo 28</span></div>
                                <div class="chunk_checker" data-chunk="0">
                                    Mateo 28
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
                                <div class="resource_chunk no_margin" data-chunk="1" style="height: 43px;">
                                    <div class="resource_text">28:1-10</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="1" style="height: 43px;"><span>28:1-10</span></div>
                                <div class="chunk_checker" data-chunk="1">
                                    28:1-10
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
                                    <div class="resource_text">What was the Sabbath?</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="2" style="height: 43px;"><del style="background: #ffe6e6;">Wrong text has been replaced by checker</del><ins style="background: #e6ffe6;">¿Qué era el sábado?</ins></div>
                                <div class="chunk_checker" data-chunk="2">
                                    ¿Qué era el sábado?
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
                                    <div class="resource_text">[28:1]</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="3" style="height: 43px;"><span>[28:1]</span></div>
                                <div class="chunk_checker" data-chunk="3">
                                    [28:1]
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
                                <div class="resource_chunk no_margin" data-chunk="4" style="height: 43px;">
                                    <div class="resource_text">See: Sabbath</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="4" style="height: 43px;"><span>Ver: Sábado</span></div>
                                <div class="chunk_checker" data-chunk="4">
                                    Ver: Sábado
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
                                <div class="resource_chunk no_margin" data-chunk="5" style="height: 43px;">
                                    <div class="resource_text">What day was the first day of the week?</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="5" style="height: 43px;"><span>¿Qué día fue el primer día de la semana?</span></div>
                                <div class="chunk_checker" data-chunk="5">
                                    ¿Qué día fue el primer día de la semana?
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
                                <div class="resource_chunk no_margin" data-chunk="6" style="height: 43px;">
                                    <div class="resource_text">[28:1]</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="6" style="height: 43px;"><span>[28:1]</span></div>
                                <div class="chunk_checker" data-chunk="6">
                                    [28:1]
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
                                    <div class="resource_text">
                                        The Sabbath began on Friday at sunset and ended Saturday at sunset. The day after the Sabbath was the first day of the week. This day began on Saturday at sunset and ended on Sunday at sunset.
                                    </div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="7" style="height: 89px;">
                    <span>
                        El sábado comenzaba el viernes al atardecer y terminaba el sábado al atardecer. El día después del sábado era el primer día de la semana. Este día comenzaba el sábado al atardecer y terminaba el domingo al atardecer.
                    </span>
                                </div>
                                <div class="chunk_checker" data-chunk="7">
                                    El sábado comenzaba el viernes al atardecer y terminaba el sábado al atardecer. El día después del sábado era el primer día de la semana. Este día comenzaba el sábado al atardecer y terminaba el domingo al atardecer.
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
                                <div class="resource_chunk no_margin" data-chunk="8" style="height: 43px;">
                                    <div class="resource_text">See: Sabbath</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="8" style="height: 43px;"><span>Ver: Sábado</span></div>
                                <div class="chunk_checker" data-chunk="8">
                                    Ver: Sábado
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
                                    <div class="resource_text">Why did the angel roll away the stone?</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="9" style="height: 43px;"><span>¿Por qué el ángel hizo rodar la piedra?</span></div>
                                <div class="chunk_checker" data-chunk="9">
                                    ¿Por qué el ángel hizo rodar la piedra?
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
                                <div class="resource_chunk no_margin" data-chunk="10" style="height: 43px;">
                                    <div class="resource_text">[28:2]</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="10" style="height: 43px;"><span>[28:2]</span></div>
                                <div class="chunk_checker" data-chunk="10">
                                    [28:2]
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
                                <div class="resource_chunk no_margin" data-chunk="11" style="height: 43px;">
                                    <div class="resource_text">The angel rolled away the stone because it was very large.</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="11" style="height: 43px;"><span>El ángel hizo rodar la piedra porque era muy grande.</span></div>
                                <div class="chunk_checker" data-chunk="11">
                                    El ángel hizo rodar la piedra porque era muy grande.
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
                                <div class="resource_chunk no_margin" data-chunk="12" style="height: 43px;">
                                    <div class="resource_text">See: Angel; Heaven</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="12" style="height: 43px;"><span>Ver: Ver: Ángel; Cielo; Cielo</span></div>
                                <div class="chunk_checker" data-chunk="12">
                                    Ver: Ver: Ángel; Cielo; Cielo
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
                                <div class="resource_chunk no_margin" data-chunk="13" style="height: 43px;">
                                    <div class="resource_text">Why did the angel look the way he did?</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="13" style="height: 43px;"><span>¿Por qué el ángel se veía de la manera que lo hizo?</span></div>
                                <div class="chunk_checker" data-chunk="13">
                                    ¿Por qué el ángel se veía de la manera que lo hizo?
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
                                <div class="resource_chunk no_margin" data-chunk="14" style="height: 43px;">
                                    <div class="resource_text">[28:3]</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="14" style="height: 43px;"><span>[28:3]</span></div>
                                <div class="chunk_checker" data-chunk="14">
                                    [28:3]
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
                                <div class="resource_chunk no_margin" data-chunk="15" style="height: 66px;">
                                    <div class="resource_text">The angel looked the way he did because he was holy. White was a symbol of someone or something being holy.</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="15" style="height: 66px;"><span>El ángel tenía el aspecto que tenía porque era santo. El blanco era un símbolo de que alguien o algo era santo.</span></div>
                                <div class="chunk_checker" data-chunk="15">
                                    El ángel tenía el aspecto que tenía porque era santo. El blanco era un símbolo de que alguien o algo era santo.
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
                                <div class="resource_chunk no_margin" data-chunk="16" style="height: 43px;">
                                    <div class="resource_text">See: Angel; Holy (Holiness, Set Apart); White (symbol)</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="16" style="height: 43px;"><span>Ver: Ángel; Santo (Santidad, Apartado); Blanco (símbolo)</span></div>
                                <div class="chunk_checker" data-chunk="16">
                                    Ver: Ángel; Santo (Santidad, Apartado); Blanco (símbolo)
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
                                <div class="resource_chunk no_margin" data-chunk="17" style="height: 43px;">
                                    <div class="resource_text">How was Jesus crucified?</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="17" style="height: 43px;"><span>¿Cómo fue crucificado Jesús?</span></div>
                                <div class="chunk_checker" data-chunk="17">
                                    ¿Cómo fue crucificado Jesús?
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
                                <div class="resource_chunk no_margin" data-chunk="18" style="height: 43px;">
                                    <div class="resource_text">[28:5]</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="18" style="height: 43px;"><span>[28:5]</span></div>
                                <div class="chunk_checker" data-chunk="18">
                                    [28:5]
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
                                <div class="resource_chunk no_margin" data-chunk="19" style="height: 43px;">
                                    <div class="resource_text">See: Crucify (Crucifixion)</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="19" style="height: 43px;"><span>Ver: Crucificar (Crucifixión)</span></div>
                                <div class="chunk_checker" data-chunk="19">
                                    Ver: Crucificar (Crucifixión)
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
                                <div class="resource_chunk no_margin" data-chunk="20" style="height: 43px;">
                                    <div class="resource_text">How was Jesus resurrected?</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="20" style="height: 43px;"><span>¿Cómo resucitó Jesús?</span></div>
                                <div class="chunk_checker" data-chunk="20">
                                    ¿Cómo resucitó Jesús?
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
                                <div class="resource_chunk no_margin" data-chunk="21" style="height: 43px;">
                                    <div class="resource_text">[28:5, 28:6]</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="21" style="height: 43px;"><span>[28:5, 28:6]</span></div>
                                <div class="chunk_checker" data-chunk="21">
                                    [28:5, 28:6]
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
                                <div class="resource_chunk no_margin" data-chunk="22" style="height: 66px;">
                                    <div class="resource_text">The man in the tomb said that Jesus was risen. That is, Jesus was resurrected.</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="22" style="height: 66px;"><span>El hombre en la tumba dijo que Jesús había resucitado. Es decir, Jesús resucitó.</span></div>
                                <div class="chunk_checker" data-chunk="22">
                                    El hombre en la tumba dijo que Jesús había resucitado. Es decir, Jesús resucitó.
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
                                <div class="resource_chunk no_margin" data-chunk="23" style="height: 43px;">
                                    <div class="resource_text">See: Resurrect (Resurrection)</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="23" style="height: 43px;"><span>Ver: Resucitar (Resurrección)</span></div>
                                <div class="chunk_checker" data-chunk="23">
                                    Ver: Resucitar (Resurrección)
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
                                <div class="resource_chunk no_margin" data-chunk="24" style="height: 43px;">
                                    <div class="resource_text">Where was Galilee?</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="24" style="height: 43px;"><span>¿Dónde estaba Galilea?</span></div>
                                <div class="chunk_checker" data-chunk="24">
                                    ¿Dónde estaba Galilea?
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
                                <div class="resource_chunk no_margin" data-chunk="25" style="height: 43px;">
                                    <div class="resource_text">[28:7]</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="25" style="height: 43px;"><span>[28:7]</span></div>
                                <div class="chunk_checker" data-chunk="25">
                                    [28:7]
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
                                <div class="resource_chunk no_margin" data-chunk="26" style="height: 43px;">
                                    <div class="resource_text">See Map: Galilee</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="26" style="height: 43px;"><span>Ver Mapa: Galilea</span></div>
                                <div class="chunk_checker" data-chunk="26">
                                    Ver Mapa: Galilea
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
                                <div class="resource_chunk no_margin" data-chunk="27" style="height: 43px;">
                                    <div class="resource_text">What was worship?</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="27" style="height: 43px;"><span>¿Qué era la adoración?</span></div>
                                <div class="chunk_checker" data-chunk="27">
                                    ¿Qué era la adoración?
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
                                <div class="resource_chunk no_margin" data-chunk="28" style="height: 43px;">
                                    <div class="resource_text">[28:9]</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="28" style="height: 43px;"><span>[28:9]</span></div>
                                <div class="chunk_checker" data-chunk="28">
                                    [28:9]
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
                                <div class="resource_chunk no_margin" data-chunk="29" style="height: 43px;">
                                    <div class="resource_text">See: Worship</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="29" style="height: 43px;"><span>Ver: Adoración</span></div>
                                <div class="chunk_checker" data-chunk="29">
                                    Ver: Adoración
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
                                <div class="resource_chunk no_margin" data-chunk="30" style="height: 43px;">
                                    <div class="resource_text">28:11-20</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="30" style="height: 43px;"><span>28:11-20</span></div>
                                <div class="chunk_checker" data-chunk="30">
                                    28:11-20
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
                                <div class="resource_chunk no_margin" data-chunk="31" style="height: 43px;">
                                    <div class="resource_text">Who were the chief priests and elders?</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="31" style="height: 43px;"><span>¿Quiénes eran los principales sacerdotes y los ancianos?</span></div>
                                <div class="chunk_checker" data-chunk="31">
                                    ¿Quiénes eran los principales sacerdotes y los ancianos?
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
                                <div class="resource_chunk no_margin" data-chunk="32" style="height: 43px;">
                                    <div class="resource_text">[28:11]</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="32" style="height: 43px;"><span>[28:11]</span></div>
                                <div class="chunk_checker" data-chunk="32">
                                    [28:11]
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
                                <div class="resource_chunk no_margin" data-chunk="33" style="height: 43px;">
                                    <div class="resource_text">The chief priests and elders were Jewish leaders.</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="33" style="height: 43px;"><span>Los principales sacerdotes y los ancianos eran líderes judíos.</span></div>
                                <div class="chunk_checker" data-chunk="33">
                                    Los principales sacerdotes y los ancianos eran líderes judíos.
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
                                <div class="resource_chunk no_margin" data-chunk="34" style="height: 43px;">
                                    <div class="resource_text">See: Chief Priest; Elder</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="34" style="height: 43px;"><span>Ver: Sumo Sacerdote; Mayor</span></div>
                                <div class="chunk_checker" data-chunk="34">
                                    Ver: Sumo Sacerdote; Mayor
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
                                <div class="resource_chunk no_margin" data-chunk="35" style="height: 43px;">
                                    <div class="resource_text">Why did the Jewish leaders give money to the Roman soldiers?</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="35" style="height: 43px;"><span>¿Por qué los líderes judíos dieron dinero a los soldados romanos?</span></div>
                                <div class="chunk_checker" data-chunk="35">
                                    ¿Por qué los líderes judíos dieron dinero a los soldados romanos?
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
                                <div class="resource_chunk no_margin" data-chunk="36" style="height: 43px;">
                                    <div class="resource_text">[28:12]</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="36" style="height: 43px;"><span>[28:12]</span></div>
                                <div class="chunk_checker" data-chunk="36">
                                    [28:12]
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
                                <div class="resource_chunk no_margin" data-chunk="37" style="height: 89px;">
                                    <div class="resource_text">The Jewish leaders gave money to the Roman soldiers to lie about what happened. They did not want people to know about what happened at the tomb.</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="37" style="height: 89px;">
                                    <span>Los líderes judíos dieron dinero a los soldados romanos para que mintieran sobre lo sucedido. No querían que la gente supiera lo que pasó en la tumba.</span>
                                </div>
                                <div class="chunk_checker" data-chunk="37">
                                    Los líderes judíos dieron dinero a los soldados romanos para que mintieran sobre lo sucedido. No querían que la gente supiera lo que pasó en la tumba.
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
                                <div class="resource_chunk no_margin" data-chunk="38" style="height: 43px;">
                                    <div class="resource_text">Who were the eleven disciples?</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="38" style="height: 43px;"><span>¿Quiénes eran los once discípulos?</span></div>
                                <div class="chunk_checker" data-chunk="38">
                                    ¿Quiénes eran los once discípulos?
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
                                <div class="resource_chunk no_margin" data-chunk="39" style="height: 43px;">
                                    <div class="resource_text">[28:16]</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="39" style="height: 43px;"><span>[28:16]</span></div>
                                <div class="chunk_checker" data-chunk="39">
                                    [28:16]
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
                                <div class="resource_chunk no_margin" data-chunk="40" style="height: 66px;">
                                    <div class="resource_text">Normally, there were twelve disciples. At this time, Judas was not a disciple anymore.</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="40" style="height: 66px;"><span>Normalmente, había doce discípulos. En ese momento, Judas ya no era un discípulo.</span></div>
                                <div class="chunk_checker" data-chunk="40">
                                    Normalmente, había doce discípulos. En ese momento, Judas ya no era un discípulo.
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
                                <div class="resource_chunk no_margin" data-chunk="41" style="height: 43px;">
                                    <div class="resource_text">See: Disciple</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="41" style="height: 43px;"><span>Ver: Discípulo</span></div>
                                <div class="chunk_checker" data-chunk="41">
                                    Ver: Discípulo
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
                                <div class="resource_chunk no_margin" data-chunk="42" style="height: 43px;">
                                    <div class="resource_text">What did God give to Jesus?</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="42" style="height: 43px;"><span>¿Qué le dio Dios a Jesús?</span></div>
                                <div class="chunk_checker" data-chunk="42">
                                    ¿Qué le dio Dios a Jesús?
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
                                <div class="resource_chunk no_margin" data-chunk="43" style="height: 43px;">
                                    <div class="resource_text">[28:18]</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="43" style="height: 43px;"><span>[28:18]</span></div>
                                <div class="chunk_checker" data-chunk="43">
                                    [28:18]
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
                                <div class="resource_chunk no_margin" data-chunk="44" style="height: 66px;">
                                    <div class="resource_text">Jesus said that God gave him permission to do something. He gave him power to do things in heaven and on earth.</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="44" style="height: 66px;"><span>Jesús dijo que Dios le dio permiso para hacer algo. Le dio poder para hacer cosas en el cielo y en la tierra.</span></div>
                                <div class="chunk_checker" data-chunk="44">
                                    Jesús dijo que Dios le dio permiso para hacer algo. Le dio poder para hacer cosas en el cielo y en la tierra.
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
                                <div class="resource_chunk no_margin" data-chunk="45" style="height: 43px;">
                                    <div class="resource_text">See: Heaven</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="45" style="height: 43px;"><span>Ver: Cielo</span></div>
                                <div class="chunk_checker" data-chunk="45">
                                    Ver: Cielo
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
                                <div class="resource_chunk no_margin" data-chunk="46" style="height: 43px;">
                                    <div class="resource_text">How were the disciples to make disciples?</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="46" style="height: 43px;"><span>¿Cómo iban a hacer discípulos los discípulos?</span></div>
                                <div class="chunk_checker" data-chunk="46">
                                    ¿Cómo iban a hacer discípulos los discípulos?
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
                                <div class="resource_chunk no_margin" data-chunk="47" style="height: 43px;">
                                    <div class="resource_text">[28:19]</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="47" style="height: 43px;"><span>[28:19]</span></div>
                                <div class="chunk_checker" data-chunk="47">
                                    [28:19]
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
                                <div class="resource_chunk no_margin" data-chunk="48" style="height: 134px;">
                                    <div class="resource_text">
                                        Jesus wanted the disciples to make other disciples. That is, he wanted them to tell people about Jesus and help them to believe in Jesus and to do things that honored God. They did this by going to different places,
                                        baptizing people who believed in Jesus, and teaching them how to live in a way that honored God.
                                    </div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="48" style="height: 134px;">
                    <span>
                        Jesús quería que los discípulos hicieran otros discípulos. Es decir, quería que le hablaran a la gente acerca de Jesús y les ayudaran a creer en Jesús ya hacer cosas que honraran a Dios. Hicieron esto yendo a
                        diferentes lugares, bautizando a personas que creían en Jesús y enseñándoles cómo vivir de una manera que honrara a Dios.
                    </span>
                                </div>
                                <div class="chunk_checker" data-chunk="48">
                                    Jesús quería que los discípulos hicieran otros discípulos. Es decir, quería que le hablaran a la gente acerca de Jesús y les ayudaran a creer en Jesús ya hacer cosas que honraran a Dios. Hicieron esto yendo a diferentes
                                    lugares, bautizando a personas que creían en Jesús y enseñándoles cómo vivir de una manera que honrara a Dios.
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
                                <div class="resource_chunk no_margin" data-chunk="49" style="height: 43px;">
                                    <div class="resource_text">See: Disciple; Baptize (Baptism)</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="49" style="height: 43px;"><span>See: Disciple; Baptize (Baptism)</span></div>
                                <div class="chunk_checker" data-chunk="49">
                                    See: Disciple; Baptize (Baptism)
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
                                <div class="resource_chunk no_margin" data-chunk="50" style="height: 43px;">
                                    <div class="resource_text">How was Jesus with the disciples until the end of the age?</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="50" style="height: 43px;"><span>¿Cómo fue Jesús con los discípulos hasta el final de la era?</span></div>
                                <div class="chunk_checker" data-chunk="50">
                                    ¿Cómo fue Jesús con los discípulos hasta el final de la era?
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
                                <div class="resource_chunk no_margin" data-chunk="51" style="height: 43px;">
                                    <div class="resource_text">[28:20]</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="51" style="height: 43px;"><span>[28:20]</span></div>
                                <div class="chunk_checker" data-chunk="51">
                                    [28:20]
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
                                <div class="resource_chunk no_margin" data-chunk="52" style="height: 157px;">
                                    <div class="resource_text">
                                        Jesus said that he would be with the disciples until the end of the age. Some scholars think Jesus would remain with them for a time on the earth after he was resurrected. He will help them to do the things he wanted
                                        them to do. Other scholars think Jesus wanted to say that he would be with every generation of Christians to help them do the things he wanted them to do.
                                    </div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="52" style="height: 157px;">
                    <span>
                        Jesús dijo que estaría con los discípulos hasta el final de la era. Algunos eruditos piensan que Jesús permanecería con ellos por un tiempo en la tierra después de que resucitara. Él los ayudará a hacer las cosas que
                        él quería que hicieran. Otros eruditos creen que Jesús quería decir que estaría con cada generación de cristianos para ayudarlos a hacer las cosas que él quería que hicieran.
                    </span>
                                </div>
                                <div class="chunk_checker" data-chunk="52">
                                    Jesús dijo que estaría con los discípulos hasta el final de la era. Algunos eruditos piensan que Jesús permanecería con ellos por un tiempo en la tierra después de que resucitara. Él los ayudará a hacer las cosas que él
                                    quería que hicieran. Otros eruditos creen que Jesús quería decir que estaría con cada generación de cristianos para ayudarlos a hacer las cosas que él quería que hicieran.
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
                                <div class="resource_chunk no_margin" data-chunk="53" style="height: 43px;">
                                    <div class="resource_text">See: Disciple; Resurrect (Resurrection); Generation</div>
                                </div>
                            </div>
                            <div class="flex_middle font_aaa" dir="ltr">
                                <div class="chunk_translator" data-chunk="53" style="height: 43px;"><span>Ver: discípulo; Resucitar (Resurrección); Generación</span></div>
                                <div class="chunk_checker" data-chunk="53">
                                    Ver: discípulo; Resucitar (Resurrección); Generación
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
                <span><?php echo __("step_num", ["step_number" => 2]) ?>:</span> <?php echo __(EventSteps::PEER_REVIEW . "_bc") ?>
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
                    <a href="/events/demo-bc/information"><?php echo __("event_info") ?></a>
                </div>
            </div>
        </div>

        <div class="checker_view">
            <a href="/events/demo-bc/peer_review"><?php echo __("checker_other_view", [1]) ?></a>
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
            window.location.href = '/events/demo-bc/peer_review';
            return false;
        });
    });
</script>