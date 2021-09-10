<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Event;

use BitBag\SyliusAgreementPlugin\Entity\Company\CompanyUserInterface;
use Symfony\Contracts\EventDispatcher\Event;

final class CompanyUserAgreementsUpdateEvent extends Event
{
    /** @var CompanyUserInterface */
    private $companyUser;

    public function __construct(CompanyUserInterface $companyUser)
    {
        $this->companyUser = $companyUser;
    }

    public function getCompanyUser(): CompanyUserInterface
    {
        return $this->companyUser;
    }
}
