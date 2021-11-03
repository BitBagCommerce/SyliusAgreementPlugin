<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Repository;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class AgreementRepository extends EntityRepository implements AgreementRepositoryInterface
{
    public function createListQueryBuilder(): QueryBuilder
    {
        $qb = $this->createQueryBuilder('o');

        $qb->select('o');
        return $qb;
    }

    public function findOneByParent(int $id): ?AgreementInterface
    {
        $qb = $this->createQueryBuilder('o');

        $qb
            ->select('o')
            ->where($qb->expr()->eq('o.parent', ':parent'))
            ->setParameter('parent', $id);

        /** @var AgreementInterface|null $result */
        $result = $qb
            ->getQuery()
            ->getOneOrNullResult();

        return $result;
    }

    public function findAgreementsByContext(string $context, array $matchOnlyThisIdentifiers = []): array
    {
        $qb = $this->createQueryBuilder('o');

        $now = new \DateTime();

        $qb
            ->select('o')
            ->where($qb->expr()->eq('o.enabled', 'true'))
            ->andWhere('o.contexts like :context')
            ->andWhere($qb->expr()->isNull('o.archivedAt'))
            ->andWhere($qb->expr()->orX(
                $qb->expr()->isNull('o.publishedAt'),
                $qb->expr()->lte('o.publishedAt', ':now')
            ))
            ->orderBy('o.orderOnView', 'ASC')
            ->addOrderBy('o.id', 'ASC')
            ->setParameter('context', sprintf('%%%s%%', $context))
            ->setParameter('now', $now)
        ;

        if (!empty($matchOnlyThisIdentifiers)) {
            $qb
                ->andWhere(
                    $qb->expr()->in('o.id', ':identifiers')
                )
                ->setParameter('identifiers', $matchOnlyThisIdentifiers);
        }

        /** @var array $result */
        $result = $qb->getQuery()->getResult();

        return $result;
    }

    public function findAgreementsByContexts(array $contexts): array
    {
        if (empty($contexts)) {
            return [];
        }

        $qb = $this->createQueryBuilder('o');

        $now = new \DateTime();

        $qb
            ->select('o')
            ->where($qb->expr()->eq('o.enabled', 'true'))
            ->andWhere($qb->expr()->isNull('o.archivedAt'))

            ->andWhere($qb->expr()->orX(
                $qb->expr()->isNull('o.publishedAt'),
                $qb->expr()->lte('o.publishedAt', ':now')
            ))
            ->orderBy('o.orderOnView', 'ASC')
            ->addOrderBy('o.id', 'ASC')

            ->setParameter('now', $now)
        ;
        $i = 0;

        $contextExpressions = [];

        foreach ($contexts as $context) {
            $contextExpressions[] = $qb->expr()->like('o.contexts', sprintf(':context_%d', $i));
            $qb
                ->setParameter(sprintf('context_%d', $i), sprintf('%%%s%%', $context))
            ;
            ++$i;
        }

        $qb
            ->andWhere($qb->expr()->orX(...$contextExpressions));

        /** @var array $result */
        $result = $qb->getQuery()->getResult();

        return $result;
    }
}
