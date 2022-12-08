<?php
/**
 * Created by PhpStorm.
 * User: mXaln
 * Date: 20.06.2016
 * Time: 17:41
 */

namespace Helpers;


class Arrays
{

    public static function append($array, $item)
    {
        if(!is_array($array))
        {
            $array = [];
        }

        if(is_array($item))
        {
            foreach ($item as $elm) {
                $array[] = $elm;
            }
        }
        else
        {
            $array[] = $item;
        }

        return $array;
    }
}