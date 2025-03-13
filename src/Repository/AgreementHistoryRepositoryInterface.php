<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Repository;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementHistoryInterface;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;

interface AgreementHistoryRepositoryInterface extends RepositoryInterface
{
    public function findOneForShopUser(AgreementInterface $agreement, ShopUserInterface $shopUser): ?AgreementHistoryInterface;

    public function findOneForOrder(AgreementInterface $agreement, OrderInterface $order): ?AgreementHistoryInterface;
}
