<?php

namespace Session;

use Exception;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

use Carbon\Carbon;

use Closure;


class Middleware implements HttpKernelInterface
{
    /**
     * The wrapped Kernel implementation.
     *
     * @var HttpKernelInterface
     */
    protected HttpKernelInterface $app;

    /**
     * The session manager.
     *
     * @var SessionManager
     */
    protected SessionManager $manager;

    /**
     * The callback to determine to use session arrays.
     *
     * @var Closure|null
     */
    protected ?Closure $reject;

    /**
     * Create a new session middleware.
     *
     * @param HttpKernelInterface $app
     * @param SessionManager $manager
     * @param Closure|null  $reject
     * @return void
     */
    public function __construct(HttpKernelInterface $app, SessionManager $manager, Closure $reject = null)
    {
        $this->app = $app;

        $this->reject  = $reject;
        $this->manager = $manager;
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
        $this->checkRequestForArraySessions($request);

        // If a session driver has been configured, we will need to start the session here
        // so that the data is ready for an application. Note that the Laravel sessions
        // do not make use of PHP "native" sessions in any way since they are crappy.
        if ($this->sessionConfigured()) {
            $session = $this->startSession($request);

            $request->setSession($session);
        }

        $response = $this->app->handle($request, $type, $catch);

        // Again, if the session has been configured we will need to close out the session
        // so that the attributes may be persisted to some storage medium. We will also
        // add the session identifier cookie to the application response headers now.
        if ($this->sessionConfigured()) {
            $this->closeSession($session);

            $this->addCookieToResponse($response, $session);
        }

        return $response;
    }

    /**
     * Check the request and reject callback for array sessions.
     *
     * @param Request $request
     * @return void
     */
    public function checkRequestForArraySessions(Request $request)
    {
        if (is_null($this->reject)) return;

        if (call_user_func($this->reject, $request)) {
            $this->manager->setDefaultDriver('array');
        }
    }

    /**
     * Start the session for the given request.
     *
     * @param Request $request
     * @return SessionInterface
     */
    protected function startSession(Request $request): SessionInterface
    {
        with($session = $this->getSession($request))->setRequestOnHandler($request);

        $session->start();

        return $session;
    }

    /**
     * Close the session handling for the request.
     *
     * @param SessionInterface $session
     * @return void
     */
    protected function closeSession(SessionInterface $session)
    {
        $session->save();

        $this->collectGarbage($session);
    }

    /**
     * Get the full URL for the request.
     *
     * @param Request $request
     * @return string
     */
    protected function getUrl(Request $request): string
    {
        $url = rtrim(preg_replace('/\?.*/', '', $request->getUri()), '/');

        return $request->getQueryString() ? $url.'?'.$request->getQueryString() : $url;
    }

    /**
     * Remove the garbage from the session if necessary.
     *
     * @param SessionInterface $session
     * @return void
     */
    protected function collectGarbage(SessionInterface $session)
    {
        $config = $this->manager->getSessionConfig();

        // Here we will see if this request hits the garbage collection lottery by hitting
        // the odds needed to perform garbage collection on any given request. If we do
        // hit it, we'll call this handler to let it delete all the expired sessions.
        if ($this->configHitsLottery($config)) {
            $session->getHandler()->gc($this->getLifetimeSeconds());
        }
    }

    /**
     * Determine if the configuration odds hit the lottery.
     *
     * @param  array  $config
     * @return bool
     */
    protected function configHitsLottery(array $config): bool
    {
        return mt_rand(1, $config['lottery'][1]) <= $config['lottery'][0];
    }

    /**
     * Add the session cookie to the application response.
     *
     * @param Response $response
     * @param SessionInterface $session
     * @return void
     */
    protected function addCookieToResponse(Response $response, SessionInterface $session)
    {
        if ($this->sessionIsPersistent($cookie = $this->manager->getSessionConfig())) {
            $secure = array_get($cookie, 'secure', false);

            $response->headers->setCookie(new Cookie(
                $session->getName(),
                $session->getId(),
                $this->getCookieLifetime(),
                $cookie['path'],
                $cookie['domain'],
                $secure
            ));
        }
    }

    /**
     * Get the session lifetime in seconds.
     *
     *
     */
    protected function getLifetimeSeconds(): float|int
    {
        return array_get($this->manager->getSessionConfig(), 'lifetime') * 60;
    }

    /**
     * Get the cookie lifetime in seconds.
     *
     * @return Carbon|int
     */
    protected function getCookieLifetime(): Carbon|int
    {
        $config = $this->manager->getSessionConfig();

        return $config['expireOnClose'] ? 0 : Carbon::now()->addMinutes($config['lifetime']);
    }

    /**
     * Determine if a session driver has been configured.
     *
     * @return bool
     */
    protected function sessionConfigured(): bool
    {
        return ! is_null(array_get($this->manager->getSessionConfig(), 'driver'));
    }

    /**
     * Determine if the configured session driver is persistent.
     *
     * @param  array|null  $config
     * @return bool
     */
    protected function sessionIsPersistent(array $config = null): bool
    {
        // Some session drivers are not persistent, such as the test array driver or even
        // when the developer don't have a session driver configured at all, which the
        // session cookies will not need to get set on any responses in those cases.
        $config = $config ?: $this->manager->getSessionConfig();

        return ! in_array($config['driver'], array(null, 'array'));
    }

    /**
     * Get the session implementation from the manager.
     *
     * @param Request $request
     * @return SessionInterface
     */
    public function getSession(Request $request): SessionInterface
    {
        $session = $this->manager->driver();

        $session->setId($request->cookies->get($session->getName()));

        return $session;
    }

}
