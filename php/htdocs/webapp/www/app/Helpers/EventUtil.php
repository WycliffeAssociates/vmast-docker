<?php

namespace App\Helpers;

use App\Models\MembersModel;
use App\Models\TranslationsModel;

class EventUtil {

    public static function getMemberEvents($member): array {
        $events = [];

        foreach ($member->translators as $translator) {
            $events[] = $translator->eventID;
        }

        foreach ($member->checkersL2 as $translator) {
            $events[] = $translator->eventID;
        }

        foreach ($member->checkersL3 as $translator) {
            $events[] = $translator->eventID;
        }

        return array_unique($events);
    }

    public static function isMemberInEvent($member, $event): bool {
        $events = EventUtil::getMemberEvents($member);
        return in_array($event->eventID, $events);
    }

    public static function makeTurnCredentials(): array {
        $membersModel = new MembersModel();

        $turnSecret = $membersModel->getTurnSecret();
        $turnUsername = (time() + 3600) . ":vmast";
        $turnPassword = "";

        if (!empty($turnSecret)) {
            if (($turnSecret[0]->expire - time()) < 0) {
                $pass = $membersModel->generateStrongPassword(22);
                if ($membersModel->updateTurnSecret(["value" => $pass, "expire" => time() + (30 * 24 * 3600)])) // Update turn secret each month
                {
                    $turnSecret[0]->value = $pass;
                }
            }

            $turnPassword = hash_hmac("sha1", $turnUsername, $turnSecret[0]->value, true);
        }

        $turn = [];
        $turn[] = $turnUsername;
        $turn[] = base64_encode($turnPassword);

        return $turn;
    }

    public static function getComments($eventID, $chapter = null, $chunk = null) {
        $translationModel = new TranslationsModel();

        $comments = $translationModel->getCommentsByEvent($eventID, $chapter, $chunk);
        $commentsFinal = array();

        foreach ($comments as $comment) {
            $commentsFinal[$comment->chapter][$comment->chunk][] = $comment;
        }

        unset($comments);

        return $commentsFinal;
    }

}