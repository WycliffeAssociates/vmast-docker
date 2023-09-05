<?php

use Helpers\Constants\OdbSections;
use Helpers\Session;
use Helpers\Constants\EventSteps;
use Helpers\Constants\StepsStates;
use Shared\Legacy\Error;

echo Error::display($error);
if(!isset($error)):
    ?>
    <div class="back_link">
        <?php if(isset($_SERVER["HTTP_REFERER"])): ?>
            <span class="glyphicon glyphicon-chevron-left"></span>
            <a href="#" onclick="history.back(); return false;"><?php echo __("go_back") ?></a>
        <?php endif; ?>
    </div>

    <div>
        <div class="book_title"><?php echo $event->bookInfo->name ?></div>
        <div class="project_title">
            <?php echo __($event->project->bookProject)." - ".$event->project->targetLanguage->langName ?>
        </div>
        <div class="overall_progress_bar">
            <h3><?php echo __("progress_all") ?></h3>
            <div class="progress progress_all <?php echo $data["overall_progress"] <= 0 ? "zero" : ""?>">
                <div class="progress-bar progress-bar-success" role="progressbar"
                     aria-valuenow="<?php echo floor($data["overall_progress"]) ?>"
                     aria-valuemin="0" aria-valuemax="100" style="min-width: 0em; width: <?php echo floor($data["overall_progress"])."%" ?>">
                    <?php echo floor($data["overall_progress"])."%" ?>
                </div>
            </div>
        </div>
        <div class="clear"></div>
    </div>

    <div class="row" style="position:relative;">
        <div class="chapter_list">
            <?php foreach ($data["chapters"] as $key => $chapter):?>
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
                                    <span><b><?php echo $data["members"][$chapter["memberID"]]["name"] ?></b></span>
                                </div>
                                <?php if(isset($chapter["checkerID"])): ?>
                                    <div class="section_translator_name tnright">
                                        <img width="50" src="<?php echo template_url("img/avatars/n1.png") ?>">
                                        <span><b><?php echo $data["members"][$chapter["checkerID"]]["name"] ?></b></span>
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
        </div>

        <div class="members_list">
            <div class="members_title"><?php echo __("event_participants") ?>:</div>
            <?php foreach ($data["members"] as $id => $member): ?>
                <?php if(!is_numeric($id)) continue; ?>
                <div class="member_item" data="<?php echo $id ?>">
                    <span class="online_indicator glyphicon glyphicon-record">&nbsp;</span>
                    <span class="member_uname"><a target="_blank" href="/members/profile/<?php echo $id ?>"><?php echo $member["name"] ?></a></span>
                    <span class="member_admin"> <?php echo in_array($id, $data["admins"]) ? "(".__("facilitator").")" : "" ?></span>
                    <span class="online_status"><?php echo __("status_online") ?></span>
                    <span class="offline_status"><?php echo __("status_offline") ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        var memberID = <?php echo Session::get('memberID') ;?>;
        var eventID = <?php echo $event->eventID; ?>;
        var projectID = <?php echo $event->projectID; ?>;
        var chkMemberID = 0;
        var aT = '<?php echo Session::get('authToken'); ?>';
        var step = '';
        var isAdmin = <?php echo (integer)$data["isAdmin"]; ?>;
        var tMode = '<?php echo $event->project->bookProject ?>';
        var disableChat = true;
        var isChecker = false;
        var isInfoPage = true;
    </script>

    <?php if($data["isAdmin"]): ?>
    <div id="chat_container" class="closed info">
        <div id="chat_new_msgs" class="chat_new_msgs"></div>
        <div id="chat_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("chat") ?></div>

        <div class="chat panel panel-info">
            <div class="chat_tabs panel-heading">
                <div class="row">
                    <div id="evnt" class="col-sm-4 chat_tab">
                        <div><?php echo __("book") ?></div>
                        <div class="missed"></div>
                    </div>
                    <div id="proj" class="col-sm-4 chat_tab">
                        <div><?php echo __("project") ?></div>
                        <div class="missed"></div>
                    </div>
                </div>
            </div>
            <ul id="evnt_messages" class="chat_msgs info"></ul>
            <ul id="proj_messages" class="chat_msgs"></ul>
            <form action="" class="form-inline">
                <div class="form-group">
                    <textarea id="m" class="form-control"></textarea>
                    <input type="hidden" id="chat_type" value="evnt" />
                </div>
            </form>
        </div>

        <div class="members_online info panel panel-info">
            <div class="panel-heading">Members Online</div>
            <ul id="online" class="panel-body"></ul>
        </div>

        <div class="clear"></div>
    </div>

    <!-- Audio for missed chat messages -->
    <audio id="missedMsg">
        <source src="<?php echo template_url("sounds/missed.ogg")?>" type="audio/ogg" />
    </audio>
    <?php endif; ?>

    <!-- Audio for notifications -->
    <audio id="notif">
        <source src="<?php echo template_url("sounds/notif.ogg")?>" type="audio/ogg" />
    </audio>

    <script src="<?php echo template_url("js/socket.io.min.js")?>"></script>
    <script src="<?php echo template_url("js/chat-plugin.js?v=6")?>"></script>
    <script src="<?php echo template_url("js/socket.js?v=16")?>"></script>

<?php endif; ?>
