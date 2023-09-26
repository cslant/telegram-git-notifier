<?php

namespace LbilTech\TelegramGitNotifier\Services;

use Exception;
use LbilTech\TelegramGitNotifier\Exceptions\FileNotFoundException;
use LbilTech\TelegramGitNotifier\Exceptions\MessageIsEmptyException;
use LbilTech\TelegramGitNotifier\Interfaces\AppInterface;
use Telegram;

class AppService implements AppInterface
{
    protected Telegram $telegram;

    protected string $chatId;

    public function __construct(Telegram $telegram, string $chatId)
    {
        $this->telegram = $telegram;
        $this->chatId = $chatId;
    }

    public function sendMessage(string $message = '', array $options = []): void
    {
        if (empty($message)) {
            throw MessageIsEmptyException::create();
        }

        $content = array(
            'chat_id'                  => $this->chatId,
            'disable_web_page_preview' => true,
            'parse_mode'               => 'HTML',
            'text'                     => $message
        );

        if (!empty($options)) {
            $content['reply_markup'] = $options['reply_markup']
                ? $this->telegram->buildInlineKeyBoard($options['reply_markup'])
                : null;
        }

        $this->telegram->sendMessage($content);
    }

    public function sendPhoto(string $photo = '', string $caption = ''): void
    {
        if (empty($photo)) {
            throw FileNotFoundException::create();
        }

        $content = array(
            'chat_id'                  => $this->chatId,
            'disable_web_page_preview' => true,
            'parse_mode'               => 'HTML',
            'photo'                    => $photo,
            'caption'                  => $caption
        );

        $this->telegram->sendPhoto($content);
    }

    public function answerCallbackQuery(string $text = null): void
    {
        if (empty($text)) {
            throw MessageIsEmptyException::create();
        }

        try {
            $this->telegram->answerCallbackQuery([
                'callback_query_id' => $this->telegram->Callback_ID(),
                'text'              => $text,
                'show_alert'        => true
            ]);
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }

    public function editMessageText(
        ?string $text = null,
        array $options = []
    ): void {
        try {
            $content = array_merge([
                'text' => $text ?? $this->getCallbackTextMessage()
            ], $this->setCallbackContentMessage($options));

            $this->telegram->editMessageText($content);
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }

    public function editMessageReplyMarkup(array $options = []): void
    {
        try {
            $this->telegram->editMessageReplyMarkup(
                $this->setCallbackContentMessage($options)
            );
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }

    public function getCallbackTextMessage(): string
    {
        return $this->telegram->Callback_Message()['text'];
    }

    public function setCallbackContentMessage(array $options = []): array
    {
        $content = array(
            'chat_id'                  => $this->telegram->Callback_ChatID(),
            'message_id'               => $this->telegram->MessageID(),
            'disable_web_page_preview' => true,
            'parse_mode'               => 'HTML',
        );

        $content['reply_markup'] = $options['reply_markup']
            ? $this->telegram->buildInlineKeyBoard($options['reply_markup'])
            : null;

        return $content;
    }
}
