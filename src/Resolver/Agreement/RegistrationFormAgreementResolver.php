<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Resolver\Agreement;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementContexts;
use BitBag\SyliusAgreementPlugin\Repository\AgreementRepositoryInterface;
use BitBag\SyliusAgreementPlugin\Resolver\AgreementResolverInterface;
//use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class RegistrationFormAgreementResolver implements AgreementResolverInterface
{
    private AgreementRepositoryInterface $agreementRepository;

   // private SessionInterface $session;

    public function __construct(
        AgreementRepositoryInterface $agreementRepository
        //, SessionInterface $session
    )
    {
        $this->agreementRepository = $agreementRepository;
        //$this->session = $session;
    }

    public function resolve(string $context, array $options): array
    {
        return $this->agreementRepository->findAgreementsByContext(AgreementContexts::CONTEXT_REGISTRATION_FORM);
    }

    public function supports(string $context, array $options): bool
    {
        return $context === 'sylius_customer_registration';
    }
}
