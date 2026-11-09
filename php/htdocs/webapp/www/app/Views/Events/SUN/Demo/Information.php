<?php
use Helpers\Constants\EventSteps;
use Helpers\Constants\StepsStates;
?>

<div class="back_link">
    <?php if(isset($_SERVER["HTTP_REFERER"])): ?>
        <a href="<?php echo $_SERVER["HTTP_REFERER"] ?>"><?php echo __("go_back") ?></a>
    <?php endif; ?>
    <div class="demo_title"><?php echo __("demo") . " (".__("vsail").")" ?></div>
</div>

<div>
    <div class="book_title">Matthew</div>
    <div class="project_title"><?php echo __("sun") ?> - English</div>
    <div class="overall_progress_bar">
        <h3><?php echo __("progress_all") ?></h3>
        <div class="progress progress_all ">
            <div style="min-width: 0em; width: 9%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="9" role="progressbar" class="progress-bar progress-bar-success">
                9%
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>

<div class="row" style="position:relative;">
    <div class="chapter_list">
        <div class="chapter_item">
            <div class="chapter_accordion">
                <div class="section_header" data="sec_1">
                    <div class="section_arrow glyphicon glyphicon-triangle-right"></div>
                    <div class="chapter_number section_title"><?php echo __("chapter_number", ["chapter" => 1]) ?></div>
                    <div class="section_translator_progress_bar">
                        <div class="progress ">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="min-width: 0em; width: 100%"> 100% </div>
                        </div>
                        <div class="glyphicon glyphicon-ok finished_icon"></div>
                        <div class="clear"></div>
                    </div>
                    <div> <span style="font-weight: bold;"><?php echo __("last_edit") .": " ?> </span> <span class="datetime" data="Thu, 08 Feb 2018 20:43:39 +0000">Thu, February 8, 2018, 3:43 PM</span> </div>
                    <div class="clear"></div>
                </div>
                <div class="section_content" style="display: none;">
                    <div class="section_translator">
                        <div class="section_translator_name tnleft sun"> <img src="<?php echo template_url("img/avatars/n1.png") ?>" width="50"> <span><b>Max A.</b></span> </div>
                        <div class="section_translator_name tnleft sun" style="margin-left: 520px"> <img src="<?php echo template_url("img/avatars/n1.png") ?>" width="50"> <span><b>Genry M.</b></span> </div>
                        <div class="section_translator_name tnleft sun" style="margin-left: 30px"> <img src="<?php echo template_url("img/avatars/n1.png") ?>" width="50"> <span><b>Genry M.</b></span> </div>
                        <div class="clear"></div>
                    </div>
                    <div class="section_steps">
                        <!-- Consume Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/consume.png") ?>" width="40"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME) ?></div>
                        </div>
                        <!-- Chunking Check Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/chunking.png") ?>" width="40"></div>
                            <div class="step_name">2. <?php echo __(EventSteps::CHUNKING); ?></div>
                            <div class="step_chunks ">
                                <div class="section_translator_chunk"> 1 - 5 &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span> </div>
                                <div class="section_translator_chunk"> 6 - 9 &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span> </div>
                                <div class="section_translator_chunk"> 10 - 13 &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span> </div>
                                <div class="section_translator_chunk"> 14 - 18 &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span> </div>
                            </div>
                        </div>
                        <!-- Rearrange Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/rearrange.png") ?>" width="40"></div>
                            <div class="step_name">3. <?php echo __(EventSteps::REARRANGE); ?></div>
                        </div>
                        <!-- Symbol Draft Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/symbol-draft.png") ?>" width="40"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::SYMBOL_DRAFT); ?></div>
                        </div>
                        <!-- Self Check Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/self-check.png") ?>" width="40"></div>
                            <div class="step_name">5. <?php echo __(EventSteps::SELF_CHECK); ?></div>
                        </div>
                        <!-- Checking stage -->
                        <div class="section_step chk" style="width: 50px"> </div>
                        <!-- Theo Check Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/keyword-check.png") ?>" width="40"></div>
                            <div class="step_name">6. <?php echo __(EventSteps::THEO_CHECK); ?></div>
                        </div>
                        <!-- Checking stage -->
                        <div class="section_step chk" style="width: 50px"> </div>
                        <!-- Verse-by-verse Check Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/content-review.png") ?>" width="40"></div>
                            <div class="step_name">7. <?php echo __(EventSteps::CONTENT_REVIEW); ?></div>
                        </div>
                        <!-- Verse Markers Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/final-review.png") ?>" width="40"></div>
                            <div class="step_name"><?php echo __(EventSteps::FINAL_REVIEW); ?></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="chapter_item">
            <div class="chapter_accordion">
                <div class="section_header" data="sec_2">
                    <div class="section_arrow glyphicon glyphicon-triangle-right"></div>
                    <div class="chapter_number section_title"><?php echo __("chapter_number", ["chapter" => 2]) ?></div>
                    <div class="section_translator_progress_bar">
                        <div class="progress ">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="min-width: 0em; width: 100%"> 100% </div>
                        </div>
                        <div class="glyphicon glyphicon-ok finished_icon"></div>
                        <div class="clear"></div>
                    </div>
                    <div> <span style="font-weight: bold;"><?php echo __("last_edit") .": " ?> </span> <span class="datetime" data="Fri, 09 Feb 2018 15:30:52 +0000">Fri, February 9, 2018, 10:30 AM</span> </div>
                    <div class="clear"></div>
                </div>
                <div class="section_content">
                    <div class="section_translator">
                        <div class="section_translator_name tnleft sun"> <img src="<?php echo template_url("img/avatars/n1.png") ?>" width="50"> <span><b>Max A.</b></span> </div>
                        <div class="section_translator_name tnleft sun" style="margin-left: 520px"> <img src="<?php echo template_url("img/avatars/n1.png") ?>" width="50"> <span><b>Mark P.</b></span> </div>
                        <div class="section_translator_name tnleft sun" style="margin-left: 30px"> <img src="<?php echo template_url("img/avatars/n1.png") ?>" width="50"> <span><b>Amy G.</b></span> </div>
                        <div class="clear"></div>
                    </div>
                    <div class="section_steps">
                        <!-- Consume Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/consume.png") ?>" width="40"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME) ?></div>
                        </div>
                        <!-- Chunking Check Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/chunking.png") ?>" width="40"></div>
                            <div class="step_name">2. <?php echo __(EventSteps::CHUNKING); ?></div>
                            <div class="step_chunks more_chunks">
                                <div class="section_translator_chunk"> 1 - 3 &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span> </div>
                                <div class="section_translator_chunk"> 4 - 7 &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span> </div>
                                <div class="section_translator_chunk"> 8 - 11 &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span> </div>
                                <div class="section_translator_chunk"> 12 - 14 &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span> </div>
                                <div class="section_translator_chunk"> 15 - 17 &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span> </div>
                                <div class="section_translator_chunk"> 18 - 19 &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span> </div>
                                <div class="section_translator_chunk"> 20 - 22 &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span> </div>
                                <div class="section_translator_chunk"> 23 - 26 &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span> </div>
                                <div class="chunks_blind glyphicon glyphicon-triangle-bottom"></div>
                            </div>
                        </div>
                        <!-- Rearrange Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/rearrange.png") ?>" width="40"></div>
                            <div class="step_name">3. <?php echo __(EventSteps::REARRANGE); ?></div>
                        </div>
                        <!-- Symbol Draft Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/symbol-draft.png") ?>" width="40"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::SYMBOL_DRAFT); ?></div>
                        </div>
                        <!-- Self Check Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/self-check.png") ?>" width="40"></div>
                            <div class="step_name">5. <?php echo __(EventSteps::SELF_CHECK); ?></div>
                        </div>
                        <!-- Checking stage -->
                        <div class="section_step chk" style="width: 50px"> </div>
                        <!-- Theo Check Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/keyword-check.png") ?>" width="40"></div>
                            <div class="step_name">6. <?php echo __(EventSteps::THEO_CHECK); ?></div>
                        </div>
                        <!-- Checking stage -->
                        <div class="section_step chk" style="width: 50px"> </div>
                        <!-- Verse-by-verse Check Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/content-review.png") ?>" width="40"></div>
                            <div class="step_name">7. <?php echo __(EventSteps::CONTENT_REVIEW); ?></div>
                        </div>
                        <!-- Verse Markers Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/final-review.png") ?>" width="40"></div>
                            <div class="step_name"><?php echo __(EventSteps::FINAL_REVIEW); ?></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="chapter_item">
            <div class="chapter_accordion">
                <div class="section_header" data="sec_3">
                    <div class="section_arrow glyphicon glyphicon-triangle-right"></div>
                    <div class="chapter_number section_title"><?php echo __("chapter_number", ["chapter" => 3]) ?></div>
                    <div class="section_translator_progress_bar">
                        <div class="progress zero">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="min-width: 0em; width: 0%"> 0% </div>
                        </div>
                        <div class=" finished_icon"></div>
                        <div class="clear"></div>
                    </div>
                    <div> </div>
                    <div class="clear"></div>
                </div>
                <div class="section_content">
                    <div class="section_translator">
                        <div class="section_translator_name tnleft sun"> <img src="<?php echo template_url("img/avatars/n1.png") ?>" width="50"> <span><b>Mark P.</b></span> </div>
                        <div class="clear"></div>
                    </div>
                    <div class="section_steps">
                        <!-- Consume Step -->
                        <div class="section_step not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/consume.png") ?>" width="40"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME) ?></div>
                        </div>
                        <!-- Chunking Check Step -->
                        <div class="section_step not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/chunking.png") ?>" width="40"></div>
                            <div class="step_name">2. <?php echo __(EventSteps::CHUNKING); ?></div>
                            <div class="step_chunks "> </div>
                        </div>
                        <!-- Rearrange Step -->
                        <div class="section_step not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/rearrange.png") ?>" width="40"></div>
                            <div class="step_name">3. <?php echo __(EventSteps::REARRANGE); ?></div>
                        </div>
                        <!-- Symbol Draft Step -->
                        <div class="section_step not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/symbol-draft.png") ?>" width="40"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::SYMBOL_DRAFT); ?></div>
                        </div>
                        <!-- Self Check Step -->
                        <div class="section_step not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/self-check.png") ?>" width="40"></div>
                            <div class="step_name">5. <?php echo __(EventSteps::SELF_CHECK); ?></div>
                        </div>
                        <!-- Checking stage -->
                        <div class="section_step chk" style="width: 50px"> </div>
                        <!-- Theo Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/keyword-check.png") ?>" width="40"></div>
                            <div class="step_name">6. <?php echo __(EventSteps::THEO_CHECK); ?></div>
                        </div>
                        <!-- Checking stage -->
                        <div class="section_step chk" style="width: 50px"> </div>
                        <!-- Verse-by-verse Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/content-review.png") ?>" width="40"></div>
                            <div class="step_name">7. <?php echo __(EventSteps::CONTENT_REVIEW); ?></div>
                        </div>
                        <!-- Verse Markers Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/final-review.png") ?>" width="40"></div>
                            <div class="step_name"><?php echo __(EventSteps::FINAL_REVIEW); ?></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="chapter_item">
            <div class="chapter_accordion">
                <div class="section_header" data="sec_4">
                    <div class="section_arrow glyphicon glyphicon-triangle-right"></div>
                    <div class="chapter_number section_title"><?php echo __("chapter_number", ["chapter" => 4]) ?></div>
                    <div class="section_translator_progress_bar">
                        <div class="progress ">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="min-width: 0em; width: 25%"> 25% </div>
                        </div>
                        <div class=" finished_icon"></div>
                        <div class="clear"></div>
                    </div>
                    <div> <span style="font-weight: bold;"><?php echo __("last_edit") .": " ?> </span> <span class="datetime" data="Fri, 09 Feb 2018 14:19:23 +0000">Fri, February 9, 2018, 9:19 AM</span> </div>
                    <div class="clear"></div>
                </div>
                <div class="section_content">
                    <div class="section_translator">
                        <div class="section_translator_name tnleft sun"> <img src="<?php echo template_url("img/avatars/n1.png") ?>" width="50"> <span><b>Amy G.</b></span> </div>
                        <div class="clear"></div>
                    </div>
                    <div class="section_steps">
                        <!-- Consume Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/consume.png") ?>" width="40"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME) ?></div>
                        </div>
                        <!-- Chunking Check Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/chunking.png") ?>" width="40"></div>
                            <div class="step_name">2. <?php echo __(EventSteps::CHUNKING); ?></div>
                            <div class="step_chunks more_chunks">
                                <div class="section_translator_chunk"> 1 - 3 &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span> </div>
                                <div class="section_translator_chunk"> 4 - 6 &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span> </div>
                                <div class="section_translator_chunk"> 7 - 9 </div>
                                <div class="section_translator_chunk"> 10 - 11 </div>
                                <div class="section_translator_chunk"> 12 - 14 </div>
                                <div class="section_translator_chunk"> 15 - 17 </div>
                                <div class="section_translator_chunk"> 18 - 22 </div>
                                <div class="chunks_blind glyphicon glyphicon-triangle-bottom"></div>
                            </div>
                        </div>
                        <!-- Rearrange Step -->
                        <div class="section_step in_progress">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::IN_PROGRESS) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/rearrange.png") ?>" width="40"></div>
                            <div class="step_name">3. <?php echo __(EventSteps::REARRANGE); ?></div>
                        </div>
                        <!-- Symbol Draft Step -->
                        <div class="section_step not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/symbol-draft.png") ?>" width="40"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::SYMBOL_DRAFT); ?></div>
                        </div>
                        <!-- Self Check Step -->
                        <div class="section_step not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/self-check.png") ?>" width="40"></div>
                            <div class="step_name">5. <?php echo __(EventSteps::SELF_CHECK); ?></div>
                        </div>
                        <!-- Checking stage -->
                        <div class="section_step chk" style="width: 50px"> </div>
                        <!-- Theo Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/keyword-check.png") ?>" width="40"></div>
                            <div class="step_name">6. <?php echo __(EventSteps::THEO_CHECK); ?></div>
                        </div>
                        <!-- Checking stage -->
                        <div class="section_step chk" style="width: 50px"> </div>
                        <!-- Verse-by-verse Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/content-review.png") ?>" width="40"></div>
                            <div class="step_name">7. <?php echo __(EventSteps::CONTENT_REVIEW); ?></div>
                        </div>
                        <!-- Verse Markers Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/final-review.png") ?>" width="40"></div>
                            <div class="step_name"><?php echo __(EventSteps::FINAL_REVIEW); ?></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="chapter_item">
            <div class="chapter_number nofloat"><?php echo __("chapter_number", ["chapter" => 5]) ?></div>
        </div>
        <div class="chapter_item">
            <div class="chapter_number nofloat"><?php echo __("chapter_number", ["chapter" => 6]) ?></div>
        </div>
        <div class="chapter_item">
            <div class="chapter_number nofloat"><?php echo __("chapter_number", ["chapter" => 7]) ?></div>
        </div>
        <div class="chapter_item">
            <div class="chapter_number nofloat"><?php echo __("chapter_number", ["chapter" => 8]) ?></div>
        </div>
        <div class="chapter_item">
            <div class="chapter_number nofloat"><?php echo __("chapter_number", ["chapter" => 9]) ?></div>
        </div>
        <div class="chapter_item">
            <div class="chapter_number nofloat"><?php echo __("chapter_number", ["chapter" => 10]) ?></div>
        </div>
        <div class="chapter_item">
            <div class="chapter_number nofloat"><?php echo __("chapter_number", ["chapter" => 11]) ?></div>
        </div>
        <div class="chapter_item">
            <div class="chapter_number nofloat"><?php echo __("chapter_number", ["chapter" => 12]) ?></div>
        </div>
        <div class="chapter_item">
            <div class="chapter_number nofloat"><?php echo __("chapter_number", ["chapter" => 13]) ?></div>
        </div>
        <div class="chapter_item">
            <div class="chapter_number nofloat"><?php echo __("chapter_number", ["chapter" => 14]) ?></div>
        </div>
        <div class="chapter_item">
            <div class="chapter_number nofloat"><?php echo __("chapter_number", ["chapter" => 15]) ?></div>
        </div>
        <div class="chapter_item">
            <div class="chapter_number nofloat"><?php echo __("chapter_number", ["chapter" => 16]) ?></div>
        </div>
        <div class="chapter_item">
            <div class="chapter_accordion">
                <div class="section_header" data="sec_4">
                    <div class="section_arrow glyphicon glyphicon-triangle-right"></div>
                    <div class="chapter_number section_title"><?php echo __("chapter_number", ["chapter" => 17]) ?></div>
                    <div class="section_translator_progress_bar">
                        <div class="progress ">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="min-width: 0em; width: 25%"> 25% </div>
                        </div>
                        <div class=" finished_icon"></div>
                        <div class="clear"></div>
                    </div>
                    <div> <span style="font-weight: bold;"><?php echo __("last_edit") .": " ?> </span> <span class="datetime" data="Fri, 09 Feb 2018 14:19:23 +0000">Fri, February 9, 2018, 9:19 AM</span> </div>
                    <div class="clear"></div>
                </div>
                <div class="section_content">
                    <div class="section_translator">
                        <div class="section_translator_name tnleft sun"> <img src="<?php echo template_url("img/avatars/n1.png") ?>" width="50"> <span><b>Amy G.</b></span> </div>
                        <div class="clear"></div>
                    </div>
                    <div class="section_steps">
                        <!-- Consume Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/consume.png") ?>" width="40"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME) ?></div>
                        </div>
                        <!-- Chunking Check Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/chunking.png") ?>" width="40"></div>
                            <div class="step_name">2. <?php echo __(EventSteps::CHUNKING); ?></div>
                            <div class="step_chunks more_chunks">
                                <div class="section_translator_chunk"> 1 - 3 &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span> </div>
                                <div class="section_translator_chunk"> 4 - 6 &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span> </div>
                                <div class="section_translator_chunk"> 7 - 9 </div>
                                <div class="section_translator_chunk"> 10 - 11 </div>
                                <div class="section_translator_chunk"> 12 - 14 </div>
                                <div class="section_translator_chunk"> 15 - 17 </div>
                                <div class="section_translator_chunk"> 18 - 22 </div>
                                <div class="chunks_blind glyphicon glyphicon-triangle-bottom"></div>
                            </div>
                        </div>
                        <!-- Rearrange Step -->
                        <div class="section_step in_progress">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::IN_PROGRESS) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/rearrange.png") ?>" width="40"></div>
                            <div class="step_name">3. <?php echo __(EventSteps::REARRANGE); ?></div>
                        </div>
                        <!-- Symbol Draft Step -->
                        <div class="section_step not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/symbol-draft.png") ?>" width="40"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::SYMBOL_DRAFT); ?></div>
                        </div>
                        <!-- Self Check Step -->
                        <div class="section_step not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/self-check.png") ?>" width="40"></div>
                            <div class="step_name">5. <?php echo __(EventSteps::SELF_CHECK); ?></div>
                        </div>
                        <!-- Checking stage -->
                        <div class="section_step chk" style="width: 50px"> </div>
                        <!-- Theo Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/keyword-check.png") ?>" width="40"></div>
                            <div class="step_name">6. <?php echo __(EventSteps::THEO_CHECK); ?></div>
                        </div>
                        <!-- Checking stage -->
                        <div class="section_step chk" style="width: 50px"> </div>
                        <!-- Verse-by-verse Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/content-review.png") ?>" width="40"></div>
                            <div class="step_name">7. <?php echo __(EventSteps::CONTENT_REVIEW); ?></div>
                        </div>
                        <!-- Verse Markers Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/final-review.png") ?>" width="40"></div>
                            <div class="step_name"><?php echo __(EventSteps::FINAL_REVIEW); ?></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="chapter_item">
            <div class="chapter_number nofloat"><?php echo __("chapter_number", ["chapter" => 18]) ?></div>
        </div>
        <div class="chapter_item">
            <div class="chapter_number nofloat"><?php echo __("chapter_number", ["chapter" => 19]) ?></div>
        </div>
        <div class="chapter_item">
            <div class="chapter_number nofloat"><?php echo __("chapter_number", ["chapter" => 20]) ?></div>
        </div>
        <div class="chapter_item">
            <div class="chapter_number nofloat"><?php echo __("chapter_number", ["chapter" => 21]) ?></div>
        </div>
        <div class="chapter_item">
            <div class="chapter_number nofloat"><?php echo __("chapter_number", ["chapter" => 22]) ?></div>
        </div>
        <div class="chapter_item">
            <div class="chapter_number nofloat"><?php echo __("chapter_number", ["chapter" => 23]) ?></div>
        </div>
        <div class="chapter_item">
            <div class="chapter_number nofloat"><?php echo __("chapter_number", ["chapter" => 24]) ?></div>
        </div>
        <div class="chapter_item">
            <div class="chapter_number nofloat"><?php echo __("chapter_number", ["chapter" => 25]) ?></div>
        </div>
        <div class="chapter_item">
            <div class="chapter_number nofloat"><?php echo __("chapter_number", ["chapter" => 26]) ?></div>
        </div>
        <div class="chapter_item">
            <div class="chapter_number nofloat"><?php echo __("chapter_number", ["chapter" => 27]) ?></div>
        </div>
        <div class="chapter_item">
            <div class="chapter_number nofloat"><?php echo __("chapter_number", ["chapter" => 28]) ?></div>
        </div>
    </div>

    <div class="members_list">
        <div class="members_title"><?php echo __("event_participants") ?>:</div>
        <div class="member_item" data="4">
            <span class="online_indicator glyphicon glyphicon-record">&nbsp;</span>
            <span class="member_uname">Mur M.</span>
            <span class="member_admin"> </span> <span class="online_status" style="display: none;"><?php echo __("status_online") ?></span>
            <span class="offline_status"><?php echo __("status_offline") ?></span>
        </div>
        <div class="member_item" data="5">
            <span class="online_indicator glyphicon glyphicon-record">&nbsp;</span>
            <span class="member_uname">Mark P.</span>
            <span class="member_admin"> </span> <span class="online_status" style="display: none;"><?php echo __("status_online") ?></span>
            <span class="offline_status"><?php echo __("status_offline") ?></span>
        </div>
        <div class="member_item" data="3">
            <span class="online_indicator glyphicon glyphicon-record online">&nbsp;</span>
            <span class="member_uname">Max A.</span>
            <span class="member_admin"> (<?php echo __("facilitator"); ?>)</span>
            <span class="online_status" style="display: inline;"><?php echo __("status_online") ?></span>
            <span class="offline_status" style="display: none;"><?php echo __("status_offline") ?></span>
        </div>
        <div class="member_item" data="6">
            <span class="online_indicator glyphicon glyphicon-record online">&nbsp;</span>
            <span class="member_uname">Amy G.</span>
            <span class="member_admin"> </span> <span class="online_status" style="display: inline;"><?php echo __("status_online") ?></span>
            <span class="offline_status" style="display: none;"><?php echo __("status_offline") ?></span>
        </div>
    </div>
</div>