<?php
use Helpers\Constants\EventSteps;
use Helpers\Constants\InputMode;
use Helpers\Constants\StepsStates;

foreach ($data["chapters"] as $key => $chapter):?>
    <?php if(empty($chapter)): ?>
        <div class="chapter_item">
            <div class="chapter_number nofloat">
                <?php echo __("chapter_number", ["chapter" => $key]) ?>
            </div>
        </div>
    <?php continue; endif; ?>
    <div class="chapter_item">
        <div class="chapter_accordion">
            <div class="section_header" data="<?php echo "sec_".$key?>">
                <div class="section_arrow glyphicon glyphicon-triangle-right"></div>
                <div class="chapter_number section_title"><?php echo __("chapter_number", ["chapter" => $key]) ?></div>
                <div class="section_translator_progress_bar">
                    <div class="progress <?php echo $chapter["progress"] <= 0 ? "zero" : ""?>">
                        <div class="progress-bar progress-bar-success" role="progressbar"
                             aria-valuenow="<?php echo floor($chapter["progress"]) ?>" aria-valuemin="0"
                             aria-valuemax="100" style="min-width: 0em; width: <?php echo floor($chapter["progress"])."%" ?>">
                            <?php echo floor($chapter["progress"])."%" ?>
                        </div>
                    </div>
                    <div class="<?php echo $chapter["progress"] >= 100 ? "glyphicon glyphicon-ok" : "" ?> finished_icon"></div>
                    <div class="clear"></div>
                </div>
                <div>
                    <?php if(isset($chapter["lastEdit"])): ?>
                        <span style="font-weight: bold;"><?php echo __("last_edit") .": " ?></span>
                        <span class="datetime" data="<?php echo isset($chapter["lastEdit"]) ? date(DATE_RFC2822, strtotime($chapter["lastEdit"])) : "" ?>">
                                    <?php echo $chapter["lastEdit"] ?>
                                </span>
                    <?php endif; ?>
                </div>
                <div class="clear"></div>
            </div>
            <div class="section_content">
                <div class="section_translator">
                    <div class="section_translator_name">
                        <img width="50" src="<?php echo template_url("img/avatars/n1.png") ?>">
                        <span><b><?php echo $data["members"][$chapter["memberID"]]["name"] ?></b></span>
                    </div>
                </div>
                <div class="section_steps">
                    <!-- Input Step -->
                    <?php if(in_array($event->inputMode, [InputMode::SPEECH_TO_TEXT, InputMode::SCRIPTURE_INPUT])): ?>
                        <div class="section_step <?php echo $chapter["multiDraft"]["state"] ?>">
                            <div class="step_status"><?php echo __("step_status_" . $chapter["multiDraft"]["state"]) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/".EventSteps::CONTENT_REVIEW.".png") ?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::MULTI_DRAFT."_input_mode"); ?></div>
                        </div>
                    <?php endif; ?>
                    <!-- Consume Step -->
                    <?php if($event->inputMode == InputMode::NORMAL): ?>
                        <div class="section_step <?php echo $chapter["consume"]["state"] ?>">
                            <div class="step_status"><?php echo __("step_status_" . $chapter["consume"]["state"]) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/".EventSteps::CONSUME.".png") ?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME); ?></div>
                        </div>
                        <!-- Verbalize Step -->
                        <div class="section_step <?php echo $chapter["verb"]["state"] ?>">
                            <div class="step_status">
                                <?php echo __("step_status_" . $chapter["verb"]["state"]) ?>
                            </div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/".EventSteps::VERBALIZE.".png") ?>"></div>
                            <div class="step_name">2. <?php echo __(EventSteps::VERBALIZE); ?></div>
                            <?php if($chapter["verb"]["checkerID"] != "na"): ?>
                                <div class="step_checker">
                                    <div>
                                        <img width="50" src="<?php echo template_url("img/avatars/n1.png") ?>">
                                        <div><?php echo $data["members"][$chapter["verb"]["checkerID"]]["name"] ?></div>
                                    </div>
                                    <?php if($chapter["verb"]["state"] == StepsStates::CHECKED || $chapter["verb"]["state"] == StepsStates::FINISHED): ?>
                                        <span class="glyphicon glyphicon-ok checked"></span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <?php if($chapter["verb"]["state"] == StepsStates::WAITING): ?>
                                <img class="img_waiting" src="<?php echo template_url("img/waiting.png") ?>">
                            <?php endif; ?>
                        </div>
                        <!-- Chunking Step -->
                        <div class="section_step <?php echo $chapter["chunking"]["state"] ?>">
                            <div class="step_status"><?php echo __("step_status_" . $chapter["chunking"]["state"]) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/".EventSteps::CHUNKING.".png") ?>"></div>
                            <div class="step_name">3. <?php echo __(EventSteps::CHUNKING); ?></div>
                            <div class="step_chunks <?php echo sizeof($chapter["chunks"]) > 4 ? "more_chunks" : "" ?>">
                                <?php if(isset($chapter["chunks"])): ?>
                                    <?php foreach ($chapter["chunks"] as $index => $chunk):?>
                                        <div class="section_translator_chunk">
                                            <?php echo $chunk[0]." - ".$chunk[sizeof($chunk)-1]; ?>
                                            <?php if(array_key_exists($index, (array)$chapter["chunksData"])): ?>
                                                &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                    <?php if(sizeof($chapter["chunks"]) > 4): ?>
                                        <div class="chunks_blind glyphicon glyphicon-triangle-bottom"></div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <!-- Blind Draft Step -->
                        <div class="section_step <?php echo $chapter["blindDraft"]["state"] ?>">
                            <div class="step_status"><?php echo __("step_status_" . $chapter["blindDraft"]["state"]) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/".EventSteps::BLIND_DRAFT.".png") ?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::BLIND_DRAFT); ?></div>
                        </div>
                    <?php endif; ?>
                    <!-- Self Edit Step -->
                    <div class="section_step <?php echo $chapter["selfEdit"]["state"] ?>">
                        <div class="step_status"><?php echo __("step_status_" . $chapter["selfEdit"]["state"]) ?></div>
                        <div class="step_light"></div>
                        <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/".EventSteps::SELF_CHECK.".png") ?>"></div>
                        <div class="step_name"><?php echo $event->inputMode == InputMode::NORMAL ? "5" : "2" ?>. <?php echo __(EventSteps::SELF_CHECK); ?></div>
                    </div>
                    <!-- Peer Check Step -->
                    <?php if(in_array($event->inputMode, [InputMode::NORMAL, InputMode::SPEECH_TO_TEXT])): ?>
                        <div class="section_step <?php echo $chapter["peer"]["state"] ?>">
                            <div class="step_status">
                                <?php echo __("step_status_" . $chapter["peer"]["state"]) ?>
                            </div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/".EventSteps::PEER_REVIEW.".png") ?>"></div>
                            <div class="step_name"><?php echo $event->inputMode == InputMode::NORMAL ? "6" : "3" ?>. <?php echo __(EventSteps::PEER_REVIEW); ?></div>
                            <?php if($chapter["peer"]["checkerID"] != "na"): ?>
                                <div class="step_checker">
                                    <div>
                                        <img width="50" src="<?php echo template_url("img/avatars/n1.png") ?>">
                                        <div><?php echo $data["members"][$chapter["peer"]["checkerID"]]["name"] ?></div>
                                    </div>
                                    <?php if($chapter["peer"]["state"] == StepsStates::CHECKED || $chapter["peer"]["state"] == StepsStates::FINISHED): ?>
                                        <span class="glyphicon glyphicon-ok checked"></span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <?php if($chapter["peer"]["state"] == StepsStates::WAITING): ?>
                                <img class="img_waiting" src="<?php echo template_url("img/waiting.png") ?>">
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <!-- Keyword Check Step -->
                    <?php if($event->inputMode == InputMode::NORMAL): ?>
                        <div class="section_step <?php echo $chapter["kwc"]["state"] ?>">
                            <div class="step_status">
                                <?php echo __("step_status_" . $chapter["kwc"]["state"]) ?>
                            </div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/".EventSteps::KEYWORD_CHECK.".png") ?>"></div>
                            <div class="step_name">7. <?php echo __(EventSteps::KEYWORD_CHECK); ?></div>
                            <?php if($chapter["kwc"]["checkerID"] != "na"): ?>
                                <div class="step_checker">
                                    <div>
                                        <img width="50" src="<?php echo template_url("img/avatars/n1.png") ?>">
                                        <div><?php echo $data["members"][$chapter["kwc"]["checkerID"]]["name"] ?></div>
                                    </div>
                                    <?php if($chapter["kwc"]["state"] == StepsStates::CHECKED || $chapter["kwc"]["state"] == StepsStates::FINISHED): ?>
                                        <span class="glyphicon glyphicon-ok checked"></span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <?php if($chapter["kwc"]["state"] == StepsStates::WAITING): ?>
                                <img class="img_waiting" src="<?php echo template_url("img/waiting.png") ?>">
                            <?php endif; ?>
                        </div>
                        <!-- Verse-by-Verse Step -->
                        <div class="section_step <?php echo $chapter["crc"]["state"] ?>" style="width: 160px;">
                            <div class="step_status">
                                <?php echo __("step_status_" . $chapter["crc"]["state"]) ?>
                            </div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/".EventSteps::CONTENT_REVIEW.".png") ?>"></div>
                            <div class="step_name">8. <?php echo __(EventSteps::CONTENT_REVIEW); ?></div>
                            <div class="step_checker_container">
                                <?php if ($chapter["crc"]["state"] != StepsStates::WAITING): ?>
                                    <?php if($chapter["crc"]["checkerID"] != "na"): ?>
                                        <div class="step_checker">
                                            <div>
                                                <img width="50" src="<?php echo template_url("img/avatars/n1.png") ?>">
                                                <div><?php echo $data["members"][$chapter["crc"]["checkerID"]]["name"] ?></div>
                                            </div>
                                            <?php if($chapter["crc"]["state"] == StepsStates::CHECKED || $chapter["crc"]["state"] == StepsStates::FINISHED): ?>
                                                <span class="glyphicon glyphicon-ok checked"></span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if($chapter["crc"]["checkerID2"] != "na"): ?>
                                        <div class="step_checker">
                                            <div>
                                                <img width="50" src="<?php echo template_url("img/avatars/n1.png") ?>">
                                                <div><?php echo $data["members"][$chapter["crc"]["checkerID2"]]["name"] ?></div>
                                            </div>
                                            <?php if($chapter["crc"]["state2"] == StepsStates::CHECKED): ?>
                                                <span class="glyphicon glyphicon-ok checked"></span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            <?php if($chapter["crc"]["state"] == StepsStates::WAITING): ?>
                                <img class="img_waiting" src="<?php echo template_url("img/waiting.png") ?>">
                            <?php endif; ?>
                        </div>
                        <!-- Final Review Step -->
                        <div class="section_step finalReview <?php echo $chapter["finalReview"]["state"] ?>">
                            <div class="step_status"><?php echo __("step_status_" . $chapter["finalReview"]["state"]) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/".EventSteps::FINAL_REVIEW.".png") ?>"></div>
                            <div class="step_name"><?php echo __(EventSteps::FINAL_REVIEW); ?></div>
                        </div>
                    <?php endif; ?>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>