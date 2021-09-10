<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Resolver;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementContexts;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementHistoryStates;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use BitBag\SyliusAgreementPlugin\Repository\AgreementRepositoryInterface;

final class RequiredAccountAgreementsResolver implements RequiredAccountAgreementsResolverInterface
{
    /** @var AgreementRepositoryInterface */
    private $agreementRepository;

    /** @var AgreementApprovalResolverInterface */
    private $agreementApprovalResolver;

    public function __construct(
        AgreementRepositoryInterface $agreementRepository,
        AgreementApprovalResolverInterface $agreementApprovalResolver
    ) {
        $this->agreementRepository = $agreementRepository;
        $this->agreementApprovalResolver = $agreementApprovalResolver;
    }

    public function resolveIdentifiers(): array
    {
        $identifiers = [];

        /** @var AgreementInterface[] $agreements */
        $agreements = $this->agreementRepository->findAgreementsByContexts([
            AgreementContexts::CONTEXT_ACCOUNT,
            AgreementContexts::CONTEXT_REGISTRATION_FORM,
        ]);
        foreach ($agreements as $agreement) {
            $agreementHistory = $this->agreementApprovalResolver->resolveHistory($agreement);
            if (
                $agreement->getUpdatedAt() > $agreementHistory->getUpdatedAt() &&
                (
                    ($agreementHistory->getState() !== AgreementHistoryStates::STATE_ACCEPTED && in_array($agreement->getMode(), [AgreementInterface::MODE_REQUIRED_AND_NON_CANCELLABLE, AgreementInterface::MODE_REQUIRED], true)) ||
                    ($agreementHistory->getState() === AgreementHistoryStates::STATE_ASSIGNED && $agreement->getMode() === AgreementInterface::MODE_NOT_REQUIRED)
                )
            ) {
                $identifiers[] = $agreement->getId();
            }
        }

        return $identifiers;
    }
}
