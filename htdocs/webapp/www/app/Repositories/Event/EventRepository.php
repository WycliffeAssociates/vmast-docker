<?php


namespace App\Repositories\Event;


use App\Domain\ResourceProgress;
use App\Domain\ScriptureRevisionProgress;
use App\Domain\AnyL3Progress;
use App\Domain\ScriptureProgress;
use App\Domain\SunRevisionProgress;
use App\Domain\SunProgress;
use App\Domain\TnProgress;
use App\Domain\TqProgress;
use App\Domain\TwProgress;
use App\Helpers\EventUtil;
use App\Models\ORM\Event;
use App\Models\ORM\Member;
use App\Models\ORM\Notification;

class EventRepository implements IEventRepository {

    protected $event = null;

    public function __construct(Event $event) {
        $this->event = $event;
    }

    public function create($data, $project) {
        $event = new Event($data);
        $event->project()->associate($project)->save();
        return $event;
    }

    public function get($id) {
        return $this->event::find($id);
    }

    public function getWith($relation) {
        return $this->event::with($relation)->get();
    }

    public function calculateEventProgress($event, $level) {
        if ($level == "l3") {
            // All projects of level 3
            return AnyL3Progress::calculateEventProgress($event, true);
        } elseif (in_array($event->project->bookProject, ["ulb", "udb"])) {
            // ULB, UDB of level 1, 2
            if ($level == "l1") {
                $progress = ScriptureProgress::calculateEventProgress($event, true);
            } else {
                $progress = ScriptureRevisionProgress::calculateEventProgress($event, true);
            }
            return $progress;
        } elseif ($event->project->bookProject == "tn") {
            // Notes of level 1,2
            return TnProgress::calculateEventProgress($event, true);
        } elseif ($event->project->bookProject == "tq") {
            // Questions of level 1,2
            return TqProgress::calculateEventProgress($event, true);
        } elseif ($event->project->bookProject == "tw") {
            // Words of level 1,2
            return TwProgress::calculateEventProgress($event, true);
        } elseif ($event->project->bookProject == "sun") {
            // SUNs of level 1, 2
            if ($level == "l1") {
                $progress = SunProgress::calculateEventProgress($event, true);
            } else {
                $progress = SunRevisionProgress::calculateEventProgress($event, true);
            }
            return $progress;
        } elseif (in_array($event->project->bookProject, ["obs", "bc", "bca"])) {
            return ResourceProgress::calculateEventProgress($event, true);
        } else {
            return 0;
        }
    }

    public function getNotification($noteID) {
        return Notification::all()->filter(function($item) use ($noteID) {
            return $item->noteID == $noteID;
        });
    }

    public function getFromNotifications($memberID) {
        return Notification::all()->filter(function($item) use ($memberID) {
            return $item->fromMemberID == $memberID;
        });
    }

    public function getToNotifications($memberID) {
        $member = Member::find($memberID);
        $events = EventUtil::getMemberEvents($member);

        return Notification::all()->filter(function($item) use ($memberID, $events) {
            return ($item->toMemberID == $memberID
                || $item->toMemberID == null)
                && in_array($item->eventID, $events);
        });
    }

    public function notificationExists($eventID, $fromMemberID, $toMemberID, $manageMode, $step, $chapter, $type): bool {
        return !Notification::all()->filter(function($item) use ($eventID, $fromMemberID, $toMemberID, $manageMode, $step, $chapter, $type) {
            return $item->eventID == $eventID
                && $item->fromMemberID == $fromMemberID
                && $item->toMemberID == $toMemberID
                && $item->manageMode == $manageMode
                && $item->step == $step
                && $item->currentChapter == $chapter
                && $item->type == $type;
        })->isEmpty();
    }

    public function createNotification($data, $event, $fromMember, $toMember) {
        $notification = new Notification($data);
        $notification->event()->associate($event);
        $notification->fromMember()->associate($fromMember);
        if ($toMember) {
            $notification->toMember()->associate($toMember);
        }
        $notification->save();
        return $notification;
    }

    public function delete(&$self) {
        $self->delete();
    }

    public function save(&$self) {
        $self->save();
    }

    public function __call($method, $args) {
        return call_user_func_array([$this->event, $method], $args);
    }
}