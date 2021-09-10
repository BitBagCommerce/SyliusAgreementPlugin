<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Repository;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use Doctrine\ORM\QueryBuilder;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface AgreementRepositoryInterface extends RepositoryInterface
{
    public function createListQueryBuilder(): QueryBuilder;

    public function findOneByParent(int $id): ?AgreementInterface;

    public function findAgreementsByContext(string $context, array $matchOnlyThisIdentifiers = []): array;

    public function findAgreementsByContexts(array $contexts): array;
}
