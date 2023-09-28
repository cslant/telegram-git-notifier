<?php

namespace LbilTech\TelegramGitNotifier\Interfaces;

use LbilTech\TelegramGitNotifier\Exceptions\EntryNotFoundException;
use LbilTech\TelegramGitNotifier\Exceptions\MessageIsEmptyException;

interface AppInterface
{
    /**
     * Send a message to telegram
     *
     * @param string $message
     * @param array $options
     *
     * @return void
     * @throws MessageIsEmptyException
     */
    public function sendMessage(
        string $message = '',
        array $options = []
    ): void;

    /**
     * Send a photo to telegram
     *
     * @param string $photo
     * @param string $caption
     *
     * @return void
     * @throws EntryNotFoundException
     */
    public function sendPhoto(string $photo = '', string $caption = ''): void;

    /**
     * Send callback response to telegram (show alert)
     *
     * @param string|null $text
     *
     * @return void
     * @throws MessageIsEmptyException
     */
    public function answerCallbackQuery(string $text = null): void;

    /**
     * Edit message text and reply markup
     *
     * @param string|null $text
     * @param array $options
     *
     * @return void
     */
    public function editMessageText(
        ?string $text = null,
        array $options = []
    ): void;

    /**
     * Edit message reply markup from a telegram
     *
     * @param array $options
     *
     * @return void
     */
    public function editMessageReplyMarkup(array $options = []): void;

    /**
     * Get the text from callback message
     *
     * @return string
     */
    public function getCallbackTextMessage(): string;

    /**
     * Create content for a callback message
     *
     * @param array $options
     *
     * @return array
     */
    public function setCallbackContentMessage(array $options = []): array;

    /**
     * @param string $chatId
     *
     * @return void
     */
    public function setCurrentChatId(string $chatId): void;
}
