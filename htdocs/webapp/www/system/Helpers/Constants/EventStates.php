<?php
/**
 * Created by PhpStorm.
 * User: Maxim
 * Date: 29 Feb 2016
 * Time: 19:41
 */

namespace Helpers\Constants;


class EventStates
{
    const STARTED       = "started";
    const TRANSLATING   = "translating";
    const TRANSLATED    = "translated";
    const L2_RECRUIT    = "l2_recruit";
    const L2_CHECK      = "l2_check";
    const L2_CHECKED    = "l2_checked";
    const L3_RECRUIT    = "l3_recruit";
    const L3_CHECK      = "l3_check";
    const COMPLETE      = "complete";
    
    private static $enum = [
        "started" => 0,
        "translating" => 1,
        "translated" => 2,
        "l2_recruit" => 3,
        "l2_check" => 4,
        "l2_checked" => 5,
        "l3_recruit" => 6,
        "l3_check" => 7,
        "complete" => 8,
    ];
    
    public static function enum($state)
    {
        return self::$enum[$state];
    }
}