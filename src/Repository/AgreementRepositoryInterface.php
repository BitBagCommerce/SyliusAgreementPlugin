<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Repository;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use Doctrine\ORM\QueryBuilder;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;

interface AgreementRepositoryInterface extends RepositoryInterface
{
    public function createListQueryBuilder(): QueryBuilder;

    public function findOneByParent(int $id): ?AgreementInterface;

    public function findAgreementsByContext(string $context, array $matchOnlyThisIdentifiers = []): array;

    public function findAgreementsByContexts(array $contexts): array;
}
