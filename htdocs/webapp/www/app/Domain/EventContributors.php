<?php


namespace App\Domain;


use App\Models\ORM\Member;
use Helpers\Arrays;

class EventContributors
{
    private $event;
    private $level;
    private $mode;
    private $withRoles;

    private $admins = [];
    private $adminsArr = [];
    private $translators = [];
    private $translatorsArr = [];
    private $checkers = [];
    private $checkersArr = [];

    public function __construct($event, $level, $mode = "ulb", $withRoles = true)
    {
        $this->event = $event;
        $this->level = $level;
        $this->mode = $mode;
        $this->withRoles = $withRoles;
    }

    public function get()
    {
        $result = [];

        $this->setAdmins();

        switch ($this->level) {
            case 1:
                $this->setContributorsL1();
                break;
            case 2:
                $this->setContributorsL2();
                break;
            case 3:
                $this->setContributorsL3();
                break;
        }

        $this->setMembers();

        $result["admins"] = $this->admins;
        $result["checkers"] = $this->checkers;
        $result["translators"] = $this->translators;

        return $result;
    }

    private function setAdmins() {
        $this->adminsArr = $this->event->admins->map(function($item) {
            return $item->memberID;
        })->toArray();
    }

    private function setMembers() {
        $allMembers = [];
        $allMembers = Arrays::append($allMembers, $this->adminsArr);
        $allMembers = Arrays::append($allMembers, $this->checkersArr);
        $allMembers = Arrays::append($allMembers, $this->translatorsArr);
        $allMembers = array_unique($allMembers);

        $members = Member::all()->filter(function($item) use ($allMembers) {
            return in_array($item->memberID, $allMembers);
        });

        foreach ($members as $member) {
            if (in_array($member->memberID, $this->adminsArr)) {
                $tmp = [
                    "fname" => trim(mb_convert_case($member->firstName, MB_CASE_TITLE, 'UTF-8')),
                    "lname" => trim(mb_convert_case($member->lastName, MB_CASE_TITLE, 'UTF-8')),
                    "uname" => trim($member->userName),
                    "role" => "",
                    "signup" => $member->created,
                    "email" => $member->email,
                    "tou" => "yes",
                    "sof" => "yes"
                ];
                $this->admins[$member->memberID] = $tmp;
            }
            if (in_array($member->memberID, $this->checkersArr)) {
                $role = "";

                if ($this->withRoles) {
                    $church_role = (array)json_decode($member->profile->church_role);

                    if (in_array("Pastor", $church_role))
                        $role = __('pastor');
                    elseif (in_array("Seminary Professor", $church_role))
                        $role = __('seminary_professor');
                    elseif (in_array("Denominational Leader", $church_role))
                        $role = __('denominational_leader');
                    elseif (in_array("Bishop", $church_role))
                        $role = __('bishop');
                    elseif (in_array("Elder", $church_role))
                        $role = __('elder');
                    elseif (in_array("Teacher", $church_role))
                        $role = __('teacher');
                }

                $tmp = [
                    "fname" => trim(mb_convert_case($member->firstName, MB_CASE_TITLE, 'UTF-8')),
                    "lname" => trim(mb_convert_case($member->lastName, MB_CASE_TITLE, 'UTF-8')),
                    "uname" => trim($member->userName),
                    "role" => $role != "" ? $role : "",
                    "signup" => $member->created,
                    "email" => $member->email,
                    "tou" => "yes",
                    "sof" => "yes"
                ];
                $this->checkers[$member->memberID] = $tmp;
            }
            if (in_array($member->memberID, $this->translatorsArr)) {
                $tmp = [
                    "fname" => trim(mb_convert_case($member->firstName, MB_CASE_TITLE, 'UTF-8')),
                    "lname" => trim(mb_convert_case($member->lastName, MB_CASE_TITLE, 'UTF-8')),
                    "uname" => trim($member->userName),
                    "role" => "",
                    "signup" => $member->created,
                    "email" => $member->email,
                    "tou" => "yes",
                    "sof" => "yes"
                ];
                $this->translators[$member->memberID] = $tmp;
            }
        }
    }

