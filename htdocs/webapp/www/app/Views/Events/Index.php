<?php
use Helpers\Constants\EventStates;
use Helpers\Constants\EventSteps;
use Helpers\Constants\EventCheckSteps;
use Helpers\Constants\InputMode;
use Helpers\Session;
use Helpers\Tools;

?>

<div style="margin-bottom: 20px">
    <h1 class="demo_h"><?php echo __("vmast_events") ?></h1>
    <div class="demo_title events_index">
        <a href="#" class="demo_link"><?php echo __("demo")?></a>
        <span class="glyphicon glyphicon-chevron-right"></span>
        <div class="demo_options">
            <ul>
                <a href="/events/demo"><li><?php echo __("8steps_vmast") ?></li></a>
                <a href="/events/demo-scripture-input"><li><?php echo __("scripture-input") ?></li></a>
                <a href="/events/demo-speech-to-text"><li><?php echo __("speech-to-text") ?></li></a>
                <a href="/events/demo-revision"><li><?php echo __("revision_events"); ?></li></a>
                <a href="/events/demo-review"><li><?php echo __("review_events") ?></li></a>
                <a href="/events/demo-tn"><li><?php echo __("tn") ?></li></a>
                <!--<a href="/events/demo-tn-review"><li><?php /*echo __("tn_review"); */?></li></a>-->
                <a href="/events/demo-tq"><li><?php echo __("tq") ?></li></a>
                <a href="/events/demo-tw"><li><?php echo __("tw") ?></li></a>
                <a href="/events/demo-sun"><li><?php echo __("vsail") ?></li></a>
                <a href="/events/demo-sun-revision"><li><?php echo __("vsail_revision") ?></li></a>
                <a href="/events/demo-sun-review"><li><?php echo __("vsail_review") ?></li></a>
                <a href="/events/demo-sun-odb"><li><?php echo __("odb") . " (".__("vsail").")" ?></li></a>
                <a href="/events/demo-rad"><li><?php echo __("rad") ?></li></a>
                <a href="/events/demo-obs"><li><?php echo __("obs") ?></li></a>
                <a href="/events/demo-bc"><li><?php echo __("bc") ?></li></a>
                <a href="/events/demo-bca"><li><?php echo __("bca") ?></li></a>
            </ul>
        </div>
    </div>
</div>

<ul class="nav nav-tabs">
    <?php if(Session::get("isBookAdmin")): ?>
        <li role="presentation" id="my_facilitation" class="active my_tab">
            <a href="#"><?php echo __("manage") ?>
                <span>(<?php echo sizeof($data["myFacilitatorEventsInProgress"]) ?>)</span>
            </a>
        </li>
    <?php endif ?>

    <li role="presentation" id="my_translations" class="my_tab">
        <a href="#"><?php echo __("edit") ?>
            <span>(<?php echo sizeof($data["myTranslatorEvents"]) + sizeof($data["myRevisionEvents"])
                    + sizeof($data["myOtherEvents"]) ?>)</span>
        </a>
    </li>
    <li role="presentation" id="my_checks" class="my_tab">
        <a href="#"><?php echo __("check") ?>
            <span>(<?php echo sizeof($data["myCheckerL1Events"]) +
                    sizeof($data["myCheckerL2Events"]) + sizeof($data["myCheckerL3Events"])?>)</span>
        </a>
    </li>
</ul>

