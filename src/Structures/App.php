<?php

namespace CSlant\TelegramGitNotifier\Structures;

use CSlant\TelegramGitNotifier\Exceptions\EntryNotFoundException;
use CSlant\TelegramGitNotifier\Exceptions\MessageIsEmptyException;
use Exception;
use Telegram;

trait App
{
    public Telegram $telegram;

    public string $chatBotId;

    public function setCurrentChatBotId(string $chatBotId = null): void
    {
        $this->chatBotId = $chatBotId ?? config('telegram-git-notifier.bot.chat_id');
    }

    private function createTelegramBaseContent(): array
    {
        return [
            'chat_id' => $this->chatBotId,
            'disable_web_page_preview' => true,
            'parse_mode' => 'HTML',
        ];
    }

    public function sendMessage(?string $message = '', array $options = []): void
    {
        if (empty($message)) {
            throw MessageIsEmptyException::create();
        }

        $content = $this->createTelegramBaseContent();
        $content['text'] = $message;

        if (!empty($options['reply_markup'])) {
            $content['reply_markup'] = $this->telegram->buildInlineKeyBoard(
                $options['reply_markup']
            );
            unset($options['reply_markup']);
        }

        $content = $content + $options;

        $this->telegram->sendMessage($content);
    }

    public function sendPhoto(string $photo = '', array $options = []): void
    {
        if (empty($photo)) {
            throw EntryNotFoundException::fileNotFound();
        }

        $content = $this->createTelegramBaseContent();
        $content['photo'] = curl_file_create($photo);

        $content = $content + $options;

        $this->telegram->sendPhoto($content);
    }

    public function answerCallbackQuery(string $text = null, array $options = []): void
    {
        if (empty($text)) {
            throw MessageIsEmptyException::create();
        }

        try {
            $options = array_merge([
                'callback_query_id' => $this->telegram->Callback_ID(),
                'text' => $text,
                'show_alert' => true,
            ], $options);
            $this->telegram->answerCallbackQuery($options);
        } catch (Exception $e) {
            error_log("Error answering callback query: " . $e->getMessage());
        }
    }

    public function editMessageText(
        ?string $text = null,
        array $options = []
    ): void {
        try {
            $content = array_merge([
                'text' => $text ?? $this->getCallbackTextMessage(),
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
        $content = [
            'chat_id' => $this->telegram->Callback_ChatID(),
            'message_id' => $this->telegram->MessageID(),
            'disable_web_page_preview' => true,
            'parse_mode' => 'HTML',
        ];

        $content['reply_markup'] = $options['reply_markup']
            ? $this->telegram->buildInlineKeyBoard($options['reply_markup'])
            : null;

        return $content;
    }

    public function getBotName(): ?string
    {
        return $this->telegram->getMe()['result']['username'] ?? null;
    }

    public function getCommandMessage(): string
    {
        $text = $this->telegram->Text();

        return str_replace('@' . $this->getBotName(), '', $text);
    }
}
