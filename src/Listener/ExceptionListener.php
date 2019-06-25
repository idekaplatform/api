<?php

namespace App\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Exception\ValidationException;

use Psr\Log\LoggerInterface;

class ExceptionListener
{
    /** @var LoggerInterface */
    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if ($exception instanceof ValidationException) {
            $event->setResponse(new JsonResponse($exception->getErrors()));
            return;
        } elseif ($exception instanceof HttpException) {
            $message = $exception->getMessage();
            $code = $exception->getStatusCode();
        } else {
            $message = 'server.errors.internal';
            $code = 500;
        }
        $this->storeException($exception, $code);

        $event->setResponse(new JsonResponse([
            'error' => $message,
        ], $code));
    }

    protected function storeException(\Exception $exception, int $code)
    {
        $this->logger->{($code === 500) ? 'critical' : 'error'}("{$exception->getMessage()} at {$exception->getFile()}.{$exception->getLine()}");
    }
}