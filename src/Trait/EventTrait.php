<?php

namespace CSlant\TelegramGitNotifier\Trait;

use CSlant\TelegramGitNotifier\Constants\EventConstant;
use CSlant\TelegramGitNotifier\Exceptions\ConfigFileException;
use Symfony\Component\HttpFoundation\Request;

trait EventTrait
{
    use ActionEventTrait;

    public function setPlatFormForEvent(
        ?string $platform = EventConstant::DEFAULT_PLATFORM,
        ?string $platformFile = null,
    ): void {
        /** @var array<string, string> $platformFileDefaults */
        $platformFileDefaults = config('telegram-git-notifier.data_file.platform');
        $this->event->platformFile = $platformFile ?? $platformFileDefaults[$platform ?? EventConstant::DEFAULT_PLATFORM] ?? '';
        $this->event->setEventConfig($platform);
    }

    public function handleEventFromRequest(Request $request): ?string
    {
        foreach (EventConstant::WEBHOOK_EVENT_HEADER as $platform => $header) {
            $event = $request->server->get($header);
            if ($event !== null) {
                $this->event->platform = $platform;
                $this->setPlatFormForEvent($platform);

                return $event;
            }
        }

        return null;
    }

    public function validatePlatformFile(): void
    {
        if ($this->event->getEventConfig() === []) {
            throw ConfigFileException::platformFile(
                $this->event->platform,
                $this->event->platformFile,
            );
        }
    }
}
