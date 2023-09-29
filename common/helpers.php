<?php

use LbilTech\TelegramGitNotifier\Exceptions\EntryNotFoundException;
use LbilTech\TelegramGitNotifier\Exceptions\InvalidViewTemplateException;
use LbilTech\TelegramGitNotifier\Helpers\ConfigHelper;

if (!function_exists('tgn_urlencoded_message')) {
    /**
     * Urlencoded message
     *
     * @param string $message
     *
     * @return array|string|string[]
     */
    function tgn_urlencoded_message(string $message): array|string
    {
        return str_replace(["\n"], ['%0A'], urlencode($message));
    }
}

if (!function_exists('tgn_singularity')) {
    /**
     * The reverse of pluralizing, returns the singular form of a word in a string.
     *
     * @param $word
     *
     * @return bool|string
     */
    function tgn_singularity($word): bool|string
    {
        static $singular_rules = [
            '/(quiz)zes$/i' => '$1',
            '/(matr)ices$/i' => '$1ix',
            '/(vert|ind)ices$/i' => '$1ex',
            '/^(ox)en$/i' => '$1',
            '/(alias|status)es$/i' => '$1',
            '/([octop|vir])i$/i' => '$1us',
            '/(cris|ax|test)es$/i' => '$1is',
            '/(shoe)s$/i' => '$1',
            '/(o)es$/i' => '$1',
            '/(bus)es$/i' => '$1',
            '/([m|l])ice$/i' => '$1ouse',
            '/(x|ch|ss|sh)es$/i' => '$1',
            '/(m)ovies$/i' => '$1ovie',
            '/(s)eries$/i' => '$1eries',
            '/([^aeiouy]|qu)ies$/i' => '$1y',
            '/([lr])ves$/i' => '$1f',
            '/(tive)s$/i' => '$1',
            '/(hive)s$/i' => '$1',
            '/([^f])ves$/i' => '$1fe',
            '/(^analy)ses$/i' => '$1sis',
            '/((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/i' => '$1$2sis',
            '/([ti])a$/i' => '$1um',
            '/(n)ews$/i' => '$1ews',
            '/(.)s$/i' => '$1'
        ];
        return preg_replace(
            array_keys($singular_rules),
            array_values($singular_rules),
            $word
        );
    }
}

if (!function_exists('tgn_snake_case')) {
    /**
     * Convert a string to a snack case
     *
     * @param $string
     *
     * @return string
     */
    function tgn_snake_case($string): string
    {
        $string = preg_replace('/\s+/', '_', $string);
        return strtolower($string);
    }
}

if (!function_exists('tgn_event_name')) {
    /**
     * Get event name
     *
     * @param string $event
     *
     * @return string
     */
    function tgn_event_name(string $event): string
    {
        return tgn_snake_case(str_replace(' Hook', '', $event));
    }
}

if (!function_exists('tgn_convert_event_name')) {
    /**
     * Convert event name
     *
     * @param string $event
     *
     * @return string
     */
    function tgn_convert_event_name(string $event): string
    {
        return tgn_singularity(tgn_event_name($event));
    }
}

if (!function_exists('tgn_convert_action_name')) {
    /**
     * Convert action name
     *
     * @param string $action
     *
     * @return string
     */
    function tgn_convert_action_name(string $action): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $action));
    }
}

if (!function_exists('config')) {
    /**
     * Return config value by string
     *
     * @param string $string
     *
     * @return mixed
     * @throws EntryNotFoundException
     */
    function config(string $string): mixed
    {
        return (new ConfigHelper())->execConfig($string);
    }
}

if (!function_exists('view')) {
    /**
     * Get view template
     *
     * @param string $partialPath
     * @param array $data
     *
     * @return bool|string
     * @throws EntryNotFoundException|InvalidViewTemplateException
     */
    function view(string $partialPath, array $data = []): bool|string
    {
        return (new ConfigHelper())->getTemplateData($partialPath, $data);
    }
}
