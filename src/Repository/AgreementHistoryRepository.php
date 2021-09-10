<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Repository;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementHistoryInterface;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use BitBag\SyliusAgreementPlugin\Entity\Company\CompanyInterface;
use BitBag\SyliusAgreementPlugin\Entity\Order\OrderInterface;
use BitBag\SyliusAgreementPlugin\Entity\User\ShopUserInterface;
use Doctrine\ORM\Query\Expr\Join;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class AgreementHistoryRepository extends EntityRepository implements AgreementHistoryRepositoryInterface
{
    public function findOneForCompany(AgreementInterface $agreement, CompanyInterface $company): ?AgreementHistoryInterface
    {
        $qb = $this->createQueryBuilder('o');
        $qb
            ->select('o')
            ->join('o.shopUser', 'shopUser')
            ->join('shopUser.customer', 'customer')
            ->join('customer.companyUser', 'companyUser')
            ->join('companyUser.company', 'company', Join::WITH, $qb->expr()->eq('company.id', ':company'))
            ->where($qb->expr()->eq('o.agreement', ':agreement'))
            ->setParameter('agreement', $agreement)
            ->setParameter('company', $company)
            ->orderBy('o.createdAt', 'DESC')
            ->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }

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

        return $qb->getQuery()->getOneOrNullResult();
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

        return $qb->getQuery()->getOneOrNullResult();
    }
}
