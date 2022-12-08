<?php
require(app_path() . "Views/Components/CommentEditor.php");
?>

<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="main_content_title">
            <div class="demo_title">
                <?php echo __("demo") . " (" . __("odb") . " - " . __("vsail") . ")" ?>
            </div>
            <div><?php echo __("step_num", ["step_number" => 5]) . ": " . __("theo-check_odb") ?></div>
        </div>
    </div>

    <div class="">
        <div class="main_content">
            <div class="main_content_text">
                <h4>Symbolic Universal Notation - <?php echo __("odb") ?> - <span class="book_name">A01 4</span></h4>

                <ul class="nav nav-tabs">
                    <li role="presentation" id="source_scripture" class="my_tab">
                        <a href="#"><?php echo __("source_text") ?></a>
                    </li>
                    <li role="presentation" id="rearrange" class="my_tab">
                        <a href="#"><?php echo __("rearrange") ?></a>
                    </li>
                </ul>

                <div id="source_scripture_content" class="col-sm-12 no_padding my_content shown">
                    <div class="flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div>
                                <strong class="ltr"> <?php echo __("title") ?>: </strong>
                                <div class="kwverse_2_0_1">A Good Man</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area" dir="ltr">
                            <div class="vnote">
                                <textarea name="chunks[]"
                                          class="col-sm-6 verse_ta textarea sun_content"
                                          style="min-height: 80px;">    </textarea>

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
                    <div class="chunk_divider"></div>
                    <div class="flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div>
                                <strong class="ltr"> <?php echo __("author") ?>: </strong>
                                <div class="kwverse_2_0_3">Cindy Hess Kasper</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area" dir="ltr">
                            <div class="vnote">
                                <textarea name="chunks[]"
                                          class="col-sm-6 peer_verse_ta textarea sun_content"
                                          style="min-height: 80px;"> </textarea>

                            </div>
                        </div>
                        <div class="flex_right">
                            <?php
                            $comments = [$data["comments"][0][1]];
                            $hasComments = !empty($comments);
                            $commentsNumber = sizeof($comments);
                            $myMemberID = 0;
                            $enableFootNotes = false;
                            require(app_path() . "Views/Components/Comments.php");
                            ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div>
                                <strong class="ltr"> <?php echo __("passage") ?>: </strong>
                                <div class="kwverse_2_0_5">Romans 3:10-18</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area" dir="ltr">
                            <div class="vnote">
                                <textarea name="chunks[]"
                                          class="col-sm-6 peer_verse_ta textarea sun_content"
                                          style="min-height: 80px;">  :      :  -  </textarea>

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
                    <div class="chunk_divider"></div>
                    <div class="flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div>
                                <strong class="ltr"> <?php echo __("bible_in_a_year") ?>: </strong>
                                <div class="kwverse_2_0_10">Numbers 26-27; Mark 8:1-21</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area" dir="ltr">
                            <div class="vnote">
                                <textarea name="chunks[]"
                                          class="col-sm-6 peer_verse_ta textarea sun_content"
                                          style="min-height: 80px;"> </textarea>

                            </div>
                        </div>
                        <div class="flex_right">
                            <?php $enableFootNotes = false;
                            require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div>
                                <strong class="ltr"> <?php echo __("verse") ?>: </strong>
                                <div class="kwverse_2_0_14">Salvation is God’s gift. It is not based on anything you
                                    have done. Ephesians 2:8
                                </div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area" dir="ltr">
                            <div class="vnote">
                                <textarea name="chunks[]"
                                          class="col-sm-6 peer_verse_ta textarea sun_content"
                                          style="min-height: 80px;">                        :   </textarea>

                            </div>
                        </div>
                        <div class="flex_right">
                            <?php $enableFootNotes = false;
                            require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div>
                                <strong class="ltr"> <?php echo __("thought") ?>: </strong>
                                <div class="kwverse_2_0_17">We are saved by God’s work</div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area" dir="ltr">
                            <div class="vnote">
                                <textarea name="chunks[]"
                                          class="col-sm-6 peer_verse_ta textarea sun_content"
                                          style="min-height: 80px;">      </textarea>

                            </div>
                        </div>
                        <div class="flex_right">
                            <?php $enableFootNotes = false;
                            require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div>
                                <strong class="ltr"> <?php echo __("content", ["number" => 1]) ?>: </strong>
                                <div class="kwverse_2_0_19">My friend Jerry was a good man. When he died the past said
                                    Jerry loved his family. Jerry’s wife trusted him Jerry served his country in the
                                    military. Jerry was a good dad and grandfather. Jerry was a great friend. Jerry
                                    was a good man.
                                </div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area" dir="ltr">
                            <div class="vnote">
                                <textarea name="chunks[]"
                                          class="col-sm-6 peer_verse_ta textarea sun_content"
                                          style="min-height: 80px;">                                       </textarea>

                            </div>
                        </div>
                        <div class="flex_right">
                            <?php $enableFootNotes = false;
                            require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div>
                                <strong class="ltr"> <?php echo __("content", ["number" => 2]) ?>: </strong>
                                <div class="kwverse_2_0_21">The pastor said Jerry’s good things do not get him to
                                    heaven.
                                    The Bible says no one is perfect. The good things Jerry did are not good enough.
                                    Jerry knew that when you sin, the cost is death and hell. His final place after
                                    death was not from a good life. Jerry knew he needed Jesus. Jesus died in place of
                                    Jerry. Jerry knew believing in Jesus was his way to heaven.
                                </div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area" dir="ltr">
                            <div class="vnote">
                                <textarea name="chunks[]"
                                          class="col-sm-6 peer_verse_ta textarea sun_content"
                                          style="min-height: 80px;">                                                                        </textarea>

                            </div>
                        </div>
                        <div class="flex_right">
                            <?php $enableFootNotes = false;
                            require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                    <div class="flex_container chunk_block">
                        <div class="chunk_verses flex_left" dir="ltr">
                            <div>
                                <strong class="ltr"> <?php echo __("content", ["number" => 3]) ?>: </strong>
                                <div class="kwverse_2_0_24">Every person needs God to forgive them. Jesus died on the
                                    cross for our sins. We cannot be good enough. We need faith in Jesus. Heaven is
                                    God’s figt. It is not based on anything we do. His gift is wonderful.
                                </div>
                            </div>
                        </div>
                        <div class="flex_middle editor_area" dir="ltr">
                            <div class="vnote">
                                <textarea name="chunks[]"
                                          class="col-sm-6 peer_verse_ta textarea sun_content"
                                          style="min-height: 80px;">                                       </textarea>

                            </div>
                        </div>
                        <div class="flex_right">
                            <?php $enableFootNotes = false;
                            require(app_path() . "Views/Components/Comments.php"); ?>
                        </div>
                    </div>
                    <div class="chunk_divider"></div>
                </div>

                <div id="rearrange_content" class="my_content">
                    <div class="row chunk_block">
                        <div class="chunk_verses col-sm-12" dir="ltr">
                            <strong class="ltr"> <?php echo __("title") ?>: </strong> good man
                        </div>
                    </div>
                    <div class="chunk_divider col-sm-12"></div>
                    <div class="row chunk_block">
                        <div class="chunk_verses col-sm-12" dir="ltr">
                            <strong class="ltr"> <?php echo __("author") ?>: </strong></div>
                    </div>
                    <div class="chunk_divider col-sm-12"></div>
                    <div class="row chunk_block">
                        <div class="chunk_verses col-sm-12" dir="ltr">
                            <strong class="ltr"> <?php echo __("passage") ?>: </strong> read : Romans 3:10-18
                        </div>
                    </div>
                    <div class="chunk_divider col-sm-12"></div>
                    <div class="row chunk_block">
                        <div class="chunk_verses col-sm-12" dir="ltr">
                            <strong class="ltr"> <?php echo __("bible_in_a_year") ?>: </strong> Numbers 26-27, Mark
                            8:1-21
                        </div>
                    </div>
                    <div class="chunk_divider col-sm-12"></div>
                    <div class="row chunk_block">
                        <div class="chunk_verses col-sm-12" dir="ltr">
                            <strong class="ltr"> <?php echo __("verse") ?>: </strong> God save you in grace.
                            though faith. this no from you. this gift from God. Ephesians 2:8
                        </div>
                    </div>
                    <div class="chunk_divider col-sm-12"></div>
                    <div class="row chunk_block">
                        <div class="chunk_verses col-sm-12" dir="ltr">
                            <strong class="ltr"> <?php echo __("thought") ?>: </strong> God&#34;s work save us.
                        </div>
                    </div>
                    <div class="chunk_divider col-sm-12"></div>
                    <div class="row chunk_block">
                        <div class="chunk_verses col-sm-12" dir="ltr">
                            <strong class="ltr"> <?php echo __("content", ["number" => 1]) ?>: </strong> me
                            friend John good man. John die. pastor mouth John love family. John&#39;s wife trust John.
                            John serve country in army. John good dad. John great friend. John good man.
                        </div>
                    </div>
                    <div class="chunk_divider col-sm-12"></div>
                    <div class="row chunk_block">
                        <div class="chunk_verses col-sm-12" dir="ltr">
                            <strong class="ltr"> <?php echo __("content", ["number" => 2]) ?>: </strong> John
                            do good thing thing. pastor mouth good things no way to heaven. Bible write no person
                            perfect. John do good thing thing. not good for heaven. John know sin. sin worth death
                            and hell. last place after death . good life no give last place. John know need Jesus.
                            Jesus die for John. John believe Jesus. believe Jesus way to heaven.
                        </div>
                    </div>
                    <div class="chunk_divider col-sm-12"></div>
                    <div class="row chunk_block">
                        <div class="chunk_verses col-sm-12" dir="ltr">
                            <strong class="ltr"> <?php echo __("content", ["number" => 3]) ?>: </strong> all
                            people need God&#39;s forgiveness. Jesus die on cross for our sin sin. we no good .
                            we need faith in Jesus. heaven God&#39;s gift. we no earn gift. God&#39;s gift very great.
                        </div>
                    </div>
                    <div class="chunk_divider col-sm-12"></div>
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
                <div class="step_right" style="margin-top: 0px"><?php echo __("step_num", ["step_number" => 5]) ?></div>
            </div>
        </div>
    </div>
