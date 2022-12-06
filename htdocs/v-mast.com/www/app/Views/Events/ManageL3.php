<?php
use Helpers\Constants\EventCheckSteps;
use Helpers\Constants\EventStates;
use Shared\Legacy\Error;

echo Error::display($error);

if(!isset($error)):
    $mode = "l3";
?>

<div class="back_link">
    <span class="glyphicon glyphicon-chevron-left"></span>
    <a href="#" onclick="history.back(); return false;"><?php echo __("go_back") ?></a>
</div>

<div class="manage_container row">
    <div class="row">
        <div class="col-sm-6">
            <div class="book_title" style="padding-left: 15px"><?php echo $event->bookInfo->name ?></div>
            <div class="project_title" style="padding-left: 15px"><?php echo __($event->project->bookProject)." - ".$event->project->targetLanguage->langName ?></div>
        </div>
        <div class="col-sm-6 start_translation">
            <?php if($event->state == EventStates::L3_RECRUIT): ?>
                <form action="" method="post">
                    <button type="submit" name="submit" class="btn btn-warning" id="startTranslation" style="width: 150px; height: 50px;"><?php echo __("start_checking")?></button>
                </form>
            <?php else: ?>
                <div class="event_state"><?php echo __("event_status").": ".__("state_".$event->state) ?></div>
            <?php endif; ?>
        </div>
    </div>

    <div class="manage_chapters col-sm-6">
        <h3><?php echo __("chapters") ?></h3>
        <ul>
            <?php foreach ($chapters as $chapter => $chapData): ?>
                <?php
                if(!empty($chapData))
                {
                    $member = $members->find($chapData["l3memberID"]);
                    $name = $member
                        ? $member->firstName . " " . mb_substr($member->lastName, 0, 1)."."
                        : $chapData["l3memberID"];
                }
                ?>
                <li class="chapter_<?php echo $chapter ?>" style="position:relative;">
                    <div class="manage_chapter">
                        <?php echo $chapter > 0 ? __("chapter_number", ["chapter" => $chapter]) : __("chapter_number", ["chapter" => __("intro")]); ?>
                    </div>
                    <div class="manage_chapters_user">
                        <button class="btn btn-success add_person_chapter"
                                data-chapter="<?php echo $chapter ?>"
                                style="<?php echo !empty($chapData) ? "display:none" : "display:block" ?>">
                            <?php echo __("assign_chapter_title") ?>
                        </button>
                        <div class="manage_username" style="<?php echo empty($chapData) ? "display:none" : "display:block" ?>">
                            <div class="uname">
                                <a href="/members/profile/<?php echo !empty($chapData) ? $chapData["l3memberID"] : "" ?>"
                                   target="_blank"><?php echo !empty($chapData) ? $name : "" ?></a>
                            </div>
                        </div>
                    </div>
                    <div class="manage_chapters_buttons">
                        <div class="chapter_menu_loader" data-chapter="<?php echo $chapter ?>">
                            <img width="24" src="<?php echo template_url("img/loader.gif") ?>">
                        </div>
                        <div class="main_menu chapter_menu"
                             style="<?php echo empty($chapData) ? "display:none" : "display:block" ?>">
                            <div class="glyphicon glyphicon-menu-hamburger"></div>
                            <ul>
                                <li data-id="move_back"
                                    data-userid="<?php echo !empty($chapData) ? $chapData["l3memberID"] : "" ?>"
                                    data-chapter="<?php echo $chapter ?>"
                                    data-mode="<?php echo $mode ?>">
                                    <?php echo __("move_back_in_chapter") ?>
                                </li>
                                <hr>
                                <li data-id="remove_chapter"
                                    data-chapter="<?php echo $chapter ?>"
                                    data-userid="<?php echo !empty($chapData) ? $chapData["l3memberID"] : "" ?>"
                                    data-username="<?php echo !empty($chapData) ? $name : "" ?>"><?php echo __("remove_from_chapter") ?></li>
                            </ul>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="manage_members col-sm-6">
        <h3>
            <?php echo __("people_number", ["people_number" => sizeof($members)]) ?>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <button 
                class="btn btn-primary" 
                id="openMembersSearch">
                <?php echo __("add_checker") ?>
            </button>
            <button 
                class="btn btn-success glyphicon glyphicon-refresh" 
                id="refresh" 
                title="<?php echo __("refresh"); ?>">
            </button>
        </h3>
        <ul>
            <?php foreach ($members as $member):?>
                <?php
                $assignedChapters = $member->chaptersL3->filter(function($chap) use($event) {
                    return $chap->eventID == $event->eventID;
                })->getDictionary();
                $chapterNumbers = array_map(function($chap) {
                    return $chap->chapter;
                }, $assignedChapters);
                ?>
                <li>
                    <div class="member_username" data-userid="<?php echo $member->memberID ?>">
                        <a href="/members/profile/<?php echo $member->memberID ?>" target="_blank"><?php echo $member->firstName . " " . mb_substr($member->lastName, 0, 1)."."; ?></a>
                        (<span><?php echo sizeof($chapterNumbers) ?></span>)

                        <div class="user_menu_loader" data-userid="<?php echo $member->memberID ?>">
                            <img width="24" src="<?php echo template_url("img/loader.gif") ?>">
                        </div>
                        <div class="main_menu user_menu">
                            <div class="glyphicon glyphicon-menu-hamburger"></div>
                            <ul>
                                <li data-id="delete_user" data-userid="<?php echo $member->memberID ?>">
                                    <?php echo __("remove_from_event") ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="member_chapters" <?php echo !empty($chapterNumbers) ? "style='display:block'" : "" ?>>
                        <?php echo __("chapters").": <span><b>". join("</b>, <b>", $chapterNumbers)."</b></span>" ?>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="clear"></div>