<?php if(Session::get("isBookAdmin")): ?>
    <div id="my_facilitation_content" class="my_content shown">
        <div class="clear"></div>

        <?php if(sizeof($data["myFacilitatorEventsInProgress"]) > 0): ?>
        <div class="events_separator"><?php echo __("events_in_progress") ?></div>
        <?php endif; ?>

        <?php foreach($data["myFacilitatorEventsInProgress"] as $key => $event): ?>
            <?php
            switch ($event->state)
            {
                case EventStates::L2_RECRUIT:
                case EventStates::L2_CHECK:
                case EventStates::L2_CHECKED:
                    $eventType = __("revision_events");
                    $mode = $event->project->bookProject;
                    $eventImg = template_url("img/steps/big/l2_check.png");
                    $logoBorderClass = "checkingl2";
                    $bgColor = "purple-marked";
                    $currentMembers = $event->checkersL2->count();
                    $members = __("checkers");
                    $manageLink = "/events/manage-revision/".$event->eventID;
                    $progressLink = "/events/information".($mode == "sun" ? "-sun" : "")."-revision/".$event->eventID;
                    break;

                case EventStates::L3_RECRUIT:
                case EventStates::L3_CHECK:
                    $eventType = __("review_events");
                    $mode = $event->project->bookProject;
                    $eventImg = template_url("img/steps/big/l2_check.png");
                    $logoBorderClass = "checkingl3";
                    $bgColor = "purple-marked";
                    $currentMembers = $event->checkersL3->count();
                    $members = __("checkers");
                    $manageLink = "/events/manage-review/".$event->eventID;
                    $progressLink = "/events/information".(!in_array($event->project->bookProject, ["ulb","udb"]) ? "-".$event->project->bookProject : "")."-review/".$event->eventID;
                    break;

                default:
                    $mode = $event->project->bookProject;
                    if(in_array($mode, ["ulb","udb"]))
                    {
                        $eventType = $event->inputMode == InputMode::NORMAL ? __("8steps_vmast") : __($event->inputMode);
                    }
                    elseif ($mode == "sun")
                    {
                        $eventType = $event->project->sourceBible == "odb" ? __("odb") : __("vsail");
                    }
                    else
                    {
                        $eventType = "";
                    }

                    if($event->inputMode != InputMode::NORMAL)
                    {
                        $eventImg = template_url("img/steps/big/consume.png");
                    }
                    elseif ($mode == "sun")
                    {
                        $eventImg = template_url("img/steps/big/vsail.png");
                    }
                    elseif ($mode == "rad")
                    {
                        $eventImg = template_url("img/steps/big/radio.png");
                    }
                    else
                    {
                        $eventImg = template_url("img/steps/big/peer-review.png");
                    }

                    $logoBorderClass = "translation";
                    $bgColor = "purple-marked";
                    $currentMembers = $event->translators->count();
                    $members = __("translators");
                    $manageLink = "/events/manage".($mode == "tw" ? "-tw" : ($mode == "bca" ? "-words" : ""))."/".$event->eventID;
                    $progressLink = "/events/information".
                        ($event->project->sourceBible == "odb" ? "-odb" : "").
                        (Tools::isHelpExtended($mode) ? "-".$mode : "").
                        "/".$event->eventID;
                    break;
            }
            ?>

            <div class="event_block <?php echo $key%2 == 0 ? $bgColor : "" ?>">
                <div class="event_logo <?php echo $logoBorderClass ?>">
                    <div class="event_type"><?php echo __($eventType) ?></div>
                    <div class="event_mode <?php echo $mode ?>"><?php echo __($mode) ?></div>
                    <div class="event_img">
                        <img width="146" src="<?php echo $eventImg ?>">
                    </div>
                </div>
                <div class="event_project">
                    <div class="event_book"><?php echo $event->bookInfo->name ?></div>
                    <div class="event_proj">
                        <div><?php echo $event->project->sourceBible == "odb"
                                ? __($event->project->sourceBible)
                                : __($event->project->bookProject) ?></div>
                        <div><?php echo $event->project->targetLanguage->langName .
                                (!in_array($event->project->bookProject, ["obs","tw","rad","odb","bca"]) ? ", " . ($event->bookInfo->sort < 41
                                        ? __("old_test")
                                        : __("new_test")) : "")?></div>
                    </div>
                    <div class="event_facilitator">

                    </div>
                </div>
                <div class="event_current_pos">
                    <div class="event_current_title"><?php echo __("state") ?></div>
                    <div class="event_curr_step">
                        <?php echo __("state_".$event->state) ?>
                    </div>
                </div>
                <div class="event_action">
                    <div class="event_manage_link"><a href="<?php echo $manageLink ?>"><?php echo __("manage") ?></a></div>
                    <div class="event_progress_link"><a href="<?php echo $progressLink ?>"><?php echo __("progress") ?></a></div>
                    <div class="event_members">
                        <div><?php echo $members ?></div>
                        <div class="trs_num"><?php echo $currentMembers ?></div>
                    </div>
                </div>

                <div class="clear"></div>
            </div>
        <?php endforeach; ?>

        <?php if(sizeof($data["myFacilitatorEventsFinished"]) > 0): ?>
            <div class="events_separator"><?php echo __("events_finished") ?></div>
        <?php endif; ?>

        <?php foreach($data["myFacilitatorEventsFinished"] as $key => $event): ?>
            <?php
            switch ($event->state)
            {
                case EventStates::L2_RECRUIT:
                case EventStates::L2_CHECK:
                case EventStates::L2_CHECKED:
                    $eventType = __("revision_events");
                    $mode = $event->project->bookProject;
                    $eventImg = template_url("img/steps/big/l2_check.png");
                    $logoBorderClass = "checkingl2";
                    $bgColor = "purple-marked";
                    $currentMembers = $event->checkersL2->count();
                    $members = __("checkers");
                    $manageLink = "/events/manage-revision/".$event->eventID;
                    $progressLink = "/events/information".($mode == "sun" ? "-sun" : "")."-revision/".$event->eventID;
                    break;

                case EventStates::L3_RECRUIT:
                case EventStates::L3_CHECK:
                    $eventType = __("review_events");
                    $mode = $event->project->bookProject;
                    $eventImg = template_url("img/steps/big/l2_check.png");
                    $logoBorderClass = "checkingl3";
                    $bgColor = "purple-marked";
                    $currentMembers = $event->checkersL3->count();
                    $members = __("checkers");
                    $manageLink = "/events/manage-review/".$event->eventID;
                    $progressLink = "/events/information"
                        .(!in_array($event->project->bookProject, ["ulb","udb"]) ? "-".$event->project->bookProject : "")
                        ."-review/".$event->eventID;
                    break;

                default:
                    $mode = $event->project->bookProject;

                    if(in_array($mode, ["ulb","udb"]))
                    {
                        $eventType = $event->inputMode == InputMode::NORMAL ? __("8steps_vmast") : __($event->inputMode);
                    }
                    elseif ($mode == "sun")
                    {
                        $eventType = $event->project->sourceBible == "odb" ? __("odb") : __("vsail");
                    }
                    else
                    {
                        $eventType = "";
                    }

                    if($event->inputMode != InputMode::NORMAL)
                    {
                        $eventImg = template_url("img/steps/big/consume.png");
                    }
                    elseif ($mode == "sun")
                    {
                        $eventImg = template_url("img/steps/big/vsail.png");
                    }
                    elseif ($mode == "rad")
                    {
                        $eventImg = template_url("img/steps/big/radio.png");
                    }
                    else
                    {
                        $eventImg = template_url("img/steps/big/peer-review.png");
                    }

                    $logoBorderClass = "translation";
                    $bgColor = "purple-marked";
                    $currentMembers = $event->translators->count();
                    $members = __("translators");
                    $manageLink = "/events/manage".($mode == "tw" ? "-tw" : ($mode == "bca" ? "-words" : ""))."/".$event->eventID;
                    $progressLink = "/events/information".
                        ($event->project->sourceBible == "odb" ? "-odb" : "").
                        (Tools::isHelpExtended($mode) ? "-".$mode : "").
                        "/".$event->eventID;
                    break;
            }
            ?>

            <div class="event_block <?php echo $key%2 == 0 ? $bgColor : "" ?>">
                <div class="event_logo <?php echo $logoBorderClass ?>">
                    <div class="event_type"><?php echo __($eventType) ?></div>
                    <div class="event_mode <?php echo $mode ?>"><?php echo __($mode) ?></div>
                    <div class="event_img">
                        <img width="146" src="<?php echo $eventImg ?>">
                    </div>
                </div>
                <div class="event_project">
                    <div class="event_book"><?php echo $event->bookInfo->name ?></div>
                    <div class="event_proj">
                        <div><?php echo $event->project->sourceBible == "odb" ? __($event->project->sourceBible) : __($event->project->bookProject) ?></div>
                        <div><?php echo $event->project->targetLanguage->langName .
                                (Tools::isScripture($event->bookProject) ? ", " . ($event->bookInfo->sort < 41
                                        ? __("old_test")
                                        : __("new_test")) : "")?></div>
                    </div>
                    <div class="event_facilitator"></div>
                </div>
                <div class="event_current_pos">
                    <div class="event_current_title"><?php echo __("state") ?></div>
                    <div class="event_curr_step">
                        <?php echo __("state_".$event->state) ?>
                    </div>
                </div>
                <div class="event_action">
                    <div class="event_manage_link"><a href="<?php echo $manageLink ?>"><?php echo __("manage") ?></a></div>
                    <div class="event_progress_link"><a href="<?php echo $progressLink ?>"><?php echo __("progress") ?></a></div>
                    <div class="event_members">
                        <div><?php echo $members ?></div>
                        <div class="trs_num"><?php echo $currentMembers ?></div>
                    </div>
                </div>

                <div class="clear"></div>
            </div>
        <?php endforeach; ?>

        <?php if(sizeof($data["myFacilitatorEventsInProgress"]) <= 0 && sizeof($data["myFacilitatorEventsFinished"]) <= 0): ?>
            <div class="no_events_message"><?php echo __("no_events_message") ?></div>
        <?php endif; ?>
    </div>
