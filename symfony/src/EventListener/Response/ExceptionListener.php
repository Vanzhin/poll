<?php

namespace App\EventListener\Response;

use App\Response\AppException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
    const MIME_JSON = 'application/json';

    public function onKernelException(ExceptionEvent $event): void
    {
        // Получаем MIME тип из заголовка Accept
        $acceptHeader = $event->getRequest()->headers->get('Accept');

        if (str_starts_with($event->getRequest()->getRequestUri(), '/api') || $acceptHeader === static::MIME_JSON) {
            $exception = $event->getThrowable();
            $response = new JsonResponse();
            // HttpException содержит информацию о заголовках и статусе, используем это
//            пока оставлю так, не знаю для чего может пригодиться
//            можно использовать с логгером: если не моя ошибка, то отправлять ошибку в логгер

            if ($exception instanceof HttpExceptionInterface) {
                $response->setStatusCode($exception->getStatusCode());
            } else {
                $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            $response->setContent($this->exceptionToJson($exception, $response->getStatusCode()));

            if ($exception instanceof AppException) {
                $response->setStatusCode($exception->getCode());
                $response->setContent($this->appExceptionToJson($exception, $response->getStatusCode()));
            }

            $event->setResponse($response);
        }
    }

    private function exceptionToJson(\Throwable $exception, int $status): string
    {
        return json_encode(
            [
                'result' => 'error',
                'status' => $status,
                'message' => $this->getErrorMessage($status),
                'data' => [
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'message' => $exception->getMessage(),

                ],
            ]
        );
    }

    private function appExceptionToJson(AppException $exception, int $status): string
    {
        return json_encode(
            [
                'result' => 'error',
                'status' => $status,
                'message' => $exception->getMessage(),
                'data' => [
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'message' => $exception->getLog(),

                ],
            ]
        );
    }

    private function getErrorMessage(string $code): string
    {
        return match ($code) {
            '400' => 'Не удачный запрос.',
            '401' => 'Не авторизованный пользователь.',
            '403' => 'Доступ запрещен.',
            '404' => 'Страница не найдена.',
            '422' => 'Не верный формат или контент.',
            default => 'Ошибка сервера.',
        };

    }
}