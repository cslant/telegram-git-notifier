<?php

namespace LbilTech\TelegramGitNotifier\Helpers;

use LbilTech\TelegramGitNotifier\Exceptions\EntryNotFoundException;
use LbilTech\TelegramGitNotifier\Exceptions\InvalidViewTemplateException;
use Throwable;

final class ConfigHelper
{
    public array $config;

    public function __construct()
    {
        $this->config = require __DIR__ . '/../../config/tg-notifier.php';
    }

    /**
     * Handle config and return value
     *
     * @param string $string
     *
     * @return array|mixed
     */
    public function execConfig(string $string): mixed
    {
        $config = explode('.', $string);
        $result = $this->config;
        foreach ($config as $value) {
            if (!isset($result[$value])) {
                return '';
            }

            $result = $result[$value];
        }
        return $result;
    }

    /**
     * Return template data
     *
     * @param $partialPath
     * @param array $data
     *
     * @return bool|string
     */
    public function getTemplateData($partialPath, array $data = []): bool|string
    {
        $viewPathFile = $this->execConfig('telegram-git-notifier.view.path') . '/'
            . str_replace('.', '/', $partialPath) . '.php';

        if (!file_exists($viewPathFile)) {
            return '';
        }

        ob_start();
        try {
            extract($data, EXTR_SKIP);
            require_once $viewPathFile;
            $content = ob_get_clean();
        } catch (EntryNotFoundException|InvalidViewTemplateException|Throwable $e) {
            ob_end_clean();
            error_log($e->getMessage());
        }

        return $content;
    }
}
