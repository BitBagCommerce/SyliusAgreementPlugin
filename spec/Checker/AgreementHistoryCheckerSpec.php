<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace spec\BitBag\SyliusAgreementPlugin\Checker;

use BitBag\SyliusAgreementPlugin\Checker\AgreementHistoryChecker;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementHistoryInterface;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use BitBag\SyliusAgreementPlugin\Resolver\AgreementHistoryResolverInterface;
use PhpSpec\ObjectBehavior;

final class AgreementHistoryCheckerSpec extends ObjectBehavior
{
    function let(
        AgreementHistoryResolverInterface $agreementHistoryResolver,
    ): void {
        $this->beConstructedWith(
            $agreementHistoryResolver,
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(AgreementHistoryChecker::class);
    }

    function it_resolves_agreement_correctly(
        AgreementInterface $agreement,
        AgreementHistoryInterface $agreementHistory,
        AgreementHistoryResolverInterface $agreementHistoryResolver,
    ): void {
        $agreementHistoryResolver->resolveHistory($agreement)->willReturn($agreementHistory);
        $agreementHistory->getState()->willReturn('accepted');

        $this->isAgreementAccepted($agreement)->shouldReturn(true);
    }

    function it_not_resolves_agreement_when_state_is_other(
        AgreementInterface $agreement,
        AgreementHistoryInterface $agreementHistory,
        AgreementHistoryResolverInterface $agreementHistoryResolver,
    ): void {
        $agreementHistoryResolver->resolveHistory($agreement)->willReturn($agreementHistory);
        $agreementHistory->getState()->willReturn('rejected');

        $this->isAgreementAccepted($agreement)->shouldReturn(false);
    }
}
