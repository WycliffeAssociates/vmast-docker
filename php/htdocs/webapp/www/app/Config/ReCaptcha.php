<?php
/**
 * ReCaptcha
 *
 * @author Virgil-Adrian Teaca - virgil@giulianaeassociati.com
 * @version 3.0
 */

use Config\Config;

/**
 * Setup the Cloudflare Turnstile configuration (config key kept as "recaptcha")
 */
Config::set('recaptcha', array(
    'active'  => true,
    'siteKey' => $_ENV["TURNSTILE_SITE_KEY"],
    'secret'  => $_ENV["TURNSTILE_SECRET"],
));