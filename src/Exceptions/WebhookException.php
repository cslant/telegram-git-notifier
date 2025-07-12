<?php

declare(strict_types=1);

namespace CSlant\TelegramGitNotifier\Exceptions;

use Throwable;

/**
 * Class WebhookException
 * 
 * Exception thrown when an error occurs during webhook operations.
 * Provides factory methods for common webhook-related errors.
 */
final class WebhookException extends TelegramGitNotifierException
{
    /**
     * Create a new WebhookException instance
     *
     * @param string $message The exception message
     * @param int $code The exception code
     * @param Throwable|null $previous The previous exception
     */
    public function __construct(
        string $message = 'An error occurred during webhook operation',
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Create a new exception instance with a custom message
     *
     * @param string $message The exception message
     * @param int $code The exception code
     * @param Throwable|null $previous The previous exception
     * @return static
     */
    public static function create(
        string $message,
        int $code = 0,
        ?Throwable $previous = null
    ): self {
        return new self($message, $code, $previous);
    }

    /**
     * Exception thrown when setting up the webhook fails
     *
     * @param string|null $details Additional error details
     * @return self
     */
    public static function set(?string $details = null): self
    {
        $message = 'Failed to set webhook';
        if ($details) {
            $message .= ": {$details}";
        }
        
        return new self($message);
    }

    /**
     * Exception thrown when deleting the webhook fails
     *
     * @param string|null $details Additional error details
     * @return self
     */
    public static function delete(?string $details = null): self
    {
        $message = 'Failed to delete webhook';
        if ($details) {
            $message .= ": {$details}";
        }
        
        return new self($message);
    }

    /**
     * Exception thrown when getting updates fails
     *
     * @param string|null $details Additional error details
     * @return self
     */
    public static function getUpdates(?string $details = null): self
    {
        $message = 'Failed to get updates';
        if ($details) {
            $message .= ": {$details}";
        }
        
        return new self($message);
    }

    /**
     * Exception thrown when getting webhook info fails
     *
     * @param string|null $details Additional error details
     * @return self
     */
    public static function getWebHookInfo(?string $details = null): self
    {
        $message = 'Failed to get webhook info';
        if ($details) {
            $message .= ": {$details}";
        }
        
        return new self($message);
    }
}
