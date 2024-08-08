<?php

namespace Helpers\UsfmParser\Models\Markers;

class VMarker extends Marker
{
    private static string $verseRegex = "/^ *([0-9]*-?[0-9]*) ?(.*)/";

    public string $verseNumber;
    public int $startingVerse;
    public int $endingVerse;

    public function getIdentifier(): string
    {
        return "v";
    }

    public function preProcess(string $input): string
    {
        preg_match(self::$verseRegex, $input, $matches);
        $this->verseNumber = $matches[1];

        if (!self::isNullOrWhiteSpace($this->verseNumber)) {
            $verseBridgeChars = explode("-", $this->verseNumber);
            $this->startingVerse = $verseBridgeChars[0];
            $this->endingVerse = sizeof($verseBridgeChars) > 1
                && !self::isNullOrWhiteSpace($verseBridgeChars[1]) ?
                    $verseBridgeChars[1] :
                    $this->startingVerse;
        }

        return $matches[2];
    }

    public function getVerseCharacter(): string
    {
        $firstCharacterMarker = $this->getChildMarkers(VPMarker::class);
        if (!empty($firstCharacterMarker)) {
            /** @var VPMarker $marker */
            $marker = $firstCharacterMarker[0];
            return $marker->verseCharacter;
        } else {
            return $this->verseNumber;
        }
    }

    public function getAllowedContents(): array
    {
        return [
            VPMarker::class,
            VPEndMarker::class,
            TLMarker::class,
            TLEndMarker::class,
            ADDMarker::class,
            ADDEndMarker::class,
            BMarker::class,
            BKMarker::class,
            BKEndMarker::class,
            BDMarker::class,
            BDEndMarker::class,
            ITMarker::class,
            ITEndMarker::class,
            EMMarker::class,
            EMEndMarker::class,
            BDITMarker::class,
            BDITEndMarker::class,
            SUPMarker::class,
            SUPEndMarker::class,
            NOMarker::class,
            NOEndMarker::class,
            SCMarker::class,
            SCEndMarker::class,
            NDMarker::class,
            NDEndMarker::class,
            QMarker::class,
            MMarker::class,
            FMarker::class,
            FEndMarker::class,
            FRMarker::class,
            FREndMarker::class,
            SPMarker::class,
            TextBlock::class,
            WMarker::class,
            WEndMarker::class,
            XMarker::class,
            XENDMarker::class,
            CLSMarker::class,
            RQMarker::class,
            RQEndMarker::class,
            PIMarker::class,
            MIMarker::class,
            QSMarker::class,
            QSEndMarker::class,
            QRMarker::class,
            QCMarker::class,
            QDMarker::class,
            QACMarker::class,
            QACEndMarker::class,
            SMarker::class,
            VAMarker::class,
            VAEndMarker::class,
            KMarker::class,
            KEndMarker::class,
            LFMarker::class,
            LIKMarker::class,
            LIKEndMarker::class,
            LITLMarker::class,
            LITLEndMarker::class,
            LIVMarker::class,
            LIVEndMarker::class,
            ORDMarker::class,
            ORDEndMarker::class,
            PMCMarker::class,
            PMOMarker::class,
            PMRMarker::class,
            PNMarker::class,
            PNEndMarker::class,
            PNGMarker::class,
            PNGEndMarker::class,
            PRMarker::class,
            QTMarker::class,
            QTEndMarker::class,
            RBMarker::class,
            RBEndMarker::class,
            SIGMarker::class,
            SIGEndMarker::class,
            SLSMarker::class,
            SLSEndMarker::class,
            WAMarker::class,
            WAEndMarker::class,
            WGMarker::class,
            WGEndMarker::class,
            WHMarker::class,
            WHEndMarker::class,
            WJMarker::class,
            WJEndMarker::class,
            FIGMarker::class,
            FIGEndMarker::class,
            PNMarker::class,
            PNEndMarker::class,
            PROMarker::class,
            PROEndMarker::class,
            REMMarker::class,
            PMarker::class,
            LIMarker::class
        ];
    }

    public function tryInsert(Marker $input): bool
    {
        if ($input::class == VMarker::class) {
            return false;
        }

        if ($input::class == QMarker::class && $input->isPoetryBlock) {
            return false;
        }

        return parent::tryInsert($input);
    }
}