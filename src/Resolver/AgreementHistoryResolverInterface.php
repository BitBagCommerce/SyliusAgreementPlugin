<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Resolver;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementHistoryInterface;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;

interface AgreementHistoryResolverInterface
{
    public function resolveHistory(AgreementInterface $agreement): ?AgreementHistoryInterface;

    public function supports(AgreementInterface $agreement): bool;
}
