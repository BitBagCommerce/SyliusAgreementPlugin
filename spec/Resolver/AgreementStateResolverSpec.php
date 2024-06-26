<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
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
        AgreementInterface $agreement,
    ): void {
        $agreement->isApproved()->willReturn(true);

        $this->resolve(
            $agreementHistory,
            $agreement,
            AgreementHistoryStates::STATE_ASSIGNED,
        )->shouldReturn(AgreementHistoryStates::STATE_ACCEPTED);
    }

    function it_returns_state_withdrawn_when_history_has_id(
        AgreementHistoryInterface $agreementHistory,
        AgreementInterface $agreement,
    ): void {
        $agreement->isApproved()->willReturn(false);
        $agreementHistory->getId()->willReturn('1');

        $this->resolve(
            $agreementHistory,
            $agreement,
            AgreementHistoryStates::STATE_ASSIGNED,
        )->shouldReturn(AgreementHistoryStates::STATE_WITHDRAWN);
    }

    function it_returns_state_withdrawn_when_state_is_shown(
        AgreementHistoryInterface $agreementHistory,
        AgreementInterface $agreement,
    ): void {
        $agreement->isApproved()->willReturn(false);
        $agreementHistory->getId()->willReturn(null);

        $this->resolve(
            $agreementHistory,
            $agreement,
            AgreementHistoryStates::STATE_SHOWN,
        )->shouldReturn(AgreementHistoryStates::STATE_SHOWN);
    }

    function it_returns_state_shown_when_properties_are_null(
        AgreementHistoryInterface $agreementHistory,
        AgreementInterface $agreement,
    ): void {
        $agreement->isApproved()->willReturn(false);
        $agreementHistory->getId()->willReturn(null);

        $this->resolve(
            $agreementHistory,
            $agreement,
            AgreementHistoryStates::STATE_ASSIGNED,
        )->shouldReturn(AgreementHistoryStates::STATE_SHOWN);
    }
}
