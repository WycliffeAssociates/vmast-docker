<?php

namespace Helpers\UsfmParser\Models\Markers;

class USFMDocument extends Marker
{
    public array $contents;

    public function getIdentifier(): string
    {
        return "";
    }

    public function getAllowedContents(): array
    {
        return [
            HMarker::class,
            IDEMarker::class,
            IDMarker::class,
            IBMarker::class,
            IQMarker::class,
            ILIMarker::class,
            IOTMarker::class,
            IOMarker::class,
            STSMarker::class,
            USFMMarker::class,
            TOC1Marker::class,
            TOC2Marker::class,
            TOC3Marker::class,
            TOCA1Marker::class,
            TOCA2Marker::class,
            TOCA3Marker::class,
            ISMarker::class,
            MTMarker::class,
            IMTMarker::class,
            IPMarker::class,
            IPIMarker::class,
            IMMarker::class,
            IMIMarker::class,
            IPQMarker::class,
            IMQMarker::class,
            IPRMarker::class,
            CLMarker::class,
            CMarker::class
        ];
    }

    public function __construct()
    {
        parent::__construct();
    }

    public function insert($input): void
    {
        if ($input::class == USFMDocument::class) {
            $this->insertDocument($input);
        } else {
            $this->insertMarker($input);
        }
    }

    public function insertMarker(Marker $input): void
    {
        if (!$this->tryInsert($input)) {
            $this->contents[] = $input;
        }
    }

    public function insertDocument(USFMDocument $document): void
    {
        $this->insertMultiple($document->contents);
    }

    public function insertMultiple(array $input): void
    {
        foreach ($input as $i) {
            $this->insert($i);
        }
    }
}