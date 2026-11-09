<?php

namespace Tests;

use Support\Facades\Facade;

/**
 * Stands in for the app's Language service so __() works without booting the framework.
 * A key translates to itself, with its parameters appended: __('key', ['a' => 1]) === 'key(a=1)'.
 */
class FakeLanguage
{
    public static function install(): void
    {
        Facade::clearResolvedInstances();
        Facade::setFacadeApplication(['language' => new self()]);
    }

    public static function uninstall(): void
    {
        Facade::clearResolvedInstances();
        Facade::setFacadeApplication(null);
    }

    public function instance($domain = 'app'): self
    {
        return $this;
    }

    public function translate($message, $params = []): string
    {
        if (empty($params)) {
            return $message;
        }
        $pairs = array_map(fn($k, $v) => "$k=$v", array_keys($params), $params);
        return $message . '(' . implode(',', $pairs) . ')';
    }
}
