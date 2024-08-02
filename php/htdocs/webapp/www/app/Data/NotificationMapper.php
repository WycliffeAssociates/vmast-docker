<?php

namespace App\Data;

use stdClass;

class NotificationMapper {

    public static function toData($notificationModel): stdClass {
        $data = new stdClass();
        $data->noteID = $notificationModel->noteID;
        $data->eventID = $notificationModel->eventID;
        $data->memberID = $notificationModel->fromMemberID;
        $data->manageMode = $notificationModel->manageMode;
        $data->step = $notificationModel->step;
        $data->currentChapter = $notificationModel->currentChapter;
        $data->type = $notificationModel->type;

        $event = $notificationModel->event;
        $project = $event->project;
        $targetLanguage = $project->targetLanguage;
        $member = $notificationModel->fromMember;

        $data->bookProject = $project->bookProject;
        $data->tLang = $targetLanguage->langName;
        $data->sourceBible = $project->sourceBible;
        $data->bookName = $event->bookInfo->name;
        $data->inputMode = $event->inputMode;
        $data->firstName = $member->firstName;
        $data->lastName = $member->lastName;

        if ($project->bookProject == "bca") {
            $data->word = $notificationModel->word->word;
        } elseif ($project->bookProject == "tw") {
            $words = $notificationModel->wordGroup->words ?
                json_decode($notificationModel->wordGroup->words, true) :
                [];
            $first = $words[0];
            $last = $words[sizeof($words)-1];
            $data->group = "$first...$last";;
        }

        return $data;
    }
}