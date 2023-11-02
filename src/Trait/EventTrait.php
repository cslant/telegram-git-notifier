<?php

namespace CSlant\TelegramGitNotifier\Trait;

use CSlant\TelegramGitNotifier\Constants\EventConstant;
use Symfony\Component\HttpFoundation\Request;

trait EventTrait
{
    use ActionEventTrait;

    public function setPlatFormForEvent(?string $platform = EventConstant::DEFAULT_PLATFORM, string $platformFile = null): void
    {
        /** @var array $platformFileDefaults<platform, platformFile> */
        $platformFileDefaults = config('telegram-git-notifier.data_file.platform');
        $this->event->setPlatformFile($platformFile ?? $platformFileDefaults[$platform]);
        $this->event->setEventConfig($platform);
    }

    public function handleEventFromRequest(Request $request): ?string
    {
        foreach (EventConstant::WEBHOOK_EVENT_HEADER as $platform => $header) {
            $event = $request->server->get($header);
            if (!is_null($event)) {
                $this->event->platform = $platform;
                $this->setPlatFormForEvent($platform);

                return $event;
            }
        }

        return null;
    }
}
