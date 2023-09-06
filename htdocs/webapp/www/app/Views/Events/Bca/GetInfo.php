<?php
use Helpers\Constants\EventSteps;
use Helpers\Constants\StepsStates;

foreach ($event->words as $word):?>
    <?php
    $key = $word->wordID;
    $chapter = $data["chapters"][$key] ?? [];
    ?>
    <?php if(empty($chapter)): ?>
        <div class="chapter_item">
            <div class="chapter_number nofloat">
                <?php echo $word->word ?>
            </div>
        </div>
        <?php continue; endif; ?>
    <div class="chapter_item">
        <div class="chapter_accordion">
            <div class="section_header" data="<?php echo "sec_".$key?>">
                <div class="section_arrow glyphicon glyphicon-triangle-right"></div>
                <div class="chapter_number section_title"><?php echo $word->word ?></div>
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
                        <span class="datetime" data="<?php echo date(DATE_RFC2822, strtotime($chapter["lastEdit"])) ?>">
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
                        <span><b><?php echo $data["members"][$chapter["memberID"]]["name"] ?></b></span>
                    </div>
                    <?php if(isset($chapter["checkerID"])): ?>
                        <div class="section_translator_name bcright">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png") ?>">
                            <span><b><?php echo $data["members"][$chapter["checkerID"]]["name"] ?></b></span>
                        </div>
                    <?php endif; ?>
                    <div class="clear"></div>
                </div>
                <div class="section_steps">
                    <!-- Multi Draft Step -->
                    <div class="section_step <?php echo $chapter["multiDraft"]["state"] ?>">
                        <div class="step_status"><?php echo __("step_status_" . $chapter["multiDraft"]["state"]) ?></div>
                        <div class="step_light"></div>
                        <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/".EventSteps::CONTENT_REVIEW.".png") ?>"></div>
                        <div class="step_name">1. <?php echo __(EventSteps::MULTI_DRAFT); ?></div>
                        <div class="step_chunks <?php echo sizeof($chapter["chunks"]) > 4 ? "more_chunks" : "" ?>">
                            <?php if(isset($chapter["chunks"])): ?>
                                <?php foreach ($chapter["chunks"] as $index => $chunk):?>
                                    <div class="section_translator_chunk">
                                        <?php echo __("chunk_number", ["chunk_number" => $chunk[0]+1]); ?>
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
                    <!-- Self Edit Step -->
                    <div class="section_step <?php echo $chapter["selfEdit"]["state"] ?>">
                        <div class="step_status"><?php echo __("step_status_" . $chapter["selfEdit"]["state"]) ?></div>
                        <div class="step_light"></div>
                        <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/".EventSteps::SELF_CHECK.".png") ?>"></div>
                        <div class="step_name">2. <?php echo __(EventSteps::SELF_CHECK); ?></div>
                    </div>

                    <!-- Checking stage -->
                    <div class="section_step chk">
                    </div>

                    <!-- Keyword Check Step -->
                    <div class="section_step chk <?php echo $chapter["kwc"]["state"] ?>">
                        <div class="step_status"><?php echo __("step_status_" . $chapter["kwc"]["state"]) ?></div>
                        <div class="step_light"></div>
                        <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/".EventSteps::KEYWORD_CHECK.".png") ?>"></div>
                        <div class="step_name">1. <?php echo __(EventSteps::KEYWORD_CHECK); ?></div>
                    </div>
                    <!-- Peer Check Step -->
                    <div class="section_step chk <?php echo $chapter["peerChk"]["state"] ?>">
                        <div class="step_status">
                            <?php echo __("step_status_" . $chapter["peerChk"]["state"]) ?>
                        </div>
                        <div class="step_light"></div>
                        <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/".EventSteps::PEER_REVIEW.".png") ?>"></div>
                        <div class="step_name">2. <?php echo __(EventSteps::PEER_REVIEW."_bca"); ?></div>
                        <?php if($chapter["peerChk"]["checkerID"] != "na"): ?>
                            <div class="step_checker">
                                <div>
                                    <img width="50" src="<?php echo template_url("img/avatars/n1.png") ?>">
                                    <div><?php echo $data["members"][$chapter["peerChk"]["checkerID"]]["name"] ?></div>
                                </div>
                                <?php if($chapter["peerChk"]["state"] == StepsStates::CHECKED || $chapter["peerChk"]["state"] == StepsStates::FINISHED): ?>
                                    <span class="glyphicon glyphicon-ok checked"></span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <?php if($chapter["peerChk"]["state"] == StepsStates::WAITING): ?>
                            <img class="img_waiting" src="<?php echo template_url("img/waiting.png") ?>">
                        <?php endif; ?>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>