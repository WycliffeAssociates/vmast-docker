<?php

namespace Tests\Unit;

use App\Data\Resource\ResourceMapper;
use App\Domain\ParseResource;
use PHPUnit\Framework\TestCase;
use Support\Collection;

class ResourceMapperTest extends TestCase
{
    public function testRoundTripPreservesChunks(): void
    {
        $resource = new Collection([
            ParseResource::parse("# Title\n_note_\nText", 1),
            ParseResource::parse("- Item", 2),
        ]);

        $array = ResourceMapper::fromResource($resource);

        $this->assertSame(
            ['type' => 'heading_1', 'text' => 'Title', 'meta' => '# {}'],
            $array[0]['chunks'][0]
        );
        // null text/meta become "" in the array form and back to null on the way in
        $this->assertSame('', $array[0]['chunks'][2]['meta']);
        $this->assertEquals($resource, ResourceMapper::toResource($array));
    }
}
