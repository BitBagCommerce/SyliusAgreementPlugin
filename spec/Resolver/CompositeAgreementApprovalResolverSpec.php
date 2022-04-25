<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusAgreementPlugin\Resolver;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementHistoryInterface;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use BitBag\SyliusAgreementPlugin\Resolver\AgreementHistoryResolverInterface;
use BitBag\SyliusAgreementPlugin\Resolver\CompositeAgreementApprovalResolver;
use PhpSpec\ObjectBehavior;

final class CompositeAgreementApprovalResolverSpec extends ObjectBehavior
{
    function let(
        AgreementHistoryResolverInterface $agreementHistoryResolver
    ): void {
        $this->beConstructedWith(
            $agreementHistoryResolver
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(CompositeAgreementApprovalResolver::class);
    }

    function it_resolves_agreement_correctly(
        AgreementInterface $agreement,
        AgreementHistoryInterface $agreementHistory,
        AgreementHistoryResolverInterface $agreementHistoryResolver
    ): void {
        $agreementHistoryResolver->resolveHistory($agreement)->willReturn($agreementHistory);
        $agreementHistory->getState()->willReturn('accepted');

        $this->resolve($agreement)->shouldReturn(true);
    }

    function it_not_resolves_agreement_when_not_instance_of_interface(
        AgreementInterface $agreement,
        AgreementHistoryResolverInterface $agreementHistoryResolver
    ): void {
        $agreementHistoryResolver->resolveHistory($agreement)->willReturn(null);

        $this->resolve($agreement)->shouldReturn(false);
    }

}
