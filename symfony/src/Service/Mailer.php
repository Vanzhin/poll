<?php

namespace App\Service;

use App\Entity\Company;
use App\Entity\User\User;
use App\Repository\User\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class Mailer
{

    private string $appName;
    private string $defaultFromEmail;
    private string $defaultFromName;


    public function __construct(
        private readonly MailerInterface       $mailer,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly UserRepository        $repository,
        string                                 $appName,
        string                                 $defaultFromEmail,
        string                                 $defaultFromName,
    )
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

    public function sendLoginLinkEmail(User $user, string $link): void
    {
        try {
            $email = (new TemplatedEmail())
                ->from(new Address($this->defaultFromEmail, $this->defaultFromName))
                ->to(new Address($user->getEmail(), $user->getFirstName() ?? 'Пользователь'))
                ->subject('login link')
                ->htmlTemplate('emails/login_link.html.twig')
                ->context(['user' => $user,
                    'appName' => $this->appName,
                    'loginLink' => $link]);
            $this->mailer->send($email);
        } catch (\Exception $e) {
            throw new \Error(sprintf("Не удалось отправить письмо на %s", $user->getEmail()));
        }

    }

    public function sendCompanyCreatedEmail(User $user, string $link): void
    {
        try {
            $email = (new TemplatedEmail())
                ->from(new Address($this->defaultFromEmail, $this->defaultFromName))
                ->to(new Address($user->getEmail(), $user->getProfile()?->getFirstName() ?? 'Пользователь'))
                ->subject(sprintf('Регистрация компании %s на портале %s', $user->getCompany()?->getTitle(), $this->appName))
                ->htmlTemplate('emails/company_created.html.twig')
                ->context(
                    ['user' => $user,
                        'appName' => $this->appName,
                        'loginLink' => $link
                    ]);
            $this->mailer->send($email);
        } catch (\Exception|\Error $e) {
            throw new \Exception(sprintf("Не удалось отправить письмо на %s", $user->getEmail()));
        }

    }

    public function sendCompanyCreatedEmailToCompanyCreator(UserInterface $getUser, Company $company): void
    {
        try {
            $user = $this->repository->findOneByLogin($getUser->getUserIdentifier());
            if ($user->getEmail()) {
                $email = (new TemplatedEmail())
                    ->from(new Address($this->defaultFromEmail, $this->defaultFromName))
                    ->to(new Address($user->getEmail(), $user->getProfile()?->getFirstName() ?? 'Пользователь'))
                    ->subject('Регистрация новой компании')
                    ->htmlTemplate('emails/new_company_created.html.twig')
                    ->context(
                        ['user' => $user,
                            'appName' => $this->appName,
                            'company' => $company,
                            'companyViewPath' => $this->urlGenerator->generate('app_home', [], UrlGeneratorInterface::ABSOLUTE_URL)

//                            'companyViewPath' => $this->urlGenerator->generate('app_api_admin_company_show', ['id' => $company->getId()], UrlGeneratorInterface::ABSOLUTE_URL)
                        ]);
                $this->mailer->send($email);
            }

        } catch (\Exception|\Error $e) {
            throw new \Exception(sprintf("Не удалось отправить письмо на %s", $user->getEmail()));
        }
    }
}
