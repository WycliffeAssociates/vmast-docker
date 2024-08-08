<?php

namespace Helpers\UsfmParser;

use Helpers\UsfmParser\Models\ConvertToMarkerResult;
use Helpers\UsfmParser\Models\Markers;

class USFMParser
{
    /** @var array|string[] */
    private array $ignoredTags;
    private bool $ignoreUnknownMarkers;
    private static string $splitRegex = "/\\\\([a-z0-9-]*\**)([^\\\\]*)/";

    /**
     * @param ?string[] $tagsToIgnore
     * @param bool $ignoreUnknownMarkers
     */
    public function __construct(array $tagsToIgnore = null, bool $ignoreUnknownMarkers = false)
    {
        $this->ignoredTags = $tagsToIgnore ?? [];
        $this->ignoreUnknownMarkers = $ignoreUnknownMarkers;
    }

    /**
     * Parses a string into a USFMDocument
     * @param string $input A USFM string
     * @return Markers\USFMDocument A USFMDocument representing the input
     */
    public function parseFromString(string $input): Markers\USFMDocument
    {
        $output = new Markers\USFMDocument();
        $markers = $this->tokenizeFromString($input);

        // Clean out extra whitespace where it isn't needed
        $markers = $this->cleanWhitespace($markers);

        for ($markerIndex = 0; $markerIndex < sizeof($markers); $markerIndex++) {
            $marker = $markers[$markerIndex];
            if ($marker::class == Markers\TRMarker::class &&
                !in_array(Markers\TableBlock::class, $output->getTypesPathToLastMarker())) {
                $output->insert(new Markers\TableBlock());
            }

            if ($marker::class == Markers\QMarker::class &&
                $markerIndex != sizeof($markers) - 1 &&
                $markers[$markerIndex + 1]::class == Markers\VMarker::class) {
                $marker->isPoetryBlock = true;
                $output->insert($marker);
            } else {
                $output->insert($marker);
            }
        }

        return $output;
    }

    /**
     * Removes all the unnecessary whitespace while preserving space between closing markers and opening markers
     * @param Markers\Marker[] $input
     * @return Markers\Marker[]
     */
    private function cleanWhitespace(array $input): array
    {
        /** @var Markers\Marker[] $output */
        $output = [];

        for ($index = 0; $index < sizeof($input); $index++) {
            $block = $input[$index];
            if (!($block::class == Markers\TextBlock::class && Markers\Marker::isNullOrWhiteSpace($block->text))) {
                $output[] = $block;
                continue;
            }

            // If this is an empty text block at the beginning remove it
            if ($index == 0) {
                continue;
            }

            // If this is an empty text block at the end then remove it
            if ($index == sizeof($input) - 1) {
                continue;
            }

            // If this isn't between and end marker and another marker then delete it
            $prev = $input[$index - 1];
            $next = $input[$index + 1];
            if (!(str_ends_with($prev->getIdentifier(), "*") && !str_ends_with($next->getIdentifier(), "*"))) {
                continue;
            }

            $output[] = $input[$index];
        }
        return $output;
    }

    /**
     * Generate a list of Markers from a string
     * @param string $input USFM String to tokenize
     * @return Markers\Marker[] A List of Markers based upon the string
     */
    private function tokenizeFromString(string $input): array
    {
        /** @var Markers\Marker[] $output */
        $output = [];
        $matches = [];
        preg_match_all(self::$splitRegex, $input, $matches, PREG_SET_ORDER);

        foreach ($matches as $index => $match) {
            if (in_array($match[1], $this->ignoredTags)) {
                continue;
            }

            $result = $this->convertToMarker($match[1], $match[2]);
            $resultMarker = $result->marker;
            $resultMarker->position = $index;

            // If this is an unknown marker, and we're in Ignore Unknown Marker mode then don't add the marker.
            // We still keep any remaining text though
            if ($resultMarker::class == Markers\UnknownMarker::class) {
                if ($this->ignoreUnknownMarkers) {
                    $output[] = new Markers\TextBlock($resultMarker->parsedValue);
                } else {
                    $output[] = $resultMarker;
                }
            } else {
                $output[] = $resultMarker;
            }

            if (!Markers\Marker::isNullOrWhiteSpace($result->remainingText)) {
                $output[] = new Markers\TextBlock($result->remainingText);
            }
        }

        return $output;
    }


    /**
     * @param string $identifier
     * @param string $value
     * @return ConvertToMarkerResult
     */
    private function convertToMarker(string $identifier, string $value): ConvertToMarkerResult
    {
        $output = $this->selectMarker($identifier);
        $tmp = $output->preProcess($value);

        return new ConvertToMarkerResult($output, $tmp);
    }

