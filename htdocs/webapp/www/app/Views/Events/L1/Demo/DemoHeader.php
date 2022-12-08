<?php
use Helpers\Constants\EventSteps;
use Helpers\Constants\InputMode;

$addUri = $data["inputMode"] != InputMode::NORMAL ? "-" . $data["inputMode"] : "";
?>

<div id="translator_steps" class="open pray <?php echo isset($data["isCheckerPage"]) ? " is_checker_page" : "" ?>">
    <div id="tr_steps_hide" class="glyphicon glyphicon-chevron-left pray <?php echo isset($data["isCheckerPage"]) ? " is_checker_page" : "" ?>"></div>

    <ul class="steps_list">
        <li class="pray-step <?php echo $data["step"] == EventSteps::PRAY ? "active" : "" ?>">
            <a href="/events/demo<?php echo $addUri ?>/pray"><span><?php echo __(EventSteps::PRAY) ?></span></a>
        </li>

        <?php if ($data["inputMode"] == InputMode::NORMAL): ?>
        <li class="consume-step <?php echo $data["step"] == EventSteps::CONSUME ? "active" : "" ?>">
            <a href="/events/demo/consume"><span><?php echo __(EventSteps::CONSUME) ?></span></a>
        </li>

        <li class="verbalize-step <?php echo $data["step"] == EventSteps::VERBALIZE ? "active" : "" ?>">
            <a href="/events/demo/verbalize"><span><?php echo __(EventSteps::VERBALIZE) ?></span></a>
        </li>

        <li class="chunking-step <?php echo $data["step"] == EventSteps::CHUNKING ? "active" : "" ?>">
            <a href="/events/demo/chunking"><span><?php echo __(EventSteps::CHUNKING) ?></span></a>
        </li>

        <li class="blind-draft-step <?php echo $data["step"] == EventSteps::READ_CHUNK ||
                $data["step"] == EventSteps::BLIND_DRAFT ? "active" : "" ?>">
            <a href="/events/demo/read_chunk"><span><?php echo __(EventSteps::BLIND_DRAFT) ?></span></a>
        </li>
        <?php endif; ?>

        <?php if (in_array($data["inputMode"], [InputMode::SCRIPTURE_INPUT, InputMode::SPEECH_TO_TEXT])): ?>
        <li class="content-review-step <?php echo $data["step"] == EventSteps::MULTI_DRAFT ? "active" : "" ?>">
            <a href="/events/demo<?php echo $addUri ?>/input"><span><?php echo __(EventSteps::MULTI_DRAFT."_input_mode")?></span></a>
        </li>
        <?php endif; ?>

        <li class="self-check-step <?php echo $data["step"] == EventSteps::SELF_CHECK ? "active" : "" ?>">
            <a href="/events/demo<?php echo $addUri ?>/self_check"><span><?php echo __(EventSteps::SELF_CHECK) ?></span></a>
        </li>

        <?php if (in_array($data["inputMode"], [InputMode::NORMAL, InputMode::SPEECH_TO_TEXT])): ?>
        <li class="peer-review-step <?php echo $data["step"] == EventSteps::PEER_REVIEW ? "active" : "" ?>">
            <a href="/events/demo<?php echo $addUri ?>/peer_review"><span><?php echo __(EventSteps::PEER_REVIEW) ?></span></a>
        </li>
        <?php endif; ?>

        <?php if ($data["inputMode"] == InputMode::NORMAL): ?>
        <li class="keyword-check-step <?php echo $data["step"] == EventSteps::KEYWORD_CHECK ? "active" : "" ?>">
            <a href="/events/demo/keyword_check"><span><?php echo __(EventSteps::KEYWORD_CHECK) ?></span></a>
        </li>

        <li class="content-review-step <?php echo $data["step"] == EventSteps::CONTENT_REVIEW ? "active" : "" ?>">
            <a href="/events/demo/content_review"><span><?php echo __(EventSteps::CONTENT_REVIEW) ?></span></a>
        </li>

        <li class="final-review-step <?php echo $data["step"] == EventSteps::FINAL_REVIEW ? "active" : "" ?>">
            <a href="/events/demo/final_review"><span><?php echo __(EventSteps::FINAL_REVIEW) ?></span></a>
        </li>
        <?php endif; ?>
    </ul>
</div>

