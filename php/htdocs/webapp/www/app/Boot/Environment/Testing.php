<?php
/**
 * The 'testing' environment, used by the HTTP tests (tests/Http).
 *
 * Sessions and cache live in memory, so tests write nothing to app/Storage.
 */

$app['config']->set('session.driver', 'array');
$app['config']->set('cache.driver', 'array');
