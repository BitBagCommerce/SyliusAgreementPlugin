<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusAgreementPlugin\Resolver;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementHistoryInterface;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementHistoryStates;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use BitBag\SyliusAgreementPlugin\Resolver\AgreementStateResolver;
use PhpSpec\ObjectBehavior;


final class AgreementStateResolverSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(AgreementStateResolver::class);
    }

    function it_returns_state_accepted_when_agreement_is_approved(
        AgreementHistoryInterface $agreementHistory,
        AgreementInterface $agreement
    ): void {
        $agreement->isApproved()->willReturn(true);

        $this->resolve(
            $agreementHistory,
            $agreement,
            AgreementHistoryStates::STATE_ASSIGNED
        )->shouldReturn(AgreementHistoryStates::STATE_ACCEPTED);
    }

    function it_returns_state_withdrawn_when_history_has_id(
        AgreementHistoryInterface $agreementHistory,
        AgreementInterface $agreement
    ): void {
        $agreement->isApproved()->willReturn(false);
        $agreementHistory->getId()->willReturn('1');

        $this->resolve(
            $agreementHistory,
            $agreement,
            AgreementHistoryStates::STATE_ASSIGNED
        )->shouldReturn(AgreementHistoryStates::STATE_WITHDRAWN);
    }

    function it_returns_state_shown_when_properties_are_null(
        AgreementHistoryInterface $agreementHistory,
        AgreementInterface $agreement
    ): void {
        $agreement->isApproved()->willReturn(false);
        $agreementHistory->getId()->willReturn(null);

        $this->resolve(
            $agreementHistory,
            $agreement,
            AgreementHistoryStates::STATE_ASSIGNED
        )->shouldReturn(AgreementHistoryStates::STATE_SHOWN);
    }

}