    /**
     * @param string $identifier
     * @return Markers\Marker
     */
    private function selectMarker(string $identifier): Markers\Marker
    {
        switch ($identifier) {
            case "id":
                return new Markers\IDMarker();
            case "ide":
                return new Markers\IDEMarker();
            case "sts":
                return new Markers\STSMarker();
            case "h":
                return new Markers\HMarker();
            case "toc1":
                return new Markers\TOC1Marker();
            case "toc2":
                return new Markers\TOC2Marker();
            case "toc3":
                return new Markers\TOC3Marker();
            case "toca1":
                return new Markers\TOCA1Marker();
            case "toca2":
                return new Markers\TOCA2Marker();
            case "toca3":
                return new Markers\TOCA3Marker();

            /* Introduction Markers*/
            case "imt":
            case "imt1":
                return new Markers\IMTMarker();
            case "imt2":
                $imt2Marker = new Markers\IMTMarker();
                $imt2Marker->weight = 2;
                return $imt2Marker;
            case "imt3":
                $imt3Marker = new Markers\IMTMarker();
                $imt3Marker->weight = 3;
                return $imt3Marker;
            case "is":
            case "is1":
                return new Markers\ISMarker();
            case "is2":
                $is2Marker = new Markers\ISMarker();
                $is2Marker->weight = 2;
                return $is2Marker;
            case "is3":
                $is3Marker = new Markers\ISMarker();
                $is3Marker->weight = 3;
                return $is3Marker;
            case "ib":
                return new Markers\IBMarker();
            case "iq":
            case "iq1":
                return new Markers\IQMarker();
            case "iq2":
                $iq2Marker = new Markers\IQMarker();
                $iq2Marker->depth = 2;
                return $iq2Marker;
            case "iq3":
                $iq3Marker = new Markers\IQMarker();
                $iq3Marker->depth = 3;
                return $iq3Marker;
            case "iot":
                return new Markers\IOTMarker();
            case "io":
            case "io1":
                return new Markers\IOMarker();
            case "io2":
                $io2Marker = new Markers\IOMarker();
                $io2Marker->depth = 2;
                return $io2Marker;
            case "io3":
                $io3Marker = new Markers\IOMarker();
                $io3Marker->depth = 3;
                return $io3Marker;
            case "ior":
                return new Markers\IORMarker();
            case "ior*":
                return new Markers\IOREndMarker();
            case "ili":
            case "ili1":
                return new Markers\ILIMarker();
            case "ili2":
                $ili2Marker = new Markers\ILIMarker();
                $ili2Marker->depth = 2;
                return $ili2Marker;
            case "ili3":
                $ili3Marker = new Markers\ILIMarker();
                $ili3Marker->depth = 3;
                return $ili3Marker;
            case "ip":
                return new Markers\IPMarker();
            case "ipi":
                return new Markers\IPIMarker();
            case "im":
                return new Markers\IMMarker();
            case "imi":
                return new Markers\IMIMarker();
            case "ipq":
                return new Markers\IPQMarker();
            case "imq":
                return new Markers\IMQMarker();
            case "ipr":
                return new Markers\IPRMarker();
            case "mt":
            case "mt1":
                return new Markers\MTMarker();
            case "mt2":
                $mt2Marker = new Markers\MTMarker();
                $mt2Marker->weight = 2;
                return $mt2Marker;
            case "mt3":
                $mt3Marker = new Markers\MTMarker();
                $mt3Marker->weight = 3;
                return $mt3Marker;
            case "c":
                return new Markers\CMarker();
            case "cp":
                return new Markers\CPMarker();
            case "ca":
                return new Markers\CAMarker();
            case "ca*":
                return new Markers\CAEndMarker();
            case "p":
                return new Markers\PMarker();
            case "v":
                return new Markers\VMarker();
            case "va":
                return new Markers\VAMarker();
            case "va*":
                return new Markers\VAEndMarker();
            case "vp":
                return new Markers\VPMarker();
            case "vp*":
                return new Markers\VPEndMarker();
            case "q":
            case "q1":
                return new Markers\QMarker();
            case "q2":
                $q2Marker = new Markers\QMarker();
                $q2Marker->depth = 2;
                return $q2Marker;
            case "q3":
                $q3Marker = new Markers\QMarker();
                $q3Marker->depth = 3;
                return $q3Marker;
            case "q4":
                $q4Marker = new Markers\QMarker();
                $q4Marker->depth = 4;
                return $q4Marker;
            case "qr":
                return new Markers\QRMarker();
            case "qc":
                return new Markers\QCMarker();
            case "qd":
                return new Markers\QDMarker();
            case "qac":
                return new Markers\QACMarker();
            case "qac*":
                return new Markers\QACEndMarker();
            case "qm":
            case "qm1":
                return new Markers\QMMarker();
            case "qm2":
                $qm2Marker = new Markers\QMMarker();
                $qm2Marker->depth = 2;
                return $qm2Marker;
            case "qm3":
                $qm3Marker = new Markers\QMMarker();
                $qm3Marker->depth = 3;
                return $qm3Marker;

            case "m":
                return new Markers\MMarker();
            case "d":
                return new Markers\DMarker();
            case "ms":
            case "ms1":
                return new Markers\MSMarker();
            case "ms2":
                $ms2Marker = new Markers\MSMarker();
                $ms2Marker->weight = 2;
                return $ms2Marker;
            case "ms3":
                $ms3Marker = new Markers\MSMarker();
                $ms3Marker->weight = 3;
                return $ms3Marker;
            case "mr":
                return new Markers\MRMarker();
            case "cl":
                return new Markers\CLMarker();
            case "qs":
                return new Markers\QSMarker();
            case "qs*":
                return new Markers\QSEndMarker();
            case "f":
                return new Markers\FMarker();
            case "fp":
                return new Markers\FPMarker();
            case "qa":
                return new Markers\QAMarker();
            case "nb":
                return new Markers\NBMarker();
            case "fqa":
                return new Markers\FQAMarker();
            case "fqa*":
                return new Markers\FQAEndMarker();
            case "fq":
                return new Markers\FQMarker();
            case "fq*":
                return new Markers\FQEndMarker();
            case "pi":
            case "pi1":
                return new Markers\PIMarker();
            case "pi2":
                $pi2Marker = new Markers\PIMarker();
                $pi2Marker->depth = 2;
                return $pi2Marker;
            case "pi3":
                $pi3Marker = new Markers\PIMarker();
                $pi3Marker->depth = 3;
                return $pi3Marker;
            case "sp":
                return new Markers\SPMarker();
            case "ft":
                return new Markers\FTMarker();
            case "fr":
                return new Markers\FRMarker();
            case "fr*":
                return new Markers\FREndMarker();
            case "fk":
                return new Markers\FKMarker();
            case "fv":
                return new Markers\FVMarker();
            case "fv*":
                return new Markers\FVEndMarker();
            case "f*":
                return new Markers\FEndMarker();
            case "bd":
                return new Markers\BDMarker();
            case "bd*":
                return new Markers\BDEndMarker();
            case "it":
                return new Markers\ITMarker();
            case "it*":
                return new Markers\ITEndMarker();
            case "rem":
                return new Markers\REMMarker();
            case "b":
                return new Markers\BMarker();
            case "s":
            case "s1":
                return new Markers\SMarker();
            case "s2":
                $s2Marker = new Markers\SMarker();
                $s2Marker->weight = 2;
                return $s2Marker;
            case "s3":
                $s3Marker = new Markers\SMarker();
                $s3Marker->weight = 3;
                return $s3Marker;
            case "s4":
                $s4Marker = new Markers\SMarker();
                $s4Marker->weight = 4;
                return $s4Marker;
            case "s5":
                $s5Marker = new Markers\SMarker();
                $s5Marker->weight = 5;
                return $s5Marker;
            case "bk":
                return new Markers\BKMarker();
            case "bk*":
                return new Markers\BKEndMarker();
            case "li":
            case "li1":
                return new Markers\LIMarker();
            case "li2":
                $li2Marker = new Markers\LIMarker();
                $li2Marker->depth = 2;
                return $li2Marker;
            case "li3":
                $li3Marker = new Markers\LIMarker();
                $li3Marker->depth = 3;
                return $li3Marker;
            case "add":
                return new Markers\ADDMarker();
            case "add*":
                return new Markers\ADDEndMarker();
            case "tl":
                return new Markers\TLMarker();
            case "tl*":
                return new Markers\TLEndMarker();
            case "mi":
                return new Markers\MIMarker();
            case "sc":
                return new Markers\SCMarker();
            case "sc*":
                return new Markers\SCEndMarker();
            case "r":
                return new Markers\RMarker();
            case "rq":
                return new Markers\RQMarker();
            case "rq*":
                return new Markers\RQEndMarker();
            case "w":
                return new Markers\WMarker();
            case "w*":
                return new Markers\WEndMarker();
            case "x":
                return new Markers\XMarker();
            case "x*":
                return new Markers\XEndMarker();
            case "xo":
                return new Markers\XOMarker();
            case "xt":
                return new Markers\XTMarker();
            case "xq":
                return new Markers\XQMarker();
            case "pc":
                return new Markers\PCMarker();
            case "cls":
                return new Markers\CLSMarker();
            case "tr":
                return new Markers\TRMarker();
            case "th1":
                return new Markers\THMarker();
            case "thr1":
                return new Markers\THRMarker();
            case "th2":
                $th2Marker = new Markers\THMarker();
                $th2Marker->columnPosition = 2;
                return $th2Marker;
            case "thr2":
                $thr2Marker = new Markers\THRMarker();
                $thr2Marker->columnPosition = 2;
                return $thr2Marker;
            case "th3":
                $th3Marker = new Markers\THMarker();
                $th3Marker->columnPosition = 3;
                return $th3Marker;
            case "thr3":
                $thr3Marker = new Markers\THRMarker();
                $thr3Marker->columnPosition = 3;
                return $thr3Marker;
            case "tc1":
                return new Markers\TCMarker();
            case "tcr1":
                return new Markers\TCRMarker();
            case "tc2":
                $tc2Marker = new Markers\TCMarker();
                $tc2Marker->columnPosition = 2;
                return $tc2Marker;
            case "tcr2":
                $tcr2Marker = new Markers\TCRMarker();
                $tcr2Marker->columnPosition = 2;
                return $tcr2Marker;
            case "tc3":
                $tc3Marker = new Markers\TCMarker();
                $tc3Marker->columnPosition = 3;
                return $tc3Marker;
            case "tcr3":
                $tcr3Marker = new Markers\TCRMarker();
                $tcr3Marker->columnPosition = 3;
                return $tcr3Marker;
            case "usfm":
                return new Markers\USFMMarker();
            /* Character Styles */
            case "em":
                return new Markers\EMMarker();
            case "em*":
                return new Markers\EMEndMarker();
            case "bdit":
                return new Markers\BDITMarker();
            case "bdit*":
                return new Markers\BDITEndMarker();
            case "no":
                return new Markers\NOMarker();
            case "no*":
                return new Markers\NOEndMarker();
            case "k":
                return new Markers\KMarker();
            case "k*":
                return new Markers\KEndMarker();
            case "lf":
                return new Markers\LFMarker();
            case "lik":
                return new Markers\LIKMarker();
            case "lik*":
                return new Markers\LIKEndMarker();
            case "litl":
                return new Markers\LITLMarker();
            case "litl*":
                return new Markers\LITLEndMarker();
            case "liv":
                return new Markers\LIVMarker();
            case "liv*":
                return new Markers\LIVEndMarker();
            case "ord":
                return new Markers\ORDMarker();
            case "ord*":
                return new Markers\ORDEndMarker();
            case "pmc":
                return new Markers\PMCMarker();
            case "pmo":
                return new Markers\PMOMarker();
            case "pmr":
                return new Markers\PMRMarker();
            case "png":
                return new Markers\PNGMarker();
            case "png*":
                return new Markers\PNGEndMarker();
            case "pr":
                return new Markers\PRMarker();
            case "qt":
                return new Markers\QTMarker();
            case "qt*":
                return new Markers\QTEndMarker();
            case "rb":
                return new Markers\RBMarker();
            case "rb*":
                return new Markers\RBEndMarker();
            case "sig":
                return new Markers\SIGMarker();
            case "sig*":
                return new Markers\SIGEndMarker();
            case "sls":
                return new Markers\SLSMarker();
            case "sls*":
                return new Markers\SLSEndMarker();
            case "wa":
                return new Markers\WAMarker();
            case "wa*":
                return new Markers\WAEndMarker();
            case "wg":
                return new Markers\WGMarker();
            case "wg*":
                return new Markers\WGEndMarker();
            case "wh":
                return new Markers\WHMarker();
            case "wh*":
                return new Markers\WHEndMarker();
            case "wj":
                return new Markers\WJMarker();
            case "wj*":
                return new Markers\WJEndMarker();
            case "nd":
                return new Markers\NDMarker();
            case "nd*":
                return new Markers\NDEndMarker();
            case "sup":
                return new Markers\SUPMarker();
            case "sup*":
                return new Markers\SUPEndMarker();
            case "ie":
                return new Markers\IEMarker();
            case "pn":
                return new Markers\PNMarker();
            case "pn*":
                return new Markers\PNEndMarker();
            case "pro":
                return new Markers\PROMarker();
            case "pro*":
                return new Markers\PROEndMarker();


            /* Special Features */
            case "fig":
                return new Markers\FIGMarker();
            case "fig*":
                return new Markers\FIGEndMarker();

            default:
                $unknownMarker = new Markers\UnknownMarker();
                $unknownMarker->parsedIdentifier = $identifier;
                return $unknownMarker;
        }
    }
}