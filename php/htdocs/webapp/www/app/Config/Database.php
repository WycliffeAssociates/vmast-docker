<?php
/**
 * Database configuration
 *
 * @author David Carr - dave@daveismyname.com
 * @author Virgil-Adrian Teaca - virgil@giulianaeassociati.com
 * @version 3.0
 */

use Config\Config;


/**
 * Setup the Database configuration.
 */
Config::set('database', array(
    // The PDO Fetch Style.
    'fetch' => PDO::FETCH_CLASS,

    // The Default Database Connection Name.
    'default' => 'mysql',

    // The Database Connections.
    'connections' => array(
        'mysql' => array(
            'driver'    => 'mysql',
            'hostname'  => $_ENV["DB_HOST"],
            'database'  => $_ENV["DB_NAME"],
            'username'  => $_ENV["DB_USER"],
            'password'  => $_ENV["DB_PASS"],
            'prefix'    => PREFIX,
            'charset'   => 'utf8',
            'collation' => 'utf8_general_ci',
        ),
    ),
));
