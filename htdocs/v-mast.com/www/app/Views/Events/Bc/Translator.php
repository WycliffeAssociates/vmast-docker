<?php
use Helpers\Constants\EventSteps;
use Helpers\Session;
use Shared\Legacy\Error;

echo Error::display($error);

if(isset($data["success"]))
    echo Error::display($data["success"], "alert alert-success");

if(!empty($data["event"]) && !isset($data["error"]) && $data["event"][0]->step != EventSteps::FINISHED):
?>

<noscript>
    <div class="noscript">
        <?php echo __("noscript_message") ?>
    </div>
</noscript>

<div id="translator_steps" 
    class="open <?php echo $data["event"][0]->step . 
        ($data["isCheckerPage"] ? " is_checker_page".
        ($data["isPeerPage"] ? " isPeer" : "") : "") ?>">
    <div id="tr_steps_hide" 
        class="glyphicon glyphicon-chevron-left <?php echo $data["event"][0]->step . 
            ($data["isCheckerPage"] ? " is_checker_page".
            ($data["isPeerPage"] ? " isPeer" : "") : "") ?>"></div>

    <ul class="steps_list">
        <li class="pray-step <?php echo $data["event"][0]->step == EventSteps::PRAY ? "active" : "" ?>">
            <span><?php echo __(EventSteps::PRAY)?></span>
        </li>
            
        <?php if(!$data["isCheckerPage"]): ?>
        <li class="multi-draft-step <?php echo $data["event"][0]->step == EventSteps::MULTI_DRAFT ? "active" : "" ?>">
            <span><?php echo __(EventSteps::MULTI_DRAFT)?></span>
        </li>

        <li class="self-check-step <?php echo $data["event"][0]->step == EventSteps::SELF_CHECK ? "active" : "" ?>">
            <span><?php echo __(EventSteps::SELF_CHECK)?></span>
        </li>
        <?php endif; ?>
                        
        <?php if($data["isCheckerPage"]): ?>
        <li class="keyword-check-step <?php echo $data["event"][0]->step == EventSteps::KEYWORD_CHECK ? "active" : "" ?>">
            <span><?php echo __(EventSteps::KEYWORD_CHECK)?></span>
        </li>

        <li class="peer-review-step <?php echo $data["event"][0]->step == EventSteps::PEER_REVIEW ? "active" : "" ?>">
            <span><?php echo __(EventSteps::PEER_REVIEW . "_bc")?></span>
        </li>
        <?php endif; ?>
    </ul>
</div>

<script>
    var memberID = <?php echo Session::get('memberID') ;?>;
    var eventMemberID = <?php echo isset($data["event"][0]->memberID) ? $data["event"][0]->memberID : $data["event"][0]->myMemberID; ?>;
    var eventID = <?php echo $data["event"][0]->eventID; ?>;
    var projectID = <?php echo $data["event"][0]->projectID; ?>;
    var myChapter = <?php echo $data["event"][0]->currentChapter; ?>;
    var myChunk = <?php echo $data["event"][0]->currentChunk; ?>;
    var chkMemberID = <?php echo isset($data["event"][0]->myMemberID) ? $data["event"][0]->checkerID : $data["event"][0]->memberID; ?>;
    var isChecker = false;
    var aT = '<?php echo Session::get('authToken'); ?>';
    var step = '<?php echo $data["event"][0]->step; ?>';
    var tMode = '<?php echo $data["event"][0]->bookProject ?>';
    var isAdmin = false;
    var disableChat = false;
    var turnUsername = '<?php echo isset($data["turn"]) ? $data["turn"][0] : "" ?>';
    var turnPassword = '<?php echo isset($data["turn"]) ? $data["turn"][1] : "" ?>';

</script>

