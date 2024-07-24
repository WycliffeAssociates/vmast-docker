<?php
/**
 * ReCaptcha
 *
 * @author Virgil-Adrian Teaca - virgil@giulianaeassociati.com
 * @version 3.0
 */

use Config\Config;

/**
 * Setup the Google reCAPTCHA configuration
 */
Config::set('recaptcha', array(
    'active'  => true,
    'siteKey' => $_ENV["RECAPTCHA_SITE_KEY"],
    'secret'  => $_ENV["RECAPTCHA_SECRET"],
));