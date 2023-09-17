<?php

namespace App\EventListener;

use App\Entity\Company;
use App\Service\Mailer;
use App\Service\SecurityService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Contracts\EventDispatcher\Event;

class CompanyCreatedListener
{
    public function __construct(
        private readonly Mailer   $mailer,
        private readonly Security $security,
        private readonly SecurityService $service,
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
            $this->mailer->sendCompanyCreatedEmail($admin, $this->service->getLoginLink($admin));
        }

        $this->mailer->sendCompanyCreatedEmailToCompanyCreator($this->security->getUser(), $company);
    }
}