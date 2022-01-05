<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Resolver;

interface AgreementResolverInterface
{
    public function resolve(string $context, array $options): array;

    public function supports(string $context, array $options): bool;
}
