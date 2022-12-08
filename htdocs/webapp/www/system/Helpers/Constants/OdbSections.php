<?php
/**
 * Created by PhpStorm.
 * User: Maxim
 * Date: 29 Feb 2016
 * Time: 20:33
 */

namespace Helpers\Constants;


class OdbSections
{
    const TITLE             = 1;
    const AUTHOR            = 2;
    const PASSAGE           = 3;
    const BIBLE_IN_A_YEAR   = 4;
    const VERSE             = 5;
    const THOUGHT           = 6;
    const DATE              = 7;
    const CONTENT           = 8;

    private static $enum = [
        self::TITLE => "title",
        self::AUTHOR => "author",
        self::PASSAGE => "passage",
        self::BIBLE_IN_A_YEAR => "bible_in_a_year",
        self::VERSE => "verse",
        self::THOUGHT => "thought",
        self::DATE => "date",
        self::CONTENT => "content"
    ];

    public static function enum($section)
    {
        if($section > 8) return self::$enum[self::CONTENT];

        return self::$enum[$section];
    }
}