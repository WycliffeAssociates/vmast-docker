<?php
/**
 * Created by PhpStorm.
 * User: Maxim
 * Date: 29 Feb 2016
 * Time: 19:41
 */

namespace Helpers\Constants;

class EventCheckSteps
{
    const NONE                  = "none";
    const PRAY                  = "pray";
    const CONSUME               = "consume";
    const SELF_CHECK            = "self-check";
    const PEER_REVIEW           = "peer-review";
    const KEYWORD_CHECK         = "keyword-check";
    const CONTENT_REVIEW        = "content-review";
    const PEER_REVIEW_L3        = "peer-review-l3";
    const PEER_EDIT_L3          = "peer-edit-l3";
    const FINISHED              = "finished";

    private static $enum = [
        "none" => 0,
        "pray" => 1,
        "consume" => 2,
        "self-check" => 3,
        "peer-review" => 4,
        "keyword-check" => 5,
        "content-review" => 6,
        "finished" => 7
        ];

    private static $enumMinor = [
        "none" => 0,
        "pray" => 1,
        "consume" => 2,
        "self-check" => 3,
        "keyword-check" => 4,
        "content-review" => 5,
        "finished" => 6
    ];

    private static $enumSun = [
        "none" => 0,
        "pray" => 1,
        "consume" => 2,
        "self-check" => 3,
        "peer-review" => 4,
        "finished" => 5
    ];

    private static $enumL3 = [
        "none" => 0,
        "pray" => 1,
        "peer-review-l3" => 2,
        "peer-edit-l3" => 3,
        "finished" => 4
    ];

    public static function enum($step, $mode = null)
    {
        switch($mode)
        {
            case "l2_minor":
                return self::$enumMinor[$step];

            case "l2_sun":
                return self::$enumSun[$step];

            case "l3":
                return self::$enumL3[$step];

            default:
                return self::$enum[$step];
        }
    }

    public static function enumArray($mode = null)
    {
        switch($mode)
        {
            case "l2_minor":
                return self::$enumMinor;

            case "l2_sun":
                return self::$enumSun;

            case "l3":
                return self::$enumL3;

            default:
                return self::$enum;
        }
    }
}