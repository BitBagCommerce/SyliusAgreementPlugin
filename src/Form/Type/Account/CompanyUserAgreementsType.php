<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Form\Type\Account;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;

final class CompanyUserAgreementsType extends AbstractResourceType
{
    public function getBlockPrefix(): string
    {
        return 'app_company_user_agreements';
    }
}
