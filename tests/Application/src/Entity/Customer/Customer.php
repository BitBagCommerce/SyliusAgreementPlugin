<?php
declare(strict_types=1);
namespace Tests\BitBag\SyliusAgreementPlugin\Entity\Customer;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementsRequiredTrait;
use Sylius\Component\Core\Model\Customer as BaseCustomer;

class Customer extends BaseCustomer implements CustomerInterface
{
    use AgreementsRequiredTrait;
}
