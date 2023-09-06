<?php
use Helpers\Constants\EventSteps;
?>

<div class="back_link">
    <?php if(isset($_SERVER["HTTP_REFERER"])): ?>
        <a href="<?php echo $_SERVER["HTTP_REFERER"] ?>"><?php echo __("go_back") ?></a>
    <?php endif; ?>
    <div class="demo_title"><?php echo __("demo") . " (".__("review_events").")" ?></div>
</div>

<div>
    <div class="book_title">2 Timothy</div>
    <div class="project_title"><?php echo __("ulb") ?> - Papuan Malay</div>
    <div class="overall_progress_bar">
        <h3><?php echo __("progress_all") ?></h3>
        <div class="progress progress_all ">
            <div style="min-width: 0em; width: 9%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="9" role="progressbar" class="progress-bar progress-bar-success">
                9%
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
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="min-width: 0em; width: 50%"> 50% </div>
                        </div>
                        <div class=" finished_icon"></div>
                        <div class="clear"></div>
                    </div>
                    <div> </div>
                    <div class="clear"></div>
                </div>
                <div class="section_content">
                    <div class="section_translator">
                        <div class="section_translator_name"> <img width="50" src="<?php echo template_url("img/avatars/n1.png") ?>"> <span><b>Paul G.</b></span> </div>
                    </div>
                    <div class="section_steps">
                        <!-- Peer Review Step -->
                        <div class="section_step finished">
                            <div class="step_status"> <?php echo __("step_status_finished") ?> </div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/".EventSteps::PEER_REVIEW.".png") ?>"></div>
                            <div class="step_name">1. <?php echo __("peer-review-l3") ?></div>
                            <div class="step_checker">
                                <div>
                                    <img width="50" src="<?php echo template_url("img/avatars/n1.png") ?>">
                                    <div>Laura C.</div>
                                </div>
                                <span class="glyphicon glyphicon-ok checked"></span>
                            </div>
                        </div>
                        <!-- Peer Edit Step -->
                        <div class="section_step in_progress">
                            <div class="step_status"> <?php echo __("step_status_in_progress") ?> </div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/".EventSteps::PEER_REVIEW.".png") ?>"></div>
                            <div class="step_name">2. <?php echo __("peer-edit-l3") ?></div>
                            <div class="step_checker">
                                <img width="50" src="<?php echo template_url("img/avatars/n1.png") ?>">
                                <div>Laura C.</div>
                                <span class=" checked"></span>
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
                        <div class="progress zero">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="min-width: 0em; width: 0%"> 0% </div>
                        </div>
                        <div class=" finished_icon"></div>
                        <div class="clear"></div>
                    </div>
                    <div> </div>
                    <div class="clear"></div>
                </div>
                <div class="section_content">
                    <div class="section_translator">
                        <div class="section_translator_name"> <img width="50" src="<?php echo template_url("img/avatars/n1.png") ?>"> <span><b>Laura C.</b></span> </div>
                    </div>
                    <div class="section_steps">
                        <!-- Peer Review Step -->
                        <div class="section_step waiting">
                            <div class="step_status"> <?php echo __("step_status_waiting") ?> </div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/".EventSteps::PEER_REVIEW.".png") ?>"></div>
                            <div class="step_name">1. <?php echo __("peer-review-l3") ?></div>
                            <img class="img_waiting" src="<?php echo template_url("img/waiting.png") ?>">
                        </div>
                        <!-- Peer Edit Step -->
                        <div class="section_step not_started">
                            <div class="step_status"> <?php echo __("step_status_not_started") ?> </div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/".EventSteps::PEER_REVIEW.".png") ?>"></div>
                            <div class="step_name">2. <?php echo __("peer-edit-l3") ?></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="chapter_item">
            <div class="chapter_number nofloat"><?php echo __("chapter_number", ["chapter" => 3]) ?></div>
        </div>
        <div class="chapter_item">
            <div class="chapter_number nofloat"><?php echo __("chapter_number", ["chapter" => 4]) ?></div>
        </div>
        <div class="chapter_item">
            <div class="chapter_number nofloat"><?php echo __("chapter_number", ["chapter" => 5]) ?></div>
        </div>
        <div class="chapter_item">
            <div class="chapter_number nofloat"><?php echo __("chapter_number", ["chapter" => 6]) ?></div>
        </div>
        <div class="chapter_item">
            <div class="chapter_number nofloat"><?php echo __("chapter_number", ["chapter" => 7]) ?></div>
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
            <div class="chapter_accordion">
                <div class="section_header" data="sec_16">
                    <div class="section_arrow glyphicon glyphicon-triangle-right"></div>
                    <div class="chapter_number section_title"><?php echo __("chapter_number", ["chapter" => 16]) ?></div>
                    <div class="section_translator_progress_bar">
                        <div class="progress ">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="min-width: 0em; width: 100%"> 100% </div>
                        </div>
                        <div class="glyphicon glyphicon-ok finished_icon"></div>
                        <div class="clear"></div>
                    </div>
                    <div> </div>
                    <div class="clear"></div>
                </div>
                <div class="section_content">
                    <div class="section_translator">
                        <div class="section_translator_name"> <img width="50" src="<?php echo template_url("img/avatars/n1.png") ?>"> <span><b>Mark P.</b></span> </div>
                    </div>
                    <div class="section_steps">
                        <!-- Peer Review Step -->
                        <div class="section_step finished">
                            <div class="step_status"> <?php echo __("step_status_finished") ?> </div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/".EventSteps::PEER_REVIEW.".png") ?>"></div>
                            <div class="step_name">1. <?php echo __("peer-review-l3") ?></div>
                            <div class="step_checker">
                                <div>
                                    <img width="50" src="<?php echo template_url("img/avatars/n1.png") ?>">
                                    <div>Marge S.</div>
                                </div>
                                <span class="glyphicon glyphicon-ok checked"></span>
                            </div>
                        </div>
                        <!-- Peer Edit Step -->
                        <div class="section_step finished">
                            <div class="step_status"> <?php echo __("step_status_finished") ?> </div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/".EventSteps::PEER_REVIEW.".png") ?>"></div>
                            <div class="step_name">2. <?php echo __("peer-edit-l3") ?></div>
                            <div class="step_checker">
                                <div>
                                    <img width="50" src="<?php echo template_url("img/avatars/n1.png") ?>">
                                    <div>Marge S.</div>
                                </div>
                                <span class="glyphicon glyphicon-ok checked"></span>
                            </div>
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
            <span class="member_uname">Mark P.</span>
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