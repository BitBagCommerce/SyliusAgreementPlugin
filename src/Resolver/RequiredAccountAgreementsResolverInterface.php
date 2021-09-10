<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Resolver;

interface RequiredAccountAgreementsResolverInterface
{
    public const SESSION_AGREEMENT_REQUIRE_ACCEPTATION = 'AGREEMENT_REQUIRE_ACCEPTATION';

    public const SESSION_AGREEMENT_REQUIRE_ACCEPTATION_IDENTIFIERS = 'AGREEMENT_REQUIRE_ACCEPTATION_IDENTIFIERS';

    /**
     * @return array<int,int>
     */
    public function resolveIdentifiers(): array;
}
