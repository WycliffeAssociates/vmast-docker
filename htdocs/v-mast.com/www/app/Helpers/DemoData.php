<?php

namespace App\Helpers;

use Helpers\Constants\EventCheckSteps;
use Helpers\Constants\EventSteps;
use Helpers\Constants\InputMode;
use stdClass;

class DemoData {

    private const MEMBERS = [
        [
            "memberID" => 0,
            "userName" => "test1",
            "firstName" => "John",
            "lastName" => "S."
        ],
        [
            "memberID" => 1,
            "userName" => "test2",
            "firstName" => "Mary",
            "lastName" => "D."
        ],
        [
            "memberID" => 2,
            "userName" => "test3",
            "firstName" => "Henry",
            "lastName" => "W."
        ],
        [
            "memberID" => 3,
            "userName" => "test4",
            "firstName" => "George",
            "lastName" => "H."
        ],
    ];

    private const OBS_SOURCE_TEXT = [
        "en" => [
            [
                "text" => "4. God’s Covenant with Abraham"
            ],
            [
                "text" => "Many years after the flood, there were again many people in the world, and they still sinned against God and each other. Because they all spoke the same language, they gathered together and built a city instead of spreading out over the earth as God had commanded.",
                "img" => "https://cdn.door43.org/obs/jpg/360px/obs-en-04-01.jpg"
            ],
            [
                "text" => "They were very proud, and they did not want to obey God’s commands about how they should live. They even began building a tall tower that would reach heaven. God saw that, if they all kept working together to do evil, they could do many more sinful things.",
                "img" => "https://cdn.door43.org/obs/jpg/360px/obs-en-04-02.jpg"
            ],
            [
                "text" => "So God changed their language into many different languages and spread the people out all over the world. The city they had begun to build was called Babel, which means “confused.”",
                "img" => "https://cdn.door43.org/obs/jpg/360px/obs-en-04-03.jpg"
            ],
            [
                "text" => "Hundreds of years later, God spoke to a man named Abram. God told him, “Leave your country and family and go to the land I will show you. I will bless you and make you a great nation. I will make your name great. I will bless those who bless you and curse those who curse you. All families on earth will be blessed because of you.”",
                "img" => "https://cdn.door43.org/obs/jpg/360px/obs-en-04-04.jpg"
            ],
            [
                "text" => "So Abram obeyed God. He took his wife, Sarai, together with all of his servants and everything he owned and went to the land God showed him, the land of Canaan.",
                "img" => "https://cdn.door43.org/obs/jpg/360px/obs-en-04-05.jpg"
            ],
            [
                "text" => "When Abram arrived in Canaan, God said, “Look all around you. I will give to you all this land, and your descendants will always possess it.” Then Abram settled in the land.",
                "img" => "https://cdn.door43.org/obs/jpg/360px/obs-en-04-06.jpg"
            ],
            [
                "text" => "There was a man named Melchizedek who was a priest of God Most High. One day, after Abram had been in a battle, he and Abram met. Melchizedek blessed Abram and said, “May God Most High who owns heaven and earth bless Abram.” Then Abram gave Melchizedek a tenth of everything he had won in the battle.",
                "img" => "https://cdn.door43.org/obs/jpg/360px/obs-en-04-07.jpg"
            ],
            [
                "text" => "Many years went by, but Abram and Sarai still did not have a son. God spoke to Abram and promised again that he would have a son and as many descendants as the stars in the sky. Abram believed God’s promise. God declared that Abram was righteous because he believed in God’s promise.",
                "img" => "https://cdn.door43.org/obs/jpg/360px/obs-en-04-08.jpg"
            ],
            [
                "text" => "Then God made a covenant with Abram. Normally, a covenant is an agreement between two parties to do things for each other. But in this case, God made a promise to Abram while Abram was in a deep sleep, but he could still hear God. God said, “I will give you a son from your own body. I give the land of Canaan to your descendants.” But Abram still did not have a son.",
                "img" => "https://cdn.door43.org/obs/jpg/360px/obs-en-04-09.jpg"
            ],
            [
                "text" => "A Bible story from: Genesis 11-15"
            ]
        ]
    ];

