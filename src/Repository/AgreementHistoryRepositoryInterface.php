<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Repository;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementHistoryInterface;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface AgreementHistoryRepositoryInterface extends RepositoryInterface
{
    public function findOneForShopUser(AgreementInterface $agreement, ShopUserInterface $shopUser): ?AgreementHistoryInterface;

    public function findOneForOrder(AgreementInterface $agreement, OrderInterface $order): ?AgreementHistoryInterface;
}
