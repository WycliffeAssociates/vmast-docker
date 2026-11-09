<?php
/**
 * Inflector Helper
 *
 * @author Virgil-Adrian Teaca - virgil@giulianaeassociati.com
 * @version 1.0
 */

namespace Helpers;

use Doctrine\Inflector\InflectorFactory;

class Inflector
{
    public static function tableize($word): string
    {
        return InflectorFactory::create()->build()->tableize($word);
    }

    public static function classify($word): string
    {
        return InflectorFactory::create()->build()->classify($word);
    }

    public static function camelize($word): string
    {
        return InflectorFactory::create()->build()->camelize($word);
    }

    public static function capitalize($word): string
    {
        return InflectorFactory::create()->build()->capitalize($word);
    }

    public static function seemsUtf8($string): bool
    {
        return InflectorFactory::create()->build()->seemsUtf8($string);
    }

    public static function unaccent($string): string
    {
        return InflectorFactory::create()->build()->unaccent($string);
    }

    public static function urlize($string): string {
        return InflectorFactory::create()->build()->urlize($string);
    }

    public static function singularize($word): string
    {
        return InflectorFactory::create()->build()->singularize($word);
    }

    public static function pluralize($word): string
    {
        return InflectorFactory::create()->build()->pluralize($word);
    }
}
