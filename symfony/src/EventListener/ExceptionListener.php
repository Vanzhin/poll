<?php

namespace App\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{

    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    public function __invoke(ExceptionEvent $event): void
    {
        // You get the exception object from the received event
        $exception = $event->getThrowable();
        $request = $event->getRequest();
        $logContext = [
            "file" => $exception->getFile(),
            "line" => $exception->getLine(),
            "route_parameters" => [
                "uri" => $request->getRequestUri(),
                "method" => $request->getMethod(),
                "post" => $request->getContentTypeFormat() === 'form' ? $request->request->all() : json_decode($request->getContent(), true),
                "user" => $request->getUser(),
            ]


        ];

        $this->logger->error($exception->getMessage(), $logContext);
        // Customize your response object to display the exception details
        $response = new JsonResponse();

        // HttpExceptionInterface is a special type of exception that
        // holds status code and header details
        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        $json = [
            "message" => $exception->getMessage(),
            "result" => "error",
            "status" => $response->getStatusCode(),
            "data" => $logContext,
        ];
        $response->setContent(json_encode($json, true));

        // sends the modified response object to the event
        $event->setResponse($response);
    }

}