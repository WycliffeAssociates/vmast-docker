<?php


namespace App\Repositories\Event;


interface IEventRepository
{
    public function create($data, $project);

    public function get($id);

    public function getWith($relation);

    public function calculateEventProgress($event, $level);

    public function getNotification($noteID);

    public function getFromNotifications($memberID);

    public function getToNotifications($memberID);

    public function notificationExists($eventID, $fromMemberID, $toMemberID, $manageMode, $step, $chapter, $type);

    public function createNotification($data, $event, $fromMember, $toMember);

    public function delete(&$self);

    public function save(&$self);
}