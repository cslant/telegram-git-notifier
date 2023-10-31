<?php

namespace LbilTech\TelegramGitNotifier\Trait;

use LbilTech\TelegramGitNotifier\Constants\SettingConstant;

trait BotSettingTrait
{
    public function updateSetting(?string $settingFile = null): void
    {
        if ($this->setting->getSettingFile()) {
            return;
        }
        $settingFile = $settingFile ?? config('telegram-git-notifier.data_file.setting');
        $this->setting->setSettingFile($settingFile);
        $this->setting->setSettingConfig();
    }

    public function setMyCommands(
        array $menuCommand,
        ?string $view = null
    ): void {
        $this->telegram->setMyCommands([
            'commands' => json_encode($menuCommand),
        ]);
        $this->sendMessage(
            view(
                $view ??
                config('telegram-git-notifier.view.tools.set_menu_cmd')
            )
        );
    }

    public function settingHandle(?string $view = null): void
    {
        $this->sendMessage(
            view($view ?? config('telegram-git-notifier.view.tools.settings')),
            ['reply_markup' => $this->settingMarkup()]
        );
    }

    public function settingMarkup(): array
    {
        $markup = [
            [
                $this->telegram->buildInlineKeyBoardButton(
                    $this->setting->getSettings()[SettingConstant::T_IS_NOTIFIED]
                        ? '✅ Allow notifications'
                        : 'Allow notifications',
                    '',
                    SettingConstant::SETTING_IS_NOTIFIED
                ),
            ],
            [
                $this->telegram->buildInlineKeyBoardButton(
                    $this->setting->getSettings()[SettingConstant::T_ALL_EVENTS_NOTIFICATION]
                        ? '✅ Enable All Events Notify'
                        : 'Enable All Events Notify',
                    '',
                    SettingConstant::SETTING_ALL_EVENTS_NOTIFY
                ),
            ],
        ];

        $markup = $this->customEventMarkup($markup);

        $markup[] = [
            $this->telegram->buildInlineKeyBoardButton(
                '🔙 Back to menu',
                '',
                SettingConstant::SETTING_BACK . 'menu'
            ),
        ];

        return $markup;
    }

    public function customEventMarkup(array $markup): array
    {
        if (!$this->setting->getSettings()[SettingConstant::T_ALL_EVENTS_NOTIFICATION]) {
            $markup[] = [
                $this->telegram->buildInlineKeyBoardButton(
                    '🦑 Custom github events',
                    '',
                    SettingConstant::SETTING_GITHUB_EVENTS
                ),
                $this->telegram->buildInlineKeyBoardButton(
                    '🦊 Custom gitlab events',
                    '',
                    SettingConstant::SETTING_GITLAB_EVENTS
                ),
            ];
        }

        return $markup;
    }
}
