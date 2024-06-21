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
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ShopUserInterface;

class AgreementHistoryRepository extends EntityRepository implements AgreementHistoryRepositoryInterface
{
    public function findOneForShopUser(AgreementInterface $agreement, ShopUserInterface $shopUser): ?AgreementHistoryInterface
    {
        $qb = $this->createQueryBuilder('o');
        $qb
            ->select('o')
            ->where($qb->expr()->eq('o.shopUser', ':shopUser'))
            ->andWhere($qb->expr()->eq('o.agreement', ':agreement'))
            ->setParameter('agreement', $agreement)
            ->setParameter('shopUser', $shopUser)
            ->orderBy('o.createdAt', 'DESC')
            ->setMaxResults(1);

        /** @var ?AgreementHistoryInterface $result */
        $result = $qb->getQuery()->getOneOrNullResult();

        return $result;
    }

    public function findOneForOrder(AgreementInterface $agreement, OrderInterface $order): ?AgreementHistoryInterface
    {
        $qb = $this->createQueryBuilder('o');
        $qb
            ->select('o')
            ->where($qb->expr()->eq('o.order', ':order'))
            ->andWhere($qb->expr()->eq('o.agreement', ':agreement'))
            ->setParameter('agreement', $agreement)
            ->setParameter('order', $order)
            ->orderBy('o.createdAt', 'DESC')
            ->setMaxResults(1);

        /** @var AgreementHistoryInterface|null $result */
        $result = $qb->getQuery()->getOneOrNullResult();

        return $result;
    }
}