    private function setContributorsL1() {
        foreach ($this->event->translators as $translator) {
            $verbCheck = (array)json_decode($translator->pivot->verbCheck);
            $peerCheck = (array)json_decode($translator->pivot->peerCheck);
            $kwCheck = (array)json_decode($translator->pivot->kwCheck);
            $crCheck = (array)json_decode($translator->pivot->crCheck);
            $otherCheck = (array)json_decode($translator->pivot->otherCheck);

            if (in_array($this->mode, ["tn", "sun", "tw", "tq", "rad", "ulb", "udb"])) {
                $this->checkersArr = Arrays::append($this->checkersArr, array_values(array_map(function ($elm) {
                    return $elm->memberID;
                }, $peerCheck)));
                $this->checkersArr = Arrays::append($this->checkersArr, array_values(array_map(function ($elm) {
                    return $elm->memberID;
                }, $kwCheck)));
                $this->checkersArr = Arrays::append($this->checkersArr, array_values(array_map(function ($elm) {
                    return $elm->memberID;
                }, $crCheck)));
                $this->checkersArr = Arrays::append($this->checkersArr, array_values(array_map(function ($elm) {
                    return $elm->memberID;
                }, $otherCheck)));
            } else {
                $this->checkersArr = Arrays::append($this->checkersArr, array_values(array_map(function ($elm) {
                    return $elm->memberID;
                }, $verbCheck)));
            }
        }
        $this->checkersArr = array_unique($this->checkersArr);

        // Translators
        $data["chapters"] = [];
        for ($i = 1; $i <= $this->event->bookInfo->chaptersNum; $i++) {
            $data["chapters"][$i] = [];
        }
        foreach ($this->event->chapters as $chapter) {
            $tmp["memberID"] = $chapter->memberID;
            $data["chapters"][$chapter["chapter"]] = $tmp;
        }

        foreach ($data["chapters"] as $chapter) {
            if (!empty($chapter))
                $this->translatorsArr[] = $chapter["memberID"];
        }
        $this->translatorsArr = array_unique($this->translatorsArr);
    }

    private function setContributorsL2() {
        if (in_array($this->mode, ["udb","ulb","sun"])) {
            foreach ($this->event->checkersL2 as $checker) {
                $peerCheck = (array)json_decode($checker->pivot->peerCheck);
                $kwCheck = (array)json_decode($checker->pivot->kwCheck);
                $crCheck = (array)json_decode($checker->pivot->crCheck);

                $peerMems = [];
                foreach ($peerCheck as $item) {
                    $peerMems[] = $item->memberID;
                }

                $kwMems = [];
                foreach ($kwCheck as $item) {
                    $kwMems[] = $item->memberID;
                }

                $crMems = [];
                foreach ($crCheck as $item) {
                    $crMems[] = $item->memberID;
                }

                $this->checkersArr = Arrays::append($this->checkersArr, $peerMems);
                $this->checkersArr = Arrays::append($this->checkersArr, $kwMems);
                $this->checkersArr = Arrays::append($this->checkersArr, $crMems);
            }

            $data["chapters"] = [];
            for ($i = 1; $i <= $this->event->bookInfo->chaptersNum; $i++) {
                $data["chapters"][$i] = [];
            }

            foreach ($this->event->chapters as $chapter) {
                $tmp["l2memberID"] = $chapter->l2memberID;
                $data["chapters"][$chapter["chapter"]] = $tmp;
            }

            foreach ($data["chapters"] as $chapter) {
                if (!empty($chapter))
                    $this->checkersArr[] = $chapter["l2memberID"];
            }
            $this->checkersArr = array_unique($this->checkersArr);
        } else {
            $this->setContributorsL1();
        }
    }

    private function setContributorsL3() {
        foreach ($this->event->checkersL3 as $translator) {
            $peerCheck = (array)json_decode($translator->pivot->peerCheck);
            $peerMems = [];
            foreach ($peerCheck as $item) {
                $peerMems[] = $item->memberID;
            }
            $this->checkersArr = Arrays::append($this->checkersArr, $peerMems);
        }

        // Chapters
        $data["chapters"] = [];
        for ($i = 1; $i <= $this->event->bookInfo->chaptersNum; $i++) {
            $data["chapters"][$i] = [];
        }

        foreach ($this->event->chapters as $chapter) {
            $tmp["l3memberID"] = $chapter->l3memberID;
            $data["chapters"][$chapter["chapter"]] = $tmp;
        }

        foreach ($data["chapters"] as $chapter) {
            if (!empty($chapter))
                $this->checkersArr[] = $chapter["l3memberID"];
        }
        $this->checkersArr = array_unique($this->checkersArr);
    }
}