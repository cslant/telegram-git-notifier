<?php

namespace CSlant\TelegramGitNotifier\Interfaces\Structures;

use CSlant\TelegramGitNotifier\Exceptions\BotException;
use CSlant\TelegramGitNotifier\Exceptions\CallbackException;
use CSlant\TelegramGitNotifier\Exceptions\EntryNotFoundException;
use CSlant\TelegramGitNotifier\Exceptions\MessageIsEmptyException;

interface AppInterface
{
    /**
     * Send a message to telegram
     *
     * @param string|null $message
     * @param array $options
     *
     * @return void
     * @throws MessageIsEmptyException
     * @see App::sendMessage()
     */
    public function sendMessage(?string $message = '', array $options = []): void;

    /**
     * Send a photo to telegram
     *
     * @param string $photo (path to photo)
     * @param array $caption
     *
     * @return void
     * @throws EntryNotFoundException
     * @see App::sendPhoto()
     */
    public function sendPhoto(string $photo = '', array $caption = []): void;

    /**
     * Send callback response to telegram (show alert)
     *
     * @param string|null $text
     * @param array $options
     *
     * @return void
     * @throws MessageIsEmptyException
     * @throws CallbackException
     * @see App::answerCallbackQuery()
     */
    public function answerCallbackQuery(string $text = null, array $options = []): void;

    /**
     * Edit message text and reply markup
     *
     * @param string|null $text
     * @param array $options
     *
     * @return void
     * @throws BotException
     * @see App::editMessageText()
     */
    public function editMessageText(string $text = null, array $options = []): void;

    /**
     * Edit message reply markup from a telegram
     *
     * @param array $options
     *
     * @return void
     * @throws BotException
     * @see App::editMessageReplyMarkup()
     */
    public function editMessageReplyMarkup(array $options = []): void;

    /**
     * Get the text from callback message
     *
     * @return string
     * @see App::getCallbackTextMessage()
     */
    public function getCallbackTextMessage(): string;

    /**
     * Create content for a callback message
     *
     * @param array $options
     *
     * @return array
     * @see App::setCallbackContentMessage()
     */
    public function setCallbackContentMessage(array $options = []): array;

    /**
     * @param string $chatId
     *
     * @return void
     * @see App::setCurrentChatBotId()
     */
    public function setCurrentChatBotId(string $chatId): void;

    /**
     * Get the username of the bot
     *
     * @return string|null
     * @see App::getBotName()
     */
    public function getBotName(): ?string;

    /**
     * Get the command message from a telegram
     *
     * @return string
     * @see App::getCommandMessage()
     */
    public function getCommandMessage(): string;
}
