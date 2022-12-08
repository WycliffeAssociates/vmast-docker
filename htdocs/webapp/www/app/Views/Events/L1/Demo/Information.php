<?php
use Helpers\Constants\EventSteps;
use Helpers\Constants\InputMode;
use Helpers\Constants\StepsStates;
?>

<div class="back_link">
    <?php if(isset($_SERVER["HTTP_REFERER"])): ?>
        <a href="<?php echo $_SERVER["HTTP_REFERER"] ?>"><?php echo __("go_back") ?></a>
    <?php endif; ?>
    <div class="demo_title"><?php echo __("demo")
        . " (".($data["inputMode"] != InputMode::NORMAL ? __($data["inputMode"]) : __("8steps_vmast")).")" ?></div>
</div>

<div>
    <div class="book_title">2 Timothy</div>
    <div class="project_title"><?php echo __("ulb") ?> - English</div>
    <?php if ($data["inputMode"] != InputMode::NORMAL): ?>
        <div class="input_mode"><?php echo __($data["inputMode"]); ?></div>
    <?php endif; ?>
    <div class="overall_progress_bar">
        <h3><?php echo __("progress_all") ?></h3>
        <div class="progress progress_all ">
            <?php $percent = $data["inputMode"] != InputMode::NORMAL ? 37 : 28; ?>
            <div style="min-width: 0; width: <?php echo $percent ?>%"
                 aria-valuemax="100"
                 aria-valuemin="0"
                 aria-valuenow="<?php echo $percent ?>" role="progressbar" class="progress-bar progress-bar-success">
                <?php echo $percent ?>%
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
                            <?php $percent = $data["inputMode"] != InputMode::NORMAL ? 100 : 77; ?>
                            <div class="progress-bar progress-bar-success"
                                 role="progressbar"
                                 aria-valuenow="<?php echo $percent ?>"
                                 aria-valuemin="0"
                                 aria-valuemax="100"
                                 style="min-width: 0em; width: <?php echo $percent ?>%"><?php echo $percent ?>%</div>
                        </div>
                        <div class=" finished_icon"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="section_content">
                    <div class="section_translator">
                        <div class="section_translator_name">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png") ?>">
                            <span><b>Marge S.</b></span>
                        </div>
                    </div>
                    <div class="section_steps">
                        <!-- Input Step -->
                        <?php if(in_array($data["inputMode"], [InputMode::SPEECH_TO_TEXT, InputMode::SCRIPTURE_INPUT])): ?>
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_" . StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/".EventSteps::CONTENT_REVIEW.".png") ?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::MULTI_DRAFT."_input_mode"); ?></div>
                        </div>
                        <?php endif; ?>
                        <!-- Consume Step -->
                        <?php if($data["inputMode"] == InputMode::NORMAL): ?>
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png") ?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME) ?></div>
                        </div>
                        <!-- Verbalize Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/verbalize.png") ?>"></div>
                            <div class="step_name">2. <?php echo __(EventSteps::VERBALIZE) ?></div>
                            <div class="step_checker">
                                <img width="50" src="<?php echo template_url("img/avatars/n1.png") ?>">
                                <div>Mark P.</div>
                                <span class="glyphicon glyphicon-ok checked"></span>
                            </div>
                        </div>
                        <!-- Chunking Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/chunking.png") ?>"></div>
                            <div class="step_name">3. <?php echo __(EventSteps::CHUNKING) ?></div>
                            <div class="step_chunks ">
                                <div class="section_translator_chunk">
                                    1 - 5 &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>
                                </div>
                                <div class="section_translator_chunk">
                                    6 - 12 &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>
                                </div>
                                <div class="section_translator_chunk">
                                    13 - 18 &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>
                                </div>
                            </div>
                        </div>
                        <!-- Blind Draft Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/blind-draft.png") ?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::BLIND_DRAFT) ?></div>
                        </div>
                        <?php endif; ?>
                        <!-- Self Edit Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png") ?>"></div>
                            <div class="step_name"><?php echo $data["inputMode"] == InputMode::NORMAL ? "5" : "2" ?>. <?php echo __(EventSteps::SELF_CHECK) ?></div>
                        </div>
                        <!-- Peer Check Step -->
                        <?php if(in_array($data["inputMode"], [InputMode::NORMAL, InputMode::SPEECH_TO_TEXT])): ?>
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/peer-review.png") ?>"></div>
                            <div class="step_name"><?php echo $data["inputMode"] == InputMode::NORMAL ? "6" : "3" ?>. <?php echo __(EventSteps::PEER_REVIEW) ?></div>
                            <div class="step_checker">
                                <img width="50" src="<?php echo template_url("img/avatars/n1.png") ?>">
                                <div>Paul G.</div>
                                <span class="glyphicon glyphicon-ok checked"></span>
                            </div>
                        </div>
                        <?php endif; ?>
                        <!-- Keyword Check Step -->
                        <?php if($data["inputMode"] == InputMode::NORMAL): ?>
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/keyword-check.png") ?>"></div>
                            <div class="step_name">7. <?php echo __(EventSteps::KEYWORD_CHECK) ?></div>
                            <div class="step_checker">
                                <img width="50" src="<?php echo template_url("img/avatars/n1.png") ?>">
                                <div>John B.</div>
                                <span class="glyphicon glyphicon-ok checked"></span>
                            </div>
                        </div>
                        <!-- Verse-by-Verse Step -->
                        <div class="section_step waiting">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::WAITING) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/content-review.png") ?>"></div>
                            <div class="step_name">8. <?php echo __(EventSteps::CONTENT_REVIEW) ?></div>
                            <img class="img_waiting" src="<?php echo template_url("img/waiting.png") ?>">
                        </div>
                        <!-- Final Review Step -->
                        <div class="section_step finalReview not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/final-review.png") ?>"></div>
                            <div class="step_name"><?php echo __(EventSteps::FINAL_REVIEW) ?></div>
                        </div>
                        <?php endif; ?>
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
                            <?php $percent = $data["inputMode"] != InputMode::NORMAL ? 50 : 33; ?>
                            <div class="progress-bar progress-bar-success"
                                 role="progressbar"
                                 aria-valuenow="<?php echo $percent ?>"
                                 aria-valuemin="0"
                                 aria-valuemax="100"
                                 style="min-width: 0em; width: <?php echo $percent ?>%"><?php echo $percent ?>%</div>
                        </div>
                        <div class=" finished_icon"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="section_content">
                    <div class="section_translator">
                        <div class="section_translator_name">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png") ?>">
                            <span><b>Mark P.</b></span>
                        </div>
                    </div>
                    <div class="section_steps">
                        <!-- Input Step -->
                        <?php if(in_array($data["inputMode"], [InputMode::SPEECH_TO_TEXT, InputMode::SCRIPTURE_INPUT])): ?>
                            <div class="section_step finished">
                                <div class="step_status"><?php echo __("step_status_" . StepsStates::FINISHED) ?></div>
                                <div class="step_light"></div>
                                <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/".EventSteps::CONTENT_REVIEW.".png") ?>"></div>
                                <div class="step_name">1. <?php echo __(EventSteps::MULTI_DRAFT."_input_mode"); ?></div>
                            </div>
                        <?php endif; ?>
                        <!-- Consume Step -->
                        <?php if($data["inputMode"] == InputMode::NORMAL): ?>
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png") ?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME) ?></div>
                        </div>
                        <!-- Verbalize Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/verbalize.png") ?>"></div>
                            <div class="step_name">2. <?php echo __(EventSteps::VERBALIZE) ?></div>
                            <div class="step_checker">
                                <img width="50" src="<?php echo template_url("img/avatars/n1.png") ?>">
                                <div>Marge S.</div>
                                <span class="glyphicon glyphicon-ok checked"></span>
                            </div>
                        </div>
                        <!-- Chunking Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/chunking.png") ?>"></div>
                            <div class="step_name">3. <?php echo __(EventSteps::CHUNKING) ?></div>
                            <div class="step_chunks ">
                                <div class="section_translator_chunk">
                                    1 - 7 &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>
                                </div>
                                <div class="section_translator_chunk">
                                    8 - 14
                                </div>
                                <div class="section_translator_chunk">
                                    15 - 20
                                </div>
                                <div class="section_translator_chunk">
                                    21 - 26
                                </div>
                            </div>
                        </div>
                        <!-- Blind Draft Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/blind-draft.png") ?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::BLIND_DRAFT) ?></div>
                        </div>
                        <?php endif; ?>
                        <!-- Self Edit Step -->
                        <div class="section_step in_progress">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::IN_PROGRESS) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png") ?>"></div>
                            <div class="step_name"><?php echo $data["inputMode"] == InputMode::NORMAL ? "5" : "2" ?>. <?php echo __(EventSteps::SELF_CHECK) ?></div>
                        </div>
                        <!-- Peer Check Step -->
                        <?php if(in_array($data["inputMode"], [InputMode::NORMAL, InputMode::SPEECH_TO_TEXT])): ?>
                        <div class="section_step not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/peer-review.png") ?>"></div>
                            <div class="step_name"><?php echo $data["inputMode"] == InputMode::NORMAL ? "6" : "3" ?>. <?php echo __(EventSteps::PEER_REVIEW) ?></div>
                        </div>
                        <?php endif; ?>
                        <!-- Keyword Check Step -->
                        <?php if($data["inputMode"] == InputMode::NORMAL): ?>
                        <div class="section_step not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/keyword-check.png") ?>"></div>
                            <div class="step_name">7. <?php echo __(EventSteps::KEYWORD_CHECK) ?></div>
                        </div>
                        <!-- Verse-by-Verse Step -->
                        <div class="section_step not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/content-review.png") ?>"></div>
                            <div class="step_name">8. <?php echo __(EventSteps::CONTENT_REVIEW) ?></div>
                        </div>
                        <!-- Final Review Step -->
                        <div class="section_step finalReview not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/final-review.png") ?>"></div>
                            <div class="step_name"><?php echo __(EventSteps::FINAL_REVIEW) ?></div>
                        </div>
                        <?php endif; ?>
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
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="min-width: 0em; width: 0%">0%</div>
                        </div>
                        <div class=" finished_icon"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="section_content">
                    <div class="section_translator">
                        <div class="section_translator_name">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png") ?>">
                            <span><b>mSimpson</b></span>
                        </div>
                    </div>
                    <div class="section_steps">
                        <!-- Input Step -->
                        <?php if(in_array($data["inputMode"], [InputMode::SPEECH_TO_TEXT, InputMode::SCRIPTURE_INPUT])): ?>
                        <div class="section_step not_started">
                            <div class="step_status"><?php echo __("step_status_" . StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/".EventSteps::CONTENT_REVIEW.".png") ?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::MULTI_DRAFT."_input_mode"); ?></div>
                        </div>
                        <?php endif; ?>
                        <!-- Consume Step -->
                        <?php if($data["inputMode"] == InputMode::NORMAL): ?>
                        <div class="section_step not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png") ?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME) ?></div>
                        </div>
                        <!-- Verbalize Step -->
                        <div class="section_step not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/verbalize.png") ?>"></div>
                            <div class="step_name">2. <?php echo __(EventSteps::VERBALIZE) ?></div>
                        </div>
                        <!-- Chunking Step -->
                        <div class="section_step not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/chunking.png") ?>"></div>
                            <div class="step_name">3. <?php echo __(EventSteps::CHUNKING) ?></div>
                            <div class="step_chunks ">
                            </div>
                        </div>
                        <!-- Blind Draft Step -->
                        <div class="section_step not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/blind-draft.png") ?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::BLIND_DRAFT) ?></div>
                        </div>
                        <?php endif; ?>
                        <!-- Self Edit Step -->
                        <div class="section_step not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png") ?>"></div>
                            <div class="step_name"><?php echo $data["inputMode"] == InputMode::NORMAL ? "5" : "2" ?>. <?php echo __(EventSteps::SELF_CHECK) ?></div>
                        </div>
                        <!-- Peer Check Step -->
                        <?php if(in_array($data["inputMode"], [InputMode::NORMAL, InputMode::SPEECH_TO_TEXT])): ?>
                        <div class="section_step not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/peer-review.png") ?>"></div>
                            <div class="step_name"><?php echo $data["inputMode"] == InputMode::NORMAL ? "6" : "3" ?>. <?php echo __(EventSteps::PEER_REVIEW) ?></div>
                        </div>
                        <?php endif; ?>
                        <!-- Keyword Check Step -->
                        <?php if($data["inputMode"] == InputMode::NORMAL): ?>
                        <div class="section_step not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/keyword-check.png") ?>"></div>
                            <div class="step_name">7. <?php echo __(EventSteps::KEYWORD_CHECK) ?></div>
                        </div>
                        <!-- Verse-by-Verse Step -->
                        <div class="section_step not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/content-review.png") ?>"></div>
                            <div class="step_name">8. <?php echo __(EventSteps::CONTENT_REVIEW) ?></div>
                        </div>
                        <!-- Final Review Step -->
                        <div class="section_step finalReview not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/final-review.png") ?>"></div>
                            <div class="step_name"><?php echo __(EventSteps::FINAL_REVIEW) ?></div>
                        </div>
                        <?php endif; ?>
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
                        <div class="progress zero">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="min-width: 0em; width: 0%">0%</div>
                        </div>
                        <div class=" finished_icon"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="section_content">
                    <div class="section_translator">
                        <div class="section_translator_name">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png") ?>">
                            <span><b>mpat1977</b></span>
                        </div>
                    </div>
                    <div class="section_steps">
                        <!-- Input Step -->
                        <?php if(in_array($data["inputMode"], [InputMode::SPEECH_TO_TEXT, InputMode::SCRIPTURE_INPUT])): ?>
                        <div class="section_step not_started">
                            <div class="step_status"><?php echo __("step_status_" . StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/".EventSteps::CONTENT_REVIEW.".png") ?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::MULTI_DRAFT."_input_mode"); ?></div>
                        </div>
                        <?php endif; ?>
                        <!-- Consume Step -->
                        <?php if($data["inputMode"] == InputMode::NORMAL): ?>
                        <div class="section_step not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png") ?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME) ?></div>
                        </div>
                        <!-- Verbalize Step -->
                        <div class="section_step not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/verbalize.png") ?>"></div>
                            <div class="step_name">2. <?php echo __(EventSteps::VERBALIZE) ?></div>
                        </div>
                        <!-- Chunking Step -->
                        <div class="section_step not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/chunking.png") ?>"></div>
                            <div class="step_name">3. <?php echo __(EventSteps::CHUNKING) ?></div>
                            <div class="step_chunks ">
                            </div>
                        </div>
                        <!-- Blind Draft Step -->
                        <div class="section_step not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/blind-draft.png") ?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::BLIND_DRAFT) ?></div>
                        </div>
                        <?php endif; ?>
                        <!-- Self Edit Step -->
                        <div class="section_step not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png") ?>"></div>
                            <div class="step_name"><?php echo $data["inputMode"] == InputMode::NORMAL ? "5" : "2" ?>. <?php echo __(EventSteps::SELF_CHECK) ?></div>
                        </div>
                        <!-- Peer Check Step -->
                        <?php if(in_array($data["inputMode"], [InputMode::NORMAL, InputMode::SPEECH_TO_TEXT])): ?>
                        <div class="section_step not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/peer-review.png") ?>"></div>
                            <div class="step_name"><?php echo $data["inputMode"] == InputMode::NORMAL ? "6" : "3" ?>. <?php echo __(EventSteps::PEER_REVIEW) ?></div>
                        </div>
                        <?php endif; ?>
                        <!-- Keyword Check Step -->
                        <?php if($data["inputMode"] == InputMode::NORMAL): ?>
                        <div class="section_step not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/keyword-check.png") ?>"></div>
                            <div class="step_name">7. <?php echo __(EventSteps::KEYWORD_CHECK) ?></div>
                        </div>
                        <!-- Verse-by-Verse Step -->
                        <div class="section_step not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/content-review.png") ?>"></div>
                            <div class="step_name">8. <?php echo __(EventSteps::CONTENT_REVIEW) ?></div>
                        </div>
                        <!-- Final Review Step -->
                        <div class="section_step finalReview not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/final-review.png") ?>"></div>
                            <div class="step_name"><?php echo __(EventSteps::FINAL_REVIEW) ?></div>
                        </div>
                        <?php endif; ?>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="members_list">
        <div class="members_title"><?php echo __("event_participants") ?>:</div>
        <div data="16" class="member_item">
            <span class="online_indicator glyphicon glyphicon-record online">&nbsp;</span>
            <span class="member_uname">Marge S.</span>
            <span class="member_admin"> </span>
            <span class="online_status" style="display: inline;"><?php echo __("status_online") ?></span>
            <span class="offline_status" style="display: none;"><?php echo __("status_offline") ?></span>
        </div>
        <div data="7" class="member_item">
            <span class="online_indicator glyphicon glyphicon-record online">&nbsp;</span>
            <span class="member_uname">Paul G.</span>
            <span class="member_admin"> </span>
            <span class="online_status" style="display: inline;"><?php echo __("status_online") ?></span>
            <span class="offline_status" style="display: none;"><?php echo __("status_offline") ?></span>
        </div>
        <div data="17" class="member_item">
            <span class="online_indicator glyphicon glyphicon-record">&nbsp;</span>
            <span class="member_uname">Genry M.</span>
            <span class="member_admin"> (<?php echo __("facilitator"); ?>)</span>
            <span class="online_status" style="display: none;"><?php echo __("status_online") ?></span>
            <span class="offline_status"><?php echo __("status_offline") ?></span>
        </div>
    </div>
</div>