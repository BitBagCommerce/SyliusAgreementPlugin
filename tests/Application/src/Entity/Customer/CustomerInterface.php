<?php
declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\App\Entity\Customer;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementsRequiredInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Sylius\Component\Core\Model\CustomerInterface as BaseCustomerInterface;

interface CustomerInterface extends BaseCustomerInterface, AgreementsRequiredInterface
{
    public function getAgreements();
    public function setAgreements(ArrayCollection $agreements): void;
}