<?php

namespace Tests\Unit;

use App\Data\Resource\ResourceChunkType as T;
use App\Domain\ParseResource;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ParseResourceTest extends TestCase
{
    private function chunks(string $md, bool $combineImage = false): array
    {
        return array_map(
            fn($c) => [$c->type, $c->text, $c->meta],
            ParseResource::parse($md, 1, $combineImage)->chunks->all()
        );
    }

    public static function lines(): array
    {
        return [
            'heading 1' => ['# Title', [T::HEADING_1, 'Title', '# {}']],
            'heading 3' => ['### Sub', [T::HEADING_3, 'Sub', '### {}']],
            'heading 6' => ['###### Deep', [T::HEADING_6, 'Deep', '###### {}']],
            'italic' => ['_note_', [T::ITALIC, 'note', '_{}_']],
            'numbered item' => ['1. First', [T::LIST_ITEM, 'First', '1. {}']],
            'dash item' => ['- Item', [T::LIST_ITEM, 'Item', '- {}']],
            'star item' => ['* Item', [T::LIST_ITEM, 'Item', '* {}']],
            'plain text' => ['Just text', [T::TEXT, 'Just text', null]],
            'image' => ['![alt](img.png)', [T::IMAGE, null, '![alt](img.png)']],
            'link' => ['See [Word](../word.md)', [T::LINK, 'See Word', 'See [Word](../word.md)']],
        ];
    }

    #[DataProvider('lines')]
    public function testParsesSingleLine(string $md, array $expected): void
    {
        $this->assertSame([$expected], $this->chunks($md));
    }

    public function testSkipsBlankLinesAndHandlesAllLineEndings(): void
    {
        $chunks = $this->chunks("# A\r\n\r\nB\rC\n   \n");

        $this->assertSame(
            [[T::HEADING_1, 'A', '# {}'], [T::TEXT, 'B', null], [T::TEXT, 'C', null]],
            $chunks
        );
    }

    public function testCombineImageAttachesFollowingTextToImage(): void
    {
        $chunks = $this->chunks("![alt](img.png)\nCaption", true);

        $this->assertSame([[T::IMAGE, 'Caption', 'img.png']], $chunks);
    }

    public function testKeepsChapterNumber(): void
    {
        $this->assertSame(5, ParseResource::parse('x', 5)->chapter);
    }
}
