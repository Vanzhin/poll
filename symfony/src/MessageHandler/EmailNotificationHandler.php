<?php

namespace App\MessageHandler;

use App\Message\EmailNotification;
use App\Service\Mailer;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class EmailNotificationHandler
{
    public function __construct(private readonly Mailer $mailer)
    {
    }

    public function __invoke(EmailNotification $message): void
    {

        $this->mailer->sendLoginLinkEmail($message->getUser());
    }
}