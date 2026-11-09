<?php
/**
 * ReCaptcha - Cloudflare Turnstile anti-spam protection (class name kept for existing callers).
 *
 * @author Virgil-Adrian Teaca - virgil@giulianaeassociati.com
 * @version 3.0
 */

namespace Helpers;

use Config\Config;

use Support\Facades\Request as HttpRequest;


/**
 * ReCaptcha: Cloudflare Turnstile anti-spam protection for your website.
 */
class ReCaptcha
{
    /**
     * Cloudflare Turnstile siteverify url.
     */
    const VERIFY_URL = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

    /**
     * Array holding the configuration.
     */
    protected $config;


    public function __construct()
    {
        $this->config = Config::get('recaptcha', array());
    }

    /**
     * Get the Status
     *
     * @return string
     */
    protected function isActive()
    {
        return array_get($this->config, 'active' , false);
    }

    /**
     * Get the Site Key
     *
     * @return string
     */
    protected function getSiteKey()
    {
        return array_get($this->config, 'siteKey' , null);
    }

    /**
     * Get the Secret
     *
     * @return string
     */
    protected function getSecretkey()
    {
        return array_get($this->config, 'secret' , null);
    }

    /**
     * Widget appearance: hidden unless needed, visible after a failed captcha.
     *
     * @param  mixed $error  the view's error list
     * @return string
     */
    protected function appearance($error = null)
    {
        return in_array(__('captcha_wrong'), (array) $error, true) ? 'always' : 'interaction-only';
    }

    /**
     * Verify the submitted Turnstile token with Cloudflare siteverify.
     *
     * @param  string $action  expected data-action of the widget
     * @return boolean
     */
    protected function check($action)
    {
        if (! $this->isActive()) return true;

        $request = HttpRequest::instance();

        $token = $request->input('cf-turnstile-response', '');

        if (! is_string($token) || $token === '' || strlen($token) > 2048) return false;

        $context = stream_context_create(array('http' => array(
            'method'  => 'POST',
            'header'  => 'Content-Type: application/x-www-form-urlencoded',
            'timeout' => 10,
            'content' => http_build_query(array(
                'secret'   => $this->getSecretKey(),
                'response' => $token,
                'remoteip' => $request->ip(),
            )),
        )));

        // Fail closed on network error or bad response.
        $result = @file_get_contents(static::VERIFY_URL, false, $context);
        $data = $result ? json_decode($result, true) : null;

        if (! is_array($data) || ($data['success'] ?? false) !== true) return false;

        // Cloudflare test keys answer with hostname "example.com" and no action.
        if (($data['metadata']['result_with_testing_key'] ?? false) === true) return true;

        return ($data['action'] ?? null) === $action
            && ($data['hostname'] ?? null) === $request->getHost();
    }

    /**
     * Magic Method for handling dynamic functions.
     *
     * @param  string  $method
     * @param  array   $params
     * @return void|mixed
     */
    public static function __callStatic($method, $params)
    {
        $instance = new static();

        return call_user_func_array(array($instance, $method), array_values($params));
    }
}
