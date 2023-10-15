<?php

namespace LbilTech\TelegramGitNotifier;

use GuzzleHttp\Client;
use LbilTech\TelegramGitNotifier\Interfaces\Structures\NotificationInterface;
use LbilTech\TelegramGitNotifier\Structures\App;
use LbilTech\TelegramGitNotifier\Structures\Notification;
use LbilTech\TelegramGitNotifier\Trait\ActionEventTrait;

class Notifier implements NotificationInterface
{
    use App;
    use Notification;
    use ActionEventTrait;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }
}
