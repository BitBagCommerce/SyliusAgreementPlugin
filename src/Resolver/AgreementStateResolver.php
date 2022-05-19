<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Resolver;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementHistoryInterface;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementHistoryStates;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;

class AgreementStateResolver implements AgreementStateResolverInterface
{
    public function resolve(
        AgreementHistoryInterface $agreementHistory,
        AgreementInterface $submittedAgreement,
        string $resolvedAgreementHistoryState
    ): string {
        $agreementHistoryState = AgreementHistoryStates::STATE_SHOWN;

        if (true === $submittedAgreement->isApproved()) {
            $agreementHistoryState = AgreementHistoryStates::STATE_ACCEPTED;
        } elseif (
            AgreementHistoryStates::STATE_SHOWN !== $resolvedAgreementHistoryState
            && null !== $agreementHistory->getId()
        ) {
            $agreementHistoryState = AgreementHistoryStates::STATE_WITHDRAWN;
        }

        return $agreementHistoryState;
    }
}
