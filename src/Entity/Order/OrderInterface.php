<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Entity\Order;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementsRequiredInterface;
use Sylius\Component\Core\Model\OrderInterface as BaseOrderInterface;

interface OrderInterface extends BaseOrderInterface, AgreementsRequiredInterface
{
}
