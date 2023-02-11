<?php

namespace App\Service;

use App\Entity\User;
use App\Message\SendEmailMessage;

//use App\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

//use Symfony\Component\Mailer\Messenger\SendEmailMessage;
use Symfony\Component\Mime\Address;
use Symfony\Component\Security\Http\LoginLink\LoginLinkDetails;
use Symfony\Component\Security\Http\LoginLink\LoginLinkHandlerInterface;

class Mailer
{

    private string $appName;
    private string $defaultFromEmail;
    private string $defaultFromName;


    public function __construct(private readonly MailerInterface $mailer,
                                private readonly LoginLinkHandlerInterface $loginLinkHandler,
                                string $appName,
                                string $defaultFromEmail,
                                string $defaultFromName)
    {
        $this->appName = $appName;
        $this->defaultFromEmail = $defaultFromEmail;
        $this->defaultFromName = $defaultFromName;
    }

//    public function sendWelcomeEmail(SendEmailMessage $message): void
//    {
//        $this->send(
//            $message->getTemplate() ?? 'emails/welcome.html.twig',
//            $message->getFromAddress() ?? 'welcome@test.ru',
//            $message->getFromName() ?? $this->defaultFromName,
//            $message->getUser(),
//            $message->getSubject() ?? 'Welcome to ' . $this->appName,
//            function ($email) use ($message) {
//                $email->context([
//                    'user' => $message->getUser(),
//                    'appName' => $this->appName
//                ]);
//            }
//        );
//    }

//    public function sendVerificationEmail(SendEmailMessage $message): void
//    {
//        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $message->getUser(),
//            $this->email(
//                'emails/confirmation_email.html.twig',
//                $message->getFromAddress() ?? 'noreplye@test.ru',
//                $message->getFromName() ?? $this->defaultFromName,
//                $message->getUser(),
//                $message->getSubject() ?? 'Email verification from ' . $this->appName,
//            )
//        );
//    }

    private function send(string $template, string $fromEmail, string $fromName, User $user, string $subject, \Closure $callback = null): void
    {
        $email = $this->email($template, $fromEmail, $fromName, $user, $subject);


        if ($callback) {
            $callback($email);
        }
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $exception) {
            $exception->getMessage();
            return;
        }
    }

    private function email(string $template, string $fromEmail, string $fromName, User $user, string $subject): TemplatedEmail
    {
        return (new TemplatedEmail())
            ->from(new Address($fromEmail, $fromName))
            ->to(new Address($user->getEmail(), $user->getFirstName()))
            ->subject($subject)
            ->htmlTemplate($template);
    }

    public function sendLoginLinkEmail(User $user): void
    {
        $email = (new TemplatedEmail())
            ->from(new Address('welcome@poll.ru', 'poll bot'))
            ->to(new Address($user->getEmail(), $user->getFirstName()?? 'Пользователь'))
            ->subject('login link')
            ->htmlTemplate('emails/login_link.html.twig')
            ->context(['user' => $user,
                'appName' => $this->appName,
                'loginLinkDetails' => $this->loginLinkHandler->createLoginLink($user)]);
        $this->mailer->send($email);
    }
}