<?php
use Helpers\Constants\EventSteps;
use Helpers\Constants\InputMode;
use Helpers\Session;
use Shared\Legacy\Error;

echo Error::display($error);

if(isset($data["success"]))
    echo Error::display($data["success"], "alert alert-success");

if($data["event"] && !isset($data["error"]) && $data["translator"]->step != EventSteps::FINISHED):
?>

<noscript>
    <div class="noscript">
        <?php echo __("noscript_message") ?>
    </div>
</noscript>

<div id="translator_steps" class="open <?php echo $data["translator"]->step . (isset($data["isCheckerPage"]) && $data["isCheckerPage"] ? " is_checker_page" : "") ?>">
    <div id="tr_steps_hide" class="glyphicon glyphicon-chevron-left <?php echo $data["translator"]->step . (isset($data["isCheckerPage"]) && $data["isCheckerPage"] ? " is_checker_page" : "") ?>"></div>

    <ul class="steps_list">
        <li class="pray-step <?php echo $data["translator"]->step == EventSteps::PRAY ? "active" : "" ?>">
            <span><?php echo __(EventSteps::PRAY)?></span>
        </li>

        <?php if (in_array($data["event"]->inputMode, [InputMode::SCRIPTURE_INPUT, InputMode::SPEECH_TO_TEXT])): ?>
        <li class="content-review-step <?php echo $data["translator"]->step == EventSteps::MULTI_DRAFT ? "active" : "" ?>">
            <span><?php echo __(EventSteps::MULTI_DRAFT."_input_mode")?></span>
        </li>
        <?php endif; ?>

        <?php if ($data["event"]->inputMode == InputMode::NORMAL): ?>
        <li class="consume-step <?php echo $data["translator"]->step == EventSteps::CONSUME ? "active" : "" ?>">
            <span><?php echo __(EventSteps::CONSUME)?></span>
        </li>

        <li class="verbalize-step <?php echo $data["translator"]->step == EventSteps::VERBALIZE ? "active" : "" ?>">
            <span><?php echo __(EventSteps::VERBALIZE)?></span>
        </li>

        <li class="chunking-step <?php echo $data["translator"]->step == EventSteps::CHUNKING ? "active" : "" ?>">
            <span><?php echo __(EventSteps::CHUNKING)?></span>
        </li>

        <li class="blind-draft-step <?php echo $data["translator"]->step == EventSteps::READ_CHUNK ||
            $data["translator"]->step == EventSteps::BLIND_DRAFT ? "active" : "" ?>">
            <span><?php echo __(EventSteps::BLIND_DRAFT)?></span>
        </li>
        <?php endif; ?>

        <li class="self-check-step <?php echo $data["translator"]->step == EventSteps::SELF_CHECK ? "active" : "" ?>">
            <span><?php echo __(EventSteps::SELF_CHECK)?></span>
        </li>

        <?php if (in_array($data["event"]->inputMode, [InputMode::NORMAL, InputMode::SPEECH_TO_TEXT])): ?>
        <li class="peer-review-step <?php echo $data["translator"]->step == EventSteps::PEER_REVIEW ? "active" : "" ?>">
            <span><?php echo __(EventSteps::PEER_REVIEW)?></span>
        </li>
        <?php endif; ?>

        <?php if ($data["event"]->inputMode == InputMode::NORMAL): ?>
        <li class="keyword-check-step <?php echo $data["translator"]->step == EventSteps::KEYWORD_CHECK ? "active" : "" ?>">
            <span><?php echo __(EventSteps::KEYWORD_CHECK)?></span>
        </li>

        <li class="content-review-step <?php echo $data["translator"]->step == EventSteps::CONTENT_REVIEW ? "active" : "" ?>">
            <span><?php echo __(EventSteps::CONTENT_REVIEW)?></span>
        </li>

        <li class="final-review-step <?php echo $data["translator"]->step == EventSteps::FINAL_REVIEW ? "active" : "" ?>">
            <span><?php echo __(EventSteps::FINAL_REVIEW)?></span>
        </li>
        <?php endif; ?>
    </ul>
</div>

<!-- Data for tools -->
<input type="hidden" id="bookCode" value="<?php echo $data["event"]->bookCode ?>">
<input type="hidden" id="chapter" value="<?php echo $data["translator"]->currentChapter ?>">
<input type="hidden" id="tn_lang" value="<?php echo $data["project"]->tnLangID ?>">
<input type="hidden" id="tq_lang" value="<?php echo $data["project"]->tqLangID ?>">
<input type="hidden" id="tw_lang" value="<?php echo $data["project"]->twLangID ?>">
<input type="hidden" id="bc_lang" value="<?php echo $data["project"]->bcLangID ?>">
<input type="hidden" id="totalVerses" value="<?php echo sizeof($data["text"]) ?>">
<input type="hidden" id="targetLang" value="<?php echo $data["project"]->targetLang ?>">

<script>
    let memberID = <?php echo Session::get('memberID') ;?>;
    let eventID = <?php echo $data["event"]->eventID; ?>;
    let projectID = <?php echo $data["event"]->projectID; ?>;
    let myChapter = <?php echo $data["translator"]->currentChapter; ?>;
    let myChunk = <?php echo $data["translator"]->currentChunk; ?>;
    let chkMemberID = <?php echo !$data["checkers"]->isEmpty() ? ($data["isCheckerPage"] ? $data["checkers"]->first()->translator->memberID : $data["checkers"]->first()->memberID) : 0; ?>;
    let isChecker = false;
    let aT = '<?php echo Session::get('authToken'); ?>';
    let step = '<?php echo $data["translator"]->step; ?>';
    let tMode = '<?php echo $data["project"]->bookProject ?>';
    let isAdmin = false;
    let disableChat = false;
    let turnUsername = '<?php echo isset($data["turn"]) ? $data["turn"][0] : "" ?>';
    let turnPassword = '<?php echo isset($data["turn"]) ? $data["turn"][1] : "" ?>';

</script>

<div id="chat_container" class="closed">
    <div id="chat_new_msgs" class="chat_new_msgs"></div>
    <div id="chat_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("chat") ?></div>

    <div class="chat panel panel-info">
        <div class="chat_tabs panel-heading">
            <div class="row">
                <div id="chk" class="col-sm-4 chat_tab">
                    <div class="chk_title">
                        <?php echo $data["checkerName"] ?: __("not_available") ?>
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
                    <div class="<?php echo $data["checkers"]->isEmpty() ? "videoBtnHide" : "" ?>">
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
<input type="hidden" id="evntid" value="<?php echo $data["event"] && $data["event"]->eventID ?>">

<?php endif; ?>

<?php echo $page ?? "" ?>