</div>

<div class="content_help closed">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps is_checker_page_help">
            <div class="help_name_steps">
                <span><?php echo __("step_num", ["step_number" => 5]) ?>:</span> <?php echo __("theo-check_odb") ?>
            </div>
            <div class="help_descr_steps">
                <ul>
                    <li><b><?php echo __("purpose") ?></b> <?php echo __("theo-check_purpose") ?></li>
                    <li><?php echo __("theo-check_help_1") ?></li>
                    <li><?php echo __("move_to_next_step", ["step" => __($data["next_step"])]) ?></li>
                </ul>
                <div class="show_tutorial_popup"> >>> <?php echo __("show_more") ?></div>
            </div>
        </div>

        <div class="event_info is_checker_page_help">
            <div class="participant_info">
                <div class="additional_info">
                    <a href="/events/demo-sun-odb/information"><?php echo __("event_info") ?></a>
                </div>
            </div>
        </div>

        <div class="tr_tools">
            <button class="btn btn-warning ttools" data-tool="saildict"><?php echo __("show_dictionary") ?></button>
            <button class="btn btn-primary ttools" data-tool="sunbible"><?php echo __("go_sun_bible") ?></button>
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
            <h3><?php echo __("theo-check_odb") ?></h3>
            <ul>
                <li><b><?php echo __("purpose") ?></b> <?php echo __("theo-check_purpose") ?></li>
                <li><?php echo __("theo-check_help_1") ?></li>
                <li><?php echo __("move_to_next_step", ["step" => __($data["next_step"])]) ?></li>
            </ul>
        </div>
    </div>
</div>

<script>
    isChecker = true;
    $(document).ready(function () {
        $("#next_step").click(function (e) {
            e.preventDefault();
            if (!hasChangesOnPage) window.location.href = '/events/demo-sun-odb/content_review_checker';
            return false;
        });
    });
</script>