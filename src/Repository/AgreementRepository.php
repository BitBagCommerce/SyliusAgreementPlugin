<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

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

        if ([] !== $matchOnlyThisIdentifiers) {
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
        if ([] === $contexts) {
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

    public function findByNamePart(string $phrase, ?int $limit = null): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.code LIKE :code')
            ->setParameter('code', '%' . $phrase . '%')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
