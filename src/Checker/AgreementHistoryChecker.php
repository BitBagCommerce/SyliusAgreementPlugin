<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
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