    private const OBS_TARGET_TEXT = [
        "sgn-US-symbunot" => [
            "     ",
            "                                                 ",
            "                                       ",
            "                            \"  \" ",
            "                                ,                 , \"                                                    \"",
            "       ,                     ",
            "       , \"                     \"     ",
            "                     , \"           \"             ",
            "                                                     ",
            "                            ,       , \"                   \"      ",
            "        "
        ],
        "ru" => [
            "4. Божий завет с Авраамом",
            "Много лет после наводнения, в мире снова было много людей, и они все еще согрешили против Бога и друг друга. Потому что все они говорили на одном языке, они собрались вместе и построили город вместо того, чтобы распространяться над землей, когда Бог повелел.",
            "Они были очень гордыми, и они не хотели подчиняться Божьим командам о том, как они должны жить. Они даже начали строить высокую башню, которая достигла небес. Бог увидел, что, если все они продолжали работать вместе, чтобы сделать зло, они могли бы сделать много более греховных вещей.",
            "Поэтому Бог изменил свой язык на разные языки и распространил людей по всему миру. Город, который они начали строить, назывался Бабелом, что означает «смущенный».",
            "Сотни лет спустя Бог говорил с человеком по имени Абрам. Бог сказал ему: «Оставьте свою страну и семью и пойдем на землю, которую я покажу тебе. Я благословляю тебя и сделаю тебя великой нацией. Я сделаю ваше имя великолепно. Я буду благословлять тех, кто благословит вас и проклинает тех, кто проклинает тебя. Все семьи на земле будут благословлены из-за вас.",
            "Так Абрам повиновался Богу. Он взял свою жену, Сарай вместе со всеми своими слугами и всему, которое он принадлежит и пошел на землю, Бог показал ему, земля Ханаан.",
            "Когда Аврам прибыл в Ханаан, Бог сказал: «Посмотри вокруг вас. Я дам вам всю эту землю, и ваши потомки всегда будут иметь его ». Тогда Абрам поселился на земле.",
            "Был человек по имени Мелхисерек, который был священником Бога самым высоким. Однажды после того, как Аврам был в битве, он и Аврам встретились. Мельхигенек благословил Аврам и сказал: «Пусть Бог самый высокий, кто владеет небесами и благословением земли Аврам». Тогда Абрам дал Мелхиседек на десятую часть всего, что он выиграл в битве.",
            "Много лет прошло, но Аврам и Сарай все еще не было сына. Бог говорил с Абрамом и снова обещал, что у него будет сын и столько потомков, как звезды в небе. Абрам считал Божьим обещанием. Бог заявил, что Аврам был праведным, потому что он верил в Божье обещание.",
            "Тогда Бог сделал завет с Абрамом. Обычно завет - это соглашение между двумя сторонами, чтобы делать вещи друг для друга. Но в этом случае Бог дал обещание Аврам, а Аврам был глубоко сон, но он все еще мог слышать Бога. Бог сказал: «Я дам тебе сына из своего собственного тела. Я даю землю Ханаана ваших потомков ». Но Аврам все еще не было сына.",
            "Библейская история от: Бытие 11-15",
        ]
    ];

    public static function getObsSourceText($language) {
        if (array_key_exists($language, self::OBS_SOURCE_TEXT)) {
            return self::OBS_SOURCE_TEXT[$language];
        }
        return [];
    }

    public static function getObsTargetText($language) {
        if (array_key_exists($language, self::OBS_TARGET_TEXT)) {
            return self::OBS_TARGET_TEXT[$language];
        }
        return [];
    }

    public static function getComments() {
        $comment = self::getComment(self::MEMBERS[0], 1, "A note from Translator");
        $comment2 = self::getComment(self::MEMBERS[1], 1, "A note from Checker");

        return [[$comment, $comment2]];
    }

    public static function getRevisionComments() {
        $comment = self::getComment(self::MEMBERS[0], 1, "A note from Translator");
        $comment2 = self::getComment(self::MEMBERS[1], 2, "A note from Revision checker");
        $comment3 = self::getComment(self::MEMBERS[2], 2, "A note from another Revision checker");

        return [[$comment, $comment2, $comment3]];
    }

