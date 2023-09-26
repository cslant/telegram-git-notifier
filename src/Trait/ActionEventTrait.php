<?php

namespace LbilTech\TelegramGitNotifier\Trait;

trait ActionEventTrait
{
    public function getActionOfEvent($payload): string
    {
        $action = $payload?->action
            ?? $payload?->object_attributes?->action
            ?? $payload?->object_attributes?->noteable_type
            ?? '';

        if (!empty($action)) {
            return tgn_convert_action_name($action);
        }

        return '';
    }
}