<?php endif ?>

<div id="my_translations_content" class="my_content">
    <?php foreach($data["myTranslatorEvents"] as $key => $translator): ?>
        <?php
        $project = $translator->event->project;
        $event = $translator->event;
        $mode = $project->bookProject;

        if(in_array($mode, ["ulb","udb"])) {
            $eventType = $event->inputMode == InputMode::NORMAL ? __("8steps_vmast") : __($event->inputMode);
        } elseif ($mode == "sun") {
            $eventType = $project->sourceBible == "odb" ? __("odb") : __("vsail");
        } else {
            $eventType = "";
        }

        if($event->inputMode != InputMode::NORMAL) {
            $eventImg = template_url("img/steps/big/consume.png");
        } elseif ($mode == "sun") {
            $eventImg = template_url("img/steps/big/vsail.png");
        } elseif ($mode == "rad") {
            $eventImg = template_url("img/steps/big/radio.png");
        } else {
            $eventImg = template_url("img/steps/big/peer-review.png");
        }

        $wordsGroup = isset($event->words) ? json_decode($event->words, true) : null;
        $word = $event->word ?? null;
        ?>
        <div class="event_block <?php echo $key%2 == 0 ? "green-marked" : "" ?>">
            <div class="event_logo translation">
                <div class="event_type"><?php echo $eventType ?></div>
                <div class="event_mode <?php echo $project->bookProject ?>"><?php echo __($project->bookProject) ?></div>
                <div class="event_img">
                    <img width="146" src="<?php echo $eventImg?>">
                </div>
            </div>
            <div class="event_project">
                <div class="event_book"><?php echo $event->bookInfo->name ?></div>
                <div class="event_proj">
                    <div><?php echo $project->sourceBible == "odb" ? __($project->sourceBible) : __($project->bookProject) ?></div>
                    <div><?php echo $project->targetLanguage->langName .
                            (Tools::isScripture($project->bookProject) ? ", " . ($event->bookInfo->sort < 41
                                    ? __("old_test")
                                    : __("new_test")) : "")?></div>
                </div>
                <div class="event_facilitator">
                    <div><?php echo __("facilitators") ?>:</div>
                    <div class="facil_names">
                        <?php foreach ($event->admins as $admin): ?>
                            <a href="#" data="<?php echo $admin->memberID ?>">
                                <?php echo $admin->firstName . " " . mb_substr($admin->lastName, 0, 1) . "."?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="event_current_pos">
                <div class="event_current_title"><?php echo __("you_are_at") ?></div>
                <div class="event_curr_step">
                    <?php
                    $step = $translator->step;
                    if($step == EventSteps::READ_CHUNK)
                        $step = EventSteps::BLIND_DRAFT;
                    ?>
                    <img class='img_current' src="<?php echo template_url("img/steps/green_icons/". $step. ".png") ?>">
                    <div class="step_current">
                        <div>
                            <?php echo ($translator->currentChapter > 0
                                ? ($wordsGroup
                                    ? "[".$wordsGroup[0]."...".$wordsGroup[sizeof($wordsGroup)-1]."]"
                                    : ($word ?: __("chapter_number", ["chapter" => $translator->currentChapter])))
                                : ($translator->currentChapter == 0 && $project->bookProject == "tn"
                                    ? __("front")
                                    : "")) ?>
                        </div>
                        <div>
                            <?php echo __($translator->step . ($project->bookProject == "tn" && $translator->step != EventSteps::PRAY ? "_tn" :
                                    ($project->bookProject == "sun" && $translator->step == EventSteps::CHUNKING ? "_sun" : ""))) ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="event_action">
                <div class="event_link">
                    <?php
                    $chapterLink = in_array($translator->step, [
                            EventSteps::PEER_REVIEW,
                            EventSteps::KEYWORD_CHECK,
                            EventSteps::CONTENT_REVIEW,
                            EventSteps::FINAL_REVIEW,
                    ])
                        && in_array($project->bookProject, ["ulb","udb"])
                        && $translator->currentChapter > 0
                            ? "/" . $translator->currentChapter : "";
                    ?>
                    <a href="/events/translator<?php echo ($project->sourceBible == "odb" ? "-odb" : "")
                            .(Tools::isHelpExtended($project->bookProject) ? "-".
                            $project->bookProject : "") ?>/<?php echo $event->eventID . $chapterLink?>">
                        <?php echo __("continue") ?>
                    </a>
                </div>
                <div class="event_members">
                </div>
            </div>
        </div>
    <?php endforeach ?>

    <?php foreach($data["myOtherEvents"] as $key => $event): ?>
        <?php
        $mode = $event->bookProject;

        if(in_array($mode, ["ulb","udb"])) {
            $eventType = $event->inputMode == InputMode::NORMAL ? __("8steps_vmast") : __($event->inputMode);
        } elseif ($mode == "sun") {
            $eventType = $event->sourceBible == "odb" ? __("odb") : __("vsail");
        } else {
            $eventType = "";
        }

        if ($mode == "sun") {
            $eventImg = template_url("img/steps/big/vsail.png");
        } elseif ($mode == "rad") {
            $eventImg = template_url("img/steps/big/radio.png");
        } else {
            $eventImg = template_url("img/steps/big/peer-review.png");
        }

        $wordsGroup = isset($event->words) ? json_decode($event->words, true) : null;
        $word = $event->word ?? null;
        ?>
        <div class="event_block <?php echo $key%2 == 0 ? "gray-marked" : "" ?>">
            <div class="event_logo checking">
                <div class="event_type">
                    <div class="event_mode <?php echo $event->bookProject ?>"><?php echo __($event->bookProject) ?></div>
                    <?php $chk = $event->bookProject == "tn" ?>
                    <?php $add = $event->bookProject == "tn" && $event->step != EventSteps::PRAY ? "_tn" : ""; ?>
                    <?php $add = $event->step == EventSteps::SELF_CHECK && $chk ? $add."_chk" : $add; ?>
                    <?php $add .= $event->sourceBible == "odb" ? "_odb" : "" ?>
                    <div><?php echo __($event->step . $add) ?></div>
                </div>
                <div class="event_img">
                    <img width="85" src="<?php echo $eventImg ?>">
                </div>
            </div>
            <div class="event_project">
                <div class="event_book"><?php echo $event->bookName ?? $event->name ?></div>
                <div class="event_proj">
                    <div><?php echo $event->sourceBible == "odb" ? __($event->sourceBible) : __($event->bookProject) ?></div>
                    <div><?php echo $event->tLang . ", " . ($event->sort < 41 ? __("old_test") : __("new_test"))?></div>
                </div>
                <div class="event_facilitator">
                    <div><?php echo __("facilitators") ?>:</div>
                    <div class="facil_names">
                        <?php foreach ($event->admins as $admin): ?>
                            <a href="#" data="<?php echo $admin->memberID ?>"><?php echo $admin->firstName . " " . mb_substr($admin->lastName, 0, 1) . "." ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="event_translator">
                <div class="event_translator_data">
                    <div class="event_translator_title"><?php echo __("translator") ?></div>
                    <div class="event_translator_name"><?php echo $event->firstName . " " . mb_substr($event->lastName, 0, 1)."." ?></div>
                </div>
            </div>
            <div class="event_current_pos">
                <div class="event_current_title"><?php echo __("you_are_at") ?></div>
                <div class="event_curr_step">
                    <?php
                    $step = $event->step;
                    if($step == EventSteps::READ_CHUNK)
                        $step = EventSteps::BLIND_DRAFT;
                    ?>
                    <img class='img_current' src="<?php echo template_url("img/steps/green_icons/". $step. ".png") ?>">
                    <div class="step_current">
                        <div>
                            <?php echo ($event->currentChapter > 0
                                ? ($wordsGroup ?
                                    "[".$wordsGroup[0]."...".$wordsGroup[sizeof($wordsGroup)-1]."] "
                                    : ($word ?: __("chapter_number", ["chapter" => $event->currentChapter])))
                                : __("front")) ?>
                        </div>
                        <div>
                            <?php echo __($event->step.($event->sourceBible == "odb" ? "_odb" : "")) ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="event_action">
                <div class="event_link">
                    <a href="/events/checker<?php echo ($event->sourceBible == "odb" ? "-odb" : "")
                        .(Tools::isHelpExtended($event->bookProject) ? "-".$event->bookProject : "")
                        ."/".$event->eventID."/".$event->memberID
                        .(isset($event->isContinue) || in_array($event->bookProject, ["tq","tw","ulb","udb"]) ? "/".$event->currentChapter : "")?>"
                       data="<?php echo $event->eventID."_".$event->memberID?>">
                        <?php echo __("continue") ?>
                    </a>
                </div>
            </div>

            <div class="clear"></div>
        </div>
    <?php endforeach ?>

    <?php foreach($data["myRevisionEvents"] as $key => $event): ?>
        <?php
        $mode = $event->bookProject;
        $memberLink = $event->memberID != Session::get("memberID") ? "/" . $event->memberID : "";
        $chapterLink = in_array($event->step, [
            EventCheckSteps::PEER_REVIEW,
            EventCheckSteps::KEYWORD_CHECK,
            EventCheckSteps::CONTENT_REVIEW
        ])
        && $event->currentChapter > 0
            ? "/" . $event->currentChapter : "";

        $link = "/events/checker"
            .($mode == "sun" ? "-sun" : "")
            ."-revision/"
            . $event->eventID
            .$memberLink
            .$chapterLink;
        ?>
        <div class="event_block <?php echo $key%2 == 0 ? "lemon-marked" : "" ?>">
            <div class="event_logo checkingl2">
                <div class="event_type"><?php echo __("revision_events") ?></div>
                <div class="event_mode <?php echo $event->bookProject ?>"><?php echo __($event->bookProject) ?></div>
                <div class="event_img">
                    <img width="146" src="<?php echo template_url("img/steps/big/l2_check.png") ?>">
                </div>
            </div>
            <div class="event_project">
                <div class="event_book"><?php echo $event->name ?></div>
                <div class="event_proj">
                    <div><?php echo __($event->bookProject) ?></div>
                    <div><?php echo $event->tLang . ", " . ($event->sort < 41 ? __("old_test") : __("new_test"))?></div>
                </div>
                <div class="event_facilitator">
                    <div><?php echo __("facilitators") ?>:</div>
                    <div class="facil_names">
                        <?php foreach ($event->admins as $admin): ?>
                            <a href="#" data="<?php echo $admin->memberID ?>"><?php echo $admin->firstName . " " . mb_substr($admin->lastName, 0, 1) . "." ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="event_current_pos">
                <?php if($event->step != EventCheckSteps::NONE): ?>
                    <div class="event_current_title"><?php echo __("you_are_at") ?></div>
                    <div class="event_curr_step">
                        <img class='img_current' src="<?php echo template_url("img/steps/green_icons/". $event->step. ".png") ?>">
                        <div class="step_current">
                            <div>
                                <?php echo ($event->currentChapter > 0 ? __("chapter_number",
                                    ["chapter" => $event->currentChapter]) : "") ?>
                            </div>
                            <div>
                                <?php
                                $add = "";
                                if ($event->bookProject == "sun"
                                    && in_array($event->step, [EventCheckSteps::SELF_CHECK, EventCheckSteps::PEER_REVIEW])) {
                                    $add = "_sun";
                                }
                                ?>
                                <?php echo __($event->step.$add) ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="event_action check2">
                <div class="event_link">
                    <a href="<?php echo $link ?>"><?php echo __("continue") ?></a>
                </div>
            </div>

            <div class="clear"></div>
        </div>
    <?php endforeach ?>

    <?php if((sizeof($data["myTranslatorEvents"]) + sizeof($data["myRevisionEvents"])) <= 0): ?>
        <div class="no_events_message"><?php echo __("no_events_message") ?></div>
    <?php endif; ?>
