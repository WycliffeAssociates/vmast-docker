<?php
use Helpers\Constants\EventSteps;
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
                    <div class="section_translator_name tnleft sun">
                        <img width="50" src="<?php echo template_url("img/avatars/n1.png") ?>">
                        <span><b><?php echo $data["members"][$chapter["memberID"]]["name"] ?? "" ?></b></span>
                    </div>
                    <?php if(isset($chapter["theoChk"]["checkerID"]) && $chapter["theoChk"]["checkerID"] != "na"): ?>
                        <div class="section_translator_name tnleft sun" style="margin-left: 520px">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png") ?>">
                            <span><b><?php echo $data["members"][$chapter["theoChk"]["checkerID"]]["name"] ?? "" ?></b></span>
                        </div>
                    <?php endif; ?>
                    <?php if(isset($chapter["crc"]["checkerID"]) && $chapter["crc"]["checkerID"] != "na"): ?>
                        <div class="section_translator_name tnleft sun" style="margin-left: 30px">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png") ?>">
                            <span><b><?php echo $data["members"][$chapter["crc"]["checkerID"]]["name"] ?? "" ?></b></span>
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
                        <div class="step_name">1. <?php echo __(EventSteps::CONSUME); ?></div>
                    </div>
                    <!-- Chunking Check Step -->
                    <div class="section_step <?php echo $chapter["chunking"]["state"] ?>">
                        <div class="step_status"><?php echo __("step_status_" . $chapter["chunking"]["state"]) ?></div>
                        <div class="step_light"></div>
                        <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/".EventSteps::CHUNKING.".png") ?>"></div>
                        <div class="step_name">2. <?php echo __(EventSteps::CHUNKING); ?></div>
                        <div class="step_chunks <?php echo sizeof($chapter["chunks"]) > 4 ? "more_chunks" : "" ?>">
                            <?php if(isset($chapter["chunks"])): ?>
                                <?php foreach ($chapter["chunks"] as $index => $chunk):?>
                                    <div class="section_translator_chunk">
                                        <?php echo $chunk[0] > 0 ? $chunk[0]." - ".$chunk[sizeof($chunk)-1] : __("book_title"); ?>
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
                    <!-- Rearrange Step -->
                    <div class="section_step <?php echo $chapter["rearrange"]["state"] ?>">
                        <div class="step_status"><?php echo __("step_status_" . $chapter["rearrange"]["state"]) ?></div>
                        <div class="step_light"></div>
                        <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/".EventSteps::REARRANGE.".png") ?>"></div>
                        <div class="step_name">3. <?php echo __(EventSteps::REARRANGE); ?></div>
                    </div>
                    <!-- Symbol Draft Step -->
                    <div class="section_step <?php echo $chapter["symbolDraft"]["state"] ?>">
                        <div class="step_status"><?php echo __("step_status_" . $chapter["symbolDraft"]["state"]) ?></div>
                        <div class="step_light"></div>
                        <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/".EventSteps::SYMBOL_DRAFT.".png") ?>"></div>
                        <div class="step_name">4. <?php echo __(EventSteps::SYMBOL_DRAFT); ?></div>
                    </div>
                    <!-- Self Check Step -->
                    <div class="section_step <?php echo $chapter["selfEdit"]["state"] ?>">
                        <div class="step_status"><?php echo __("step_status_" . $chapter["selfEdit"]["state"]) ?></div>
                        <div class="step_light"></div>
                        <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/".EventSteps::SELF_CHECK.".png") ?>"></div>
                        <div class="step_name">5. <?php echo __(EventSteps::SELF_CHECK); ?></div>
                    </div>

                    <!-- Checking stage -->
                    <div class="section_step chk" style="width: 50px">
                    </div>

                    <!-- Theo Check Step -->
                    <div class="section_step chk <?php echo $chapter["theoChk"]["state"] ?>">
                        <div class="step_status"><?php echo __("step_status_" . $chapter["theoChk"]["state"]) ?></div>
                        <div class="step_light"></div>
                        <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/".EventSteps::KEYWORD_CHECK.".png") ?>"></div>
                        <div class="step_name">6. <?php echo __(EventSteps::THEO_CHECK); ?></div>
                        <?php if($chapter["theoChk"]["state"] == StepsStates::WAITING): ?>
                            <img class="img_waiting" src="<?php echo template_url("img/waiting.png") ?>">
                        <?php endif; ?>
                    </div>

                    <!-- Checking stage -->
                    <div class="section_step chk" style="width: 50px">
                    </div>

                    <!-- Verse-by-verse Check Step -->
                    <div class="section_step chk <?php echo $chapter["crc"]["state"] ?>">
                        <div class="step_status"><?php echo __("step_status_" . $chapter["crc"]["state"]) ?></div>
                        <div class="step_light"></div>
                        <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/".EventSteps::CONTENT_REVIEW.".png") ?>"></div>
                        <div class="step_name">7. <?php echo __(EventSteps::CONTENT_REVIEW); ?></div>
                        <?php if($chapter["crc"]["state"] == StepsStates::WAITING): ?>
                            <img class="img_waiting" src="<?php echo template_url("img/waiting.png") ?>">
                        <?php endif; ?>
                    </div>
                    <!-- Verse Markers Step -->
                    <div class="section_step chk <?php echo $chapter["finalReview"]["state"] ?>">
                        <div class="step_status"><?php echo __("step_status_" . $chapter["finalReview"]["state"]) ?></div>
                        <div class="step_light"></div>
                        <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/".EventSteps::FINAL_REVIEW.".png") ?>"></div>
                        <div class="step_name"><?php echo __(EventSteps::FINAL_REVIEW); ?></div>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>