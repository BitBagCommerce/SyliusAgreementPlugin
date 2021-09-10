<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Repository;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementHistoryInterface;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use BitBag\SyliusAgreementPlugin\Entity\Company\CompanyInterface;
use BitBag\SyliusAgreementPlugin\Entity\Order\OrderInterface;
use BitBag\SyliusAgreementPlugin\Entity\User\ShopUserInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface AgreementHistoryRepositoryInterface extends RepositoryInterface
{
    public function findOneForCompany(AgreementInterface $agreement, CompanyInterface $company): ?AgreementHistoryInterface;

    public function findOneForShopUser(AgreementInterface $agreement, ShopUserInterface $shopUser): ?AgreementHistoryInterface;

    public function findOneForOrder(AgreementInterface $agreement, OrderInterface $order): ?AgreementHistoryInterface;
}
