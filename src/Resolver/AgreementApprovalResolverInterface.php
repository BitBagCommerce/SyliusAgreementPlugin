<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Resolver;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;

interface AgreementApprovalResolverInterface
{
    public function resolve(AgreementInterface $agreement): bool;

    public function supports(AgreementInterface $agreement): bool;
}
