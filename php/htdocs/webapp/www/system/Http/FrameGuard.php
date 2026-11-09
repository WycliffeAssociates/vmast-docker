<?php

namespace Http;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\Response as SymphonyResponse;


class FrameGuard implements HttpKernelInterface
{
    /**
     * The wrapped Kernel implementation.
     *
     * @var HttpKernelInterface
     */
    protected HttpKernelInterface $app;

    /**
     * Create a new FrameGuard instance.
     *
     * @param HttpKernelInterface $app
     * @return void
     */
    public function __construct(HttpKernelInterface $app)
    {
        $this->app = $app;
    }

    /**
     * Handle the given request and get the response.
     *
     * @implements HttpKernelInterface::handle
     *
     * @param SymfonyRequest $request
     * @param int $type
     * @param bool $catch
     * @return SymphonyResponse
     * @throws \Exception
     */
    public function handle(SymfonyRequest $request, int $type = HttpKernelInterface::MAIN_REQUEST, bool $catch = true): SymphonyResponse
    {
        $response = $this->app->handle($request, $type, $catch);

        $response->headers->set('X-Frame-Options', 'SAMEORIGIN', false);

        return $response;
    }

}
