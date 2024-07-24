<?php


namespace App\Domain;


use App\Models\ORM\Member;
use Helpers\Arrays;

class ProjectContributors
{
    private $project;
    private $withRoles;
    private $withAdmins;

    private $contributors = [];
    private $contributorsIDs = [];

    public function __construct($project, $withRoles = true, $withAdmins = true)
    {
        $this->project = $project;
        $this->withRoles = $withRoles;
        $this->withAdmins = $withAdmins;
    }

    public function get()
    {
        foreach ($this->project->events as $event) {
            if ($this->withAdmins) {
                $this->setAdmins($event);
            }

            $this->setTranslators($event->chapters);
            $this->setCheckers($event->translators);
            $this->setCheckersL2($event->checkersL2);
            $this->setCheckersL3($event->checkersL3);
        }

        $this->contributorsIDs = array_unique($this->contributorsIDs);

        $this->setNonNumericMembers();
        $this->setNumericMembers();

        $this->contributors = array_unique($this->contributors, SORT_REGULAR);
        sort($this->contributors);

        return $this->contributors;
    }

    private function setAdmins($event) {
        $this->contributorsIDs += $event->admins->map(function($item) {
            return $item->memberID;
        })->toArray();
    }

    private function setTranslators($chapters) {
        foreach ($chapters as $chapter) {
            if ($chapter->memberID) {
                $this->contributorsIDs[] = $chapter->memberID;
            }
            if ($chapter->l2memberID) {
                $this->contributorsIDs[] = $chapter->l2memberID;
            }
            if ($chapter->l3memberID) {
                $this->contributorsIDs[] = $chapter->l3memberID;
            }
        }
    }

    private function setCheckers($translators) {
        foreach ($translators as $translator) {
            $verbCheck = (array)json_decode($translator->pivot->verbCheck);
            $peerCheck = (array)json_decode($translator->pivot->peerCheck);
            $kwCheck = (array)json_decode($translator->pivot->kwCheck);
            $crCheck = (array)json_decode($translator->pivot->crCheck);
            $otherCheck = (array)json_decode($translator->pivot->otherCheck);

            $this->contributorsIDs = Arrays::append($this->contributorsIDs, array_values(array_map(function ($elm) {
                return $elm->memberID;
            }, $verbCheck)));
            $this->contributorsIDs = Arrays::append($this->contributorsIDs, array_values(array_map(function ($elm) {
                return $elm->memberID;
            }, $peerCheck)));
            $this->contributorsIDs = Arrays::append($this->contributorsIDs, array_values(array_map(function ($elm) {
                return $elm->memberID;
            }, $kwCheck)));
            $this->contributorsIDs = Arrays::append($this->contributorsIDs, array_values(array_map(function ($elm) {
                return $elm->memberID;
            }, $crCheck)));
            $this->contributorsIDs = Arrays::append($this->contributorsIDs, array_values(array_map(function ($elm) {
                return $elm->memberID;
            }, $otherCheck)));
        }
    }

    private function setCheckersL2($checkers) {
        foreach ($checkers as $checker) {
            $peerCheck = (array)json_decode($checker->pivot->peerCheck);
            $kwCheck = (array)json_decode($checker->pivot->kwCheck);
            $crCheck = (array)json_decode($checker->pivot->crCheck);

            $this->contributorsIDs = Arrays::append($this->contributorsIDs, array_values(array_map(function ($elm) {
                return $elm->memberID;
            }, $peerCheck)));
            $this->contributorsIDs = Arrays::append($this->contributorsIDs, array_values(array_map(function ($elm) {
                return $elm->memberID;
            }, $kwCheck)));
            $this->contributorsIDs = Arrays::append($this->contributorsIDs, array_values(array_map(function ($elm) {
                return $elm->memberID;
            }, $crCheck)));
        }
    }

    private function setCheckersL3($checkers) {
        foreach ($checkers as $checker) {
            $peerCheck = (array)json_decode($checker->pivot->peerCheck);

            $this->contributorsIDs = Arrays::append($this->contributorsIDs, array_values(array_map(function ($elm) {
                return $elm->memberID;
            }, $peerCheck)));
        }
    }

    private function setNonNumericMembers() {
        $this->contributors = Arrays::append($this->contributors, array_filter($this->contributorsIDs, function ($elm) {
            return !is_numeric($elm);
        }));

        $this->contributors = array_map(function ($elm) {
            $name = mb_split(" ", $elm);
            return [
                "fname" => trim(mb_convert_case($name[0], MB_CASE_TITLE, 'UTF-8')),
                "lname" => trim(mb_convert_case($name[1] ?? "", MB_CASE_TITLE, 'UTF-8')),
                "uname" => "---",
                "role" => "",
                "signup" => "---",
                "email" => "---",
                "tou" => "yes",
                "sof" => "yes"
            ];
        }, $this->contributors);
    }

    private function setNumericMembers() {
        $filteredNumeric = array_filter($this->contributorsIDs, function ($elm) {
            return is_numeric($elm) && $elm > 0;
        });

        $members = Member::all()->filter(function($item) use ($filteredNumeric) {
            return in_array($item->memberID, $filteredNumeric);
        });

        foreach ($members as $member) {
            if (in_array($member->memberID, $filteredNumeric)) {
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

                $this->contributors[] = $tmp;
            }
        }
    }
}