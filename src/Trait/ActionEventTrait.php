<?php

namespace CSlant\TelegramGitNotifier\Trait;

trait ActionEventTrait
{
    public function getActionOfEvent(object $payload): string
    {
        $action = $payload->action
            ?? $payload->object_attributes?->action
            ?? $payload->object_attributes?->noteable_type
            ?? '';

        if ($action !== '') {
            return tgn_convert_action_name($action);
        }

        return '';
    }
}
