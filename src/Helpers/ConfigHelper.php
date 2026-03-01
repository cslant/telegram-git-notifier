<?php

namespace CSlant\TelegramGitNotifier\Helpers;

use CSlant\TelegramGitNotifier\Exceptions\EntryNotFoundException;
use CSlant\TelegramGitNotifier\Exceptions\InvalidViewTemplateException;
use Throwable;

final class ConfigHelper
{
    /**
     * @var array<string, mixed>
     */
    public array $config;

    public function __construct()
    {
        $this->config = require __DIR__ . '/../../config/tg-notifier.php';
    }

    /**
     * Resolve a dot-notation config key to its value.
     */
    public function execConfig(string $string): mixed
    {
        $keys = explode('.', $string);
        $result = $this->config;

        foreach ($keys as $key) {
            if (!isset($result[$key])) {
                return '';
            }

            $result = $result[$key];
        }

        return $result;
    }

    /**
     * Render a PHP template file with the given data.
     *
     * Uses output buffering and a closure to isolate the template scope,
     * preventing variable pollution.
     *
     * @param string $partialPath Dot-notation path to the view file
     * @param array<string, mixed> $data Variables to pass to the template
     */
    public function getTemplateData(string $partialPath, array $data = []): string
    {
        $viewPathFile = $this->execConfig('telegram-git-notifier.view.path') . '/'
            . str_replace('.', '/', $partialPath) . '.php';

        if (!file_exists($viewPathFile)) {
            return '';
        }

        try {
            $content = (static function (string $__viewFile, array $__data): string {
                extract($__data, EXTR_SKIP);
                ob_start();
                require $__viewFile;

                return (string) ob_get_clean();
            })($viewPathFile, $data);
        } catch (EntryNotFoundException|InvalidViewTemplateException|Throwable $e) {
            if (ob_get_level() > 0) {
                ob_end_clean();
            }

            throw new InvalidViewTemplateException(
                "Failed to render template '{$partialPath}': {$e->getMessage()}",
                0,
                $e,
            );
        }

        return $content;
    }
}
