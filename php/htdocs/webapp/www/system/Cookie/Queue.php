<?php

namespace Cookie;

use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class Queue implements HttpKernelInterface
{
    /**
     * The wrapped Kernel implementation.
     *
     * @var HttpKernelInterface
     */
    protected HttpKernelInterface $app;

    /**
     * The cookie jar instance.
     *
     * @var CookieJar
     */
    protected CookieJar $cookies;

    /**
     * Create a new CookieQueue instance.
     *
     * @param HttpKernelInterface $app
     * @param CookieJar $cookies
     * @return void
     */
    public function __construct(HttpKernelInterface $app, CookieJar $cookies)
    {
        $this->app = $app;
        $this->cookies = $cookies;
    }

    /**
     * Handle the given request and get the response.
     *
     * @implements HttpKernelInterface::handle
     *
     * @param Request $request
     * @param int $type
     * @param bool $catch
     * @return Response
     * @throws Exception
     */
    public function handle(Request $request, int $type = HttpKernelInterface::MAIN_REQUEST, bool $catch = true): Response
    {
        $response = $this->app->handle($request, $type, $catch);

        foreach ($this->cookies->getQueuedCookies() as $cookie) {
            $response->headers->setCookie($cookie);
        }

        return $response;
    }

}
