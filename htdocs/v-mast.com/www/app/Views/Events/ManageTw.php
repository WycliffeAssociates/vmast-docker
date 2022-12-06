<?php
use Helpers\Constants\EventStates;
use Shared\Legacy\Error;

echo Error::display($error);

$groups = [];
$mode = "tw";

if(!isset($error)):
    ?>

    <div class="back_link">
        <span class="glyphicon glyphicon-chevron-left"></span>
        <a href="#" onclick="history.back(); return false;"><?php echo __("go_back") ?></a>
    </div>

    <div class="manage_container">
        <div class="row">
            <div class="col-sm-6">
                <div class="book_title" style="padding-left: 15px"><?php echo $event->bookInfo->name ?></div>
                <div class="project_title" style="padding-left: 15px"><?php echo __($event->project->bookProject)." - ".$event->project->targetLanguage->langName ?></div>
            </div>
            <div class="col-sm-6 start_translation">
                <?php if($event->state == EventStates::STARTED): ?>
                    <form action="" method="post">
                        <button type="submit" name="submit" class="btn btn-warning" id="startTranslation" style="width: 150px; height: 50px;"><?php echo __("start_translation")?></button>
                    </form>
                <?php else: ?>
                    <div class="event_state"><?php echo __("event_status").": ".__("state_".$event->state) ?></div>
                <?php endif; ?>
            </div>
        </div>

        <div class="manage_body">
            <div class="manage_chapters">
                <h3><?php echo __("chapters") ?></h3>
                <div id="word_group_block">
                    <button class="btn btn-primary" id="word_group_create"><?php echo __("create_words_group") ?></button>
                </div>
                <ul>
                    <?php $group_order = 1; foreach ($chapters as $chapter => $chapData): ?>
                        <?php
                        $group_name = null;
                        foreach ($data["word_groups"] as $word_group) {
                            if($word_group->groupID == $chapter) {
                                $words = (array) json_decode($word_group->words, true);
                                $group_name = join(", ", $words);
                                break;
                            }
                        }

                        if(!empty($chapData)) {
                            $member = $members->find($chapData["memberID"]);
                            $name = $members
                                ? $member->firstName . " " . mb_substr($member->lastName, 0, 1)."."
                                : $chapData["memberID"];

                            if (!array_key_exists($member->memberID, $groups)) {
                                $groups[$member->memberID] = [];
                            }
                            $groups[$member->memberID][] = $group_order;
                        }
                        ?>
                        <li class="chapter_<?php echo $chapter ?>" style="position:relative;">
                            <div class="manage_chapter">
                                <?php echo __("group_id", $group_order); ?>
                                <span class='glyphicon glyphicon-info-sign'
                                      data-toggle='tooltip'
                                      title="<?php echo $group_name ?: "" ?>"
                                      style="font-size: 16px;"></span>
                            </div>
                            <div class="manage_chapters_user">
                                <button class="btn btn-success add_person_chapter"
                                        data-chapter="<?php echo $chapter ?>"
                                        data-group="<?php echo $group_order ?>" <?php echo !empty($chapData) ? 'style="display: none"' : '' ?>
                                        style="<?php echo !empty($chapData) ? "display:none" : "display:block" ?>">
                                    <?php echo __("assign") ?>
                                </button>
                                <div class="manage_username" style="<?php echo empty($chapData) ? "display:none" : "display:block" ?>">
                                    <div class="uname">
                                        <a href="/members/profile/<?php echo !empty($chapData) ? $chapData["memberID"] : "" ?>"
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
                                            data-userid="<?php echo !empty($chapData) ? $chapData["memberID"] : "" ?>"
                                            data-chapter="<?php echo $chapter ?>"
                                            data-mode="<?php echo $mode ?>">
                                            <?php echo __("move_back_in_chapter") ?>
                                        </li>
                                        <hr>
                                        <li data-id="remove_chapter"
                                            data-chapter="<?php echo $chapter ?>"
                                            data-userid="<?php echo !empty($chapData) ? $chapData["memberID"] : "" ?>"
                                            data-username="<?php echo !empty($chapData) ? $name : "" ?>"><?php echo __("remove_from_chapter") ?></li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                        <?php $group_order++; endforeach; ?>
                </ul>
            </div>

            <div class="manage_members">
                <h3>
                    <?php echo __("people_number", ["people_number" => sizeof($members)]) ?>
                    <div class="manage_buttons">
                        <button
                                class="btn btn-primary"
                                id="openMembersSearch">
                            <?php echo __("add_translator") ?>
                        </button>
                        <button
                                class="btn btn-success glyphicon glyphicon-refresh"
                                id="refresh"
                                title="<?php echo __("refresh"); ?>">
                        </button>
                    </div>
                </h3>
                <ul>
                    <?php foreach ($members as $member):?>
                        <?php
                        $assignedGroups = array_key_exists($member->memberID, $groups) ? $groups[$member->memberID] : [];
                        ?>
                        <li>
                            <div class="member_username" data-userid="<?php echo $member->memberID ?>">
                                <a href="/members/profile/<?php echo $member->memberID ?>" target="_blank"><?php echo $member->firstName . " " . mb_substr($member->lastName, 0, 1)."."; ?></a>
                                (<span><?php echo sizeof($assignedGroups) ?></span>)

                                <div class="user_menu_loader" data-userid="<?php echo $member->memberID ?>">
                                    <img width="24" src="<?php echo template_url("img/loader.gif") ?>">
                                </div>
                                <div class="main_menu user_menu">
                                    <div class="glyphicon glyphicon-menu-hamburger"></div>
                                    <ul>
                                        <li data-id="set_checker" data-userid="<?php echo $member->memberID ?>">
                                            <input type="checkbox" disabled
                                                <?php echo $member->pivot->isChecker ? "checked" : "" ?>> <?php echo __("checking_tab_title") ?>
                                        </li>
                                        <hr/>
                                        <li data-id="delete_user" data-userid="<?php echo $member->memberID ?>">
                                            <?php echo __("remove_from_event") ?>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="member_chapters" <?php echo !empty($assignedGroups) ? "style='display:block'" : "" ?>>
                                <?php echo __("chapters").": <b>". join("</b>, <b>", $assignedGroups)."</b>" ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

    <input type="hidden" id="eventID" value="<?php echo $event->eventID ?>">
    <input type="hidden" id="mode" value="<?php echo $event->project->bookProject ?>">

    <div class="chapter_members">
        <div class="chapter_members_div panel panel-default">
            <div class="panel-heading">
                <h1 class="panel-title"><?php echo __("assign_group_title")?> <span></span></h1>
                <span class="chapter-members-close glyphicon glyphicon-remove-sign"></span>
            </div>
            <div class="assignChapterLoader dialog_f">
                <img src="<?php echo template_url("img/loader.gif") ?>">
            </div>
            <ul>
                <?php foreach ($members as $member): ?>
                    <?php
                    $assignedGroups = [];
                    ?>
                    <li>
                        <div class="member_username userlist chapter_ver">
                            <div class="divname"><?php echo $member->firstName . " " . mb_substr($member->lastName, 0, 1)."."; ?></div>
                            <div class="divvalue">(<span><?php echo sizeof($assignedGroups) ?></span>)</div>
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
                <h1 class="panel-title"><?php echo __("add_translator")?> <span></span></h1>
                <span class="members-search-dialog-close glyphicon glyphicon-remove-sign"></span>
            </div>
            <div class="openMembersSearch dialog_f">
                <img src="<?php echo template_url("img/loader.gif") ?>">
            </div>
            <div class="members-search-dialog-content">
                <div class="form-group">
                    <input type="text" class="form-control input-lg" id="user_translator" placeholder="Enter a name" required="">
                </div>
                <ul class="user_translators">

                </ul>
            </div>
        </div>
    </div>

    <div class="words_group_dialog">
        <div class="words_group_dialog_div panel panel-default">
            <div class="panel-heading">
                <h1 class="panel-title"><?php echo __("create_words_group")?> <span></span></h1>
                <span class="words-group-dialog-close glyphicon glyphicon-remove-sign"></span>
            </div>
            <div class="openWordsGroup dialog_f">
                <img src="<?php echo template_url("img/loader.gif") ?>">
            </div>
            <div class="words-group-dialog-content">
                <div class="word_group_hint"><?php echo __("word_group_hint") ?></div>
                <div class="form-group">
                    <select class="form-control input-lg" id="word_group" multiple>
                        <?php foreach ($data["words"] as $word): ?>
                            <option <?php echo in_array($word["word"], $data["words_in_groups"]) ? "disabled" : "" ?>>
                                <?php echo $word["word"] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div style="text-align: right">
                    <button class="btn btn-success" id="create_group"><?php echo __("create_group") ?></button>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <a href="#" onclick="history.back(); return false"><?php echo __('go_back')?></a>
<?php endif; ?>

<script>
    isManagePage = true;
    manageMode = "l1";
    userType = EventMembers.TRANSLATOR;
</script>
