<?php
use Helpers\Constants\EventSteps;
use Helpers\Constants\OdbSections;
use Helpers\Constants\StepsStates;

foreach ($data["chapters"] as $key => $chapter):?>
    <?php if(empty($chapter)): ?>
        <div class="chapter_item">
            <div class="chapter_number nofloat">
                <?php echo __("chapter_number", ["chapter" => $key]) ?>
                <span class="glyphicon glyphicon-info-sign"
                      data-toggle="tooltip"
                      title="<?php echo isset($data["rad"]["chapters"][$key]) ? $data["rad"]["chapters"][$key][1] . ": " . $data["rad"]["chapters"][$key][2] : "" ?>"
                      style="font-size: 16px;"></span>
            </div>
        </div>
    <?php continue; endif; ?>
    <div class="chapter_item">
        <div class="chapter_accordion">
            <div class="section_header" data="<?php echo "sec_".$key?>">
                <div class="section_arrow glyphicon glyphicon-triangle-right"></div>
                <div class="chapter_number section_title">
                    <?php echo __("chapter_number", ["chapter" => $key]) ?>
                    <span class="glyphicon glyphicon-info-sign"
                          data-toggle="tooltip"
                          title="<?php echo isset($data["rad"]["chapters"][$key]) ? $data["rad"]["chapters"][$key][1] . ": " . $data["rad"]["chapters"][$key][2] : "" ?>"
                          style="font-size: 16px;"></span>
                </div>
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
                        <span><b><?php echo $data["members"][$chapter["memberID"]]["name"] ?? "" ?></b></span>
                    </div>
                    <?php if(isset($chapter["checkerID"])): ?>
                        <div class="section_translator_name tnright">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png") ?>">
                            <span><b><?php echo $data["members"][$chapter["checkerID"]]["name"] ?? "" ?></b></span>
                        </div>
                    <?php endif; ?>
                    <div class="clear"></div>
                </div>
                <div class="section_steps">
                    <!-- Consume Step -->
                    <div class="section_step <?php echo $chapter["consume"]["state"] ?>">
                        <div class="step_status"><?php echo __("step_status_" . $chapter["consume"]["state"]) ?></div>
                        <div class="step_light"></div>
                        <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/".EventSteps::CONSUME.".png") ?>"></div>
                        <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_odb"); ?></div>
                    </div>
                    <!-- Draft Step -->
                    <div class="section_step <?php echo $chapter["multiDraft"]["state"] ?>">
                        <div class="step_status"><?php echo __("step_status_" . $chapter["multiDraft"]["state"]) ?></div>
                        <div class="step_light"></div>
                        <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/".EventSteps::CONTENT_REVIEW.".png") ?>"></div>
                        <div class="step_name">2. <?php echo __(EventSteps::MULTI_DRAFT); ?></div>
                    </div>
                    <!-- Self Check Step -->
                    <div class="section_step <?php echo $chapter["selfEdit"]["state"] ?>">
                        <div class="step_status"><?php echo __("step_status_" . $chapter["selfEdit"]["state"]) ?></div>
                        <div class="step_light"></div>
                        <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/".EventSteps::SELF_CHECK.".png") ?>"></div>
                        <div class="step_name">3. <?php echo __(EventSteps::SELF_CHECK); ?></div>
                    </div>

                    <!-- Checking stage -->
                    <div class="section_step chk">
                    </div>

                    <!-- Peer Check Step -->
                    <div class="section_step chk <?php echo $chapter["peerChk"]["state"] ?>">
                        <div class="step_status">
                            <?php echo __("step_status_" . $chapter["peerChk"]["state"]) ?>
                        </div>
                        <div class="step_light"></div>
                        <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/".EventSteps::PEER_REVIEW.".png") ?>"></div>
                        <div class="step_name">4. <?php echo __(EventSteps::PEER_REVIEW); ?></div>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>