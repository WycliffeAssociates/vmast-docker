<?php
use Helpers\Constants\EventSteps;
use Helpers\Constants\StepsStates;
?>

<div class="back_link">
    <?php if(isset($_SERVER["HTTP_REFERER"])): ?>
        <a href="<?php echo $_SERVER["HTTP_REFERER"] ?>"><?php echo __("go_back") ?></a>
    <?php endif; ?>
    <div class="demo_title"><?php echo __("demo") . " (".__("tn").")" ?></div>
</div>

<div>
    <div class="book_title">Acts</div>
    <div class="project_title"><?php echo __("tn") ?> - Bahasa Indonesia</div>
    <div class="overall_progress_bar">
        <h3><?php echo __("progress_all") ?></h3>
        <div class="progress progress_all ">
            <div style="min-width: 0em; width: 70%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="70" role="progressbar" class="progress-bar progress-bar-success">
                70%
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>

<div class="row" style="position:relative;">
    <div class="chapter_list"><div class="chapter_item">
            <div class="chapter_accordion">
                <div class="section_header" data="sec_0">
                    <div class="section_arrow glyphicon glyphicon-triangle-right"></div>
                    <div class="chapter_number section_title"><?php echo __("intro") ?></div>
                    <div class="section_translator_progress_bar">
                        <div class="progress ">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="min-width: 0em; width: 100%">
                                100%                        </div>
                        </div>
                        <div class="glyphicon glyphicon-ok finished_icon"></div>
                        <div class="clear"></div>
                    </div>
                    <div>
                        <span style="font-weight: bold;"><?php echo __("last_edit") .": " ?></span>
                        <span class="datetime" data="Fri, 17 Nov 2017 09:40:29 +0000">Fri, November 17, 2017, 4:40 AM</span>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="section_content">
                    <div class="section_translator">
                        <div class="section_translator_name tnleft">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                            <span><b>Sylvia T.</b></span>
                        </div>
                        <div class="section_translator_name tnright">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                            <span><b>Ketut S.</b></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="section_steps">
                        <!-- Consume Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Blind Draft Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/blind-draft.png")?>"></div>
                            <div class="step_name">2-3. <?php echo __(EventSteps::BLIND_DRAFT); ?></div>
                            <div class="step_chunks ">
                                <div class="section_translator_chunk">
                                    <?php echo __("intro"); ?>                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                            </div>
                        </div>
                        <!-- Self Edit Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::SELF_CHECK); ?></div>
                        </div>

                        <!-- Checking stage -->
                        <div class="section_step chk">
                        </div>

                        <!-- Consume Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Highlight Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/highlight.png")?>"></div>
                            <div class="step_name">2. <?php echo __(EventSteps::HIGHLIGHT."_tn"); ?></div>
                        </div>
                        <!-- Self Check Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">3. <?php echo __(EventSteps::SELF_CHECK."_tn_chk"); ?></div>
                            <div class="step_chunks ">
                                <div class="section_translator_chunk">
                                    <?php echo __("intro"); ?>                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                            </div>
                        </div>
                        <!-- Keyword Check Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/keyword-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::KEYWORD_CHECK."_tn"); ?></div>
                        </div>
                        <!-- Peer Check Step -->
                        <div class="section_step chk finished">
                            <div class="step_status">
                                <?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/peer-review.png")?>"></div>
                            <div class="step_name">5. <?php echo __(EventSteps::PEER_REVIEW."_tn"); ?></div>
                            <div class="step_checker">
                                <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                                <div>John C.</div>
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
                <div class="section_header" data="sec_1">
                    <div class="section_arrow glyphicon glyphicon-triangle-right"></div>
                    <div class="chapter_number section_title"><?php echo __("chapter_number", ["chapter" => 1]) ?></div>
                    <div class="section_translator_progress_bar">
                        <div class="progress ">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="min-width: 0em; width: 100%">
                                100%                        </div>
                        </div>
                        <div class="glyphicon glyphicon-ok finished_icon"></div>
                        <div class="clear"></div>
                    </div>
                    <div>
                        <span style="font-weight: bold;"><?php echo __("last_edit") .": " ?></span>
                        <span class="datetime" data="Mon, 20 Nov 2017 07:51:29 +0000">Mon, November 20, 2017, 2:51 AM</span>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="section_content">
                    <div class="section_translator">
                        <div class="section_translator_name tnleft">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                            <span><b>Lili J.</b></span>
                        </div>
                        <div class="section_translator_name tnright">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                            <span><b>Ketut S.</b></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="section_steps">
                        <!-- Consume Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Blind Draft Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/blind-draft.png")?>"></div>
                            <div class="step_name">2-3. <?php echo __(EventSteps::BLIND_DRAFT); ?></div>
                            <div class="step_chunks more_chunks">
                                <div class="section_translator_chunk">
                                    <?php echo __("intro"); ?>                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    1 - 3                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    4 - 5                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    6 - 8                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    9 - 11                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    12 - 14                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    15 - 16                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    17 - 19                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    20 - 20                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    21 - 23                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    24 - 26                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="chunks_blind glyphicon glyphicon-triangle-bottom"></div>
                            </div>
                        </div>
                        <!-- Self Edit Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::SELF_CHECK); ?></div>
                        </div>

                        <!-- Checking stage -->
                        <div class="section_step chk">
                        </div>

                        <!-- Consume Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Highlight Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/highlight.png")?>"></div>
                            <div class="step_name">2. <?php echo __(EventSteps::HIGHLIGHT."_tn"); ?></div>
                        </div>
                        <!-- Self Check Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">3. <?php echo __(EventSteps::SELF_CHECK."_tn_chk"); ?></div>
                            <div class="step_chunks more_chunks">
                                <div class="section_translator_chunk">
                                    <?php echo __("intro"); ?>                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    1 - 3                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    4 - 5                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    6 - 8                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    9 - 11                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    12 - 14                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    15 - 16                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    17 - 19                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    20 - 20                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    21 - 23                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    24 - 26                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="chunks_blind glyphicon glyphicon-triangle-bottom"></div>
                            </div>
                        </div>
                        <!-- Keyword Check Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/keyword-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::KEYWORD_CHECK."_tn"); ?></div>
                        </div>
                        <!-- Peer Check Step -->
                        <div class="section_step chk finished">
                            <div class="step_status">
                                <?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/peer-review.png")?>"></div>
                            <div class="step_name">5. <?php echo __(EventSteps::PEER_REVIEW."_tn"); ?></div>
                            <div class="step_checker">
                                <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                                <div>John C.</div>
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
                <div class="section_header" data="sec_2">
                    <div class="section_arrow glyphicon glyphicon-triangle-right"></div>
                    <div class="chapter_number section_title"><?php echo __("chapter_number", ["chapter" => 2]) ?></div>
                    <div class="section_translator_progress_bar">
                        <div class="progress ">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="81" aria-valuemin="0" aria-valuemax="100" style="min-width: 0em; width: 81%">
                                81%                        </div>
                        </div>
                        <div class=" finished_icon"></div>
                        <div class="clear"></div>
                    </div>
                    <div>
                        <span style="font-weight: bold;"><?php echo __("last_edit") .": " ?></span>
                        <span class="datetime" data="Tue, 21 Nov 2017 10:36:35 +0000">Tue, November 21, 2017, 5:36 AM</span>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="section_content">
                    <div class="section_translator">
                        <div class="section_translator_name tnleft">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                            <span><b>Amanda L.</b></span>
                        </div>
                        <div class="section_translator_name tnright">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                            <span><b>John C.</b></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="section_steps">
                        <!-- Consume Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Blind Draft Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/blind-draft.png")?>"></div>
                            <div class="step_name">2-3. <?php echo __(EventSteps::BLIND_DRAFT); ?></div>
                            <div class="step_chunks more_chunks">
                                <div class="section_translator_chunk">
                                    <?php echo __("intro"); ?>                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    1 - 4                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    5 - 7                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    8 - 11                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    12 - 13                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    14 - 15                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    16 - 17                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    18 - 19                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    20 - 21                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    22 - 24                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    25 - 26                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    27 - 28                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    29 - 31                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    32 - 33                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    34 - 36                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    37 - 39                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    40 - 42                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    43 - 45                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    46 - 47                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="chunks_blind glyphicon glyphicon-triangle-bottom"></div>
                            </div>
                        </div>
                        <!-- Self Edit Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::SELF_CHECK); ?></div>
                        </div>

                        <!-- Checking stage -->
                        <div class="section_step chk">
                        </div>

                        <!-- Consume Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Highlight Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/highlight.png")?>"></div>
                            <div class="step_name">2. <?php echo __(EventSteps::HIGHLIGHT."_tn"); ?></div>
                        </div>
                        <!-- Self Check Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">3. <?php echo __(EventSteps::SELF_CHECK."_tn_chk"); ?></div>
                            <div class="step_chunks more_chunks">
                                <div class="section_translator_chunk">
                                    <?php echo __("intro"); ?>                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    1 - 4                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    5 - 7                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    8 - 11                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    12 - 13                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    14 - 15                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    16 - 17                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    18 - 19                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    20 - 21                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    22 - 24                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    25 - 26                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    27 - 28                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    29 - 31                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    32 - 33                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    34 - 36                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    37 - 39                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    40 - 42                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    43 - 45                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    46 - 47                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="chunks_blind glyphicon glyphicon-triangle-bottom"></div>
                            </div>
                        </div>
                        <!-- Keyword Check Step -->
                        <div class="section_step chk in_progress">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::IN_PROGRESS) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/keyword-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::KEYWORD_CHECK."_tn"); ?></div>
                        </div>
                        <!-- Peer Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status">
                                <?php echo __("step_status_".StepsStates::NOT_STARTED) ?>                        </div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/peer-review.png")?>"></div>
                            <div class="step_name">5. <?php echo __(EventSteps::PEER_REVIEW."_tn"); ?></div>
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
                        <div class="progress ">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="min-width: 0em; width: 100%">
                                100%                        </div>
                        </div>
                        <div class="glyphicon glyphicon-ok finished_icon"></div>
                        <div class="clear"></div>
                    </div>
                    <div>
                        <span style="font-weight: bold;"><?php echo __("last_edit") .": " ?></span>
                        <span class="datetime" data="Fri, 17 Nov 2017 16:50:26 +0000">Fri, November 17, 2017, 11:50 AM</span>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="section_content">
                    <div class="section_translator">
                        <div class="section_translator_name tnleft">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                            <span><b>Yosua A.</b></span>
                        </div>
                        <div class="section_translator_name tnright">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                            <span><b>Jhonson S.</b></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="section_steps">
                        <!-- Consume Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Blind Draft Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/blind-draft.png")?>"></div>
                            <div class="step_name">2-3. <?php echo __(EventSteps::BLIND_DRAFT); ?></div>
                            <div class="step_chunks more_chunks">
                                <div class="section_translator_chunk">
                                    <?php echo __("intro"); ?>                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    1 - 3                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    4 - 6                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    7 - 8                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    9 - 10                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    11 - 12                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    13 - 14                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    15 - 16                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    17 - 18                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    19 - 20                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    21 - 23                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    24 - 26                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="chunks_blind glyphicon glyphicon-triangle-bottom"></div>
                            </div>
                        </div>
                        <!-- Self Edit Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::SELF_CHECK); ?></div>
                        </div>

                        <!-- Checking stage -->
                        <div class="section_step chk">
                        </div>

                        <!-- Consume Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Highlight Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/highlight.png")?>"></div>
                            <div class="step_name">2. <?php echo __(EventSteps::HIGHLIGHT."_tn"); ?></div>
                        </div>
                        <!-- Self Check Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">3. <?php echo __(EventSteps::SELF_CHECK."_tn_chk"); ?></div>
                            <div class="step_chunks more_chunks">
                                <div class="section_translator_chunk">
                                    <?php echo __("intro"); ?>                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    1 - 3                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    4 - 6                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    7 - 8                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    9 - 10                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    11 - 12                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    13 - 14                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    15 - 16                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    17 - 18                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    19 - 20                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    21 - 23                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    24 - 26                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="chunks_blind glyphicon glyphicon-triangle-bottom"></div>
                            </div>
                        </div>
                        <!-- Keyword Check Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/keyword-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::KEYWORD_CHECK."_tn"); ?></div>
                        </div>
                        <!-- Peer Check Step -->
                        <div class="section_step chk finished">
                            <div class="step_status">
                                <?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/peer-review.png")?>"></div>
                            <div class="step_name">5. <?php echo __(EventSteps::PEER_REVIEW."_tn"); ?></div>
                            <div class="step_checker">
                                <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                                <div>Walben S.</div>
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
                        <div class="progress ">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="min-width: 0em; width: 100%">
                                100%                        </div>
                        </div>
                        <div class="glyphicon glyphicon-ok finished_icon"></div>
                        <div class="clear"></div>
                    </div>
                    <div>
                        <span style="font-weight: bold;"><?php echo __("last_edit") .": " ?></span>
                        <span class="datetime" data="Mon, 20 Nov 2017 08:33:09 +0000">Mon, November 20, 2017, 3:33 AM</span>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="section_content">
                    <div class="section_translator">
                        <div class="section_translator_name tnleft">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                            <span><b>Nana S.</b></span>
                        </div>
                        <div class="section_translator_name tnright">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                            <span><b>Walben S.</b></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="section_steps">
                        <!-- Consume Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Blind Draft Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/blind-draft.png")?>"></div>
                            <div class="step_name">2-3. <?php echo __(EventSteps::BLIND_DRAFT); ?></div>
                            <div class="step_chunks more_chunks">
                                <div class="section_translator_chunk">
                                    <?php echo __("intro"); ?>                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    1 - 4                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    5 - 7                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    8 - 10                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    11 - 12                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    13 - 14                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    15 - 18                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    19 - 20                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    21 - 22                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    23 - 25                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    26 - 26                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    27 - 28                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    29 - 31                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    32 - 33                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    34 - 35                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    36 - 37                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="chunks_blind glyphicon glyphicon-triangle-bottom"></div>
                            </div>
                        </div>
                        <!-- Self Edit Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::SELF_CHECK); ?></div>
                        </div>

                        <!-- Checking stage -->
                        <div class="section_step chk">
                        </div>

                        <!-- Consume Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Highlight Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/highlight.png")?>"></div>
                            <div class="step_name">2. <?php echo __(EventSteps::HIGHLIGHT."_tn"); ?></div>
                        </div>
                        <!-- Self Check Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">3. <?php echo __(EventSteps::SELF_CHECK."_tn_chk"); ?></div>
                            <div class="step_chunks more_chunks">
                                <div class="section_translator_chunk">
                                    <?php echo __("intro"); ?>                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    1 - 4                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    5 - 7                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    8 - 10                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    11 - 12                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    13 - 14                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    15 - 18                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    19 - 20                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    21 - 22                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    23 - 25                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    26 - 26                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    27 - 28                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    29 - 31                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    32 - 33                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    34 - 35                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    36 - 37                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="chunks_blind glyphicon glyphicon-triangle-bottom"></div>
                            </div>
                        </div>
                        <!-- Keyword Check Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/keyword-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::KEYWORD_CHECK."_tn"); ?></div>
                        </div>
                        <!-- Peer Check Step -->
                        <div class="section_step chk finished">
                            <div class="step_status">
                                <?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/peer-review.png")?>"></div>
                            <div class="step_name">5. <?php echo __(EventSteps::PEER_REVIEW."_tn"); ?></div>
                            <div class="step_checker">
                                <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                                <div>Jhonson S.</div>
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
                <div class="section_header" data="sec_5">
                    <div class="section_arrow glyphicon glyphicon-triangle-right"></div>
                    <div class="chapter_number section_title"><?php echo __("chapter_number", ["chapter" => 5]) ?></div>
                    <div class="section_translator_progress_bar">
                        <div class="progress ">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="81" aria-valuemin="0" aria-valuemax="100" style="min-width: 0em; width: 81%">
                                81%                        </div>
                        </div>
                        <div class=" finished_icon"></div>
                        <div class="clear"></div>
                    </div>
                    <div>
                        <span style="font-weight: bold;"><?php echo __("last_edit") .": " ?></span>
                        <span class="datetime" data="Tue, 21 Nov 2017 10:34:33 +0000">Tue, November 21, 2017, 5:34 AM</span>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="section_content">
                    <div class="section_translator">
                        <div class="section_translator_name tnleft">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                            <span><b>Ria W.</b></span>
                        </div>
                        <div class="section_translator_name tnright">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                            <span><b>Ketut S.</b></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="section_steps">
                        <!-- Consume Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Blind Draft Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/blind-draft.png")?>"></div>
                            <div class="step_name">2-3. <?php echo __(EventSteps::BLIND_DRAFT); ?></div>
                            <div class="step_chunks more_chunks">
                                <div class="section_translator_chunk">
                                    <?php echo __("intro"); ?>                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    1 - 2                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    3 - 6                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    7 - 8                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    9 - 11                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    12 - 13                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    14 - 16                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    17 - 18                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    19 - 21                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    22 - 23                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    24 - 25                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    26 - 28                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    29 - 32                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    33 - 34                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    35 - 37                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    38 - 39                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    40 - 42                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="chunks_blind glyphicon glyphicon-triangle-bottom"></div>
                            </div>
                        </div>
                        <!-- Self Edit Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::SELF_CHECK); ?></div>
                        </div>

                        <!-- Checking stage -->
                        <div class="section_step chk">
                        </div>

                        <!-- Consume Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Highlight Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/highlight.png")?>"></div>
                            <div class="step_name">2. <?php echo __(EventSteps::HIGHLIGHT."_tn"); ?></div>
                        </div>
                        <!-- Self Check Step -->
                        <div class="section_step chk in_progress">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::IN_PROGRESS) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">3. <?php echo __(EventSteps::SELF_CHECK."_tn_chk"); ?></div>
                            <div class="step_chunks more_chunks">
                                <div class="section_translator_chunk">
                                    <?php echo __("intro"); ?>                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    1 - 2                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    3 - 6                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    7 - 8                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    9 - 11                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    12 - 13                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    14 - 16                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    17 - 18                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    19 - 21                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    22 - 23                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    24 - 25                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    26 - 28                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    29 - 32                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    33 - 34                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    35 - 37                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    38 - 39                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    40 - 42                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="chunks_blind glyphicon glyphicon-triangle-bottom"></div>
                            </div>
                        </div>
                        <!-- Keyword Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/keyword-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::KEYWORD_CHECK."_tn"); ?></div>
                        </div>
                        <!-- Peer Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status">
                                <?php echo __("step_status_".StepsStates::NOT_STARTED) ?>                        </div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/peer-review.png")?>"></div>
                            <div class="step_name">5. <?php echo __(EventSteps::PEER_REVIEW."_tn"); ?></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="chapter_item">
            <div class="chapter_accordion">
                <div class="section_header" data="sec_6">
                    <div class="section_arrow glyphicon glyphicon-triangle-right"></div>
                    <div class="chapter_number section_title"><?php echo __("chapter_number", ["chapter" => 6]) ?></div>
                    <div class="section_translator_progress_bar">
                        <div class="progress ">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="min-width: 0em; width: 100%">
                                100%                        </div>
                        </div>
                        <div class="glyphicon glyphicon-ok finished_icon"></div>
                        <div class="clear"></div>
                    </div>
                    <div>
                        <span style="font-weight: bold;"><?php echo __("last_edit") .": " ?></span>
                        <span class="datetime" data="Fri, 17 Nov 2017 16:10:27 +0000">Fri, November 17, 2017, 11:10 AM</span>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="section_content">
                    <div class="section_translator">
                        <div class="section_translator_name tnleft">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                            <span><b>Ririn B.</b></span>
                        </div>
                        <div class="section_translator_name tnright">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                            <span><b>Ketut S.</b></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="section_steps">
                        <!-- Consume Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Blind Draft Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/blind-draft.png")?>"></div>
                            <div class="step_name">2-3. <?php echo __(EventSteps::BLIND_DRAFT); ?></div>
                            <div class="step_chunks more_chunks">
                                <div class="section_translator_chunk">
                                    <?php echo __("intro"); ?>                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    1 - 1                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    2 - 4                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    5 - 6                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    7 - 7                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    8 - 9                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    10 - 11                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    12 - 15                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="chunks_blind glyphicon glyphicon-triangle-bottom"></div>
                            </div>
                        </div>
                        <!-- Self Edit Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::SELF_CHECK); ?></div>
                        </div>

                        <!-- Checking stage -->
                        <div class="section_step chk">
                        </div>

                        <!-- Consume Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Highlight Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/highlight.png")?>"></div>
                            <div class="step_name">2. <?php echo __(EventSteps::HIGHLIGHT."_tn"); ?></div>
                        </div>
                        <!-- Self Check Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">3. <?php echo __(EventSteps::SELF_CHECK."_tn_chk"); ?></div>
                            <div class="step_chunks more_chunks">
                                <div class="section_translator_chunk">
                                    <?php echo __("intro"); ?>                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    1 - 1                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    2 - 4                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    5 - 6                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    7 - 7                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    8 - 9                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    10 - 11                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    12 - 15                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="chunks_blind glyphicon glyphicon-triangle-bottom"></div>
                            </div>
                        </div>
                        <!-- Keyword Check Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/keyword-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::KEYWORD_CHECK."_tn"); ?></div>
                        </div>
                        <!-- Peer Check Step -->
                        <div class="section_step chk finished">
                            <div class="step_status">
                                <?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/peer-review.png")?>"></div>
                            <div class="step_name">5. <?php echo __(EventSteps::PEER_REVIEW."_tn"); ?></div>
                            <div class="step_checker">
                                <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                                <div>Jhonson S.</div>
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
                <div class="section_header" data="sec_7">
                    <div class="section_arrow glyphicon glyphicon-triangle-right"></div>
                    <div class="chapter_number section_title"><?php echo __("chapter_number", ["chapter" => 7]) ?></div>
                    <div class="section_translator_progress_bar">
                        <div class="progress ">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="51" aria-valuemin="0" aria-valuemax="100" style="min-width: 0em; width: 51%">
                                51%                        </div>
                        </div>
                        <div class=" finished_icon"></div>
                        <div class="clear"></div>
                    </div>
                    <div>
                        <span style="font-weight: bold;"><?php echo __("last_edit") .": " ?></span>
                        <span class="datetime" data="Sat, 18 Nov 2017 14:47:54 +0000">Sat, November 18, 2017, 9:47 AM</span>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="section_content">
                    <div class="section_translator">
                        <div class="section_translator_name tnleft">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                            <span><b>Efelin O.</b></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="section_steps">
                        <!-- Consume Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Blind Draft Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/blind-draft.png")?>"></div>
                            <div class="step_name">2-3. <?php echo __(EventSteps::BLIND_DRAFT); ?></div>
                            <div class="step_chunks more_chunks">
                                <div class="section_translator_chunk">
                                    <?php echo __("intro"); ?>                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    1 - 3                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    4 - 5                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    6 - 8                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    9 - 10                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    11 - 13                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    14 - 16                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    17 - 19                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    20 - 21                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    22 - 25                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    26 - 28                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    29 - 30                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    31 - 32                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    33 - 34                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    35 - 37                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    38 - 40                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    41 - 42                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    43 - 43                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    44 - 46                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    47 - 50                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    51 - 53                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    54 - 56                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    57 - 58                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    59 - 60                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="chunks_blind glyphicon glyphicon-triangle-bottom"></div>
                            </div>
                        </div>
                        <!-- Self Edit Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::SELF_CHECK); ?></div>
                        </div>

                        <!-- Checking stage -->
                        <div class="section_step chk">
                        </div>

                        <!-- Consume Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Highlight Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/highlight.png")?>"></div>
                            <div class="step_name">2. <?php echo __(EventSteps::HIGHLIGHT."_tn"); ?></div>
                        </div>
                        <!-- Self Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">3. <?php echo __(EventSteps::SELF_CHECK."_tn_chk"); ?></div>
                        </div>
                        <!-- Keyword Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/keyword-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::KEYWORD_CHECK."_tn"); ?></div>
                        </div>
                        <!-- Peer Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status">
                                <?php echo __("step_status_".StepsStates::NOT_STARTED) ?>                        </div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/peer-review.png")?>"></div>
                            <div class="step_name">5. <?php echo __(EventSteps::PEER_REVIEW."_tn"); ?></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="chapter_item">
            <div class="chapter_accordion">
                <div class="section_header" data="sec_8">
                    <div class="section_arrow glyphicon glyphicon-triangle-right"></div>
                    <div class="chapter_number section_title"><?php echo __("chapter_number", ["chapter" => 8]) ?></div>
                    <div class="section_translator_progress_bar">
                        <div class="progress zero">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="min-width: 0em; width: 0%">
                                0%                        </div>
                        </div>
                        <div class=" finished_icon"></div>
                        <div class="clear"></div>
                    </div>
                    <div>
                        <span style="font-weight: bold;"></span>
                        <span class="datetime" data=""></span>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="section_content">
                    <div class="section_translator">
                        <div class="section_translator_name tnleft">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                            <span><b>Tirza T.</b></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="section_steps">
                        <!-- Consume Step -->
                        <div class="section_step not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Blind Draft Step -->
                        <div class="section_step not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/blind-draft.png")?>"></div>
                            <div class="step_name">2-3. <?php echo __(EventSteps::BLIND_DRAFT); ?></div>
                            <div class="step_chunks more_chunks">

                            </div>
                        </div>
                        <!-- Self Edit Step -->
                        <div class="section_step not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::SELF_CHECK); ?></div>
                        </div>

                        <!-- Checking stage -->
                        <div class="section_step chk">
                        </div>

                        <!-- Consume Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Highlight Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/highlight.png")?>"></div>
                            <div class="step_name">2. <?php echo __(EventSteps::HIGHLIGHT."_tn"); ?></div>
                        </div>
                        <!-- Self Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">3. <?php echo __(EventSteps::SELF_CHECK."_tn_chk"); ?></div>
                        </div>
                        <!-- Keyword Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/keyword-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::KEYWORD_CHECK."_tn"); ?></div>
                        </div>
                        <!-- Peer Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status">
                                <?php echo __("step_status_".StepsStates::NOT_STARTED) ?>                        </div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/peer-review.png")?>"></div>
                            <div class="step_name">5. <?php echo __(EventSteps::PEER_REVIEW."_tn"); ?></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="chapter_item">
            <div class="chapter_accordion">
                <div class="section_header" data="sec_9">
                    <div class="section_arrow glyphicon glyphicon-triangle-right"></div>
                    <div class="chapter_number section_title"><?php echo __("chapter_number", ["chapter" => 9]) ?></div>
                    <div class="section_translator_progress_bar">
                        <div class="progress ">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="51" aria-valuemin="0" aria-valuemax="100" style="min-width: 0em; width: 51%">
                                51%                        </div>
                        </div>
                        <div class=" finished_icon"></div>
                        <div class="clear"></div>
                    </div>
                    <div>
                        <span style="font-weight: bold;"><?php echo __("last_edit") .": " ?></span>
                        <span class="datetime" data="Fri, 17 Nov 2017 09:49:13 +0000">Fri, November 17, 2017, 4:49 AM</span>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="section_content">
                    <div class="section_translator">
                        <div class="section_translator_name tnleft">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                            <span><b>Popi P.</b></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="section_steps">
                        <!-- Consume Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Blind Draft Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/blind-draft.png")?>"></div>
                            <div class="step_name">2-3. <?php echo __(EventSteps::BLIND_DRAFT); ?></div>
                            <div class="step_chunks more_chunks">
                                <div class="section_translator_chunk">
                                    <?php echo __("intro"); ?>                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    1 - 2                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    3 - 4                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    5 - 7                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    8 - 9                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    10 - 12                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    13 - 16                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    17 - 19                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    20 - 22                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    23 - 25                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    26 - 27                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    28 - 30                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    31 - 32                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    33 - 35                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    36 - 37                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    38 - 39                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    40 - 43                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="chunks_blind glyphicon glyphicon-triangle-bottom"></div>
                            </div>
                        </div>
                        <!-- Self Edit Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::SELF_CHECK); ?></div>
                        </div>

                        <!-- Checking stage -->
                        <div class="section_step chk">
                        </div>

                        <!-- Consume Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Highlight Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/highlight.png")?>"></div>
                            <div class="step_name">2. <?php echo __(EventSteps::HIGHLIGHT."_tn"); ?></div>
                        </div>
                        <!-- Self Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">3. <?php echo __(EventSteps::SELF_CHECK."_tn_chk"); ?></div>
                        </div>
                        <!-- Keyword Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/keyword-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::KEYWORD_CHECK."_tn"); ?></div>
                        </div>
                        <!-- Peer Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status">
                                <?php echo __("step_status_".StepsStates::NOT_STARTED) ?>                        </div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/peer-review.png")?>"></div>
                            <div class="step_name">5. <?php echo __(EventSteps::PEER_REVIEW."_tn"); ?></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="chapter_item">
            <div class="chapter_accordion">
                <div class="section_header" data="sec_10">
                    <div class="section_arrow glyphicon glyphicon-triangle-right"></div>
                    <div class="chapter_number section_title"><?php echo __("chapter_number", ["chapter" => 10]) ?></div>
                    <div class="section_translator_progress_bar">
                        <div class="progress ">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="min-width: 0em; width: 100%">
                                100%                        </div>
                        </div>
                        <div class="glyphicon glyphicon-ok finished_icon"></div>
                        <div class="clear"></div>
                    </div>
                    <div>
                        <span style="font-weight: bold;"><?php echo __("last_edit") .": " ?></span>
                        <span class="datetime" data="Mon, 20 Nov 2017 08:13:09 +0000">Mon, November 20, 2017, 3:13 AM</span>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="section_content">
                    <div class="section_translator">
                        <div class="section_translator_name tnleft">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                            <span><b>Maya S.</b></span>
                        </div>
                        <div class="section_translator_name tnright">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                            <span><b>John C.</b></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="section_steps">
                        <!-- Consume Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Blind Draft Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/blind-draft.png")?>"></div>
                            <div class="step_name">2-3. <?php echo __(EventSteps::BLIND_DRAFT); ?></div>
                            <div class="step_chunks more_chunks">
                                <div class="section_translator_chunk">
                                    <?php echo __("intro"); ?>                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    1 - 2                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    3 - 6                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    7 - 8                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    9 - 12                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    13 - 16                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    17 - 18                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    19 - 21                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    22 - 23                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    24 - 24                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    25 - 26                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    27 - 29                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    30 - 33                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    34 - 35                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    36 - 38                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    39 - 41                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    42 - 43                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    44 - 45                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    46 - 48                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="chunks_blind glyphicon glyphicon-triangle-bottom"></div>
                            </div>
                        </div>
                        <!-- Self Edit Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::SELF_CHECK); ?></div>
                        </div>

                        <!-- Checking stage -->
                        <div class="section_step chk">
                        </div>

                        <!-- Consume Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Highlight Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/highlight.png")?>"></div>
                            <div class="step_name">2. <?php echo __(EventSteps::HIGHLIGHT."_tn"); ?></div>
                        </div>
                        <!-- Self Check Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">3. <?php echo __(EventSteps::SELF_CHECK."_tn_chk"); ?></div>
                            <div class="step_chunks more_chunks">
                                <div class="section_translator_chunk">
                                    <?php echo __("intro"); ?>                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    1 - 2                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    3 - 6                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    7 - 8                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    9 - 12                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    13 - 16                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    17 - 18                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    19 - 21                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    22 - 23                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    24 - 24                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    25 - 26                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    27 - 29                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    30 - 33                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    34 - 35                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    36 - 38                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    39 - 41                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    42 - 43                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    44 - 45                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    46 - 48                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="chunks_blind glyphicon glyphicon-triangle-bottom"></div>
                            </div>
                        </div>
                        <!-- Keyword Check Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/keyword-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::KEYWORD_CHECK."_tn"); ?></div>
                        </div>
                        <!-- Peer Check Step -->
                        <div class="section_step chk finished">
                            <div class="step_status">
                                <?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/peer-review.png")?>"></div>
                            <div class="step_name">5. <?php echo __(EventSteps::PEER_REVIEW."_tn"); ?></div>
                            <div class="step_checker">
                                <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                                <div>Ketut S.</div>
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
                <div class="section_header" data="sec_11">
                    <div class="section_arrow glyphicon glyphicon-triangle-right"></div>
                    <div class="chapter_number section_title"><?php echo __("chapter_number", ["chapter" => 11]) ?></div>
                    <div class="section_translator_progress_bar">
                        <div class="progress ">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="min-width: 0em; width: 100%">
                                100%                        </div>
                        </div>
                        <div class="glyphicon glyphicon-ok finished_icon"></div>
                        <div class="clear"></div>
                    </div>
                    <div>
                        <span style="font-weight: bold;"><?php echo __("last_edit") .": " ?></span>
                        <span class="datetime" data="Fri, 17 Nov 2017 16:38:29 +0000">Fri, November 17, 2017, 11:38 AM</span>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="section_content">
                    <div class="section_translator">
                        <div class="section_translator_name tnleft">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                            <span><b>Niel N.</b></span>
                        </div>
                        <div class="section_translator_name tnright">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                            <span><b>John C.</b></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="section_steps">
                        <!-- Consume Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Blind Draft Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/blind-draft.png")?>"></div>
                            <div class="step_name">2-3. <?php echo __(EventSteps::BLIND_DRAFT); ?></div>
                            <div class="step_chunks more_chunks">
                                <div class="section_translator_chunk">
                                    <?php echo __("intro"); ?>                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    1 - 3                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    4 - 6                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    7 - 10                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    11 - 14                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    15 - 16                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    17 - 18                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    19 - 21                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    22 - 24                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    25 - 26                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    27 - 28                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    29 - 30                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="chunks_blind glyphicon glyphicon-triangle-bottom"></div>
                            </div>
                        </div>
                        <!-- Self Edit Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::SELF_CHECK); ?></div>
                        </div>

                        <!-- Checking stage -->
                        <div class="section_step chk">
                        </div>

                        <!-- Consume Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Highlight Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/highlight.png")?>"></div>
                            <div class="step_name">2. <?php echo __(EventSteps::HIGHLIGHT."_tn"); ?></div>
                        </div>
                        <!-- Self Check Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">3. <?php echo __(EventSteps::SELF_CHECK."_tn_chk"); ?></div>
                            <div class="step_chunks more_chunks">
                                <div class="section_translator_chunk">
                                    <?php echo __("intro"); ?>                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    1 - 3                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    4 - 6                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    7 - 10                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    11 - 14                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    15 - 16                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    17 - 18                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    19 - 21                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    22 - 24                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    25 - 26                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    27 - 28                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    29 - 30                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="chunks_blind glyphicon glyphicon-triangle-bottom"></div>
                            </div>
                        </div>
                        <!-- Keyword Check Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/keyword-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::KEYWORD_CHECK."_tn"); ?></div>
                        </div>
                        <!-- Peer Check Step -->
                        <div class="section_step chk finished">
                            <div class="step_status">
                                <?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/peer-review.png")?>"></div>
                            <div class="step_name">5. <?php echo __(EventSteps::PEER_REVIEW."_tn"); ?></div>
                            <div class="step_checker">
                                <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                                <div>Walben S.</div>
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
                <div class="section_header" data="sec_12">
                    <div class="section_arrow glyphicon glyphicon-triangle-right"></div>
                    <div class="chapter_number section_title"><?php echo __("chapter_number", ["chapter" => 12]) ?></div>
                    <div class="section_translator_progress_bar">
                        <div class="progress ">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="min-width: 0em; width: 100%">
                                100%                        </div>
                        </div>
                        <div class="glyphicon glyphicon-ok finished_icon"></div>
                        <div class="clear"></div>
                    </div>
                    <div>
                        <span style="font-weight: bold;"><?php echo __("last_edit") .": " ?></span>
                        <span class="datetime" data="Fri, 17 Nov 2017 15:36:57 +0000">Fri, November 17, 2017, 10:36 AM</span>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="section_content">
                    <div class="section_translator">
                        <div class="section_translator_name tnleft">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                            <span><b>Epa H.</b></span>
                        </div>
                        <div class="section_translator_name tnright">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                            <span><b>Walben S.</b></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="section_steps">
                        <!-- Consume Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Blind Draft Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/blind-draft.png")?>"></div>
                            <div class="step_name">2-3. <?php echo __(EventSteps::BLIND_DRAFT); ?></div>
                            <div class="step_chunks more_chunks">
                                <div class="section_translator_chunk">
                                    <?php echo __("intro"); ?>                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    1 - 2                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    3 - 4                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    5 - 6                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    7 - 8                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    9 - 10                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    11 - 12                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    13 - 15                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    16 - 17                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    18 - 19                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    20 - 21                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    22 - 23                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    24 - 25                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="chunks_blind glyphicon glyphicon-triangle-bottom"></div>
                            </div>
                        </div>
                        <!-- Self Edit Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::SELF_CHECK); ?></div>
                        </div>

                        <!-- Checking stage -->
                        <div class="section_step chk">
                        </div>

                        <!-- Consume Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Highlight Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/highlight.png")?>"></div>
                            <div class="step_name">2. <?php echo __(EventSteps::HIGHLIGHT."_tn"); ?></div>
                        </div>
                        <!-- Self Check Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">3. <?php echo __(EventSteps::SELF_CHECK."_tn_chk"); ?></div>
                            <div class="step_chunks more_chunks">
                                <div class="section_translator_chunk">
                                    <?php echo __("intro"); ?>                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    1 - 2                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    3 - 4                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    5 - 6                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    7 - 8                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    9 - 10                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    11 - 12                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    13 - 15                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    16 - 17                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    18 - 19                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    20 - 21                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    22 - 23                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    24 - 25                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="chunks_blind glyphicon glyphicon-triangle-bottom"></div>
                            </div>
                        </div>
                        <!-- Keyword Check Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/keyword-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::KEYWORD_CHECK."_tn"); ?></div>
                        </div>
                        <!-- Peer Check Step -->
                        <div class="section_step chk finished">
                            <div class="step_status">
                                <?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/peer-review.png")?>"></div>
                            <div class="step_name">5. <?php echo __(EventSteps::PEER_REVIEW."_tn"); ?></div>
                            <div class="step_checker">
                                <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                                <div>Ketut S.</div>
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
                <div class="section_header" data="sec_13">
                    <div class="section_arrow glyphicon glyphicon-triangle-right"></div>
                    <div class="chapter_number section_title"><?php echo __("chapter_number", ["chapter" => 13]) ?></div>
                    <div class="section_translator_progress_bar">
                        <div class="progress ">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="51" aria-valuemin="0" aria-valuemax="100" style="min-width: 0em; width: 51%">
                                51%                        </div>
                        </div>
                        <div class=" finished_icon"></div>
                        <div class="clear"></div>
                    </div>
                    <div>
                        <span style="font-weight: bold;"><?php echo __("last_edit") .": " ?></span>
                        <span class="datetime" data="Fri, 17 Nov 2017 15:18:17 +0000">Fri, November 17, 2017, 10:18 AM</span>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="section_content">
                    <div class="section_translator">
                        <div class="section_translator_name tnleft">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                            <span><b>Dian N.</b></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="section_steps">
                        <!-- Consume Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Blind Draft Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/blind-draft.png")?>"></div>
                            <div class="step_name">2-3. <?php echo __(EventSteps::BLIND_DRAFT); ?></div>
                            <div class="step_chunks more_chunks">
                                <div class="section_translator_chunk">
                                    <?php echo __("intro"); ?>                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    1 - 3                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    4 - 5                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    6 - 8                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    9 - 10                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    11 - 12                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    13 - 15                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    16 - 18                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    19 - 20                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    21 - 22                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    23 - 25                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    26 - 27                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    28 - 29                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    30 - 31                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    32 - 34                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    35 - 37                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    38 - 39                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    40 - 41                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    42 - 43                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    44 - 45                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    46 - 47                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    48 - 49                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    50 - 52                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="chunks_blind glyphicon glyphicon-triangle-bottom"></div>
                            </div>
                        </div>
                        <!-- Self Edit Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::SELF_CHECK); ?></div>
                        </div>

                        <!-- Checking stage -->
                        <div class="section_step chk">
                        </div>

                        <!-- Consume Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Highlight Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/highlight.png")?>"></div>
                            <div class="step_name">2. <?php echo __(EventSteps::HIGHLIGHT."_tn"); ?></div>
                        </div>
                        <!-- Self Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">3. <?php echo __(EventSteps::SELF_CHECK."_tn_chk"); ?></div>
                        </div>
                        <!-- Keyword Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/keyword-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::KEYWORD_CHECK."_tn"); ?></div>
                        </div>
                        <!-- Peer Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status">
                                <?php echo __("step_status_".StepsStates::NOT_STARTED) ?>                        </div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/peer-review.png")?>"></div>
                            <div class="step_name">5. <?php echo __(EventSteps::PEER_REVIEW."_tn"); ?></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="chapter_item">
            <div class="chapter_accordion">
                <div class="section_header" data="sec_14">
                    <div class="section_arrow glyphicon glyphicon-triangle-right"></div>
                    <div class="chapter_number section_title"><?php echo __("chapter_number", ["chapter" => 14]) ?></div>
                    <div class="section_translator_progress_bar">
                        <div class="progress ">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="51" aria-valuemin="0" aria-valuemax="100" style="min-width: 0em; width: 51%">
                                51%                        </div>
                        </div>
                        <div class=" finished_icon"></div>
                        <div class="clear"></div>
                    </div>
                    <div>
                        <span style="font-weight: bold;"><?php echo __("last_edit") .": " ?></span>
                        <span class="datetime" data="Fri, 17 Nov 2017 10:22:50 +0000">Fri, November 17, 2017, 5:22 AM</span>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="section_content">
                    <div class="section_translator">
                        <div class="section_translator_name tnleft">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                            <span><b>Jesica K.</b></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="section_steps">
                        <!-- Consume Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Blind Draft Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/blind-draft.png")?>"></div>
                            <div class="step_name">2-3. <?php echo __(EventSteps::BLIND_DRAFT); ?></div>
                            <div class="step_chunks more_chunks">
                                <div class="section_translator_chunk">
                                    <?php echo __("intro"); ?>                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    1 - 2                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    3 - 4                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    5 - 7                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    8 - 10                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    11 - 13                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    14 - 16                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    17 - 18                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    19 - 20                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    21 - 22                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    23 - 26                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    27 - 28                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="chunks_blind glyphicon glyphicon-triangle-bottom"></div>
                            </div>
                        </div>
                        <!-- Self Edit Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::SELF_CHECK); ?></div>
                        </div>

                        <!-- Checking stage -->
                        <div class="section_step chk">
                        </div>

                        <!-- Consume Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Highlight Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/highlight.png")?>"></div>
                            <div class="step_name">2. <?php echo __(EventSteps::HIGHLIGHT."_tn"); ?></div>
                        </div>
                        <!-- Self Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">3. <?php echo __(EventSteps::SELF_CHECK."_tn_chk"); ?></div>
                        </div>
                        <!-- Keyword Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/keyword-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::KEYWORD_CHECK."_tn"); ?></div>
                        </div>
                        <!-- Peer Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status">
                                <?php echo __("step_status_".StepsStates::NOT_STARTED) ?>                        </div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/peer-review.png")?>"></div>
                            <div class="step_name">5. <?php echo __(EventSteps::PEER_REVIEW."_tn"); ?></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="chapter_item">
            <div class="chapter_accordion">
                <div class="section_header" data="sec_15">
                    <div class="section_arrow glyphicon glyphicon-triangle-right"></div>
                    <div class="chapter_number section_title"><?php echo __("chapter_number", ["chapter" => 15]) ?></div>
                    <div class="section_translator_progress_bar">
                        <div class="progress ">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="51" aria-valuemin="0" aria-valuemax="100" style="min-width: 0em; width: 51%">
                                51%                        </div>
                        </div>
                        <div class=" finished_icon"></div>
                        <div class="clear"></div>
                    </div>
                    <div>
                        <span style="font-weight: bold;"><?php echo __("last_edit") .": " ?></span>
                        <span class="datetime" data="Fri, 17 Nov 2017 10:31:02 +0000">Fri, November 17, 2017, 5:31 AM</span>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="section_content">
                    <div class="section_translator">
                        <div class="section_translator_name tnleft">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                            <span><b>Sarai E.</b></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="section_steps">
                        <!-- Consume Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Blind Draft Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/blind-draft.png")?>"></div>
                            <div class="step_name">2-3. <?php echo __(EventSteps::BLIND_DRAFT); ?></div>
                            <div class="step_chunks more_chunks">
                                <div class="section_translator_chunk">
                                    <?php echo __("intro"); ?>                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    1 - 2                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    3 - 4                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    5 - 6                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    7 - 9                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    10 - 11                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    12 - 12                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    13 - 14                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    15 - 18                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    19 - 21                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    22 - 23                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    24 - 26                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    27 - 29                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    30 - 32                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    33 - 35                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    36 - 38                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    39 - 41                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="chunks_blind glyphicon glyphicon-triangle-bottom"></div>
                            </div>
                        </div>
                        <!-- Self Edit Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::SELF_CHECK); ?></div>
                        </div>

                        <!-- Checking stage -->
                        <div class="section_step chk">
                        </div>

                        <!-- Consume Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Highlight Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/highlight.png")?>"></div>
                            <div class="step_name">2. <?php echo __(EventSteps::HIGHLIGHT."_tn"); ?></div>
                        </div>
                        <!-- Self Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">3. <?php echo __(EventSteps::SELF_CHECK."_tn_chk"); ?></div>
                        </div>
                        <!-- Keyword Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/keyword-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::KEYWORD_CHECK."_tn"); ?></div>
                        </div>
                        <!-- Peer Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status">
                                <?php echo __("step_status_".StepsStates::NOT_STARTED) ?>                        </div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/peer-review.png")?>"></div>
                            <div class="step_name">5. <?php echo __(EventSteps::PEER_REVIEW."_tn"); ?></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="chapter_item">
            <div class="chapter_accordion">
                <div class="section_header" data="sec_16">
                    <div class="section_arrow glyphicon glyphicon-triangle-right"></div>
                    <div class="chapter_number section_title"><?php echo __("chapter_number", ["chapter" => 16]) ?></div>
                    <div class="section_translator_progress_bar">
                        <div class="progress ">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="51" aria-valuemin="0" aria-valuemax="100" style="min-width: 0em; width: 51%">
                                51%                        </div>
                        </div>
                        <div class=" finished_icon"></div>
                        <div class="clear"></div>
                    </div>
                    <div>
                        <span style="font-weight: bold;"><?php echo __("last_edit") .": " ?></span>
                        <span class="datetime" data="Fri, 17 Nov 2017 12:43:43 +0000">Fri, November 17, 2017, 7:43 AM</span>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="section_content">
                    <div class="section_translator">
                        <div class="section_translator_name tnleft">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                            <span><b>Devi T.</b></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="section_steps">
                        <!-- Consume Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Blind Draft Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/blind-draft.png")?>"></div>
                            <div class="step_name">2-3. <?php echo __(EventSteps::BLIND_DRAFT); ?></div>
                            <div class="step_chunks more_chunks">
                                <div class="section_translator_chunk">
                                    <?php echo __("intro"); ?>                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    1 - 3                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    4 - 5                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    6 - 8                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    9 - 10                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    11 - 13                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    14 - 15                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    16 - 18                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    19 - 21                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    22 - 24                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    25 - 26                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    27 - 28                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    29 - 31                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    32 - 34                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    35 - 36                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    37 - 39                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    40 - 40                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="chunks_blind glyphicon glyphicon-triangle-bottom"></div>
                            </div>
                        </div>
                        <!-- Self Edit Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::SELF_CHECK); ?></div>
                        </div>

                        <!-- Checking stage -->
                        <div class="section_step chk">
                        </div>

                        <!-- Consume Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Highlight Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/highlight.png")?>"></div>
                            <div class="step_name">2. <?php echo __(EventSteps::HIGHLIGHT."_tn"); ?></div>
                        </div>
                        <!-- Self Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">3. <?php echo __(EventSteps::SELF_CHECK."_tn_chk"); ?></div>
                        </div>
                        <!-- Keyword Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/keyword-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::KEYWORD_CHECK."_tn"); ?></div>
                        </div>
                        <!-- Peer Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status">
                                <?php echo __("step_status_".StepsStates::NOT_STARTED) ?>                        </div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/peer-review.png")?>"></div>
                            <div class="step_name">5. <?php echo __(EventSteps::PEER_REVIEW."_tn"); ?></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="chapter_item">
            <div class="chapter_accordion">
                <div class="section_header" data="sec_17">
                    <div class="section_arrow glyphicon glyphicon-triangle-right"></div>
                    <div class="chapter_number section_title"><?php echo __("chapter_number", ["chapter" => 17]) ?></div>
                    <div class="section_translator_progress_bar">
                        <div class="progress ">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="51" aria-valuemin="0" aria-valuemax="100" style="min-width: 0em; width: 51%">
                                51%                        </div>
                        </div>
                        <div class=" finished_icon"></div>
                        <div class="clear"></div>
                    </div>
                    <div>
                        <span style="font-weight: bold;"><?php echo __("last_edit") .": " ?></span>
                        <span class="datetime" data="Fri, 17 Nov 2017 15:13:20 +0000">Fri, November 17, 2017, 10:13 AM</span>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="section_content">
                    <div class="section_translator">
                        <div class="section_translator_name tnleft">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                            <span><b>Ve K.</b></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="section_steps">
                        <!-- Consume Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Blind Draft Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/blind-draft.png")?>"></div>
                            <div class="step_name">2-3. <?php echo __(EventSteps::BLIND_DRAFT); ?></div>
                            <div class="step_chunks more_chunks">
                                <div class="section_translator_chunk">
                                    <?php echo __("intro"); ?>                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    1 - 2                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    3 - 4                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    5 - 7                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    8 - 9                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    10 - 12                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    13 - 15                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    16 - 17                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    18 - 18                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    19 - 21                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    22 - 23                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    24 - 25                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    26 - 27                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    28 - 29                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    30 - 31                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    32 - 34                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="chunks_blind glyphicon glyphicon-triangle-bottom"></div>
                            </div>
                        </div>
                        <!-- Self Edit Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::SELF_CHECK); ?></div>
                        </div>

                        <!-- Checking stage -->
                        <div class="section_step chk">
                        </div>

                        <!-- Consume Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Highlight Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/highlight.png")?>"></div>
                            <div class="step_name">2. <?php echo __(EventSteps::HIGHLIGHT."_tn"); ?></div>
                        </div>
                        <!-- Self Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">3. <?php echo __(EventSteps::SELF_CHECK."_tn_chk"); ?></div>
                        </div>
                        <!-- Keyword Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/keyword-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::KEYWORD_CHECK."_tn"); ?></div>
                        </div>
                        <!-- Peer Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status">
                                <?php echo __("step_status_".StepsStates::NOT_STARTED) ?>                        </div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/peer-review.png")?>"></div>
                            <div class="step_name">5. <?php echo __(EventSteps::PEER_REVIEW."_tn"); ?></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="chapter_item">
            <div class="chapter_accordion">
                <div class="section_header" data="sec_18">
                    <div class="section_arrow glyphicon glyphicon-triangle-right"></div>
                    <div class="chapter_number section_title"><?php echo __("chapter_number", ["chapter" => 18]) ?></div>
                    <div class="section_translator_progress_bar">
                        <div class="progress ">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="min-width: 0em; width: 100%">
                                100%                        </div>
                        </div>
                        <div class="glyphicon glyphicon-ok finished_icon"></div>
                        <div class="clear"></div>
                    </div>
                    <div>
                        <span style="font-weight: bold;"><?php echo __("last_edit") .": " ?></span>
                        <span class="datetime" data="Fri, 17 Nov 2017 15:05:29 +0000">Fri, November 17, 2017, 10:05 AM</span>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="section_content">
                    <div class="section_translator">
                        <div class="section_translator_name tnleft">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                            <span><b>Chris A.</b></span>
                        </div>
                        <div class="section_translator_name tnright">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                            <span><b>Jhonson S.</b></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="section_steps">
                        <!-- Consume Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Blind Draft Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/blind-draft.png")?>"></div>
                            <div class="step_name">2-3. <?php echo __(EventSteps::BLIND_DRAFT); ?></div>
                            <div class="step_chunks more_chunks">
                                <div class="section_translator_chunk">
                                    <?php echo __("intro"); ?>                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    1 - 3                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    4 - 6                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    7 - 8                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    9 - 11                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    12 - 13                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    14 - 15                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    16 - 17                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    18 - 19                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    20 - 21                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    22 - 23                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    24 - 26                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    27 - 28                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="chunks_blind glyphicon glyphicon-triangle-bottom"></div>
                            </div>
                        </div>
                        <!-- Self Edit Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::SELF_CHECK); ?></div>
                        </div>

                        <!-- Checking stage -->
                        <div class="section_step chk">
                        </div>

                        <!-- Consume Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Highlight Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/highlight.png")?>"></div>
                            <div class="step_name">2. <?php echo __(EventSteps::HIGHLIGHT."_tn"); ?></div>
                        </div>
                        <!-- Self Check Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">3. <?php echo __(EventSteps::SELF_CHECK."_tn_chk"); ?></div>
                            <div class="step_chunks more_chunks">
                                <div class="section_translator_chunk">
                                    <?php echo __("intro"); ?>                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    1 - 3                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    4 - 6                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    7 - 8                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    9 - 11                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    12 - 13                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    14 - 15                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    16 - 17                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    18 - 19                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    20 - 21                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    22 - 23                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    24 - 26                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    27 - 28                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="chunks_blind glyphicon glyphicon-triangle-bottom"></div>
                            </div>
                        </div>
                        <!-- Keyword Check Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/keyword-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::KEYWORD_CHECK."_tn"); ?></div>
                        </div>
                        <!-- Peer Check Step -->
                        <div class="section_step chk finished">
                            <div class="step_status">
                                <?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/peer-review.png")?>"></div>
                            <div class="step_name">5. <?php echo __(EventSteps::PEER_REVIEW."_tn"); ?></div>
                            <div class="step_checker">
                                <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                                <div>Walben S.</div>
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
                <div class="section_header" data="sec_19">
                    <div class="section_arrow glyphicon glyphicon-triangle-right"></div>
                    <div class="chapter_number section_title"><?php echo __("chapter_number", ["chapter" => 19]) ?></div>
                    <div class="section_translator_progress_bar">
                        <div class="progress ">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="51" aria-valuemin="0" aria-valuemax="100" style="min-width: 0em; width: 51%">
                                51%                        </div>
                        </div>
                        <div class=" finished_icon"></div>
                        <div class="clear"></div>
                    </div>
                    <div>
                        <span style="font-weight: bold;"><?php echo __("last_edit") .": " ?></span>
                        <span class="datetime" data="Fri, 17 Nov 2017 15:43:41 +0000">Fri, November 17, 2017, 10:43 AM</span>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="section_content">
                    <div class="section_translator">
                        <div class="section_translator_name tnleft">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                            <span><b>Ekle S.</b></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="section_steps">
                        <!-- Consume Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Blind Draft Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/blind-draft.png")?>"></div>
                            <div class="step_name">2-3. <?php echo __(EventSteps::BLIND_DRAFT); ?></div>
                            <div class="step_chunks more_chunks">
                                <div class="section_translator_chunk">
                                    <?php echo __("intro"); ?>                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    1 - 2                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    3 - 4                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    5 - 7                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    8 - 10                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    11 - 12                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    13 - 14                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    15 - 17                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    18 - 20                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    21 - 22                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    23 - 25                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    26 - 27                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    28 - 29                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    30 - 32                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    33 - 34                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    35 - 37                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    38 - 41                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="chunks_blind glyphicon glyphicon-triangle-bottom"></div>
                            </div>
                        </div>
                        <!-- Self Edit Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::SELF_CHECK); ?></div>
                        </div>

                        <!-- Checking stage -->
                        <div class="section_step chk">
                        </div>

                        <!-- Consume Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Highlight Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/highlight.png")?>"></div>
                            <div class="step_name">2. <?php echo __(EventSteps::HIGHLIGHT."_tn"); ?></div>
                        </div>
                        <!-- Self Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">3. <?php echo __(EventSteps::SELF_CHECK."_tn_chk"); ?></div>
                        </div>
                        <!-- Keyword Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/keyword-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::KEYWORD_CHECK."_tn"); ?></div>
                        </div>
                        <!-- Peer Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status">
                                <?php echo __("step_status_".StepsStates::NOT_STARTED) ?>                        </div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/peer-review.png")?>"></div>
                            <div class="step_name">5. <?php echo __(EventSteps::PEER_REVIEW."_tn"); ?></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="chapter_item">
            <div class="chapter_accordion">
                <div class="section_header" data="sec_20">
                    <div class="section_arrow glyphicon glyphicon-triangle-right"></div>
                    <div class="chapter_number section_title"><?php echo __("chapter_number", ["chapter" => 20]) ?></div>
                    <div class="section_translator_progress_bar">
                        <div class="progress ">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="91" aria-valuemin="0" aria-valuemax="100" style="min-width: 0em; width: 91%">
                                91%                        </div>
                        </div>
                        <div class=" finished_icon"></div>
                        <div class="clear"></div>
                    </div>
                    <div>
                        <span style="font-weight: bold;"><?php echo __("last_edit") .": " ?></span>
                        <span class="datetime" data="Tue, 21 Nov 2017 08:39:18 +0000">Tue, November 21, 2017, 3:39 AM</span>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="section_content">
                    <div class="section_translator">
                        <div class="section_translator_name tnleft">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                            <span><b>Sylvia T.</b></span>
                        </div>
                        <div class="section_translator_name tnright">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                            <span><b>Walben S.</b></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="section_steps">
                        <!-- Consume Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Blind Draft Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/blind-draft.png")?>"></div>
                            <div class="step_name">2-3. <?php echo __(EventSteps::BLIND_DRAFT); ?></div>
                            <div class="step_chunks more_chunks">
                                <div class="section_translator_chunk">
                                    <?php echo __("intro"); ?>                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    1 - 3                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    4 - 6                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    7 - 8                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    9 - 10                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    11 - 12                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    13 - 14                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    15 - 16                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    17 - 21                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    22 - 24                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    25 - 27                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    28 - 30                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    31 - 32                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    33 - 35                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    36 - 38                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="chunks_blind glyphicon glyphicon-triangle-bottom"></div>
                            </div>
                        </div>
                        <!-- Self Edit Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::SELF_CHECK); ?></div>
                        </div>

                        <!-- Checking stage -->
                        <div class="section_step chk">
                        </div>

                        <!-- Consume Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Highlight Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/highlight.png")?>"></div>
                            <div class="step_name">2. <?php echo __(EventSteps::HIGHLIGHT."_tn"); ?></div>
                        </div>
                        <!-- Self Check Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">3. <?php echo __(EventSteps::SELF_CHECK."_tn_chk"); ?></div>
                            <div class="step_chunks more_chunks">
                                <div class="section_translator_chunk">
                                    <?php echo __("intro"); ?>                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    1 - 3                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    4 - 6                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    7 - 8                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    9 - 10                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    11 - 12                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    13 - 14                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    15 - 16                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    17 - 21                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    22 - 24                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    25 - 27                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    28 - 30                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    31 - 32                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    33 - 35                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    36 - 38                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="chunks_blind glyphicon glyphicon-triangle-bottom"></div>
                            </div>
                        </div>
                        <!-- Keyword Check Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/keyword-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::KEYWORD_CHECK."_tn"); ?></div>
                        </div>
                        <!-- Peer Check Step -->
                        <div class="section_step chk waiting">
                            <div class="step_status">
                                <?php echo __("step_status_".StepsStates::WAITING) ?>                        </div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/peer-review.png")?>"></div>
                            <div class="step_name">5. <?php echo __(EventSteps::PEER_REVIEW."_tn"); ?></div>
                            <img class="img_waiting" src="https://id.v-mast.com/templates/default/assets/img/waiting.png">
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="chapter_item">
            <div class="chapter_accordion">
                <div class="section_header" data="sec_21">
                    <div class="section_arrow glyphicon glyphicon-triangle-right"></div>
                    <div class="chapter_number section_title"><?php echo __("chapter_number", ["chapter" => 21]) ?></div>
                    <div class="section_translator_progress_bar">
                        <div class="progress ">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="81" aria-valuemin="0" aria-valuemax="100" style="min-width: 0em; width: 81%">
                                81%                        </div>
                        </div>
                        <div class=" finished_icon"></div>
                        <div class="clear"></div>
                    </div>
                    <div>
                        <span style="font-weight: bold;"><?php echo __("last_edit") .": " ?></span>
                        <span class="datetime" data="Tue, 21 Nov 2017 10:33:01 +0000">Tue, November 21, 2017, 5:33 AM</span>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="section_content">
                    <div class="section_translator">
                        <div class="section_translator_name tnleft">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                            <span><b>Chris A.</b></span>
                        </div>
                        <div class="section_translator_name tnright">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                            <span><b>Jhonson S.</b></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="section_steps">
                        <!-- Consume Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Blind Draft Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/blind-draft.png")?>"></div>
                            <div class="step_name">2-3. <?php echo __(EventSteps::BLIND_DRAFT); ?></div>
                            <div class="step_chunks more_chunks">
                                <div class="section_translator_chunk">
                                    <?php echo __("intro"); ?>                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    1 - 2                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    3 - 4                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    5 - 6                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    7 - 9                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    10 - 11                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    12 - 14                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    15 - 16                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    17 - 19                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    20 - 21                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    22 - 24                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    25 - 26                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    27 - 29                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    30 - 31                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    32 - 33                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    34 - 36                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    37 - 38                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    39 - 40                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="chunks_blind glyphicon glyphicon-triangle-bottom"></div>
                            </div>
                        </div>
                        <!-- Self Edit Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::SELF_CHECK); ?></div>
                        </div>

                        <!-- Checking stage -->
                        <div class="section_step chk">
                        </div>

                        <!-- Consume Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Highlight Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/highlight.png")?>"></div>
                            <div class="step_name">2. <?php echo __(EventSteps::HIGHLIGHT."_tn"); ?></div>
                        </div>
                        <!-- Self Check Step -->
                        <div class="section_step chk in_progress">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::IN_PROGRESS) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">3. <?php echo __(EventSteps::SELF_CHECK."_tn_chk"); ?></div>
                            <div class="step_chunks more_chunks">
                                <div class="section_translator_chunk">
                                    <?php echo __("intro"); ?>                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    1 - 2                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    3 - 4                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    5 - 6                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    7 - 9                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    10 - 11                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    12 - 14                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    15 - 16                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    17 - 19                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    20 - 21                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    22 - 24                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    25 - 26                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    27 - 29                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    30 - 31                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    32 - 33                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    34 - 36                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    37 - 38                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    39 - 40                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="chunks_blind glyphicon glyphicon-triangle-bottom"></div>
                            </div>
                        </div>
                        <!-- Keyword Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/keyword-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::KEYWORD_CHECK."_tn"); ?></div>
                        </div>
                        <!-- Peer Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status">
                                <?php echo __("step_status_".StepsStates::NOT_STARTED) ?>                        </div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/peer-review.png")?>"></div>
                            <div class="step_name">5. <?php echo __(EventSteps::PEER_REVIEW."_tn"); ?></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="chapter_item">
            <div class="chapter_accordion">
                <div class="section_header" data="sec_22">
                    <div class="section_arrow glyphicon glyphicon-triangle-right"></div>
                    <div class="chapter_number section_title"><?php echo __("chapter_number", ["chapter" => 22]) ?></div>
                    <div class="section_translator_progress_bar">
                        <div class="progress ">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="min-width: 0em; width: 100%">
                                100%                        </div>
                        </div>
                        <div class="glyphicon glyphicon-ok finished_icon"></div>
                        <div class="clear"></div>
                    </div>
                    <div>
                        <span style="font-weight: bold;"><?php echo __("last_edit") .": " ?></span>
                        <span class="datetime" data="Fri, 17 Nov 2017 18:08:46 +0000">Fri, November 17, 2017, 1:08 PM</span>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="section_content">
                    <div class="section_translator">
                        <div class="section_translator_name tnleft">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                            <span><b>Niel N.</b></span>
                        </div>
                        <div class="section_translator_name tnright">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                            <span><b>Walben S.</b></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="section_steps">
                        <!-- Consume Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Blind Draft Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/blind-draft.png")?>"></div>
                            <div class="step_name">2-3. <?php echo __(EventSteps::BLIND_DRAFT); ?></div>
                            <div class="step_chunks more_chunks">
                                <div class="section_translator_chunk">
                                    <?php echo __("intro"); ?>                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    1 - 2                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    3 - 5                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    6 - 8                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    9 - 11                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    12 - 13                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    14 - 16                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    17 - 18                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    19 - 21                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    22 - 24                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    25 - 26                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    27 - 29                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    30 - 30                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="chunks_blind glyphicon glyphicon-triangle-bottom"></div>
                            </div>
                        </div>
                        <!-- Self Edit Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::SELF_CHECK); ?></div>
                        </div>

                        <!-- Checking stage -->
                        <div class="section_step chk">
                        </div>

                        <!-- Consume Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Highlight Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/highlight.png")?>"></div>
                            <div class="step_name">2. <?php echo __(EventSteps::HIGHLIGHT."_tn"); ?></div>
                        </div>
                        <!-- Self Check Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">3. <?php echo __(EventSteps::SELF_CHECK."_tn_chk"); ?></div>
                            <div class="step_chunks more_chunks">
                                <div class="section_translator_chunk">
                                    <?php echo __("intro"); ?>                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    1 - 2                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    3 - 5                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    6 - 8                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    9 - 11                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    12 - 13                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    14 - 16                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    17 - 18                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    19 - 21                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    22 - 24                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    25 - 26                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    27 - 29                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    30 - 30                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="chunks_blind glyphicon glyphicon-triangle-bottom"></div>
                            </div>
                        </div>
                        <!-- Keyword Check Step -->
                        <div class="section_step chk finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/keyword-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::KEYWORD_CHECK."_tn"); ?></div>
                        </div>
                        <!-- Peer Check Step -->
                        <div class="section_step chk finished">
                            <div class="step_status">
                                <?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/peer-review.png")?>"></div>
                            <div class="step_name">5. <?php echo __(EventSteps::PEER_REVIEW."_tn"); ?></div>
                            <div class="step_checker">
                                <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                                <div>Jhonson S.</div>
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
                <div class="section_header" data="sec_23">
                    <div class="section_arrow glyphicon glyphicon-triangle-right"></div>
                    <div class="chapter_number section_title"><?php echo __("chapter_number", ["chapter" => 23]) ?></div>
                    <div class="section_translator_progress_bar">
                        <div class="progress ">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="51" aria-valuemin="0" aria-valuemax="100" style="min-width: 0em; width: 51%">
                                51%                        </div>
                        </div>
                        <div class=" finished_icon"></div>
                        <div class="clear"></div>
                    </div>
                    <div>
                        <span style="font-weight: bold;"><?php echo __("last_edit") .": " ?></span>
                        <span class="datetime" data="Fri, 17 Nov 2017 16:51:24 +0000">Fri, November 17, 2017, 11:51 AM</span>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="section_content">
                    <div class="section_translator">
                        <div class="section_translator_name tnleft">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                            <span><b>Ririn B.</b></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="section_steps">
                        <!-- Consume Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Blind Draft Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/blind-draft.png")?>"></div>
                            <div class="step_name">2-3. <?php echo __(EventSteps::BLIND_DRAFT); ?></div>
                            <div class="step_chunks more_chunks">
                                <div class="section_translator_chunk">
                                    <?php echo __("intro"); ?>                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    1 - 3                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    4 - 5                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    6 - 8                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    9 - 10                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    11 - 11                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    12 - 13                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    14 - 15                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    16 - 17                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    18 - 19                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    20 - 21                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    22 - 24                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    25 - 27                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    28 - 30                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    31 - 33                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    34 - 35                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="chunks_blind glyphicon glyphicon-triangle-bottom"></div>
                            </div>
                        </div>
                        <!-- Self Edit Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::SELF_CHECK); ?></div>
                        </div>

                        <!-- Checking stage -->
                        <div class="section_step chk">
                        </div>

                        <!-- Consume Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Highlight Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/highlight.png")?>"></div>
                            <div class="step_name">2. <?php echo __(EventSteps::HIGHLIGHT."_tn"); ?></div>
                        </div>
                        <!-- Self Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">3. <?php echo __(EventSteps::SELF_CHECK."_tn_chk"); ?></div>
                        </div>
                        <!-- Keyword Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/keyword-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::KEYWORD_CHECK."_tn"); ?></div>
                        </div>
                        <!-- Peer Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status">
                                <?php echo __("step_status_".StepsStates::NOT_STARTED) ?>                        </div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/peer-review.png")?>"></div>
                            <div class="step_name">5. <?php echo __(EventSteps::PEER_REVIEW."_tn"); ?></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="chapter_item">
            <div class="chapter_accordion">
                <div class="section_header" data="sec_24">
                    <div class="section_arrow glyphicon glyphicon-triangle-right"></div>
                    <div class="chapter_number section_title"><?php echo __("chapter_number", ["chapter" => 24]) ?></div>
                    <div class="section_translator_progress_bar">
                        <div class="progress ">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="51" aria-valuemin="0" aria-valuemax="100" style="min-width: 0em; width: 51%">
                                51%                        </div>
                        </div>
                        <div class=" finished_icon"></div>
                        <div class="clear"></div>
                    </div>
                    <div>
                        <span style="font-weight: bold;"><?php echo __("last_edit") .": " ?></span>
                        <span class="datetime" data="Fri, 17 Nov 2017 14:57:10 +0000">Fri, November 17, 2017, 9:57 AM</span>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="section_content">
                    <div class="section_translator">
                        <div class="section_translator_name tnleft">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                            <span><b>Epa H.</b></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="section_steps">
                        <!-- Consume Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Blind Draft Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/blind-draft.png")?>"></div>
                            <div class="step_name">2-3. <?php echo __(EventSteps::BLIND_DRAFT); ?></div>
                            <div class="step_chunks more_chunks">
                                <div class="section_translator_chunk">
                                    <?php echo __("intro"); ?>                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    1 - 3                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    4 - 6                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    7 - 9                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    10 - 13                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    14 - 16                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    17 - 19                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    20 - 21                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    22 - 23                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    24 - 25                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    26 - 27                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="chunks_blind glyphicon glyphicon-triangle-bottom"></div>
                            </div>
                        </div>
                        <!-- Self Edit Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::SELF_CHECK); ?></div>
                        </div>

                        <!-- Checking stage -->
                        <div class="section_step chk">
                        </div>

                        <!-- Consume Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Highlight Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/highlight.png")?>"></div>
                            <div class="step_name">2. <?php echo __(EventSteps::HIGHLIGHT."_tn"); ?></div>
                        </div>
                        <!-- Self Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">3. <?php echo __(EventSteps::SELF_CHECK."_tn_chk"); ?></div>
                        </div>
                        <!-- Keyword Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/keyword-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::KEYWORD_CHECK."_tn"); ?></div>
                        </div>
                        <!-- Peer Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status">
                                <?php echo __("step_status_".StepsStates::NOT_STARTED) ?>                        </div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/peer-review.png")?>"></div>
                            <div class="step_name">5. <?php echo __(EventSteps::PEER_REVIEW."_tn"); ?></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="chapter_item">
            <div class="chapter_accordion">
                <div class="section_header" data="sec_25">
                    <div class="section_arrow glyphicon glyphicon-triangle-right"></div>
                    <div class="chapter_number section_title"><?php echo __("chapter_number", ["chapter" => 25]) ?></div>
                    <div class="section_translator_progress_bar">
                        <div class="progress ">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="51" aria-valuemin="0" aria-valuemax="100" style="min-width: 0em; width: 51%">
                                51%                        </div>
                        </div>
                        <div class=" finished_icon"></div>
                        <div class="clear"></div>
                    </div>
                    <div>
                        <span style="font-weight: bold;"><?php echo __("last_edit") .": " ?></span>
                        <span class="datetime" data="Fri, 17 Nov 2017 16:20:58 +0000">Fri, November 17, 2017, 11:20 AM</span>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="section_content">
                    <div class="section_translator">
                        <div class="section_translator_name tnleft">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                            <span><b>Popi P.</b></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="section_steps">
                        <!-- Consume Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Blind Draft Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/blind-draft.png")?>"></div>
                            <div class="step_name">2-3. <?php echo __(EventSteps::BLIND_DRAFT); ?></div>
                            <div class="step_chunks more_chunks">
                                <div class="section_translator_chunk">
                                    <?php echo __("intro"); ?>                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    1 - 3                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    4 - 5                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    6 - 8                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    9 - 10                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    11 - 12                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    13 - 16                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    17 - 20                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    21 - 22                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    23 - 24                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    25 - 27                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="chunks_blind glyphicon glyphicon-triangle-bottom"></div>
                            </div>
                        </div>
                        <!-- Self Edit Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::SELF_CHECK); ?></div>
                        </div>

                        <!-- Checking stage -->
                        <div class="section_step chk">
                        </div>

                        <!-- Consume Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Highlight Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/highlight.png")?>"></div>
                            <div class="step_name">2. <?php echo __(EventSteps::HIGHLIGHT."_tn"); ?></div>
                        </div>
                        <!-- Self Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">3. <?php echo __(EventSteps::SELF_CHECK."_tn_chk"); ?></div>
                        </div>
                        <!-- Keyword Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/keyword-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::KEYWORD_CHECK."_tn"); ?></div>
                        </div>
                        <!-- Peer Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status">
                                <?php echo __("step_status_".StepsStates::NOT_STARTED) ?>                        </div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/peer-review.png")?>"></div>
                            <div class="step_name">5. <?php echo __(EventSteps::PEER_REVIEW."_tn"); ?></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="chapter_item">
            <div class="chapter_accordion">
                <div class="section_header" data="sec_26">
                    <div class="section_arrow glyphicon glyphicon-triangle-right"></div>
                    <div class="chapter_number section_title"><?php echo __("chapter_number", ["chapter" => 26]) ?></div>
                    <div class="section_translator_progress_bar">
                        <div class="progress ">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="51" aria-valuemin="0" aria-valuemax="100" style="min-width: 0em; width: 51%">
                                51%                        </div>
                        </div>
                        <div class=" finished_icon"></div>
                        <div class="clear"></div>
                    </div>
                    <div>
                        <span style="font-weight: bold;"><?php echo __("last_edit") .": " ?></span>
                        <span class="datetime" data="Fri, 17 Nov 2017 16:40:05 +0000">Fri, November 17, 2017, 11:40 AM</span>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="section_content">
                    <div class="section_translator">
                        <div class="section_translator_name tnleft">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                            <span><b>Jesica K.</b></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="section_steps">
                        <!-- Consume Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Blind Draft Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/blind-draft.png")?>"></div>
                            <div class="step_name">2-3. <?php echo __(EventSteps::BLIND_DRAFT); ?></div>
                            <div class="step_chunks more_chunks">
                                <div class="section_translator_chunk">
                                    <?php echo __("intro"); ?>                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    1 - 3                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    4 - 5                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    6 - 8                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    9 - 11                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    12 - 14                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    15 - 18                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    19 - 21                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    22 - 23                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    24 - 26                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    27 - 29                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    30 - 32                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="chunks_blind glyphicon glyphicon-triangle-bottom"></div>
                            </div>
                        </div>
                        <!-- Self Edit Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::SELF_CHECK); ?></div>
                        </div>

                        <!-- Checking stage -->
                        <div class="section_step chk">
                        </div>

                        <!-- Consume Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Highlight Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/highlight.png")?>"></div>
                            <div class="step_name">2. <?php echo __(EventSteps::HIGHLIGHT."_tn"); ?></div>
                        </div>
                        <!-- Self Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">3. <?php echo __(EventSteps::SELF_CHECK."_tn_chk"); ?></div>
                        </div>
                        <!-- Keyword Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/keyword-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::KEYWORD_CHECK."_tn"); ?></div>
                        </div>
                        <!-- Peer Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status">
                                <?php echo __("step_status_".StepsStates::NOT_STARTED) ?>                        </div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/peer-review.png")?>"></div>
                            <div class="step_name">5. <?php echo __(EventSteps::PEER_REVIEW."_tn"); ?></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="chapter_item">
            <div class="chapter_accordion">
                <div class="section_header" data="sec_27">
                    <div class="section_arrow glyphicon glyphicon-triangle-right"></div>
                    <div class="chapter_number section_title"><?php echo __("chapter_number", ["chapter" => 27]) ?></div>
                    <div class="section_translator_progress_bar">
                        <div class="progress ">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="51" aria-valuemin="0" aria-valuemax="100" style="min-width: 0em; width: 51%">
                                51%                        </div>
                        </div>
                        <div class=" finished_icon"></div>
                        <div class="clear"></div>
                    </div>
                    <div>
                        <span style="font-weight: bold;"><?php echo __("last_edit") .": " ?></span>
                        <span class="datetime" data="Fri, 17 Nov 2017 16:00:46 +0000">Fri, November 17, 2017, 11:00 AM</span>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="section_content">
                    <div class="section_translator">
                        <div class="section_translator_name tnleft">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                            <span><b>Sarai E.</b></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="section_steps">
                        <!-- Consume Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Blind Draft Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/blind-draft.png")?>"></div>
                            <div class="step_name">2-3. <?php echo __(EventSteps::BLIND_DRAFT); ?></div>
                            <div class="step_chunks more_chunks">
                                <div class="section_translator_chunk">
                                    <?php echo __("intro"); ?>                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    1 - 2                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    3 - 6                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    7 - 8                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    9 - 11                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    12 - 13                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    14 - 16                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    17 - 18                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    19 - 20                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    21 - 22                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    23 - 26                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    27 - 29                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    30 - 32                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    33 - 35                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    36 - 38                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    39 - 41                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    42 - 44                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="chunks_blind glyphicon glyphicon-triangle-bottom"></div>
                            </div>
                        </div>
                        <!-- Self Edit Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::SELF_CHECK); ?></div>
                        </div>

                        <!-- Checking stage -->
                        <div class="section_step chk">
                        </div>

                        <!-- Consume Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Highlight Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/highlight.png")?>"></div>
                            <div class="step_name">2. <?php echo __(EventSteps::HIGHLIGHT."_tn"); ?></div>
                        </div>
                        <!-- Self Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">3. <?php echo __(EventSteps::SELF_CHECK."_tn_chk"); ?></div>
                        </div>
                        <!-- Keyword Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/keyword-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::KEYWORD_CHECK."_tn"); ?></div>
                        </div>
                        <!-- Peer Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status">
                                <?php echo __("step_status_".StepsStates::NOT_STARTED) ?>                        </div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/peer-review.png")?>"></div>
                            <div class="step_name">5. <?php echo __(EventSteps::PEER_REVIEW."_tn"); ?></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="chapter_item">
            <div class="chapter_accordion">
                <div class="section_header" data="sec_28">
                    <div class="section_arrow glyphicon glyphicon-triangle-right"></div>
                    <div class="chapter_number section_title"><?php echo __("chapter_number", ["chapter" => 28]) ?></div>
                    <div class="section_translator_progress_bar">
                        <div class="progress ">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="51" aria-valuemin="0" aria-valuemax="100" style="min-width: 0em; width: 51%">
                                51%                        </div>
                        </div>
                        <div class=" finished_icon"></div>
                        <div class="clear"></div>
                    </div>
                    <div>
                        <span style="font-weight: bold;"><?php echo __("last_edit") .": " ?></span>
                        <span class="datetime" data="Fri, 17 Nov 2017 14:24:13 +0000">Fri, November 17, 2017, 9:24 AM</span>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="section_content">
                    <div class="section_translator">
                        <div class="section_translator_name tnleft">
                            <img width="50" src="<?php echo template_url("img/avatars/n1.png")?>">
                            <span><b>Tirza T.</b></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="section_steps">
                        <!-- Consume Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Blind Draft Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/blind-draft.png")?>"></div>
                            <div class="step_name">2-3. <?php echo __(EventSteps::BLIND_DRAFT); ?></div>
                            <div class="step_chunks more_chunks">
                                <div class="section_translator_chunk">
                                    <?php echo __("intro"); ?>                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    1 - 2                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    3 - 4                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    5 - 6                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    7 - 10                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    11 - 12                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    13 - 15                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    16 - 18                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    19 - 20                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    21 - 22                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    23 - 24                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    25 - 26                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    27 - 27                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    28 - 29                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="section_translator_chunk">
                                    30 - 31                                    &nbsp;&nbsp;<span class="finished_msg glyphicon glyphicon-ok"></span>                                    </div>
                                <div class="chunks_blind glyphicon glyphicon-triangle-bottom"></div>
                            </div>
                        </div>
                        <!-- Self Edit Step -->
                        <div class="section_step finished">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::FINISHED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::SELF_CHECK); ?></div>
                        </div>

                        <!-- Checking stage -->
                        <div class="section_step chk">
                        </div>

                        <!-- Consume Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/consume.png")?>"></div>
                            <div class="step_name">1. <?php echo __(EventSteps::CONSUME."_tn"); ?></div>
                        </div>
                        <!-- Highlight Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/highlight.png")?>"></div>
                            <div class="step_name">2. <?php echo __(EventSteps::HIGHLIGHT."_tn"); ?></div>
                        </div>
                        <!-- Self Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/self-check.png")?>"></div>
                            <div class="step_name">3. <?php echo __(EventSteps::SELF_CHECK."_tn_chk"); ?></div>
                        </div>
                        <!-- Keyword Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status"><?php echo __("step_status_".StepsStates::NOT_STARTED) ?></div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/keyword-check.png")?>"></div>
                            <div class="step_name">4. <?php echo __(EventSteps::KEYWORD_CHECK."_tn"); ?></div>
                        </div>
                        <!-- Peer Check Step -->
                        <div class="section_step chk not_started">
                            <div class="step_status">
                                <?php echo __("step_status_".StepsStates::NOT_STARTED) ?>                        </div>
                            <div class="step_light"></div>
                            <div class="step_icon"><img width="40" src="<?php echo template_url("img/steps/icons/peer-review.png")?>"></div>
                            <div class="step_name">5. <?php echo __(EventSteps::PEER_REVIEW."_tn"); ?></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="members_list">
        <div class="members_title"><?php echo __("event_participants") ?>:</div>
        <div class="member_item" data="9">
            <span class="online_indicator glyphicon glyphicon-record">&nbsp;</span>
            <span class="member_uname">Walben S.</span>
            <span class="member_admin"> </span>
            <span class="online_status" style="display: inline;"><?php echo __("status_online") ?></span>
            <span class="offline_status" style="display: none;"><?php echo __("status_offline") ?></span>
        </div>
        <div class="member_item" data="10">
            <span class="online_indicator glyphicon glyphicon-record">&nbsp;</span>
            <span class="member_uname">Jhonson S.</span>
            <span class="member_admin"> </span>
            <span class="online_status" style="display: none;"><?php echo __("status_online") ?></span>
            <span class="offline_status"><?php echo __("status_offline") ?></span>
        </div>
        <div class="member_item" data="11">
            <span class="online_indicator glyphicon glyphicon-record">&nbsp;</span>
            <span class="member_uname">Ketut S.</span>
            <span class="member_admin"> </span>
            <span class="online_status" style="display: none;"><?php echo __("status_online") ?></span>
            <span class="offline_status"><?php echo __("status_offline") ?></span>
        </div>
        <div class="member_item" data="12">
            <span class="online_indicator glyphicon glyphicon-record">&nbsp;</span>
            <span class="member_uname">John C.</span>
            <span class="member_admin"> </span>
            <span class="online_status" style="display: inline;"><?php echo __("status_online") ?></span>
            <span class="offline_status" style="display: none;"><?php echo __("status_offline") ?></span>
        </div>
        <div class="member_item" data="29">
            <span class="online_indicator glyphicon glyphicon-record">&nbsp;</span>
            <span class="member_uname">Sylvia T.</span>
            <span class="member_admin"> </span>
            <span class="online_status" style="display: inline;"><?php echo __("status_online") ?></span>
            <span class="offline_status" style="display: none;"><?php echo __("status_offline") ?></span>
        </div>
        <div class="member_item" data="30">
            <span class="online_indicator glyphicon glyphicon-record">&nbsp;</span>
            <span class="member_uname">Lili J.</span>
            <span class="member_admin"> </span>
            <span class="online_status" style="display: inline;"><?php echo __("status_online") ?></span>
            <span class="offline_status" style="display: none;"><?php echo __("status_offline") ?></span>
        </div>
        <div class="member_item" data="31">
            <span class="online_indicator glyphicon glyphicon-record">&nbsp;</span>
            <span class="member_uname">Amanda L.</span>
            <span class="member_admin"> </span>
            <span class="online_status" style="display: none;"><?php echo __("status_online") ?></span>
            <span class="offline_status"><?php echo __("status_offline") ?></span>
        </div>
        <div class="member_item" data="32">
            <span class="online_indicator glyphicon glyphicon-record">&nbsp;</span>
            <span class="member_uname">Yosua A.</span>
            <span class="member_admin"> </span>
            <span class="online_status" style="display: inline;"><?php echo __("status_online") ?></span>
            <span class="offline_status" style="display: none;"><?php echo __("status_offline") ?></span>
        </div>
        <div class="member_item" data="33">
            <span class="online_indicator glyphicon glyphicon-record">&nbsp;</span>
            <span class="member_uname">Nana S.</span>
            <span class="member_admin"> </span>
            <span class="online_status" style="display: inline;"><?php echo __("status_online") ?></span>
            <span class="offline_status" style="display: none;"><?php echo __("status_offline") ?></span>
        </div>
        <div class="member_item" data="34">
            <span class="online_indicator glyphicon glyphicon-record">&nbsp;</span>
            <span class="member_uname">Ria W.</span>
            <span class="member_admin"> </span>
            <span class="online_status" style="display: none;"><?php echo __("status_online") ?></span>
            <span class="offline_status"><?php echo __("status_offline") ?></span>
        </div>
        <div class="member_item" data="35">
            <span class="online_indicator glyphicon glyphicon-record">&nbsp;</span>
            <span class="member_uname">Ririn B.</span>
            <span class="member_admin"> </span>
            <span class="online_status" style="display: inline;"><?php echo __("status_online") ?></span>
            <span class="offline_status" style="display: none;"><?php echo __("status_offline") ?></span>
        </div>
        <div class="member_item" data="36">
            <span class="online_indicator glyphicon glyphicon-record">&nbsp;</span>
            <span class="member_uname">Efelin O.</span>
            <span class="member_admin"> </span>
            <span class="online_status" style="display: inline;"><?php echo __("status_online") ?></span>
            <span class="offline_status" style="display: none;"><?php echo __("status_offline") ?></span>
        </div>
        <div class="member_item" data="37">
            <span class="online_indicator glyphicon glyphicon-record">&nbsp;</span>
            <span class="member_uname">Tirza T.</span>
            <span class="member_admin"> </span>
            <span class="online_status" style="display: inline;"><?php echo __("status_online") ?></span>
            <span class="offline_status" style="display: none;"><?php echo __("status_offline") ?></span>
        </div>
        <div class="member_item" data="38">
            <span class="online_indicator glyphicon glyphicon-record">&nbsp;</span>
            <span class="member_uname">Popi P.</span>
            <span class="member_admin"> </span>
            <span class="online_status" style="display: none;"><?php echo __("status_online") ?></span>
            <span class="offline_status"><?php echo __("status_offline") ?></span>
        </div>
        <div class="member_item" data="39">
            <span class="online_indicator glyphicon glyphicon-record">&nbsp;</span>
            <span class="member_uname">Maya S.</span>
            <span class="member_admin"> </span>
            <span class="online_status" style="display: none;"><?php echo __("status_online") ?></span>
            <span class="offline_status"><?php echo __("status_offline") ?></span>
        </div>
        <div class="member_item" data="40">
            <span class="online_indicator glyphicon glyphicon-record">&nbsp;</span>
            <span class="member_uname">Niel N.</span>
            <span class="member_admin"> </span>
            <span class="online_status" style="display: none;"><?php echo __("status_online") ?></span>
            <span class="offline_status"><?php echo __("status_offline") ?></span>
        </div>
        <div class="member_item" data="41">
            <span class="online_indicator glyphicon glyphicon-record">&nbsp;</span>
            <span class="member_uname">Epa H.</span>
            <span class="member_admin"> </span>
            <span class="online_status" style="display: none;"><?php echo __("status_online") ?></span>
            <span class="offline_status"><?php echo __("status_offline") ?></span>
        </div>
        <div class="member_item" data="42">
            <span class="online_indicator glyphicon glyphicon-record">&nbsp;</span>
            <span class="member_uname">Dian N.</span>
            <span class="member_admin"> </span>
            <span class="online_status" style="display: inline;"><?php echo __("status_online") ?></span>
            <span class="offline_status" style="display: none;"><?php echo __("status_offline") ?></span>
        </div>
        <div class="member_item" data="43">
            <span class="online_indicator glyphicon glyphicon-record">&nbsp;</span>
            <span class="member_uname">Jesica K.</span>
            <span class="member_admin"> </span>
            <span class="online_status" style="display: none;"><?php echo __("status_online") ?></span>
            <span class="offline_status"><?php echo __("status_offline") ?></span>
        </div>
        <div class="member_item" data="44">
            <span class="online_indicator glyphicon glyphicon-record">&nbsp;</span>
            <span class="member_uname">Sarai E.</span>
            <span class="member_admin"> </span>
            <span class="online_status" style="display: inline;"><?php echo __("status_online") ?></span>
            <span class="offline_status" style="display: none;"><?php echo __("status_offline") ?></span>
        </div>
        <div class="member_item" data="45">
            <span class="online_indicator glyphicon glyphicon-record">&nbsp;</span>
            <span class="member_uname">Devi T.</span>
            <span class="member_admin"> </span>
            <span class="online_status" style="display: none;"><?php echo __("status_online") ?></span>
            <span class="offline_status"><?php echo __("status_offline") ?></span>
        </div>
        <div class="member_item" data="46">
            <span class="online_indicator glyphicon glyphicon-record">&nbsp;</span>
            <span class="member_uname">Ve K.</span>
            <span class="member_admin"> </span>
            <span class="online_status" style="display: inline;"><?php echo __("status_online") ?></span>
            <span class="offline_status" style="display: none;"><?php echo __("status_offline") ?></span>
        </div>
        <div class="member_item" data="47">
            <span class="online_indicator glyphicon glyphicon-record">&nbsp;</span>
            <span class="member_uname">Chris A.</span>
            <span class="member_admin"> </span>
            <span class="online_status" style="display: none;"><?php echo __("status_online") ?></span>
            <span class="offline_status"><?php echo __("status_offline") ?></span>
        </div>
        <div class="member_item" data="48">
            <span class="online_indicator glyphicon glyphicon-record">&nbsp;</span>
            <span class="member_uname">Ekle S.</span>
            <span class="member_admin"> </span>
            <span class="online_status" style="display: none;"><?php echo __("status_online") ?></span>
            <span class="offline_status"><?php echo __("status_offline") ?></span>
        </div>
        <div class="member_item" data="2">
            <span class="online_indicator glyphicon glyphicon-record">&nbsp;</span>
            <span class="member_uname">Gloria G.</span>
            <span class="member_admin"> (<?php echo __("facilitator"); ?>)</span>
            <span class="online_status" style="display: inline;"><?php echo __("status_online") ?></span>
            <span class="offline_status" style="display: none;"><?php echo __("status_offline") ?></span>
        </div>
        <div class="member_item" data="3">
            <span class="online_indicator glyphicon glyphicon-record">&nbsp;</span>
            <span class="member_uname">Sisca F.</span>
            <span class="member_admin"> (<?php echo __("facilitator"); ?>)</span>
            <span class="online_status" style="display: none;"><?php echo __("status_online") ?></span>
            <span class="offline_status"><?php echo __("status_offline") ?></span>
        </div>
        <div class="member_item" data="4">
            <span class="online_indicator glyphicon glyphicon-record">&nbsp;</span>
            <span class="member_uname">Elika T.</span>
            <span class="member_admin"> (<?php echo __("facilitator"); ?>)</span>
            <span class="online_status" style="display: none;"><?php echo __("status_online") ?></span>
            <span class="offline_status"><?php echo __("status_offline") ?></span>
        </div>
        <div class="member_item" data="5">
            <span class="online_indicator glyphicon glyphicon-record">&nbsp;</span>
            <span class="member_uname">Vio S.</span>
            <span class="member_admin"> (<?php echo __("facilitator"); ?>)</span>
            <span class="online_status" style="display: none;"><?php echo __("status_online") ?></span>
            <span class="offline_status"><?php echo __("status_offline") ?></span>
        </div>
        <div class="member_item" data="8">
            <span class="online_indicator glyphicon glyphicon-record">&nbsp;</span>
            <span class="member_uname">Paula O.</span>
            <span class="member_admin"> (<?php echo __("facilitator"); ?>)</span>
            <span class="online_status" style="display: inline;"><?php echo __("status_online") ?></span>
            <span class="offline_status" style="display: none;"><?php echo __("status_offline") ?></span>
        </div>
        <div class="member_item" data="52">
            <span class="online_indicator glyphicon glyphicon-record">&nbsp;</span>
            <span class="member_uname">Maria K.</span>
            <span class="member_admin"> (<?php echo __("facilitator"); ?>)</span>
            <span class="online_status" style="display: inline;"><?php echo __("status_online") ?></span>
            <span class="offline_status" style="display: none;"><?php echo __("status_offline") ?></span>
        </div>
    </div>
</div>