<?php

namespace App\EventListener;

use App\Entity\Company;
use App\Service\Mailer;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\EventDispatcher\Event;

class CompanyCreatedListener
{
    public function __construct(
        private readonly Mailer   $mailer,
        private readonly Security $security,
    )
    {
    }

    public function onCompanyCreated(Event $event): void
    {
        /**
         * @var $company Company
         */
        $company = $event->getCompany();
        $companyAdmins = $company->getAdmins();
        foreach ($companyAdmins as $admin) {
            $this->mailer->sendCompanyCreatedEmail($admin);
        }

        $this->mailer->sendCompanyCreatedEmailToCompanyCreator($this->security->getUser(), $company);
    }
}