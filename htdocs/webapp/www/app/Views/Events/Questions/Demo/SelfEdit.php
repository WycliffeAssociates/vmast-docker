<?php
require(app_path() . "Views/Components/CommentEditor.php");
?>

<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="main_content_title">
            <div class="demo_title"><?php echo __("demo") . " (" . __("tq") . ")" ?></div>
            <div><?php echo __("step_num", ["step_number" => 2]) . ": " . __("self-check") ?></div>
        </div>
    </div>

    <div class="">
        <div class="main_content">
            <form action="" method="post" id="main_form">
                <div class="main_content_text row" style="padding-left: 15px">
                    <h4 dir="ltr">Русский - <?php echo __("tq") ?> - <?php echo __("new_test") ?> -
                        <span class='book_name'>3 John 1</span></h4>

                    <div class="col-sm-12 no_padding questions_bd">
                        <div class="parent_q questions_chunk" data-question="1" data-chapter="1" data-event="0">
                            <div class="row">
                                <div class="col-md-4"
                                     style="color: #00a74d; font-weight: bold;"> <?php echo __("verse_number", 1) ?> </div>
                            </div>
                            <div class="flex_container">
                                <div class="question_content flex_left" dir="ltr">
                                    <h1>By what title does the author John introduce himself in this letter?</h1>
                                    <p>John introduces himself as the elder. </p>
                                    <h1>What relationship does John have with Gaius, the one receiving this letter?</h1>
                                    <p>John loves Gaius in truth. </p>
                                </div>
                                <div class="questions_editor font_ru flex_middle" dir="ltr">
                                <textarea name="chunks[0]" class="add_questions_editor blind_ta"><h1>Как Иоанн представляется в начале своего послания?</h1>
                                    <p>Он представляется как пресвитер, старейшина, священник.</p>
                                    <h1>Как Иоанн относится к Гаию, к которому обращено послание?</h1>
                                    <p>Иоанн говорит, что любит Гаия истинной любовью.</p></textarea>
                                </div>
                                <div class="flex_right">
                                    <?php
                                    $hasComments = false;
                                    $enableFootNotes = false;
                                    require(app_path() . "Views/Components/Comments.php");
                                    ?>
                                </div>
                            </div>
                            <div class="chunk_divider" style="margin-top: 10px"></div>
                        </div>
                        <div class="parent_q questions_chunk" data-question="2" data-chapter="1" data-event="0">
                            <div class="row">
                                <div class="col-md-4"
                                     style="color: #00a74d; font-weight: bold;"> <?php echo __("verse_number", 2) ?> </div>
                            </div>
                            <div class="flex_container">
                                <div class="question_content flex_left" dir="ltr">
                                    <h1>For what does John pray concerning Gaius?</h1>
                                    <p>John prays that Gaius would prosper in all things and be in health, as his soul
                                        prospers. </p>
                                </div>
                                <div class="questions_editor font_ru flex_middle" dir="ltr">
                                <textarea name="chunks[1]" class="add_questions_editor blind_ta"><h1>Как Иоанн молится о Гаие?</h1>
                                    <p>Иоанн молится, чтобы Гаий преуспевал и здравствовал так же, как преуспевает его душа.</p></textarea>


                                </div>
                                <div class="flex_right">
                                    <?php
                                    $hasComments = false;
                                    $enableFootNotes = false;
                                    require(app_path() . "Views/Components/Comments.php");
                                    ?>
                                </div>
                            </div>
                            <div class="chunk_divider" style="margin-top: 10px"></div>
                        </div>
                        <div class="parent_q questions_chunk" data-question="4" data-chapter="1" data-event="0">
                            <div class="row">
                                <div class="col-md-4"
                                     style="color: #00a74d; font-weight: bold;"> <?php echo __("verse_number", 4) ?> </div>
                            </div>
                            <div class="flex_container">
                                <div class="question_content flex_left" dir="ltr">
                                    <h1>What is John's greatest joy?</h1>
                                    <p>John's greatest joy is to hear that his children walk in the truth. </p>
                                </div>
                                <div class="questions_editor font_ru flex_middle" dir="ltr">
                                <textarea name="chunks[2]" class="add_questions_editor blind_ta"><h1>Когда Иоанн радуется?</h1>
                                    <p>Он радуется, когда слышит, что его дети ходят в истине.</p></textarea>


                                </div>
                                <div class="flex_right">
                                    <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                                </div>
                            </div>
                            <div class="chunk_divider" style="margin-top: 10px"></div>
                        </div>
                        <div class="parent_q questions_chunk" data-question="6" data-chapter="1" data-event="0">
                            <div class="row">
                                <div class="col-md-4"
                                     style="color: #00a74d; font-weight: bold;"> <?php echo __("verse_number", 6) ?> </div>
                            </div>
                            <div class="flex_container">
                                <div class="question_content flex_left" dir="ltr">
                                    <h1>Who did Gaius welcome and then send out on their journey?</h1>
                                    <p>Gaius welcomed and then sent out on their journey some who were going out for the
                                        sake of the Name. </p>
                                </div>
                                <div class="questions_editor font_ru flex_middle" dir="ltr">
                                <textarea name="chunks[3]" class="add_questions_editor blind_ta"><h1>Кого Гаий принял и снарядил в дорогу?</h1>
                                    <p>Гаий принял и снарядил в дорогу человека Божьего.</p></textarea>


                                </div>
                                <div class="flex_right">
                                    <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                                </div>
                            </div>
                            <div class="chunk_divider" style="margin-top: 10px"></div>
                        </div>
                        <div class="parent_q questions_chunk" data-question="7" data-chapter="1" data-event="0">
                            <div class="row">
                                <div class="col-md-4"
                                     style="color: #00a74d; font-weight: bold;"> <?php echo __("verse_number", 7) ?> </div>
                            </div>
                            <div class="flex_container">
                                <div class="question_content flex_left" dir="ltr">
                                    <h1>Who did Gaius welcome and then send out on their journey?</h1>
                                    <p>Gaius welcomed and then sent out on their journey some who were going out for the
                                        sake of the Name. </p>
                                </div>
                                <div class="questions_editor font_ru flex_middle" dir="ltr">
                                <textarea name="chunks[4]" class="add_questions_editor blind_ta"><h1>Кого Гаий принял и снарядил в дорогу?</h1>
                                    <p>Гаий принял и снарядил в дорогу человека Божьего.</p></textarea>


                                </div>
                                <div class="flex_right">
                                    <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                                </div>
                            </div>
                            <div class="chunk_divider" style="margin-top: 10px"></div>
                        </div>
                        <div class="parent_q questions_chunk" data-question="8" data-chapter="1" data-event="0">
                            <div class="row">
                                <div class="col-md-4"
                                     style="color: #00a74d; font-weight: bold;"> <?php echo __("verse_number", 8) ?> </div>
                            </div>
                            <div class="flex_container">
                                <div class="question_content flex_left" dir="ltr">
                                    <h1>Who did Gaius welcome and then send out on their journey?</h1>
                                    <p>Gaius welcomed and then sent out on their journey some who were going out for the
                                        sake of the Name. </p>
                                    <h1>Why does John say believers should welcome brothers such as these?</h1>
                                    <p>John says believers should welcome them so that they may be fellow-workers for
                                        the truth. </p>
                                </div>
                                <div class="questions_editor font_ru flex_middle" dir="ltr">
                                <textarea name="chunks[5]" class="add_questions_editor blind_ta"><h1>Кого Гаий принял и снарядил в дорогу?</h1>
                                    <p>Гаий принял и снарядил в дорогу человека Божьего.</p>
                                    <h1>Для чего верующие должны принимать Божьих служителей?</h1>
                                    <p>Для того, чтобы быть сотрудниками в их труде.</p></textarea>


                                </div>
                                <div class="flex_right">
                                    <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                                </div>
                            </div>
                            <div class="chunk_divider" style="margin-top: 10px"></div>
                        </div>
                        <div class="parent_q questions_chunk" data-question="9" data-chapter="1" data-event="0">
                            <div class="row">
                                <div class="col-md-4"
                                     style="color: #00a74d; font-weight: bold;"> <?php echo __("verse_number", 9) ?> </div>
                            </div>
                            <div class="flex_container">
                                <div class="question_content flex_left" dir="ltr">
                                    <h1>What does Diotrephes love?</h1>
                                    <p>Diotrephes loves to be first among the congregation. </p>
                                    <h1>What is Diotrephes' attitude toward John?</h1>
                                    <p>Diotrephes does not receive John. </p>
                                </div>
                                <div class="questions_editor font_ru flex_middle" dir="ltr">
                                <textarea name="chunks[6]" class="add_questions_editor blind_ta"><h1>Что любит Диотреф?</h1>
                                    <p>Диотреф любит быть первым среди членов общины.</p>
                                    <h1>Как Диотреф относится к Иоанну?</h1>
                                    <p>Диотреф не принимает Иоанна.</p></textarea>


                                </div>
                                <div class="flex_right">
                                    <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                                </div>
                            </div>
                            <div class="chunk_divider" style="margin-top: 10px"></div>
                        </div>
                        <div class="parent_q questions_chunk" data-question="10" data-chapter="1" data-event="0">
                            <div class="row">
                                <div class="col-md-4"
                                     style="color: #00a74d; font-weight: bold;"> <?php echo __("verse_number", 10) ?> </div>
                            </div>
                            <div class="flex_container">
                                <div class="question_content flex_left" dir="ltr">
                                    <h1>What will John do if he comes to Gaius and the congregation?</h1>
                                    <p>If John comes he will remember Diotrephes' evil deeds. </p>
                                    <h1>What does Diotrephes do with the brothers going forth for the Name?</h1>
                                    <p>Diotrephes does not receive the brothers. </p>
                                    <h1>What does Diotrephes do with those who receive the brothers going
                                        <strong>forth</strong> for the Name?</h1>
                                    <p>Diotrephes forbids them from receiving the brothers, and drives them out of the
                                        congregation. </p>
                                </div>
                                <div class="questions_editor font_ru flex_middle" dir="ltr">
                                <textarea name="chunks[7]" class="add_questions_editor blind_ta"><h1>Что намерен сделать Иоанн, когда придет к Гаию и другим членам общины?</h1>
                                    <p>Он разоблачит Диотрефа.</p>
                                    <h1>Как Диотреф поступает с братьями, которые служат Богу?</h1>
                                    <p>Диотреф их не принимает.</p>
                                    <h1>Как Диотреф поступает с теми, кто принимает Божьих служителей?</h1>
                                    <p>Он запрещает братьям принимать Божьих служителей, а тех, кто его ослушался, выгоняет из общины.</p></textarea>


                                </div>
                                <div class="flex_right">
                                    <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                                </div>
                            </div>
                            <div class="chunk_divider" style="margin-top: 10px"></div>
                        </div>
                        <div class="parent_q questions_chunk" data-question="11" data-chapter="1" data-event="0">
                            <div class="row">
                                <div class="col-md-4"
                                     style="color: #00a74d; font-weight: bold;"> <?php echo __("verse_number", 11) ?> </div>
                            </div>
                            <div class="flex_container">
                                <div class="question_content flex_left" dir="ltr">
                                    <h1>What does John tell Gaius to imitate?</h1>
                                    <p>John tells Gaius to imitate good. </p>
                                </div>
                                <div class="questions_editor font_ru flex_middle" dir="ltr">
                                <textarea name="chunks[8]" class="add_questions_editor blind_ta"><h1>Чему должен подражать Гаий?</h1>
                                    <p>Гаий должен подражать добру.</p></textarea>


                                </div>
                                <div class="flex_right">
                                    <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                                </div>
                            </div>
                            <div class="chunk_divider" style="margin-top: 10px"></div>
                        </div>
                        <div class="parent_q questions_chunk" data-question="14" data-chapter="1" data-event="0">
                            <div class="row">
                                <div class="col-md-4"
                                     style="color: #00a74d; font-weight: bold;"> <?php echo __("verse_number", 14) ?> </div>
                            </div>
                            <div class="flex_container">
                                <div class="question_content flex_left" dir="ltr">
                                    <h1>What does John hope to do in the future?</h1>
                                    <p>John hopes to come and speak with Gaius face to face. </p>
                                </div>
                                <div class="questions_editor font_ru flex_middle" dir="ltr">
                                <textarea name="chunks[9]" class="add_questions_editor blind_ta"><h1>Что Иоанн намерен сделать в будущем?</h1>
                                    <p>Иоанн хочет прийти к Гаию и лично с ним поговорить.</p></textarea>


                                </div>
                                <div class="flex_right">
                                    <?php $enableFootNotes = false; require(app_path() . "Views/Components/Comments.php"); ?>
                                </div>
                            </div>
                            <div class="chunk_divider" style="margin-top: 10px"></div>
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
            <div class="step_right alt"><?php echo __("step_num", ["step_number" => 2]) ?></div>
        </div>
    </div>
