<?php
use Helpers\Constants\EventCheckSteps;
use Helpers\Constants\StepsStates;
?>

<div class="back_link">
    <?php if(isset($_SERVER["HTTP_REFERER"])): ?>
        <a href="<?php echo $_SERVER["HTTP_REFERER"] ?>"><?php echo __("go_back") ?></a>
    <?php endif; ?>
    <div class="demo_title"><?php echo __("demo") . " (".__("revision_events").")" ?></div>
</div>

<div>
    <div class="book_title">2 Timothy</div>
    <div class="project_title"><?php echo __("ulb") ?> - Papuan Malay</div>
    <div class="overall_progress_bar">
        <h3><?php echo __("progress_all") ?></h3>
        <div class="progress progress_all ">
            <div style="min-width: 0em; width: 55%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="55" role="progressbar" class="progress-bar progress-bar-success">
                55%
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
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="min-width: 0em; width: 80%">80%</div>
                        </div>
                        <div class=" finished_icon"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="section_content">
                    <div class="section_translator">
                        <div class="section_translator_name tnleft"> <img src="<?php echo template_url("img/avatars/n1.png")?>" width="50"> <span><b>Christine B.</b></span> </div>
                        <div class="clear"></div>
                    </div>
                    <div class="section_steps">
                        <!-- Consume Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/consume.png")?>" width="40"></div>
                            <div class="step_name">1. <?php echo __(EventCheckSteps::CONSUME); ?></div>
                        </div>
                        <!-- Self Check Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/self-check.png")?>" width="40"></div>
                            <div class="step_name">2. <?php echo __(EventCheckSteps::SELF_CHECK); ?></div>
                        </div>
                        <!-- Peer Check Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/peer-review.png")?>" width="40"></div>
                            <div class="step_name">3. <?php echo __(EventCheckSteps::PEER_REVIEW); ?></div>
                            <div class="step_checker">
                                <img src="<?php echo template_url("img/avatars/n1.png")?>" width="50">
                                <div>Genry M.</div>
                            </div>
                        </div>
                        <!-- Keywords Check Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/keyword-check.png")?>" width="40"></div>
                            <div class="step_name">4. <?php echo __(EventCheckSteps::KEYWORD_CHECK); ?></div>
                            <div class="step_checker">
                                <img src="<?php echo template_url("img/avatars/n1.png")?>" width="50">
                                <div>Paul G.</div>
                            </div>
                        </div>
                        <!-- VbV Check Step -->
                        <div class="section_step chk in_progress">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::IN_PROGRESS) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/content-review.png")?>" width="40"></div>
                            <div class="step_name">5. <?php echo __(EventCheckSteps::CONTENT_REVIEW); ?></div>
                            <div class="step_checker">
                                <img src="<?php echo template_url("img/avatars/n1.png")?>" width="50">
                                <div>Dana S.</div>
                            </div>
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
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="min-width: 0em; width: 40%">40%</div>
                        </div>
                        <div class=" finished_icon"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="section_content">
                    <div class="section_translator">
                        <div class="section_translator_name tnleft"> <img src="<?php echo template_url("img/avatars/n1.png")?>" width="50"> <span><b>Genry M.</b></span> </div>
                        <div class="section_translator_name tnleft" style="margin-left: 170px"> </div>
                        <div class="section_translator_name tnleft" style="margin-left: 180px"> </div>
                        <div class="clear"></div>
                    </div>
                    <div class="section_steps">
                        <!-- Consume Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/consume.png")?>" width="40"></div>
                            <div class="step_name">1. <?php echo __(EventCheckSteps::CONSUME); ?></div>
                        </div>
                        <!-- Self Check Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/self-check.png")?>" width="40"></div>
                            <div class="step_name">2. <?php echo __(EventCheckSteps::SELF_CHECK); ?></div>
                        </div>
                        <!-- Peer Check Step -->
                        <div class="section_step chk waiting">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::WAITING) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/peer-review.png")?>" width="40"></div>
                            <div class="step_name">3. <?php echo __(EventCheckSteps::PEER_REVIEW); ?></div>
                            <img class="img_waiting" src="<?php echo template_url("img/waiting.png")?>">
                        </div>
                        <!-- Keywords Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/keyword-check.png")?>" width="40"></div>
                            <div class="step_name">4. <?php echo __(EventCheckSteps::KEYWORD_CHECK); ?></div>
                        </div>
                        <!-- VbV Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/content-review.png")?>" width="40"></div>
                            <div class="step_name">5. <?php echo __(EventCheckSteps::CONTENT_REVIEW); ?></div>
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
                        <div class="progress">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="min-width: 0em; width: 100%">100%</div>
                        </div>
                        <div class=" finished_icon"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="section_content">
                    <div class="section_translator">
                        <div class="section_translator_name tnleft"> <img src="<?php echo template_url("img/avatars/n1.png")?>" width="50"> <span><b>Paul G.</b></span> </div>
                        <div class="clear"></div>
                    </div>
                    <div class="section_steps">
                        <!-- Consume Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/consume.png")?>" width="40"></div>
                            <div class="step_name">1. <?php echo __(EventCheckSteps::CONSUME); ?></div>
                        </div>
                        <!-- Self Check Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/self-check.png")?>" width="40"></div>
                            <div class="step_name">2. <?php echo __(EventCheckSteps::SELF_CHECK); ?></div>
                        </div>
                        <!-- Peer Check Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/peer-review.png")?>" width="40"></div>
                            <div class="step_name">3. <?php echo __(EventCheckSteps::PEER_REVIEW); ?></div>
                            <div class="step_checker">
                                <img src="<?php echo template_url("img/avatars/n1.png")?>" width="50">
                                <div>Christine B.</div>
                                <span class="glyphicon glyphicon-ok checked"></span>
                            </div>
                        </div>
                        <!-- Keywords Check Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/keyword-check.png")?>" width="40"></div>
                            <div class="step_name">4. <?php echo __(EventCheckSteps::KEYWORD_CHECK); ?></div>
                            <div class="step_checker">
                                <img src="<?php echo template_url("img/avatars/n1.png")?>" width="50">
                                <div>Genry M.</div>
                                <span class="glyphicon glyphicon-ok checked"></span>
                            </div>
                        </div>
                        <!-- VbV Check Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/content-review.png")?>" width="40"></div>
                            <div class="step_name">5. <?php echo __(EventCheckSteps::CONTENT_REVIEW); ?></div>
                            <div class="step_checker">
                                <img src="<?php echo template_url("img/avatars/n1.png")?>" width="50">
                                <div>Dana S.</div>
                                <span class="glyphicon glyphicon-ok checked"></span>
                            </div>
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
                        <div class="section_translator_name tnleft">
                            <img src="<?php echo template_url("img/avatars/n1.png")?>" width="50">
                            <span><b>Laura C.</b></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="section_steps">
                        <!-- Consume Step -->
                        <div class="section_step not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/consume.png")?>" width="40"></div>
                            <div class="step_name">1. <?php echo __(EventCheckSteps::CONSUME); ?></div>
                        </div>
                        <!-- Self Check Step -->
                        <div class="section_step not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/self-check.png")?>" width="40"></div>
                            <div class="step_name">2. <?php echo __(EventCheckSteps::SELF_CHECK); ?></div>
                        </div>
                        <!-- Peer Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/peer-review.png")?>" width="40"></div>
                            <div class="step_name">3. <?php echo __(EventCheckSteps::PEER_REVIEW); ?></div>
                        </div>
                        <!-- Keywords Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/keyword-check.png")?>" width="40"></div>
                            <div class="step_name">4. <?php echo __(EventCheckSteps::KEYWORD_CHECK); ?></div>
                        </div>
                        <!-- VbV Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img src="<?php echo template_url("img/steps/icons/content-review.png")?>" width="40"></div>
                            <div class="step_name">5. <?php echo __(EventCheckSteps::CONTENT_REVIEW); ?></div>
                        </div>
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
            <span class="member_uname">Genry M.</span>
            <span class="member_admin"> </span>
            <span class="online_status" style="display: inline;"><?php echo __("status_online") ?></span>
            <span class="offline_status" style="display: none;"><?php echo __("status_offline") ?></span>
        </div>
        <div data="17" class="member_item">
            <span class="online_indicator glyphicon glyphicon-record">&nbsp;</span>
            <span class="member_uname">Paul G.</span>
            <span class="member_admin"> (<?php echo __("facilitator"); ?>)</span>
            <span class="online_status" style="display: none;"><?php echo __("status_online") ?></span>
            <span class="offline_status"><?php echo __("status_offline") ?></span>
        </div>
        <div data="21" class="member_item">
            <span class="online_indicator glyphicon glyphicon-record">&nbsp;</span>
            <span class="member_uname">Laura C.</span>
            <span class="member_admin"> </span>
            <span class="online_status" style="display: none;"><?php echo __("status_online") ?></span>
            <span class="offline_status"><?php echo __("status_offline") ?></span>
        </div>
    </div>
</div>