<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Resolver;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementContexts;
use BitBag\SyliusAgreementPlugin\Repository\AgreementRepositoryInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class AgreementResolver implements AgreementResolverInterface
{
    /** @var AgreementRepositoryInterface */
    private $agreementRepository;

    /** @var SessionInterface */
    private $session;

    public function __construct(
        AgreementRepositoryInterface $agreementRepository,
        SessionInterface $session
    ) {
        $this->agreementRepository = $agreementRepository;
        $this->session = $session;
    }

    public function resolve(string $context): array
    {
        $excludedIdentifiers = [];

        if (AgreementContexts::CONTEXT_ACCOUNT === $context) {
            $excludedIdentifiers = $this->session->get(RequiredAccountAgreementsResolverInterface::SESSION_AGREEMENT_REQUIRE_ACCEPTATION_IDENTIFIERS, []);
        }

        return $this->agreementRepository->findAgreementsByContext($context, $excludedIdentifiers);
    }
}
