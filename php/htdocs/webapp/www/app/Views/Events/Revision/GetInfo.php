<?php
use Helpers\Constants\EventCheckSteps;
use Helpers\Constants\RevisionMode;
use Helpers\Constants\StepsStates;

$isMajorMode = $data["revisionMode"] == RevisionMode::MAJOR;

foreach ($data["chapters"] as $key => $chapter):?>
    <?php if(empty($chapter) || $chapter["l2memberID"] == 0): ?>
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
                    <div class="section_translator_name tnleft">
                        <img width="50" src="<?php echo template_url("img/avatars/n1.png") ?>">
                        <span><b><?php echo $data["members"][$chapter["l2memberID"]]["name"] ?></b></span>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="section_steps">
                    <!-- Consume Step -->
                    <div class="section_step <?php echo $chapter["consume"]["state"] ?>">
                        <div class="step_status"><?php echo __("step_status_" . $chapter["consume"]["state"]) ?></div>
                        <div class="step_light"></div>
                        <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/".EventCheckSteps::CONSUME.".png") ?>"></div>
                        <div class="step_name">1. <?php echo __(EventCheckSteps::CONSUME); ?></div>
                    </div>
                    <!-- Self Check Step -->
                    <div class="section_step <?php echo $chapter["selfEdit"]["state"] ?>">
                        <div class="step_status"><?php echo __("step_status_" . $chapter["selfEdit"]["state"]) ?></div>
                        <div class="step_light"></div>
                        <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/".EventCheckSteps::SELF_CHECK.".png") ?>"></div>
                        <div class="step_name">2. <?php echo __(($isMajorMode ? EventCheckSteps::SELF_CHECK : EventCheckSteps::PEER_REVIEW)); ?></div>
                    </div>

                    <!-- Peer Check Step -->
                    <?php if ($isMajorMode): ?>
                    <div class="section_step chk <?php echo $chapter["peer"]["state"] ?>">
                        <div class="step_status"><?php echo __("step_status_" . $chapter["peer"]["state"]) ?></div>
                        <div class="step_light"></div>
                        <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/".EventCheckSteps::PEER_REVIEW.".png") ?>"></div>
                        <div class="step_name">3. <?php echo __(EventCheckSteps::PEER_REVIEW); ?></div>
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
                    <!-- Keywords Check Step -->
                    <div class="section_step chk <?php echo $chapter["kwc"]["state"] ?>">
                        <div class="step_status"><?php echo __("step_status_" . $chapter["kwc"]["state"]) ?></div>
                        <div class="step_light"></div>
                        <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/".EventCheckSteps::KEYWORD_CHECK.".png") ?>"></div>
                        <div class="step_name">4. <?php echo __(EventCheckSteps::KEYWORD_CHECK); ?></div>
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

                    <!-- VbV Check Step -->
                    <div class="section_step chk <?php echo $chapter["crc"]["state"] ?>">
                        <div class="step_status">
                            <?php echo __("step_status_" . $chapter["crc"]["state"]) ?>
                        </div>
                        <div class="step_light"></div>
                        <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/".EventCheckSteps::CONTENT_REVIEW.".png") ?>"></div>
                        <div class="step_name">5. <?php echo __(EventCheckSteps::CONTENT_REVIEW); ?></div>
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
                        <?php if($chapter["crc"]["state"] == StepsStates::WAITING): ?>
                            <img class="img_waiting" src="<?php echo template_url("img/waiting.png") ?>">
                        <?php endif; ?>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>