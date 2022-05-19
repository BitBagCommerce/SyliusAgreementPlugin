<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Checker;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementHistoryStates;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use BitBag\SyliusAgreementPlugin\Resolver\AgreementHistoryResolverInterface;

class AgreementHistoryChecker implements AgreementHistoryCheckerInterface
{
    private AgreementHistoryResolverInterface $agreementHistoryResolver;

    public function __construct(AgreementHistoryResolverInterface $agreementHistoryResolver)
    {
        $this->agreementHistoryResolver = $agreementHistoryResolver;
    }

    public function isAgreementAccepted(AgreementInterface $agreement): bool
    {
        $agreementHistory = $this->agreementHistoryResolver->resolveHistory($agreement);

        return AgreementHistoryStates::STATE_ACCEPTED === $agreementHistory->getState();
    }
}