<div id="chat_container" class="closed">
    <div id="chat_new_msgs" class="chat_new_msgs"></div>
    <div id="chat_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("chat") ?></div>

    <div class="chat panel panel-info">
        <div class="chat_tabs panel-heading">
            <div class="row">
                <div id="chk" class="col-sm-4 chat_tab">
                    <div class="chk_title">
                        <?php
                        echo isset($data["event"][0]->checkerFName) && $data["event"][0]->checkerFName !== null ?
                            $data["event"][0]->checkerFName . " " . mb_substr($data["event"][0]->checkerLName, 0, 1)."." :
                                (isset($data["event"][0]->firstName) && $data["event"][0]->firstName !== null ?
                                    $data["event"][0]->firstName . " " . mb_substr($data["event"][0]->lastName, 0, 1)."." : __("not_available"))
                        ?>
                    </div>
                    <div class="missed"></div>
                </div>
                <div id="evnt" class="col-sm-2 chat_tab active">
                    <div><?php echo __("book") ?></div>
                    <div class="missed"></div>
                </div>
                <div id="proj" class="col-sm-2 chat_tab">
                    <div><?php echo __("project") ?></div>
                    <div class="missed"></div>
                </div>
                <div class="col-sm-4" style="text-align: right; float: right; padding: 2px 20px 0 0">
                    <div class="<?php echo $data["event"][0]->checkerID <= 0 ? "videoBtnHide" : "" ?>">
                        <button class="btn btn-success videoCallOpen videocall mdi mdi-camcorder" title="<?php echo __("video_call") ?>"></button>
                        <button class="btn btn-success videoCallOpen audiocall mdi mdi-phone" title="<?php echo __("audio_call") ?>"></button>
                    </div>
                </div>
            </div>
        </div>
        <ul id="chk_messages" class="chat_msgs"></ul>
        <ul id="evnt_messages" class="chat_msgs"></ul>
        <ul id="proj_messages" class="chat_msgs"></ul>
        <form action="" class="form-inline">
            <div class="form-group">
                <textarea id="m" class="form-control"></textarea>
                <input type="hidden" id="chat_type" value="evnt" />
            </div>
        </form>
    </div>

    <div class="members_online panel panel-info">
        <div class="panel-heading"><?php echo __("members_online_title") ?></div>
        <ul id="online" class="panel-body"></ul>
    </div>

    <div class="clear"></div>
</div>

<div class="video_chat_container">
    <div class="video-chat-close glyphicon glyphicon-remove"></div>
    <div class="video_chat panel panel-info">
        <div class="panel-heading">
            <h1 class="panel-title"><?php echo __("video_call_title")?><span></span></h1>
            <span class="video-chat-close glyphicon glyphicon-remove"></span>
        </div>
        <div class="video">
            <video id="localVideo" muted autoplay width="160"></video>
            <video id="remoteVideo" autoplay ></video>

            <div class="buttons">
                <button class="btn btn-primary callButton mdi mdi-camcorder" id="cameraButton" title="<?php echo __("turn_off_camera") ?>"></button>
                <button class="btn btn-primary callButton mdi mdi-microphone" id="micButton" title="<?php echo __("mute_mic") ?>"></button>
                <button class="btn btn-success callButton mdi mdi-phone" id="answerButton" disabled="disabled" title="<?php echo __("answer_call") ?>"></button>
                <button class="btn btn-danger callButton mdi mdi-phone-hangup" id="hangupButton" disabled="disabled" title="<?php echo __("hangup") ?>"></button>
            </div>

            <div id="callLog"></div>
        </div>
    </div>
</div>

<!-- Audio for missed chat messages -->
<audio id="missedMsg">
    <source src="<?php echo template_url("sounds/missed.ogg")?>" type="audio/ogg" />
</audio>

<!-- Audio for notifications -->
<audio id="notif">
    <source src="<?php echo template_url("sounds/notif.ogg")?>" type="audio/ogg" />
</audio>

<!-- Audio for video calls -->
<audio id="callin">
    <source src="<?php echo template_url("sounds/callin.ogg")?>" type="audio/ogg" />
</audio>

<audio id="callout">
    <source src="<?php echo template_url("sounds/callout.ogg")?>" type="audio/ogg" />
</audio>

<script src="<?php echo template_url("js/socket.io.min.js")?>"></script>
<script src="<?php echo template_url("js/chat-plugin.js?v=6")?>"></script>
<script src="<?php echo template_url("js/socket.js?v=15")?>"></script>
<script src="<?php echo template_url("js/adapter-latest.js?v=3")?>"></script>
<script src="<?php echo template_url("js/video-chat.js?v=3")?>"></script>

<?php else:?>

<input type="hidden" id="evnt_state_checker" value="<?php echo isset($data["error"]) && $data["error"] === true ? "error" : "" ?>">
<input type="hidden" id="evntid" value="<?php echo !empty($data["event"]) && $data["event"][0]->eventID ?>">

<?php endif; ?>

<?php echo $page ?? "" ?>
