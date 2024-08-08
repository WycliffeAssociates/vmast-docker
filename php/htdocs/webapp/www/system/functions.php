<?php
/**
 * Functions - small collection of Framework wide interest functions.
 *
 * @author Virgil-Adrian Teaca - virgil@giulianaeassociati.com
 * @version 3.0
 * @date April 12th, 2016
 */

use Config\Config;
use Helpers\Url;
use JetBrains\PhpStorm\NoReturn;
use Routing\Route;
use Support\Arr;
use Support\Collection;
use Support\Str;
use Support\Facades\Language;

if (! defined('NOVA_SYSTEM_FUNCTIONS')) {

    define('NOVA_SYSTEM_FUNCTIONS', 1);

    /**
     * Site URL helper
     * @param string $path
     * @return string
     */
    function site_url(string $path = '/'): string
    {
        // The base URL.
        $siteUrl = Config::get('app.url');

        return $siteUrl .ltrim($path, '/');
    }

    /**
     * Resource URL helper
     * @param string $path
     * @param string|null $module
     * @return string
     */
    function resource_url(string $path, string $module = null): string
    {
        return Url::resourcePath($module) .ltrim($path, '/');
    }

    /**
     * Template URL helper
     * @param string $path
     * @param string $template
     * @param string $folder
     * @return string
     */
    function template_url(string $path, $template = TEMPLATE, string $folder = '/assets/'): string
    {
        return Url::templatePath($template, $folder) .ltrim($path, '/');
    }

    /**
     * Application Path helper
     * @return string
     */
    function app_path(): string
    {
        return APPDIR;
    }

    /**
     * Storage Path helper
     * @return string
     */
    function storage_path(): string
    {
        return STORAGE_PATH;
    }

    //
    // I18N functions

    /**
     * Get the formatted and translated message back.
     *
     * @param string $message English default message
     * @param mixed|null $args
     * @return string|void
     */
    function __(string $message, mixed $args = null)
    {
        if (!$message) return '';

        $params = (func_num_args() === 2) ? (array)$args : array_slice(func_get_args(), 1);

        return Language::instance('app')->translate($message, $params);
    }

    /**
     * Get the formatted and translated message back with Domain.
     *
     * @param string $domain
     * @param string $message
     * @param mixed|null $args
     * @return string|void
     */
    function __d(string $domain, string $message, mixed $args = null)
    {
        if (! $message) return '';

        //
        $params = (func_num_args() === 3) ? (array)$args : array_slice(func_get_args(), 2);

        return Language::instance($domain)->translate($message, $params);
    }

    /**
     * Get the root Facade application instance.
     *
     * @param string|null $make
     * @return mixed
     */
    function app(string $make = null): mixed
    {
        if (! is_null($make)) {
            return app()->make($make);
        }

        return Support\Facades\Facade::getFacadeApplication();
    }

    /**
     * Generate a URL to a named route.
     *
     * @param string $name
     * @param array $parameters
     * @param bool $absolute
     * @param Route|null $route
     * @return string
     */
    function route(string $name, array $parameters = array(), bool $absolute = true, Route $route = null): string
    {
        return app('url')->route($name, $parameters, $absolute, $route);
    }

    /**
     * Generate a URL to a controller action.
     *
     * @param string $name
     * @param array $parameters
     * @return string
     */
    function action(string $name, array $parameters = array()): string
    {
        return app('url')->action($name, $parameters);
    }

    /** Array helpers. */

    if(!function_exists("array_add")) {
        /**
         * Add an element to an array if it doesn't exist.
         *
         * @param array $array
         * @param string $key
         * @param  mixed   $value
         * @return array
         */
        function array_add(array $array, string $key, mixed $value): array
        {
            if (! isset($array[$key])) $array[$key] = $value;

            return $array;
        }
    }

    if(!function_exists("array_build")) {
        /**
         * Build a new array using a callback.
         *
         * @param array $array
         * @param  Closure  $callback
         * @return array
         */
        function array_build(array $array, Closure $callback): array
        {
            $results = array();

            foreach ($array as $key => $value) {
                list($innerKey, $innerValue) = call_user_func($callback, $key, $value);

                $results[$innerKey] = $innerValue;
            }

            return $results;
        }
    }

    if(!function_exists("array_divide")) {
        /**
         * Divide an array into two arrays. One with keys and the other with values.
         *
         * @param array $array
         * @return array
         */
        function array_divide(array $array): array
        {
            return array(array_keys($array), array_values($array));
        }
    }

    if(!function_exists("array_dot")) {
        /**
         * Flatten a multi-dimensional associative array with dots.
         *
         * @param array $array
         * @param string $prepend
         * @return array
         */
        function array_dot(array $array, string $prepend = ''): array
        {
            $results = array();

            foreach ($array as $key => $value) {
                if (is_array($value)) {
                    $results = array_merge($results, array_dot($value, $prepend . $key . '.'));
                } else {
                    $results[$prepend . $key] = $value;
                }
            }

            return $results;
        }
    }

    if(!function_exists("array_except")) {
        /**
         * Get all the given array except for a specified array of items.
         *
         * @param array $array
         * @param array $keys
         * @return array
         */
        function array_except(array $array, array $keys): array
        {
            return array_diff_key($array, array_flip($keys));
        }
    }

    if(!function_exists("array_fetch")) {
        /**
         * Fetch a flattened array of a nested array element.
         *
         * @param array $array
         * @param string $key
         * @return array
         */
        function array_fetch(array $array, string $key): array
        {
            $results = array();

            foreach (explode('.', $key) as $segment) {
                $results = array();

                foreach ($array as $value) {
                    $value = (array)$value;

                    $results[] = $value[$segment];
                }

                $array = array_values($results);
            }

            return array_values($results);
        }
    }

    if(!function_exists("array_first")) {
        /**
         * Return the first element in an array passing a given truth test.
         *
         * @param array $array
         * @param Closure $callback
         * @param mixed|null $default
         * @return mixed
         */
        function array_first(array $array, Closure $callback, mixed $default = null): mixed
        {
            foreach ($array as $key => $value) {
                if (call_user_func($callback, $key, $value)) return $value;
            }

            return value($default);
        }
    }

    if(!function_exists("array_last")) {
        /**
         * Return the last element in an array passing a given truth test.
         *
         * @param array $array
         * @param Closure $callback
         * @param mixed|null $default
         * @return mixed
         */
        function array_last(array $array, Closure $callback, mixed $default = null): mixed
        {
            return array_first(array_reverse($array), $callback, $default);
        }
    }

    if(!function_exists("array_flatten")) {
        /**
         * Flatten a multi-dimensional array into a single level.
         *
         * @param array $array
         * @return array
         */
        function array_flatten(array $array): array
        {
            $return = array();

            array_walk_recursive($array, function ($x) use (&$return) {
                $return[] = $x;
            });

            return $return;
        }
    }

    if(!function_exists("array_get")) {
        /**
         * Get an item from an array using "dot" notation.
         *
         * @param array $array
         * @param ?string $key
         * @param mixed|null $default
         * @return mixed
         */
        function array_get(array $array, ?string $key, mixed $default = null): mixed
        {
            if (is_null($key)) return $array;

            if (isset($array[$key])) return $array[$key];

            foreach (explode('.', $key) as $segment) {
                if (!is_array($array) || !array_key_exists($segment, $array)) {
                    return $default;
                }

                $array = $array[$segment];
            }

            return $array;
        }
    }

    if(!function_exists("array_set")) {
        /**
         * Set an array item to a given value using "dot" notation.
         *
         * If no key is given to the method, the entire array will be replaced.
         *
         * @param array $array
         * @param string $key
         * @param  mixed   $value
         * @return array
         */
        function array_set(array &$array, string $key, mixed $value): array
        {
            return Arr::set($array, $key, $value);
        }
    }


    if(!function_exists("array_only")) {
        /**
         * Get a subset of the items from the given array.
         *
         * @param array $array
         * @param array $keys
         * @return array
         */
        function array_only(array $array, array $keys): array
        {
            return array_intersect_key($array, array_flip($keys));
        }
    }

    if(!function_exists("array_forget")) {
        /**
         * Remove an array item from a given array using "dot" notation.
         *
         * @param array $array
         * @param string $key
         * @return void
         */
        function array_forget(array &$array, string $key): void
        {
            $keys = explode('.', $key);

            while (count($keys) > 1) {
                $key = array_shift($keys);

                if (!isset($array[$key]) || !is_array($array[$key])) {
                    return;
                }

                $array =& $array[$key];
            }

            unset($array[array_shift($keys)]);
        }
    }

    if(!function_exists("array_pluck")) {
        /**
         * Pluck an array of values from an array.
         *
         * @param array $array
         * @param string $value
         * @param string|null $key
         * @return array
         */
        function array_pluck(array $array, string $value, string $key = null): array
        {
            $results = array();

            foreach ($array as $item) {
                $itemValue = is_object($item) ? $item->{$value} : $item[$value];

                // If the key is "null", we will just append the value to the array and keep
                // looping. Otherwise, we will key the array using the value of the key we
                // received from the developer. Then we'll return the final array form.
                if (is_null($key)) {
                    $results[] = $itemValue;
                } else {
                    $itemKey = is_object($item) ? $item->{$key} : $item[$key];

                    $results[$itemKey] = $itemValue;
                }
            }

            return $results;
        }
    }

    if(!function_exists("array_pull")) {
        /**
         * Get a value from the array, and remove it.
         *
         * @param array $array
         * @param string $key
         * @param mixed|null $default
         * @return mixed
         */
        function array_pull(array &$array, string $key, mixed $default = null): mixed
        {
            $value = array_get($array, $key, $default);

            array_forget($array, $key);

            return $value;
        }
    }

    if(!function_exists("array_sort")) {
        /**
         * Sort the array using the given Closure.
         *
         * @param array $array
         * @param  \Closure  $callback
         * @return array
         */
        function array_sort(array $array, Closure $callback): array
        {
            return Collection::make($array)->sortBy($callback)->all();
        }
    }

    if(!function_exists("array_where")) {
        /**
         * Filter the array using the given Closure.
         *
         * @param array $array
         * @param  \Closure  $callback
         * @return array
         */
        function array_where(array $array, Closure $callback): array
        {
            $filtered = array();

            foreach ($array as $key => $value) {
                if (call_user_func($callback, $key, $value)) $filtered[$key] = $value;
            }

            return $filtered;
        }
    }

    if(!function_exists("head")) {
        /**
         * Get the first element of an array.
         *
         * @param array $array
         * @return mixed
         */
        function head(array $array): mixed
        {
            return reset($array);
        }
    }

    if(!function_exists("last")) {
        /**
         * Get the last element from an array.
         *
         * @param array $array
         * @return mixed
         */
        function last(array $array): mixed
        {
            return end($array);
        }
    }

    /** String helpers. */

    if(!function_exists("str_is")) {
        /**
         * Determine if a given string matches a given pattern.
         *
         * @param string $pattern
         * @param string $value
         * @return bool
         */
        function str_is(string $pattern, string $value): bool
        {
            return Str::is($pattern, $value);
        }
    }

    if(!function_exists("str_contains")) {
        /**
         * Determine if a given string contains a given substring.
         *
         * @param string $haystack
         * @param array|string $needles
         * @return bool
         */
        function str_contains(string $haystack, array|string $needles): bool
        {
            return Str::contains($haystack, $needles);
        }
    }

    if(!function_exists("str_starts_with")) {
        /**
         * Test for string starts with
         * @param $haystack
         * @param $needle
         * @return bool
         */
        function str_starts_with($haystack, $needle): bool
        {
            return Str::startsWith($haystack, $needle);
        }
    }

    if(!function_exists("str_ends_with")) {
        /**
         * Test for string ends with
         * @param $haystack
         * @param $needle
         * @return bool
         */
        function str_ends_with($haystack, $needle): bool
        {
            return Str::endsWith($haystack, $needle);
        }
    }


    if(!function_exists("str_random")) {
        /**
         * Generate a random alphanumeric string.
         *
         * @param int $length
         * @return string
         *
         * @throws RuntimeException
         */
        function str_random(int $length = 16): string
        {
            return Str::random($length);
        }
    }

    if(!function_exists("str_replace_array")) {
        /**
         * Replace a given value in the string sequentially with an array.
         *
         * @param string $search
         * @param  array   $replace
         * @param string $subject
         * @return string
         */
        function str_replace_array(string $search, array $replace, string $subject): string
        {
            foreach ($replace as $value) {
                $subject = preg_replace('/' . $search . '/', $value ?? '', $subject, 1);
            }

            return $subject;
        }
    }

    if(!function_exists("e")) {
        /**
         * Escape HTML entities in a string.
         *
         * @param string $value
         * @return string
         */
        function e(string $value): string
        {
            return htmlentities($value, ENT_QUOTES, 'UTF-8', false);
        }
    }

    if(!function_exists("class_basename")) {
        /**
         * Class name helper
         * @param $class
         * @return string
         */
        function class_basename($class): string
        {
            $className = is_object($class) ? get_class($class) : $class;

            return basename(str_replace('\\', '/', $className));
        }
    }

    if(!function_exists("trait_uses_recursive")) {
        /**
         * Returns all traits used by a trait and its traits
         *
         * @param string $trait
         * @return array
         */
        function trait_uses_recursive(string $trait): array
        {
            $traits = class_uses($trait);

            foreach ($traits as $trait) {
                $traits += trait_uses_recursive($trait);
            }

            return $traits;
        }
    }

    if(!function_exists("class_uses_recursive")) {
        /**
         * Returns all traits used by a class, it's subclasses and trait of their traits
         *
         * @param string $class
         * @return array
         */
        function class_uses_recursive(string $class): array
        {
            $results = [];

            foreach (array_merge([$class => $class], class_parents($class)) as $class) {
                $results += trait_uses_recursive($class);
            }

            return array_unique($results);
        }
    }

    if(!function_exists("str_object")) {
        /**
         * Determine if the given object has a toString method.
         *
         * @param object $object
         * @return bool
         */
        function str_object(object $object): bool
        {
            return (method_exists($object, '__toString'));
        }
    }

    if(!function_exists("value")) {
        /**
         * Return the default value of the given value.
         *
         * @param  mixed $value
         * @return mixed
         */
        function value(mixed $value): mixed
        {
            return $value instanceof Closure ? $value() : $value;
        }
    }

    if(!function_exists("with")) {
        /**
         * Return the given object.
         *
         * @param  mixed  $object
         * @return mixed
         */
        function with(mixed $object): mixed
        {
            return $object;
        }
    }

    /** Common data lookup methods. */

    if(!function_exists("data_get")) {
        /**
         * Get an item from an array or object using "dot" notation.
         *
         * @param  mixed $target
         * @param ?string $key
         * @param mixed|null $default
         * @return mixed
         */
        function data_get(mixed $target, ?string $key, mixed $default = null): mixed
        {
            if (is_null($key)) return $target;

            foreach (explode('.', $key) as $segment) {
                if (is_array($target)) {
                    if (!array_key_exists($segment, $target)) {
                        return value($default);
                    }

                    $target = $target[$segment];
                } elseif (is_object($target)) {
                    if (!isset($target->{$segment})) {
                        return value($default);
                    }

                    $target = $target->{$segment};
                } else {
                    return value($default);
                }
            }

            return $target;
        }
    }

    if(!function_exists("object_get")) {
        /**
         * Get an item from an object using "dot" notation.
         *
         * @param object $object
         * @param ?string $key
         * @param mixed|null $default
         * @return mixed
         */
        function object_get(object $object, ?string $key, mixed $default = null): mixed
        {
            if (is_null($key) || trim($key) == '') return $object;

            foreach (explode('.', $key) as $segment) {
                if (!is_object($object) || !isset($object->{$segment})) {
                    return value($default);
                }

                $object = $object->{$segment};
            }

            return $object;
        }
    }

    if(!function_exists("dd")) {
        /**
         * Dump the passed variables and end the script.
         * @return void
         */
        #[NoReturn] function dd(): void
        {
            array_map(function ($x) {
                var_dump($x);
            }, func_get_args());
            die;
        }
    }

    if(!function_exists("pr")) {
        /**
         * print_r call wrapped in pre tags
         *
         * @param mixed $data or array $data
         * @param boolean $exit
         */
        function pr(mixed $data, bool $exit = false): void
        {
            echo "<pre>";
            print_r($data);
            echo "</pre>";

            if ($exit) {
                exit;
            }
        }
    }

    if(!function_exists("vd")) {
        /**
         * var_dump call
         *
         * @param string $data or array $data
         * @param boolean $exit
         *
         */
        function vd(string $data, bool $exit = false): void
        {
            var_dump($data);

            if ($exit) {
                exit;
            }
        }
    }

    if(!function_exists("sl")) {
        /**
         * count the length of the string.
         *
         * @param string $data
         * @return string return the count
         */
        function sl(string $data): string
        {
            return strlen($data);
        }
    }

    if(!function_exists("stu")) {
        /**
         * convert string to uppercase.
         *
         * @param string $data
         * @return string
         */
        function stu(string $data): string
        {
            return strtoupper($data);
        }
    }

    if(!function_exists("stl")) {
        /**
         * convert string to lowercase.
         *
         * @param string $data
         * @return string
         */
        function stl(string $data): string
        {
            return strtolower($data);
        }
    }

    if(!function_exists("ucw")) {
        /**
         * the first letter of each word to be a capital.
         *
         * @param string $data
         * @return string
         */
        function ucw(string $data): string
        {
            return ucwords($data);
        }
    }

    if(!function_exists("createKey")) {
        /**
         * this will generate a 32 character key
         * @param int $length
         * @return string
         */
        function createKey(int $length = 32): string
        {
            return str_random($length);
        }
    }

    if(!function_exists("add_http")) {
        /**
         * this will ensure $url starts with http
         *
         * @param $url string
         * @param $scheme string
         * @return string
         */
        function add_http(string $url, string $scheme = 'http://'): string
        {
            return parse_url($url, PHP_URL_SCHEME) === null ? $scheme . $url : $url;
        }
    }

    if(!function_exists("array_any")) {
        /**
         * Returns true if array has at least one element.
         *
         * @param $array array
         * @param $fn callable
         * @return bool
         */
        function array_any(array $array, callable $fn): bool
        {
            foreach ($array as $value) {
                if($fn($value)) {
                    return true;
                }
            }
            return false;
        }
    }

}
