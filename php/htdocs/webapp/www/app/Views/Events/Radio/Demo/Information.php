<?php
use Helpers\Constants\EventSteps;
use Helpers\Constants\StepsStates;
?>

<div class="back_link">
    <?php if(isset($_SERVER["HTTP_REFERER"])): ?>
        <a href="<?php echo $_SERVER["HTTP_REFERER"] ?>"><?php echo __("go_back") ?></a>
    <?php endif; ?>
    <div class="demo_title"><?php echo __("demo") . " (".__("rad").")" ?></div>
</div>

<div>
    <div class="book_title">Radio 2</div>
    <div class="project_title"><?php echo __("rad") ?> - Español Latin America</div>
    <div class="overall_progress_bar">
        <h3><?php echo __("progress_all") ?></h3>
        <div class="progress progress_all ">
            <div style="min-width: 0em; width: 25%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="25" role="progressbar" class="progress-bar progress-bar-success">
                25%
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
                    <div class="chapter_number section_title">
                        <?php echo __("chapter_number", ["chapter" => 1]) ?>
                        <span class="glyphicon glyphicon-info-sign"
                              data-toggle="tooltip"
                              title="Broadcast Introduction"
                              style="font-size: 16px;"></span>
                    </div>
                    <div class="section_translator_progress_bar">
                        <div class="progress ">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="min-width: 0em; width: 75%">75%</div>
                        </div>
                        <div class=" finished_icon"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="section_content">
                    <div class="section_translator">
                        <div class="section_translator_name tnleft">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png") ?>">
                            <span><b>Mark P.</b></span>
                        </div>
                        <div class="section_translator_name tnright">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png") ?>">
                            <span><b>Henry S.</b></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="section_steps">
                        <!-- Consume Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/".EventSteps::CONSUME.".png") ?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_odb"); ?></div>
                        </div>
                        <!-- Draft Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/".EventSteps::CONTENT_REVIEW.".png") ?>"></div>
                            <div class="step_name">2. <?php echo __(EventSteps::MULTI_DRAFT); ?></div>
                        </div>
                        <!-- Self Edit Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png") ?>"></div>
                            <div class="step_name">3. <?php echo __(EventSteps::SELF_CHECK) ?></div>
                        </div>

                        <!-- Checking stage -->
                        <div class="section_step chk">
                        </div>

                        <!-- Peer Check Step -->
                        <div class="section_step waiting">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::WAITING) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/peer-review.png") ?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::PEER_REVIEW) ?></div>
                            <div class="step_checker">
                            </div>
                            <img class="img_waiting" src="<?php echo template_url("img/waiting.png") ?>">
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="chapter_item">
            <div class="chapter_number nofloat">
                <?php echo __("chapter_number", ["chapter" => 2]) ?>
                <span class="glyphicon glyphicon-info-sign"
                      data-toggle="tooltip"
                      title="The Formula"
                      style="font-size: 16px;"></span>
            </div>
        </div>
        <div class="chapter_item">
            <div class="chapter_number nofloat">
                <?php echo __("chapter_number", ["chapter" => 3]) ?>
                <span class="glyphicon glyphicon-info-sign"
                      data-toggle="tooltip"
                      title="MAST Drafting"
                      style="font-size: 16px;"></span>
            </div>
        </div>
    </div>

    <div class="members_list">
        <div class="members_title"><?php echo __("event_participants") ?>:</div>
        <div data="16" class="member_item">
            <span class="online_indicator glyphicon glyphicon-record online">&nbsp;</span>
            <span class="member_uname">Mark P.</span>
            <span class="member_admin"> </span>
            <span class="online_status" style="display: inline;"><?php echo __("status_online") ?></span>
            <span class="offline_status" style="display: none;"><?php echo __("status_offline") ?></span>
        </div>
        <div data="7" class="member_item">
            <span class="online_indicator glyphicon glyphicon-record online">&nbsp;</span>
            <span class="member_uname">Henry S.</span>
            <span class="member_admin"> </span>
            <span class="online_status" style="display: inline;"><?php echo __("status_online") ?></span>
            <span class="offline_status" style="display: none;"><?php echo __("status_offline") ?></span>
        </div>
        <div data="17" class="member_item">
            <span class="online_indicator glyphicon glyphicon-record online">&nbsp;</span>
            <span class="member_uname">Max A.</span>
            <span class="member_admin"> (<?php echo __("facilitator"); ?>)</span>
            <span class="online_status" style="display: inline;"><?php echo __("status_online") ?></span>
            <span class="offline_status" style="display: none;"><?php echo __("status_offline") ?></span>
        </div>
        <div data="17" class="member_item">
            <span class="online_indicator glyphicon glyphicon-record">&nbsp;</span>
            <span class="member_uname">Antonio D.</span>
            <span class="member_admin"></span>
            <span class="online_status" style="display: none;"><?php echo __("status_online") ?></span>
            <span class="offline_status"><?php echo __("status_offline") ?></span>
        </div>
    </div>
</div>