</div>

<div class="content_help closed">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps">
            <div class="help_name_steps">
                <span><?php echo __("step_num", ["step_number" => 2]) ?>:</span> <?php echo __("self-check") ?></div>
            <div class="help_descr_steps">
                <ul>
                    <li><b><?php echo __("purpose") ?></b> <?php echo __("self-check_tq_purpose") ?></li>
                    <li><?php echo __("self-check_help_2") ?></li>
                    <li><?php echo __("self-check_tn_help_2") ?></li>
                    <li><?php echo __("self-check_tn_help_3") ?></li>
                    <li><?php echo __("self-check_tq_help_4", ["icon" => "<span class='mdi mdi-lead-pencil'></span>"]) ?></li>
                    <li><?php echo __("move_to_next_step_alt", ["step" => __($data["next_step"])]) ?></li>
                    <li><?php echo __("self-check_tq_help_5", ["step" => __($data["next_step"])]) ?></li>
                </ul>
                <div class="show_tutorial_popup"> >>> <?php echo __("show_more") ?></div>
            </div>
        </div>

        <div class="event_info">
            <div class="participant_info">
                <div class="additional_info">
                    <a href="/events/demo-tq/information"><?php echo __("event_info") ?></a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="tutorial_container">
    <div class="tutorial_popup">
        <div class="tutorial-close glyphicon glyphicon-remove"></div>
        <div class="tutorial_pic">
            <img src="<?php echo template_url("img/steps/icons/self-check.png") ?>" width="100" height="100">
            <img src="<?php echo template_url("img/steps/big/self-check.png") ?>" width="280" height="280">

        </div>

        <div class="tutorial_content">
            <h3><?php echo __("self-check") ?></h3>
            <ul>
                <li><b><?php echo __("purpose") ?></b> <?php echo __("self-check_tq_purpose") ?></li>
                <li><?php echo __("self-check_help_2") ?></li>
                <li><?php echo __("self-check_tn_help_2") ?></li>
                <li><?php echo __("self-check_tn_help_3") ?></li>
                <li><?php echo __("self-check_tq_help_4", ["icon" => "<span class='mdi mdi-lead-pencil'></span>"]) ?></li>
                <li><?php echo __("move_to_next_step_alt", ["step" => __($data["next_step"])]) ?></li>
                <li><?php echo __("self-check_tq_help_5", ["step" => __($data["next_step"])]) ?></li>
            </ul>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#next_step").click(function (e) {
            e.preventDefault();
            if (!hasChangesOnPage) window.location.href = '/events/demo-tq/pray_chk';
            return false;
        });
    });
</script>