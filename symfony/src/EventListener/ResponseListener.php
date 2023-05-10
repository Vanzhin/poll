<?php

namespace App\EventListener;

use Doctrine\DBAL\Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ResponseListener
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    public function __invoke(ResponseEvent $event): void
    {
        $response=$event->getResponse();
        if($response->getStatusCode()>=400){
            $this->logger->error('hello');

            throw new Exception('gopa');
        }
        // sends the modified response object to the event
        $event->setResponse($response);
    }

}