</div>

<input type="hidden" id="eventID" value="<?php echo $event->eventID ?>">
<input type="hidden" id="mode" value="<?php echo $event->project->bookProject ?>">


<div class="chapter_members">
    <div class="chapter_members_div panel panel-default">
        <div class="panel-heading">
            <h1 class="panel-title"><?php echo __("assign_chapter_title")?> <span></span></h1>
            <span class="chapter-members-close glyphicon glyphicon-remove-sign"></span>
        </div>
        <div class="assignChapterLoader dialog_f">
            <img src="<?php echo template_url("img/loader.gif") ?>">
        </div>
        <ul>
            <?php foreach ($members as $member): ?>
                <?php
                $assignedChapters = $member->chaptersL2->filter(function($chap) use($event) {
                    return $chap->eventID == $event->eventID;
                })->getDictionary();
                $chapterNumbers = array_map(function($chap) {
                    return $chap->chapter;
                }, $assignedChapters);
                ?>
            <li>
                <div class="member_username userlist chapter_ver">
                    <div class="divname"><?php echo $member->firstName . " " . mb_substr($member->lastName, 0, 1)."."; ?></div>
                    <div class="divvalue">(<span><?php echo sizeof($chapterNumbers) ?></span>)</div>
                </div>
                <button class="btn btn-success assign_chapter"
                        data-userid="<?php echo $member->memberID ?>"
                        data-username="<?php echo $member->firstName . " " . mb_substr($member->lastName, 0, 1)."."; ?>"><?php echo __("assign") ?></button>
                <div class="clear"></div>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<div class="members_search_dialog">
    <div class="members_search_dialog_div panel panel-default">
        <div class="panel-heading">
            <h1 class="panel-title"><?php echo __("add_checker")?> <span></span></h1>
            <span class="members-search-dialog-close glyphicon glyphicon-remove-sign"></span>
        </div>
        <div class="openMembersSearch dialog_f">
            <img src="<?php echo template_url("img/loader.gif") ?>">
        </div>
        <div class="members-search-dialog-content">
            <div class="form-group">
                <input type="text" class="form-control input-lg" id="user_translator" placeholder="Enter a name" required="">
            </div>
            <ul class="user_translators"></ul>
        </div>
    </div>
</div>
<?php else: ?>
    <a href="#" onclick="history.back(); return false"><?php echo __('go_back')?></a>
<?php endif; ?>

<script>
    isManagePage = true;
    manageMode = "l3";
    userType = EventMembers.L3_CHECKER;
</script>
