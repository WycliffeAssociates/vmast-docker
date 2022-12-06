<?php
/**
 * Created by PhpStorm.
 * User: Maxim
 * Date: 29 Feb 2016
 * Time: 20:33
 */

namespace Helpers\Constants;


class RadioSections
{
    const ENTRY             = 1;
    const TITLE             = 2;
    const SPEAKERS          = 3;

    private static $enum = [
        self::ENTRY => "entry",
        self::TITLE => "title",
        self::SPEAKERS => "speakers"
    ];

    public static function enum($section)
    {
        if($section > 3) return self::$enum[self::SPEAKERS];

        return self::$enum[$section];
    }
}