<?php

namespace CSlant\TelegramGitNotifier;

use CSlant\TelegramGitNotifier\Constants\EventConstant;
use CSlant\TelegramGitNotifier\Exceptions\ConfigFileException;
use CSlant\TelegramGitNotifier\Interfaces\BotInterface;
use CSlant\TelegramGitNotifier\Models\Event;
use CSlant\TelegramGitNotifier\Models\Setting;
use CSlant\TelegramGitNotifier\Structures\App;
use CSlant\TelegramGitNotifier\Structures\TelegramBot;
use CSlant\TelegramGitNotifier\Trait\BotSettingTrait;
use CSlant\TelegramGitNotifier\Trait\EventSettingTrait;
use CSlant\TelegramGitNotifier\Trait\EventTrait;
use Telegram;

class Bot implements BotInterface
{
    use App;
    use TelegramBot;

    use EventTrait;
    use BotSettingTrait;
    use EventSettingTrait;

    public Event $event;

    public Setting $setting;

    /**
     * @param Telegram|null $telegram
     * @param string|null $chatBotId
     * @param Event|null $event
     * @param string|null $platform
     * @param string|null $platformFile
     * @param Setting|null $setting
     * @param string|null $settingFile
     *
     * @throws ConfigFileException
     */
    public function __construct(
        Telegram $telegram = null,
        ?string $chatBotId = null,
        Event $event = null,
        ?string $platform = EventConstant::DEFAULT_PLATFORM,
        ?string $platformFile = null,
        Setting $setting = null,
        ?string $settingFile = null,
    ) {
        $this->event = $event ?? new Event();
        $this->setPlatFormForEvent($platform, $platformFile);
        $this->validatePlatformFile();

        $this->setting = $setting ?? new Setting();
        $this->updateSetting($settingFile);
        $this->validateSettingFile();

        $this->telegram = $telegram ?? new Telegram(config('telegram-git-notifier.bot.token'));
        $this->setCurrentChatBotId($chatBotId);
    }

    public function validateSettingFile(): void
    {
        if (empty($this->setting->getSettingFile())) {
            throw ConfigFileException::settingFile($this->setting->getSettingFile());
        }
    }
}