</div>

<div id="my_checks_content" class="my_content">
    <?php foreach($data["myCheckerL1Events"] as $key => $event): ?>
        <?php
        $mode = $event->bookProject;

        if(in_array($mode, ["ulb","udb"])) {
            $eventType = $event->inputMode == InputMode::NORMAL ? __("8steps_vmast") : __($event->inputMode);
        } elseif ($mode == "sun") {
            $eventType = $event->sourceBible == "odb" ? __("odb") : __("vsail");
        } else {
            $eventType = "";
        }

        if ($mode == "sun") {
            $eventImg = template_url("img/steps/big/vsail.png");
        } elseif ($mode == "rad") {
            $eventImg = template_url("img/steps/big/radio.png");
        } else {
            $eventImg = template_url("img/steps/big/peer-review.png");
        }

        $wordsGroup = isset($event->words) ? json_decode($event->words, true) : null;
        $word = $event->word ?? null;
        ?>
        <div class="event_block <?php echo $key%2 == 0 ? "gray-marked" : "" ?>">
            <div class="event_logo checking">
                <div class="event_type">
                    <div class="event_mode <?php echo $event->bookProject ?>"><?php echo __($event->bookProject) ?></div>
                    <?php $chk = in_array($event->bookProject, ["tn"]) ?>
                    <?php $add = in_array($event->bookProject, ["tn"]) ? "_tn" : ""; ?>
                    <?php $add = $event->step == EventSteps::SELF_CHECK && $chk ? $add."_chk" : $add; ?>
                    <?php $add .= $event->sourceBible == "odb" ? "_odb" : "" ?>
                    <div><?php echo __($event->step . $add) ?></div>
                </div>
                <div class="event_img">
                    <img width="85" src="<?php echo $eventImg ?>">
                </div>
            </div>
            <div class="event_project">
                <div class="event_book"><?php echo isset($event->bookName) ? $event->bookName : $event->name ?></div>
                <div class="event_proj">
                    <div><?php echo $event->sourceBible == "odb" ? __($event->sourceBible) : __($event->bookProject) ?></div>
                    <div><?php echo $event->tLang . ", " . ($event->sort < 41 ? __("old_test") : __("new_test"))?></div>
                </div>
                <div class="event_facilitator">
                    <div><?php echo __("facilitators") ?>:</div>
                    <div class="facil_names">
                        <?php foreach ($event->admins as $admin): ?>
                            <a href="#" data="<?php echo $admin->memberID ?>"><?php echo $admin->firstName . " " . mb_substr($admin->lastName, 0, 1) . "." ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="event_translator">
                <div class="event_translator_data">
                    <div class="event_translator_title"><?php echo __("translator") ?></div>
                    <div class="event_translator_name"><?php echo $event->firstName . " " . mb_substr($event->lastName, 0, 1)."." ?></div>
                </div>
            </div>
            <div class="event_current_pos">
                <div class="event_current_title"><?php echo __("you_are_at") ?></div>
                <div class="event_curr_step">
                    <?php
                    $step = $event->step;
                    if($step == EventSteps::READ_CHUNK)
                        $step = EventSteps::BLIND_DRAFT;
                    ?>
                    <img class='img_current' src="<?php echo template_url("img/steps/green_icons/". $step. ".png") ?>">
                    <div class="step_current">
                        <div>
                            <?php echo ($event->currentChapter > 0
                                    ? ($wordsGroup ?
                                        "[".$wordsGroup[0]."...".$wordsGroup[sizeof($wordsGroup)-1]."] "
                                        : ($word ?: __("chapter_number", ["chapter" => $event->currentChapter])))
                                    : __("front")) ?>
                        </div>
                        <div>
                            <?php echo __($event->step.($event->sourceBible == "odb" ? "_odb" : "")) ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="event_action">
                <div class="event_link">
                    <a href="/events/checker<?php echo ($event->sourceBible == "odb" ? "-odb" : "")
                        .(Tools::isHelpExtended($event->bookProject) ? "-".$event->bookProject : "")
                            ."/".$event->eventID."/".$event->memberID
                            .(isset($event->isContinue) || in_array($event->bookProject, ["tq","tw", "ulb", "udb"]) ? "/".$event->currentChapter : "")?>"
                       data="<?php echo $event->eventID."_".$event->memberID?>">
                        <?php echo __("continue") ?>
                    </a>
                </div>
            </div>

            <div class="clear"></div>
        </div>
    <?php endforeach ?>

    <?php foreach($data["myCheckerL2Events"] as $key => $event): ?>
        <?php
        $mode = $event->bookProject;
        $memberLink = $event->memberID != Session::get("memberID") ? "/" . $event->memberID : "";
        $chapterLink = in_array($event->step, [
            EventCheckSteps::PEER_REVIEW,
            EventCheckSteps::KEYWORD_CHECK,
            EventCheckSteps::CONTENT_REVIEW
        ])
        && $event->currentChapter > 0
            ? "/" . $event->currentChapter : "";

        $link = "/events/checker"
            .($mode == "sun" ? "-sun" : "")
            ."-revision/"
            . $event->eventID
            .$memberLink
            .$chapterLink;
        ?>
        <div class="event_block <?php echo $key%2 == 0 ? "lemon-marked" : "" ?>">
            <div class="event_logo checkingl2">
                <div class="event_type"><?php echo __("revision_events") ?></div>
                <div class="event_mode <?php echo $event->bookProject ?>"><?php echo __($event->bookProject) ?></div>
                <div class="event_img">
                    <img width="146" src="<?php echo template_url("img/steps/big/l2_check.png") ?>">
                </div>
            </div>
            <div class="event_project">
                <div class="event_book"><?php echo $event->name ?></div>
                <div class="event_proj">
                    <div><?php echo __($event->bookProject) ?></div>
                    <div><?php echo $event->tLang . ", " . ($event->sort < 41 ? __("old_test") : __("new_test"))?></div>
                </div>
                <div class="event_facilitator">
                    <div><?php echo __("facilitators") ?>:</div>
                    <div class="facil_names">
                        <?php foreach ($event->admins as $admin): ?>
                            <a href="#" data="<?php echo $admin->memberID ?>"><?php echo $admin->firstName . " " . mb_substr($admin->lastName, 0, 1) . "." ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="event_current_pos">
                <?php if($event->step != EventCheckSteps::NONE): ?>
                    <div class="event_current_title"><?php echo __("you_are_at") ?></div>
                    <div class="event_curr_step">
                        <img class='img_current' src="<?php echo template_url("img/steps/green_icons/". $event->step. ".png") ?>">
                        <div class="step_current">
                            <div>
                                <?php echo ($event->currentChapter > 0 ? __("chapter_number",
                                        ["chapter" => $event->currentChapter]) : "") ?>
                            </div>
                            <div>
                                <?php
                                $add = "";
                                if ($event->bookProject == "sun"
                                    && in_array($event->step, [EventCheckSteps::SELF_CHECK, EventCheckSteps::PEER_REVIEW])) {
                                    $add = "_sun";
                                }
                                ?>
                                <?php echo __($event->step.$add) ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="event_action check2">
                <div class="event_link">
                    <a href="<?php echo $link ?>"><?php echo __("continue") ?></a>
                </div>
            </div>

            <div class="clear"></div>
        </div>
    <?php endforeach ?>

    <?php foreach($data["myCheckerL3Events"] as $key => $event): ?>
        <div class="event_block <?php echo $key%2 == 0 ? "blue-marked" : "" ?>">
            <div class="event_logo checkingl3">
                <div class="event_type"><?php echo __("review_events") ?></div>
                <div class="event_mode <?php echo $event->bookProject ?>"><?php echo __($event->bookProject) ?></div>
                <div class="event_img">
                    <img width="146" src="<?php echo template_url("img/steps/big/l2_check.png") ?>">
                </div>
            </div>
            <div class="event_project">
                <div class="event_book"><?php echo $event->name ?></div>
                <div class="event_proj">
                    <div><?php echo __($event->bookProject) ?></div>
                    <div><?php echo $event->tLang . ", " . ($event->sort < 41 ? __("old_test") : __("new_test"))?></div>
                </div>
                <div class="event_facilitator">
                    <div><?php echo __("facilitators") ?>:</div>
                    <div class="facil_names">
                        <?php foreach ($event->admins as $admin): ?>
                            <a href="#" data="<?php echo $admin->memberID ?>"><?php echo $admin->firstName . " " . mb_substr($admin->lastName, 0, 1) . "." ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="event_current_pos">
                <?php if($event->step != EventCheckSteps::NONE): ?>
                    <div class="event_current_title"><?php echo __("you_are_at") ?></div>
                    <div class="event_curr_step">
                        <img class='img_current' src="<?php echo template_url("img/steps/green_icons/". $event->step. ".png") ?>">
                        <div class="step_current">
                            <div>
                                <?php echo ($event->currentChapter > 0
                                        ? __("chapter_number", ["chapter" => $event->currentChapter])
                                        : ($event->bookProject == "tn" && $event->currentChapter > -1 ? __("front") : "")) ?>
                            </div>
                            <div>
                                <?php echo __($event->step) ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="event_action <?php echo !empty($event->isContinue) ? "check3" : "" ?>">
                <div class="event_link">
                    <a href="/events/checker<?php echo (in_array($event->bookProject, ["tn","tq","tw","sun"]) ? "-".$event->bookProject : "")
                        ."-review/".$event->eventID
                        .(isset($event->isContinue) ? "/".$event->memberID."/".$event->currentChapter : "")?>"
                        data="<?php echo $event->eventID."_".$event->memberID?>">
                        <?php echo __("continue") ?>
                    </a>
                </div>
            </div>

            <div class="clear"></div>
        </div>
    <?php endforeach ?>

    <?php if((sizeof($data["myCheckerL1Events"]) + sizeof($data["myCheckerL2Events"]) + sizeof($data["myCheckerL3Events"])) <= 0): ?>
        <div class="no_events_message"><?php echo __("no_events_message") ?></div>
    <?php endif; ?>

    <div class="clear"></div>
</div>

<div class="mailer_container">
    <div class="mailer_block">
        <div class="mailer-close glyphicon glyphicon-remove"></div>

        <form class="mailer_form">
            <div class="form-group">
                <div class="mailer_name">
                    <label><?php echo __("send_message_to") ?>:
                        <span></span>
                    </label>
                </div>
            </div>
            <div class="form-group">
                <div class="mailer_subject">
                    <label><?php echo __("message_subject") ?>:
                        <input name="subject" type="text" size="90" class="form-control"></label>
                </div>
            </div>
            <div class="form-group">
                <div class="mailer_message">
                    <label><?php echo __("message_content") ?>:
                        <textarea name="message" rows="10" class="form-control"></textarea></label>
                </div>
            </div>
            <div class="form-group">
                <div class="mailer_button">
                    <button class="btn btn-primary form-control"><?php echo __("send") ?></button>
                </div>
            </div>
            <input type="hidden" name="adminID" value="" class="adm_id">
        </form>
    </div>
</div>
