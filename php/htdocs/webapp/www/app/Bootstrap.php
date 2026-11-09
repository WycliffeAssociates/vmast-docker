<?php
/**
 * Bootstrap - the Application's specific Bootstrap.
 *
 * @author Virgil-Adrian Teaca - virgil@giulianaeassociati.com
 * @version 3.0
 */

use Config\Config;
use Helpers\Session;

Sentry\init([
    'dsn' => Config::get("sentry.dsn"),
    'release' => Config::get("version.release"),
    'environment' => ENVIRONMENT,
]);

if(Session::get("memberID")) {
    Sentry\configureScope(function (Sentry\State\Scope $scope) {
        $scope->setExtras([
            'username' => Session::get("userName")
        ]);
    });
}
