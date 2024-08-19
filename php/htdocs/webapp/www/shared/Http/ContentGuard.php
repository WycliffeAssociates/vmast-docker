<?php
/**
 * ContentGuard - Implements a Response Content processing.
 *
 * @author Virgil-Adrian Teaca - virgil@giulianaeassociati.com
 * @version 3.0
 */

namespace Shared\Http;

use Helpers\Profiler;
use Forensics\Profiler as QuickProfiler;
use Http\Response;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;


class ContentGuard implements HttpKernelInterface
{
    /**
     * The wrapped Kernel implementation.
     *
     * @var \HttpKernelInterface
     */
    protected HttpKernelInterface $app;

    /**
     * The debug flag.
     *
     * @var bool
     */
    protected bool $debug;


    /**
     * Create a new FrameGuard instance.
     *
     * @param  HttpKernelInterface  $app
     * @return void
     */
    public function __construct(HttpKernelInterface $app, $debug)
    {
        $this->app = $app;

        $this->debug = $debug;
    }

    /**
     * Handle the given request and get the response.
     *
     * @implements HttpKernelInterface::handle
     *
     * @param SymfonyRequest $request
     * @param int $type
     * @param bool $catch
     * @return Response
     * @throws \Exception
     */
    public function handle(SymfonyRequest $request, int $type = HttpKernelInterface::MAIN_REQUEST, bool $catch = true): SymfonyResponse
    {
        $response = $this->app->handle($request, $type, $catch);

        return $this->processResponseContent($response);
    }

    /**
     * Minify the Response instance Content.
     *
     * @param  SymfonyResponse $response
     * @return SymfonyResponse
     */
    protected function processResponseContent(SymfonyResponse $response): SymfonyResponse
    {
        $contentType = $response->headers->get('Content-Type');

        if (! str_is('text/html*', $contentType)) return $response;

        if ($this->debug) {
            // Insert the QuickProfiler Widget in the Response's Content.
            $content = str_replace(
                array(
                    '<!-- DO NOT DELETE! - Forensics Profiler -->',
                    '<!-- DO NOT DELETE! - Profiler -->',
                ),
                array(
                    QuickProfiler::process(true),
                    Profiler::getReport(),
                ),
                $response->getContent()
            );
        } else {
            // Minify the Response's Content.
            $search = array(
                '/>[^\S ]+/s', // Strip whitespaces after tags, except space.
                '/[^\S ]+</s', // Strip whitespaces before tags, except space.
                '/(\s)+/s'      // Shorten multiple whitespace sequences.
            );

            $replace = array('>', '<', '\\1');

            $content = preg_replace($search, $replace, $response->getContent());
        }

        $response->setContent($content);

        return $response;
    }

}
