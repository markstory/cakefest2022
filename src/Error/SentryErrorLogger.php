<?php
declare(strict_types=1);

namespace App\Error;

use Cake\Error\ErrorLogger;
use Cake\Error\ErrorLoggerInterface;
use Cake\Error\PhpError;
use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;
use Throwable;

class SentryErrorLogger implements ErrorLoggerInterface
{
    /**
     * @var \App\Error\Cake\Error\ErrorLoggerInterface ;
     */
    private $logger;

    public function __construct(array $config)
    {
        $this->logger = new ErrorLogger($config);
    }

    /**
     * Log an error for an exception with optional request context.
     *
     * @param \Throwable $exception The exception to log a message for.
     * @param \Psr\Http\Message\ServerRequestInterface|null $request The current request if available.
     * @return bool
     * @deprecated 4.4.0 Implement `logException` instead.
     */
    public function log(
        Throwable $exception,
        ?ServerRequestInterface $request = null
    ): bool {
        throw new RuntimeException('Not implemented');
    }

    /**
     * Log a an error message to the error logger.
     *
     * @param string|int $level The logging level
     * @param string $message The message to be logged.
     * @param array $context Context.
     * @return bool
     * @deprecated 4.4.0 Implement `logError` instead.
     */
    public function logMessage($level, string $message, array $context = []): bool
    {
        throw new RuntimeException('Not implemented');
    }

    public function logException(Throwable $exception, ?ServerRequestInterface $request = null, bool $includeTrace = false): void
    {
        $this->logger->logException($exception, $request, $includeTrace);

        \Sentry\captureException($exception);
    }

    public function logError(PhpError $error, ?ServerRequestInterface $request = null, bool $includeTrace = false): void
    {
        $this->logger->logError($error, $request, $includeTrace);

        \Sentry\captureError($error->getMessage(), \Sentry\Severity::fromError($error->getCode()));
    }
}