<!-- Data for tools -->
<input type="hidden" id="bookCode" value="<?php echo $data["bookCode"] ?>">
<input type="hidden" id="chapter" value="<?php echo $data["currentChapter"] ?>">
<input type="hidden" id="tn_lang" value="<?php echo $data["tnLangID"] ?>">
<input type="hidden" id="tq_lang" value="<?php echo $data["tqLangID"] ?>">
<input type="hidden" id="tw_lang" value="<?php echo $data["twLangID"] ?>">
<input type="hidden" id="bc_lang" value="<?php echo $data["bcLangID"] ?>">
<input type="hidden" id="totalVerses" value="<?php echo $data["totalVerses"] ?>">
<input type="hidden" id="targetLang" value="<?php echo $data["targetLang"] ?>">

<?php
$isCheckPage = $data["step"] == EventSteps::PEER_REVIEW ||
    $data["step"] == EventSteps::KEYWORD_CHECK ||
    $data["step"] == EventSteps::CONTENT_REVIEW;
?>

<script>
    var memberID = 0;
    var eventID = 0;
    var chkMemberID = <?php echo $isCheckPage? "1" : "0"; ?>;
    var step = '<?php echo $data["step"]; ?>';
    var isDemo = true;
    var myChapter = 2;
    var tMode = "ulb";
    var inputMode = '<?php echo $data["inputMode"] ?>';
</script>

<div style="position: fixed; right: 0;">
</div>

<div id="chat_container" class="closed">
    <div id="chat_new_msgs" class="chat_new_msgs"></div>
    <div id="chat_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("chat") ?></div>

    <div class="chat panel panel-info">
        <div class="chat_tabs panel-heading">
            <div class="row">
                <div id="chk" class="col-sm-4 chat_tab">
                    <div>Marge S.</div>
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
                <div class="col-sm-4" style="text-align: right; padding: 2px 20px 0 0">
                    <div class="<?php echo !$isCheckPage ? "videoBtnHide" : "" ?>">
                        <button class="btn btn-success videoCallOpen videocall mdi mdi-camcorder" title="<?php echo __("video_call") ?>"></button>
                        <button class="btn btn-success videoCallOpen audiocall mdi mdi-phone" title="<?php echo __("audio_call") ?>"></button>
                    </div>
                </div>
            </div>
        </div>
        <ul id="chk_messages" class="chat_msgs">
            <li class="message msg_my" data="7">
                <div class="msg_name">You</div>
                <div data-original-title="04.07.2016, 17:36:45" class="msg_text" data-toggle="tooltip" data-placement="top" title="">This is chat tab for checking dialog</div>
            </li>
        </ul>
        <ul id="evnt_messages" class="chat_msgs">
            <li class="message msg_other" data="16">
                <div class="msg_name">Marge S.</div>
                <div data-original-title="30.06.2016, 18:38:09" class="msg_text" data-toggle="tooltip" data-placement="top" title="">Hi, this a test event message</div>
            </li>
            <li class="message msg_my" data="7">
                <div class="msg_name">You</div>
                <div data-original-title="01.07.2016, 18:22:02" class="msg_text" data-toggle="tooltip" data-placement="top" title="">Hi, this a test event message 2</div>
            </li>
        </ul>
        <ul id="proj_messages" class="chat_msgs">
            <li class="message msg_other" data="16">
                <div class="msg_name">Marge S.</div>
                <div data-original-title="30.06.2016, 18:38:09" class="msg_text" data-toggle="tooltip" data-placement="top" title="">Hi, this a test project message</div>
            </li>
            <li class="message msg_my" data="7">
                <div class="msg_name">You</div>
                <div data-original-title="01.07.2016, 18:22:02" class="msg_text" data-toggle="tooltip" data-placement="top" title="">Hi, this a test project message 2</div>
            </li>
        </ul>
        <form action="" class="form-inline">
            <div class="form-group">
                <textarea style="overflow: hidden; word-wrap: break-word; resize: horizontal; height: 54px;" id="m" class="form-control"></textarea>
                <input id="chat_type" value="evnt" type="hidden">
            </div>
        </form>
    </div>

    <div class="members_online panel panel-info">
        <div class="panel-heading"><?php echo __("members_online_title") ?></div>
        <ul id="online" class="panel-body">
            <li>Genry M.</li>
            <li>Mark P.</li>
            <li class="mine">Marge S. (<?php echo __("facilitator"); ?>)</li>
        </ul>
    </div>

    <div class="clear"></div>
</div>

<!-- Audio for missed chat messages -->
<audio id="missedMsg">
    <source src="<?php echo template_url("sounds/missed.ogg")?>" type="audio/ogg">
</audio>

<script src="<?php echo template_url("js/chat-plugin.js?v=6")?>"></script>

<script>
    (function($) {
        $("#chat_container").chat({
            step: step,
            chkMemberID: chkMemberID
        });
        $('[data-toggle="tooltip"]').tooltip();
    }(jQuery));
</script>

<?php echo $page ?? "" ?>
