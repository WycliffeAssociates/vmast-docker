<?php

namespace Helpers\Manifest;

class MediaFormat {
    const MP3 = "mp3";
    const WAV = "wav";
    const CUE = "cue";
    const PDF = "pdf";
    const JPG = "jpg";
    const PNG = "png";
    const UNKNOWN = "unknown";

    static function valueOf($format): string {
        $value = self::UNKNOWN;

        switch ($format) {
            case self::MP3:
                $value = self::MP3;
                break;
            case self::WAV:
                $value = self::WAV;
                break;
            case self::CUE:
                $value = self::CUE;
                break;
            case self::PDF:
                $value = self::PDF;
                break;
            case self::JPG:
                $value = self::JPG;
                break;
            case self::PNG:
                $value = self::PNG;
                break;

        }

        return $value;
    }
}