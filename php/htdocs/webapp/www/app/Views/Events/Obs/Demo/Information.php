<?php
use Helpers\Constants\EventSteps;
use Helpers\Constants\StepsStates;
?>

<div class="back_link">
    <?php if(isset($_SERVER["HTTP_REFERER"])): ?>
        <a href="<?php echo $_SERVER["HTTP_REFERER"] ?>"><?php echo __("go_back") ?></a>
    <?php endif; ?>
    <div class="demo_title"><?php echo __("demo") . " (".__("obs").")" ?></div>
</div>

<div>
    <div class="book_title">Open Bible Stories</div>
    <div class="project_title"><?php echo __("obs") ?> - <?php echo $data["targetLangName"] ?></div>
    <div class="overall_progress_bar">
        <h3><?php echo __("progress_all") ?></h3>
        <div class="progress progress_all ">
            <div style="min-width: 0em; width: 8%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="8" role="progressbar" class="progress-bar progress-bar-success">
                8%
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>

<div class="row" style="position:relative;">
    <div class="chapter_list">
        <div class="chapter_item">
            <div class="chapter_number nofloat"><?php echo __("chapter_number", ["chapter" => 1]) ?></div>
        </div>
        <div class="chapter_item">
            <div class="chapter_number nofloat"><?php echo __("chapter_number", ["chapter" => 2]) ?></div>
        </div>
        <div class="chapter_item">
            <div class="chapter_number nofloat"><?php echo __("chapter_number", ["chapter" => 3]) ?></div>
        </div>
        <div class="chapter_item">
            <div class="chapter_accordion">
                <div class="section_header" data="sec_1">
                    <div class="section_arrow glyphicon glyphicon-triangle-right"></div>
                    <div class="chapter_number section_title"><?php echo __("chapter_number", ["chapter" => 4]) ?></div>
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
                        <div class="section_translator_name tnleft">
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
                        <!-- Consume Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_" . StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/".EventSteps::CONSUME.".png") ?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME); ?></div>
                        </div>
                        <!-- Blind Draft Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/blind-draft.png") ?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::BLIND_DRAFT) ?></div>
                            <div class="step_chunks more_chunks">
                                <div class="section_translator_chunk">
                                    <?php echo __("chunk_number", ["chunk_number" => 1]); ?>&nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>
                                </div>
                                <div class="section_translator_chunk">
                                    <?php echo __("chunk_number", ["chunk_number" => 2]); ?>&nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>
                                </div>
                                <div class="section_translator_chunk">
                                    <?php echo __("chunk_number", ["chunk_number" => 3]); ?>&nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>
                                </div>
                                <div class="section_translator_chunk">
                                    <?php echo __("chunk_number", ["chunk_number" => 4]); ?>&nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>
                                </div>
                                <div class="section_translator_chunk">
                                    <?php echo __("chunk_number", ["chunk_number" => 5]); ?>&nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>
                                </div>
                                <div class="section_translator_chunk">
                                    <?php echo __("chunk_number", ["chunk_number" => 6]); ?>&nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>
                                </div>
                                <div class="section_translator_chunk">
                                    <?php echo __("chunk_number", ["chunk_number" => 7]); ?>&nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>
                                </div>
                                <div class="section_translator_chunk">
                                    <?php echo __("chunk_number", ["chunk_number" => 8]); ?>&nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>
                                </div>
                                <div class="section_translator_chunk">
                                    <?php echo __("chunk_number", ["chunk_number" => 9]); ?>&nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>
                                </div>
                                <div class="section_translator_chunk">
                                    <?php echo __("chunk_number", ["chunk_number" => 10]); ?>&nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>
                                </div>
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
                            <div class="step_name">2. <?php echo __(EventSteps::PEER_REVIEW . "_obs") ?></div>
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
            <div class="chapter_number nofloat"><?php echo __("chapter_number", ["chapter" => 5]) ?></div>
        </div>
        <div class="chapter_item">
            <div class="chapter_accordion">
                <div class="section_header" data="sec_1">
                    <div class="section_arrow glyphicon glyphicon-triangle-right"></div>
                    <div class="chapter_number section_title"><?php echo __("chapter_number", ["chapter" => 6]) ?></div>
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
                        <div class="section_translator_name tnleft">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png") ?>">
                            <span><b>Tanya E.</b></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="section_steps">
                        <!-- Consume Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_" . StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/".EventSteps::CONSUME.".png") ?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME); ?></div>
                        </div>
                        <!-- Blind Draft Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/blind-draft.png") ?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::BLIND_DRAFT) ?></div>
                            <div class="step_chunks more_chunks">
                                <div class="section_translator_chunk">
                                    <?php echo __("chunk_number", ["chunk_number" => 1]); ?>&nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>
                                </div>
                                <div class="section_translator_chunk">
                                    <?php echo __("chunk_number", ["chunk_number" => 2]); ?>&nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>
                                </div>
                                <div class="section_translator_chunk">
                                    <?php echo __("chunk_number", ["chunk_number" => 3]); ?>&nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>
                                </div>
                                <div class="section_translator_chunk">
                                    <?php echo __("chunk_number", ["chunk_number" => 4]); ?>&nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>
                                </div>
                                <div class="section_translator_chunk">
                                    <?php echo __("chunk_number", ["chunk_number" => 5]); ?>&nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>
                                </div>
                                <div class="section_translator_chunk">
                                    <?php echo __("chunk_number", ["chunk_number" => 6]); ?>&nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>
                                </div>
                                <div class="section_translator_chunk">
                                    <?php echo __("chunk_number", ["chunk_number" => 7]); ?>&nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>
                                </div>
                                <div class="chunks_blind glyphicon glyphicon-triangle-bottom"></div>
                            </div>
                        </div>
                        <!-- Self Edit Step -->
                        <div class="section_step in_progress">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::IN_PROGRESS) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png") ?>"></div>
                            <div class="step_name">2. <?php echo __(EventSteps::SELF_CHECK) ?></div>
                        </div>

                        <!-- Checking stage -->
                        <div class="section_step chk">
                        </div>

                        <!-- Keyword Check Step -->
                        <div class="section_step not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/keyword-check.png") ?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::KEYWORD_CHECK) ?></div>
                        </div>
                        <!-- Peer Check Step -->
                        <div class="section_step not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/peer-review.png") ?>"></div>
                            <div class="step_name">2. <?php echo __(EventSteps::PEER_REVIEW . "_obs") ?></div>
                            <div class="step_checker">
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="chapter_item">
            <div class="chapter_accordion">
                <div class="section_header" data="sec_1">
                    <div class="section_arrow glyphicon glyphicon-triangle-right"></div>
                    <div class="chapter_number section_title"><?php echo __("chapter_number", ["chapter" => 7]) ?></div>
                    <div class="section_translator_progress_bar">
                        <div class="progress ">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="min-width: 0em; width: 100%">100%</div>
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
                            <span><b>Антон Ш.</b></span>
                        </div>
                        <div class="section_translator_name tnright">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png") ?>">
                            <span><b>Tanya E.</b></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="section_steps">
                        <!-- Consume Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_" . StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/".EventSteps::CONSUME.".png") ?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME); ?></div>
                        </div>
                        <!-- Blind Draft Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/blind-draft.png") ?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::BLIND_DRAFT) ?></div>
                            <div class="step_chunks more_chunks">
                                <div class="section_translator_chunk">
                                    <?php echo __("chunk_number", ["chunk_number" => 1]); ?>&nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>
                                </div>
                                <div class="section_translator_chunk">
                                    <?php echo __("chunk_number", ["chunk_number" => 2]); ?>&nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>
                                </div>
                                <div class="section_translator_chunk">
                                    <?php echo __("chunk_number", ["chunk_number" => 3]); ?>&nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>
                                </div>
                                <div class="section_translator_chunk">
                                    <?php echo __("chunk_number", ["chunk_number" => 4]); ?>&nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>
                                </div>
                                <div class="section_translator_chunk">
                                    <?php echo __("chunk_number", ["chunk_number" => 5]); ?>&nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>
                                </div>
                                <div class="section_translator_chunk">
                                    <?php echo __("chunk_number", ["chunk_number" => 6]); ?>&nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>
                                </div>
                                <div class="section_translator_chunk">
                                    <?php echo __("chunk_number", ["chunk_number" => 7]); ?>&nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>
                                </div>
                                <div class="section_translator_chunk">
                                    <?php echo __("chunk_number", ["chunk_number" => 8]); ?>&nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>
                                </div>
                                <div class="section_translator_chunk">
                                    <?php echo __("chunk_number", ["chunk_number" => 9]); ?>&nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>
                                </div>
                                <div class="section_translator_chunk">
                                    <?php echo __("chunk_number", ["chunk_number" => 10]); ?>&nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>
                                </div>
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
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/peer-review.png") ?>"></div>
                            <div class="step_name">2. <?php echo __(EventSteps::PEER_REVIEW . "_obs") ?></div>
                            <div class="step_checker">
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="chapter_item">
            <div class="chapter_number nofloat"><?php echo __("chapter_number", ["chapter" => 8]) ?></div>
        </div>
        <div class="chapter_item">
            <div class="chapter_number nofloat"><?php echo __("chapter_number", ["chapter" => 9]) ?></div>
        </div>
        <div class="chapter_item">
            <div class="chapter_number nofloat"><?php echo __("chapter_number", ["chapter" => 10]) ?></div>
        </div>
        <div class="chapter_item">
            <div class="chapter_number nofloat"><?php echo __("chapter_number", ["chapter" => 11]) ?></div>
        </div>
        <div class="chapter_item">
            <div class="chapter_number nofloat"><?php echo __("chapter_number", ["chapter" => 12]) ?></div>
        </div>
        <div class="chapter_item">
            <div class="chapter_number nofloat"><?php echo __("chapter_number", ["chapter" => 13]) ?></div>
        </div>
        <div class="chapter_item">
            <div class="chapter_number nofloat"><?php echo __("chapter_number", ["chapter" => 14]) ?></div>
        </div>
        <div class="chapter_item">
            <div class="chapter_number nofloat"><?php echo __("chapter_number", ["chapter" => 15]) ?></div>
        </div>
        <div class="chapter_item">
            <div class="chapter_number nofloat">...</div>
        </div>
        <div class="chapter_item">
            <div class="chapter_number nofloat"><?php echo __("chapter_number", ["chapter" => 50]) ?></div>
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