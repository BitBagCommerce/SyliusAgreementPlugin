<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Resolver\Agreement;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementContexts;
use BitBag\SyliusAgreementPlugin\Repository\AgreementRepositoryInterface;
use BitBag\SyliusAgreementPlugin\Resolver\AgreementResolverInterface;

final class RegistrationFormAgreementResolver implements AgreementResolverInterface
{
    private AgreementRepositoryInterface $agreementRepository;

    public function __construct(
        AgreementRepositoryInterface $agreementRepository
    ) {
        $this->agreementRepository = $agreementRepository;
    }

    public function resolve(string $context, array $options): array
    {
        return $this->agreementRepository->findAgreementsByContext(AgreementContexts::CONTEXT_REGISTRATION_FORM);
    }

    public function supports(string $context, array $options): bool
    {
        return 'sylius_customer_registration' === $context;
    }
}
