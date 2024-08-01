<?php
use Helpers\Constants\EventStates;

$language = Language::code();

if($project):
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title">
            <?php echo "[".$project->targetLang."] "
                . $project->targetLanguage->langName
                . ($project->targetLanguage->langName != $project->targetLanguage->angName
                    && $project->targetLanguage->angName != "" ? " (" . $project->targetLanguage->angName . ")" : "")
                . " - ".(__("odb"))." - ".__($project->bookProject) ?>
        </h1>
    </div>

    <div class="form-inline dt-bootstrap no-footer">
        <div style="display: flex; margin-bottom: 50px; border-bottom: 1px solid #ccc;">
            <div style="flex: 2; display: flex; justify-content: flex-end">
                <div class="add-event-btn">
                    <img class="contibLoader" width="24px" src="<?php echo template_url("img/loader.gif") ?>">
                    <button style="margin-top: 12px" class="btn btn-warning showAllContibutors"
                            data-projectid="<?php echo $project->projectID ?>"><?php echo __("contributors") ?></button>
                </div>
            </div>
        </div>

        <div class="row" id="old_test">
            <div class="project_progress progress <?php echo $data["ODBprogress"] <= 0 ? "zero" : ""?>"
                 style="left: 30px;">
                <div class="progress-bar progress-bar-success" role="progressbar"
                     aria-valuenow="<?php echo floor($data["ODBprogress"]) ?>"
                     aria-valuemin="0" aria-valuemax="100" style="min-width: 0em; width: <?php echo floor($data["OTprogress"])."%" ?>">
                    <?php echo floor($data["ODBprogress"])."%" ?>
                </div>
            </div>
            <div class="col-sm-12">
                <table class="table table-bordered table-hover" role="grid">
                    <thead>
                    <tr>
                        <th><?php echo __("book") ?></th>
                        <th><?php echo __("time_start") ?></th>
                        <th><?php echo __("time_end") ?></th>
                        <th><?php echo __("state") ?></th>
                        <th><?php echo __("progress") ?></th>
                        <th><?php echo __("contributors") ?></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($bookInfos as $bookInfo): ?>
                        <tr>
                            <td><?php echo $bookInfo->name ?></td>
                            <td class="datetime" data="<?php echo $bookInfo->event && $bookInfo->event->dateFrom != "" && $bookInfo->event->dateFrom != "0000-00-00 00:00:00" ?
                                date(DATE_RFC2822, strtotime($bookInfo->event->dateFrom)) : "" ?>">
                                <?php echo $bookInfo->event && $bookInfo->event->dateFrom != "" && $bookInfo->event->dateFrom != "0000-00-00 00:00:00" ? $bookInfo->event->dateFrom . " UTC" : "" ?></td>
                            <td class="datetime" data="<?php echo $bookInfo->event && $bookInfo->event->dateTo != "" && $bookInfo->event->dateTo != "0000-00-00 00:00:00" ?
                                date(DATE_RFC2822, strtotime($bookInfo->event->dateTo)) : "" ?>">
                                <?php echo $bookInfo->event && $bookInfo->event->dateTo != "" && $bookInfo->event->dateTo != "0000-00-00 00:00:00" ? $bookInfo->event->dateTo . " UTC" : "" ?></td>
                            <td><?php echo $bookInfo->event && $bookInfo->event->state ? __("state_".$bookInfo->event->state) : "" ?></td>
                            <td style="position:relative;">
                                <div class="event_column progress zero" data-eventid="<?php echo $bookInfo->event ? $bookInfo->event->eventID : ""?>">
                                    <div class="progress-bar progress-bar-success" role="progressbar"
                                         aria-valuenow="0"
                                         aria-valuemin="0" aria-valuemax="100" style="min-width: 0em; width: 0%">
                                        0%
                                    </div>
                                    <img class="progressLoader" width="24px" src="<?php echo template_url("img/loader.gif") ?>">
                                </div>
                            </td>
                            <td>
                                <?php if($bookInfo->event && EventStates::enum($bookInfo->event->state) >= EventStates::enum(EventStates::TRANSLATED)): ?>
                                    <button class="btn btn-warning showContributors"
                                            data-eventid="<?php echo $bookInfo->event->eventID?>"
                                            data-level="2"
                                            data-mode="<?php echo $project->bookProject ?>">
                                        <?php echo __("L2") ?>
                                    </button>
                                <?php endif; ?>
                                <?php if($bookInfo->event && EventStates::enum($bookInfo->event->state) >= EventStates::enum(EventStates::COMPLETE)): ?>
                                    <button class="btn btn-warning showContributors"
                                            data-eventid="<?php echo $bookInfo->event->eventID?>"
                                            data-level="3"
                                            data-mode="<?php echo $project->bookProject ?>">
                                        <?php echo __("L3") ?>
                                    </button>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                switch($bookInfo->event)
                                {
                                    case null:
                                        echo '<button 
                                        data-bookcode="'.$bookInfo->code.'" 
                                        data-bookname="'.$bookInfo->name.'" 
                                        data-chapternum="'.$bookInfo->chaptersNum.'" 
                                            class="btn btn-primary startEvnt">'.__("create").'</button>';
                                        break;

                                    default:
                                        echo '<button 
                                        data-bookcode="'.$bookInfo->code.'" 
                                        data-eventid="'.$bookInfo->event->eventID.'" 
                                        data-sort="'.$bookInfo->sort.'"
                                            class="btn btn-success editEvnt">'.__("edit").'</button>';
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="event-content form-panel">
    <div class="create-event-content panel panel-default">
        <div class="panel-heading">
            <h1 class="panel-title"><?php echo __("create_event"); ?></h1>
            <span class="panel-close glyphicon glyphicon-remove"></span>
        </div>

        <div class="page-content panel-body">
            <div class="bookName"></div>
            <div class="clear"></div>

            <div class="main_menu event_menu">
                <div class="glyphicon glyphicon-menu-hamburger"></div>
                <ul>
                    <li data-id="clearCache">
                        <?php echo __("clear_cache"); ?>
                        <span class="glyphicon glyphicon-question-sign" title="<?php echo __("clear_cache_info") ?>"></span>
                    </li>
                    <li data-id="deleteEvent"><?php echo __("delete"); ?></li>

                    <hr>
                    <div class="event_links_l3">
                        <li class="option_group"><?php echo __("review_events") ?></li>
                        <li class="event_progress"><a href="#"><?php echo __("progress"); ?></a></li>
                        <li class="event_manage"><a href="#"><?php echo __("manage"); ?></a></li>
                    </div>
                </ul>
            </div>

            <div class="errors"></div>

            <div class="row">
                <div class="col-sm-12">
                    <form action="/admin/rpc/create_event" method="post" id="startEvent">
                        <div class="form-group" style="width: 450px;">
                            <label for="adminsSelect" style="width: 100%; display: block"><?php echo __('facilitators'); ?></label>
                            <select class="form-control" name="admins[]" id="adminsSelect" multiple data-placeholder="<?php echo __("add_admins_by_username") ?>">
                                <option></option>
                            </select>
                        </div>

                        <div class="form-group delinput" style="width: 350px; display: none">
                            <label for="delevnt" style="width: 100%; display: block; color: #f00;"><?php echo __('delete_warning'); ?></label>
                            <div style="display: flex;">
                                <input class="form-control" type="text" id="delevnt" autocomplete="off" style="margin-right: 10px">
                                <button type="submit" name="deleteEvent" class="btn btn-danger"><?php echo __("delete"); ?></button>
                            </div>
                        </div>

                        <div class="event_imports"> </div>

                        <input type="hidden" name="eID" id="eID" value="">
                        <input type="hidden" name="act" id="eventAction" value="create">
                        <input type="hidden" name="sort" id="sort" value="" />
                        <input type="hidden" name="book_code" id="bookCode" value="" />
                        <input type="hidden" name="projectID" id="projectID" value="<?php echo $project->projectID?>" />
                        <input type="hidden" name="sourceBible" id="sourceBible" value="<?php echo $project->sourceBible?>" />
                        <input type="hidden" name="bookProject" id="bookProject" value="<?php echo $project->bookProject?>" />
                        <input type="hidden" name="sourceLangID" id="sourceLangID" value="<?php echo $project->sourceLangID?>" />
                        <input type="hidden" name="targetLangID" id="targetLangID" value="<?php echo $project->targetLang?>" />
                        <input type="hidden" name="initialLevel" id="initialLevel" value="1" />
                        <input type="hidden" name="importLevel" id="importLevel" value="1" />
                        <input type="hidden" name="importProject" id="importProject" value="<?php echo $project->bookProject?>" />

                        <br>
                        <button type="submit" name="startEvent" class="btn btn-primary"><?php echo __("create"); ?></button>

                        <img class="startEventLoader" style="position:absolute; bottom: 5px; right: 5px;" width="24px" src="<?php echo template_url("img/loader.gif") ?>">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="contributors_container">
    <div class="contributors_block">
        <div class="contributors-close glyphicon glyphicon-remove"></div>
        <div class="contributors_title"><?php echo __("event_contributors") ?></div>
        <div class="contributors_title proj">
            <?php echo __("contributors") ?>
            <button class="btn btn-link contribs_download_tsv">Download (.tsv)</button>
        </div>
        <div class="contributors_content"></div>
    </div>
</div>
<?php else: ?>
<div>Project does not exist or you do not have rights to see it</div>
<?php endif; ?>

<link href="<?php echo template_url("css/jquery-ui-timepicker-addon.css")?>" type="text/css" rel="stylesheet" />
<script src="<?php echo template_url("js/jquery-ui-timepicker-addon.min.js")?>"></script>
<?php if($language != "en"): ?>
<script src="<?php echo template_url("js/i18n/jquery-ui-timepicker-".mb_strtolower($language).".js")?>"></script>
<script src="<?php echo template_url("js/i18n/datepicker-".mb_strtolower($language).".js")?>"></script>
<?php endif; ?>

<link href="<?php echo template_url("css/chosen.min.css?v=2")?>" type="text/css" rel="stylesheet" />
<script src="<?php echo template_url("js/chosen.jquery.min.js?v=2")?>"></script>
<script src="<?php echo template_url("js/ajax-chosen.min.js")?>"></script>