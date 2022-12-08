<?php
/**
 * Created by PhpStorm.
 * User: mXaln
 * Date: 20.06.2016
 * Time: 17:41
 */

namespace Helpers;


use Helpers\Constants\EventCheckSteps;
use Helpers\Constants\EventSteps;

class Tools {

    /** Parses combined verses (ex. 4-5, 1-4) into array of verses
     * @param string $verse
     * @return array
     */
    public static function parseCombinedVerses($verse) {
        $versesArr = array();
        $verses = explode("-", $verse);

        if(sizeof($verses) < 2) {
            $versesArr[] = $verse;
            return $versesArr;
        }

        $fv = $verses[0];
        $lv = $verses[1];

        for($i=$fv; $i <= $lv; $i++) {
            $versesArr[] = $i;
        }

        return $versesArr;
    }

    /**
     * Unzip file to directory
     * @param $file
     * @param $directory
     * @return bool
     */
    public static function unzip($file, $directory) {
        $zip = new \ZipArchive();
        $res = $zip->open($file);
        if($res === true) {
            $zip->extractTo($directory);
            $zip->close();
            return true;
        }

        return false;
    }

    /**
     * Recursively iterate given directory and return the list of files/subdirs
     * @param $path
     * @return array
     */
    public static function iterateDir($path) {
        $directory = new \RecursiveDirectoryIterator($path,  \FilesystemIterator::SKIP_DOTS);
        $iterator = new \RecursiveIteratorIterator($directory, \RecursiveIteratorIterator::SELF_FIRST);

        $files = [];

        foreach ($iterator as $name => $fileinfo) {
            $files[] = [
                "rel" => str_replace($path, "", $name).($fileinfo->isDir() ? "/" : ""),
                "abs" => $name
            ];
        }

        return $files;
    }

    /**
     * Advanced trim the string
     * @param $mixed
     * @return array|string
     */
    public static function trim($mixed) {
        if(is_array($mixed)) {
            return array_map(function($elm) {
                return Tools::trim($elm);
            }, $mixed);
        } else {
            return trim(html_entity_decode($mixed), " \t\n\r\0\x0B\xC2\xA0");
        }
    }

    /**
     * Advanced strip_tags
     * @param $mixed
     * @return array|string
     */
    public static function strip_tags($mixed) {
        if(is_array($mixed)) {
            return array_map(function($elm) {
                return Tools::strip_tags($elm);
            }, $mixed);
        } else {
            return strip_tags($mixed);
        }
    }

    /**
     * Advanced html_entity_decode
     * @param $mixed
     * @return array|string
     */
    public static function html_entity_decode($mixed) {
        if(is_array($mixed)) {
            return array_map(function($elm) {
                return Tools::html_entity_decode($elm);
            }, $mixed);
        } else {
            return html_entity_decode($mixed);
        }
    }

    /**
     * Advanced htmlentities
     * @param $mixed
     * @return array|string
     */
    public static function htmlentities($mixed) {
        if(is_array($mixed)) {
            return array_map(function($elm) {
                return Tools::htmlentities($elm);
            }, $mixed);
        } else {
            return htmlentities($mixed, ENT_QUOTES|ENT_SUBSTITUTE);
        }
    }

    /**
     * Advanced htmlentities
     * @param $mixed
     * @return array|string
     */
    public static function has_empty($mixed) {
        if(is_array($mixed)) {
            foreach ($mixed as $elm) {
                if(is_array($elm)) {
                    return Tools::empty(Tools::trim($elm));
                } else {
                    return empty(Tools::trim($elm));
                }
            }
        } else {
            return empty(Tools::trim($mixed));
        }
    }

    /**
     * Define if project is HELP
     * @param $mode
     * @return boolean
     */
    public static function isHelp($mode) {
        return in_array($mode, ["tn","tq","tw","obs","bc","bca"]);
    }

    /**
     * Define if project is extended HELP
     * @param $mode
     * @return boolean
     */
    public static function isHelpExtended($mode) {
        return in_array($mode, ["tn","tq","tw","sun","rad","obs","bc","bca"]);
    }

    /**
     * Define if project is scripture (has scripture books)
     * @param $mode
     * @return boolean
     */
    public static function isScripture($mode) {
        return !in_array($mode, ["obs","tw","rad","odb","bca"]);
    }

    /**
     * Get appropriate step name based on mode
     * @param $mode
     * @param $isCheck
     * @return string
     */
    public static function getStepName($step, $mode, $isCheck) {
        $modifiedStep = $step;
        if ($mode == "tn") {
            if ($step != EventSteps::PRAY && $step != EventSteps::BLIND_DRAFT) {
                $modifiedStep = $step . "_tn";
                if ($isCheck && $step == EventSteps::SELF_CHECK) {
                    $modifiedStep .= "_chk";
                }
            }
        } elseif ($mode == "odbsun") {
            if (in_array($step, [EventSteps::CONSUME, EventSteps::THEO_CHECK, EventSteps::CONTENT_REVIEW])) {
                $modifiedStep .= "_odb";
            }
        } elseif ($mode == "sun" && $step == EventSteps::CHUNKING) {
            $modifiedStep .= "_sun";
        } elseif ($mode == "l2_minor" && $step == EventSteps::SELF_CHECK) {
            $modifiedStep = EventSteps::PEER_REVIEW;
        } elseif ($mode == "l2_sun" && ($step == EventSteps::SELF_CHECK || $step == EventSteps::PEER_REVIEW)) {
            $modifiedStep .= "_sun";
        }
        return $modifiedStep;
    }
}