    public static function getReviewComments() {
        $comment = self::getComment(self::MEMBERS[0], 1, "A note from Translator");
        $comment2 = self::getComment(self::MEMBERS[1], 2, "A note from Revision checker");
        $comment3 = self::getComment(self::MEMBERS[2], 3, "A note from Review checker");
        $comment4 = self::getComment(self::MEMBERS[3], 3, "A note from another Review checker");

        return [[$comment, $comment2, $comment3, $comment4]];
    }

    public static function getHelpComments() {
        $comment = self::getComment(self::MEMBERS[0], 1, "A note from Translator");
        $comment2 = self::getComment(self::MEMBERS[1], 2, "A note from Checker");
        return [[$comment, $comment2]];
    }

    public static function getScriptureNotifications(
        $resource,
        $bookCode,
        $bookName,
        $langName,
        $chapter,
        $level = "l1",
        $sourceBible = "ulb",
        $inputMode = InputMode::NORMAL
    ) {
        $notifications = [];

        $isSunL1 = $level == "l1" && $resource == "sun";
        $isSunL2 = $level == "l2" && $resource == "sun";

        if ($inputMode == InputMode::SPEECH_TO_TEXT) {
            $count = 1;
        } elseif ($level == "l3") {
            $count = 1;
        } elseif ($isSunL2) {
            $count = 1;
        } elseif ($isSunL1) {
            $count = 2;
        } elseif ($resource == "rad") {
            $count = 1;
        } else {
            $count = 3;
        }

        for ($i = 0; $i < $count; $i++) {
            $notification = new stdClass();

            if ($i == 0) {
                if ($isSunL1) {
                    $notification->step = EventSteps::THEO_CHECK;
                } else {
                    $notification->step = EventSteps::PEER_REVIEW;
                }
            } elseif ($i == 1) {
                if ($isSunL1) {
                    $notification->step = EventSteps::CONTENT_REVIEW;
                } else {
                    $notification->step = EventSteps::KEYWORD_CHECK;
                }
            } else {
                $notification->step = EventSteps::CONTENT_REVIEW;
            }

            if ($level == "l3")
                $notification->step = EventCheckSteps::PEER_REVIEW_L3;

            $notification->currentChapter = $chapter;
            $notification->firstName = self::MEMBERS[0]["firstName"];
            $notification->lastName = self::MEMBERS[0]["lastName"];
            $notification->bookCode = $bookCode;
            $notification->bookName = $bookName;
            $notification->bookProject = $resource;
            $notification->tLang = $langName;
            $notification->manageMode = $level == "l1" && in_array($resource, ["sun","rad"]) ? $resource : $level;
            $notification->sourceBible = $sourceBible;
            if ($inputMode != InputMode::NORMAL)
                $notification->inputMode = $inputMode;

            $notifications[] = $notification;
        }

        return $notifications;
    }

    public static function getHelpNotifications($resource, $bookCode, $bookName, $langName, $chapter) {
        $notifications = [];

        for ($i = 0; $i < 2; $i++) {
            $notification = new stdClass();

            if ($i == 0) {
                $notification->firstName = self::MEMBERS[0]["firstName"];
                $notification->lastName = self::MEMBERS[0]["lastName"];
                $notification->bookCode = $bookCode;
                $notification->bookName = $bookName;
                $notification->bookProject = $resource;
                $notification->tLang = $langName;
                $notification->step = "other";
            } else {
                $notification->step = EventSteps::PEER_REVIEW;
                $notification->firstName = self::MEMBERS[1]["firstName"];;
                $notification->lastName = self::MEMBERS[1]["lastName"];
                $notification->bookCode = $bookCode;
                $notification->bookName = $bookName;
                $notification->bookProject = $resource;
                $notification->tLang = $langName;
            }
            $notification->manageMode = $resource;
            $notification->sourceBible = "ulb";

            if (is_numeric($chapter)) {
                $notification->currentChapter = $chapter;
            } elseif ($resource == "tw") {
                $notification->group = $chapter;
            } else {
                $notification->word = $chapter;
            }

            $notifications[] = $notification;
        }

        return $notifications;
    }

    private static function getComment($member, $level, $text) {
        $comment = new stdClass();
        $comment->memberID = $member["memberID"];
        $comment->level = $level;
        $comment->text = $text;
        $comment->firstName = $member["firstName"];
        $comment->lastName = $member["lastName"];
        $comment->saved = true;
        $comment->cID = 0;

        return $comment;
    }
}