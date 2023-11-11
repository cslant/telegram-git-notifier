<?php

use CSlant\TelegramGitNotifier\Helpers\ConfigHelper;

if (!function_exists('tgn_singularity')) {
    /**
     * The reverse of pluralizing, returns the singular form of a word in a string.
     *
     * @param string $word
     *
     * @return string|null
     */
    function tgn_singularity(string $word): string|null
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
            '/(.)s$/i' => '$1',
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
     * @param string $string
     *
     * @return string
     */
    function tgn_snake_case(string $string): string
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

if (!class_exists('Illuminate\Foundation\Application')) {
    if (!function_exists('config')) {
        /**
         * Return config value by string
         *
         * @param string $string
         *
         * @return mixed
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
         * @return null|string
         */
        function view(string $partialPath, array $data = []): null|string
        {
            $content = (new ConfigHelper())->getTemplateData(
                $partialPath,
                $data
            );

            return $content ?: null;
        }
    }
}

if (!function_exists('tgn_view')) {
    /**
     * Get view template
     *
     * @param string $partialPath
     * @param array $data
     *
     * @return null|string
     */
    function tgn_view(string $partialPath, array $data = []): null|string
    {
        if (class_exists('Illuminate\Foundation\Application')) {
            $partialPath = config('telegram-git-notifier.view.namespace') . $partialPath;
        }

        $content = view($partialPath, $data);

        return $content ?: null;
    }
}
