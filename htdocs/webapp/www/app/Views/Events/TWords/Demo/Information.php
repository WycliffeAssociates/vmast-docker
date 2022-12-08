<?php
use Helpers\Constants\EventSteps;
use Helpers\Constants\StepsStates;
?>

<div class="back_link">
    <?php if(isset($_SERVER["HTTP_REFERER"])): ?>
        <a href="<?php echo $_SERVER["HTTP_REFERER"] ?>"><?php echo __("go_back") ?></a>
    <?php endif; ?>
    <div class="demo_title"><?php echo __("demo") . " (".__("tq").")" ?></div>
</div>

<div>
    <div class="book_title">names</div>
    <div class="project_title"><?php echo __("tw") ?> - Русский</div>
    <div class="overall_progress_bar">
        <h3><?php echo __("progress_all") ?></h3>
        <div class="progress progress_all ">
            <div style="min-width: 0em; width: 75%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="75" role="progressbar" class="progress-bar progress-bar-success">
                75%
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
                        <?php echo __("group_id", [1]) ?>
                        <span class='glyphicon glyphicon-info-sign'
                              data-toggle='tooltip'
                              title='aaron...adam'
                              style='font-size: 16px;'></span>
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
                        <div class="section_translator_name">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png") ?>">
                            <span><b>Антон Ш.</b></span>
                        </div>
                        <div class="section_translator_name tnright">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png") ?>">
                            <span><b>Tanya E.</b></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="section_steps">
                        <!-- Multi Draft Step -->
                        <div class="section_step finished" style="width: 200px;">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/content-review.png") ?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::MULTI_DRAFT) ?></div>
                            <div class="step_chunks more_chunks">
                                <div class="section_translator_chunk">
                                    aaron &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span></div>
                                <div class="section_translator_chunk">
                                    abel &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span></div>
                                <div class="section_translator_chunk">
                                    abiathar &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span></div>
                                <div class="section_translator_chunk">
                                    abijah &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span></div>
                                <div class="section_translator_chunk">
                                    abimelech &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span></div>
                                <div class="section_translator_chunk">
                                    abner &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span></div>
                                <div class="section_translator_chunk">
                                    abraham &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span></div>
                                <div class="section_translator_chunk">
                                    absalom &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span></div>
                                <div class="section_translator_chunk">
                                    adam &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span></div>
                                <div class="chunks_blind glyphicon glyphicon-triangle-bottom"></div>
                            </div>
                        </div>
                        <!-- Self Edit Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png") ?>"></div>
                            <div class="step_name">2. <?php echo __(EventSteps::SELF_CHECK) ?></div>
                        </div>

                        <!-- Checking stage -->
                        <div class="section_step chk">
                        </div>

                        <!-- Keyword Check Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/keyword-check.png") ?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::KEYWORD_CHECK) ?></div>
                        </div>
                        <!-- Peer Check Step -->
                        <div class="section_step waiting">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::WAITING) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/peer-review.png") ?>"></div>
                            <div class="step_name">2. <?php echo __(EventSteps::PEER_REVIEW . "_tq") ?></div>
                            <div class="step_checker">
                            </div>
                            <img class="img_waiting" src="<?php echo template_url("img/waiting.png") ?>">
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
            <span class="member_uname">Антон Ш.</span>
            <span class="member_admin"> </span>
            <span class="online_status" style="display: inline;"><?php echo __("status_online") ?></span>
            <span class="offline_status" style="display: none;"><?php echo __("status_offline") ?></span>
        </div>
        <div data="7" class="member_item">
            <span class="online_indicator glyphicon glyphicon-record online">&nbsp;</span>
            <span class="member_uname">Tanya E.</span>
            <span class="member_admin"> </span>
            <span class="online_status" style="display: inline;"><?php echo __("status_online") ?></span>
            <span class="offline_status" style="display: none;"><?php echo __("status_offline") ?></span>
        </div>
        <div data="17" class="member_item">
            <span class="online_indicator glyphicon glyphicon-record">&nbsp;</span>
            <span class="member_uname">Михаил Б.</span>
            <span class="member_admin"> (<?php echo __("facilitator"); ?>)</span>
            <span class="online_status" style="display: none;"><?php echo __("status_online") ?></span>
            <span class="offline_status"><?php echo __("status_offline") ?></span>
        </div>
        <div data="17" class="member_item">
            <span class="online_indicator glyphicon glyphicon-record online">&nbsp;</span>
            <span class="member_uname">Mark P.</span>
            <span class="member_admin"> (<?php echo __("facilitator"); ?>)</span>
            <span class="online_status" style="display: inline;"><?php echo __("status_online") ?></span>
            <span class="offline_status" style="display: none;"><?php echo __("status_offline") ?></span>
        </div>
        <div data="7" class="member_item">
            <span class="online_indicator glyphicon glyphicon-record online">&nbsp;</span>
            <span class="member_uname">Irina T.</span>
            <span class="member_admin"> </span>
            <span class="online_status" style="display: inline;"><?php echo __("status_online") ?></span>
            <span class="offline_status" style="display: none;"><?php echo __("status_offline") ?></span>
        </div>
        <div data="17" class="member_item">
            <span class="online_indicator glyphicon glyphicon-record">&nbsp;</span>
            <span class="member_uname">Aleksandr D.</span>
            <span class="member_admin"></span>
            <span class="online_status" style="display: none;"><?php echo __("status_online") ?></span>
            <span class="offline_status"><?php echo __("status_offline") ?></span>
        </div>
    </div>
</div>