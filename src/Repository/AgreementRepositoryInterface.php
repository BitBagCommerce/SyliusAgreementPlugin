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
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface AgreementRepositoryInterface extends RepositoryInterface
{
    public function createListQueryBuilder(): QueryBuilder;

    public function findOneByParent(int $id): ?AgreementInterface;

    public function findAgreementsByContext(string $context, array $matchOnlyThisIdentifiers = []): array;

    public function findAgreementsByContexts(array $contexts): array;
}
