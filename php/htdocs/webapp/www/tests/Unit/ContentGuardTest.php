<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Shared\Http\ContentGuard;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/** ContentGuard minifies every HTML response when debug is off, i.e. in production. */
class ContentGuardTest extends TestCase
{
    private function serve(string $content, string $contentType = 'text/html; charset=UTF-8'): string
    {
        $app = new class($content, $contentType) implements HttpKernelInterface {
            public function __construct(private string $content, private string $contentType) {}

            public function handle(Request $request, int $type = self::MAIN_REQUEST, bool $catch = true): Response
            {
                return new Response($this->content, 200, ['Content-Type' => $this->contentType]);
            }
        };

        return (new ContentGuard($app, false))->handle(Request::create('/'))->getContent();
    }

    public function testMinifiesWhitespaceBetweenTags(): void
    {
        $html = "<html>\n  <body>\n\t<p>Hello   world</p>\n  </body>\n</html>";

        // Newlines and tabs next to a tag go; the indentation spaces after them shrink to one
        $this->assertSame('<html> <body><p>Hello world</p> </body></html>', $this->serve($html));
    }

    public function testKeepsSingleSpacesBetweenInlineElements(): void
    {
        $this->assertSame('<b>a</b> <i>b</i>', $this->serve('<b>a</b> <i>b</i>'));
    }

    public function testLeavesNonHtmlResponsesAlone(): void
    {
        $json = "{\n  \"a\":   1\n}";

        $this->assertSame($json, $this->serve($json, 'application/json'));
        $this->assertSame($json, $this->serve($json, 'text/plain'));
    }

    public function testLeavesResponsesWithoutContentTypeAlone(): void
    {
        $app = new class implements HttpKernelInterface {
            public function handle(Request $request, int $type = self::MAIN_REQUEST, bool $catch = true): Response
            {
                $response = new Response("a\n\n  b");
                $response->headers->remove('Content-Type');
                return $response;
            }
        };

        $this->assertSame("a\n\n  b", (new ContentGuard($app, false))->handle(Request::create('/'))->getContent());
    }

    /**
     * Text inside <textarea> is minified like everything else: each whitespace run shrinks to its last character.
     * That is safe for the app's editors: scripture, SUN and OBS text is single-line, so only double spaces
     * become single; notes, questions and words are rendered to HTML (Parsedown) before they reach a textarea.
     */
    public function testCollapsesWhitespaceRunsInsideTextareaToTheirLastCharacter(): void
    {
        $this->assertSame('<textarea>In the beginning</textarea>', $this->serve("<textarea>In the  beginning</textarea>"));
        $this->assertSame(
            "<textarea># Title\nFirst paragraph. 1. Item 2. Item</textarea>",
            $this->serve("<textarea># Title\n\nFirst paragraph.\n\n  1. Item\n  2. Item</textarea>")
        );
    }
}
