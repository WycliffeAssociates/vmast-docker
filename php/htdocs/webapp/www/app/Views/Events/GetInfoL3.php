<?php
use Helpers\Constants\EventSteps;
use Helpers\Constants\EventCheckSteps;
use Helpers\Constants\StepsStates;

foreach ($data["chapters"] as $key => $chapter):?>
    <?php if(empty($chapter["l3chID"])): ?>
        <div class="chapter_item">
            <div class="chapter_number nofloat">
                <?php echo ($key > 0
                    ? __("chapter_number", ["chapter" => $key])
                    : __("intro")) ?>
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
                        <span><b><?php echo $data["members"][$chapter["l3memberID"]]["name"] ?? "" ?></b></span>
                    </div>
                </div>
                <div class="section_steps">
                    <!-- Peer Review Step -->
                    <div class="section_step <?php echo $chapter["peerReview"]["state"] ?>">
                        <div class="step_status">
                            <?php echo __("step_status_" . $chapter["peerReview"]["state"]) ?>
                        </div>
                        <div class="step_light"></div>
                        <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/".EventSteps::PEER_REVIEW.".png") ?>"></div>
                        <div class="step_name">1. <?php echo __(EventCheckSteps::PEER_REVIEW_L3); ?></div>
                        <?php if($chapter["peerChk"]["checkerID"] != "na"): ?>
                            <div class="step_checker">
                                <img width="50" src="<?php echo template_url("img/avatars/n1.png") ?>">
                                <div><?php echo $data["members"][$chapter["peerChk"]["checkerID"]]["name"] ?? "" ?></div>
                                <?php if($chapter["peerReview"]["state"] == StepsStates::CHECKED || $chapter["peerReview"]["state"] == StepsStates::FINISHED): ?>
                                    <span class="glyphicon glyphicon-ok checked"></span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <?php if($chapter["peerReview"]["state"] == StepsStates::WAITING): ?>
                            <img class="img_waiting" src="<?php echo template_url("img/waiting.png") ?>">
                        <?php endif; ?>
                    </div>
                    <!-- Peer Edit Step -->
                    <div class="section_step <?php echo $chapter["peerEdit"]["state"] ?>">
                        <div class="step_status">
                            <?php echo __("step_status_" . $chapter["peerEdit"]["state"]) ?>
                        </div>
                        <div class="step_light"></div>
                        <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/".EventSteps::PEER_REVIEW.".png") ?>"></div>
                        <div class="step_name">2. <?php echo __(EventCheckSteps::PEER_EDIT_L3); ?></div>
                        <?php if($chapter["peerChk"]["checkerID"] != "na"): ?>
                            <div class="step_checker">
                                <img width="50" src="<?php echo template_url("img/avatars/n1.png") ?>">
                                <div><?php echo $data["members"][$chapter["peerChk"]["checkerID"]]["name"] ?? "" ?></div>
                                <?php if($chapter["peerEdit"]["state"] == StepsStates::CHECKED || $chapter["peerEdit"]["state"] == StepsStates::FINISHED): ?>
                                    <span class="glyphicon glyphicon-ok checked"></span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <?php if($chapter["peerEdit"]["state"] == StepsStates::WAITING): ?>
                            <img class="img_waiting" src="<?php echo template_url("img/waiting.png") ?>">
                        <?php endif; ?>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>