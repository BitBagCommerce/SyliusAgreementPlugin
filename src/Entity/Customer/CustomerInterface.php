<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Entity\Customer;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementsRequiredInterface;
use Sylius\Component\Core\Model\CustomerInterface as BaseCustomerInterface;

interface CustomerInterface extends BaseCustomerInterface, AgreementsRequiredInterface
{
}
