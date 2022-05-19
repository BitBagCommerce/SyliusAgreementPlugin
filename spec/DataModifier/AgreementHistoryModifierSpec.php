<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusAgreementPlugin\DataModifier;

use BitBag\SyliusAgreementPlugin\DataModifier\AgreementHistoryModifier;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementHistoryInterface;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use BitBag\SyliusAgreementPlugin\Resolver\AgreementHistoryResolverInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ShopUserInterface;

final class AgreementHistoryModifierSpec extends ObjectBehavior
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
        $this->shouldHaveType(AgreementHistoryModifier::class);
    }

    function it_returns_agreement_history_from_repository(
        OrderInterface $order,
        ShopUserInterface $shopUser,
        AgreementInterface $agreement,
        AgreementHistoryResolverInterface $agreementHistoryResolver,
        AgreementHistoryInterface $agreementHistory
    ): void {
        $agreementHistoryResolver->resolveHistory($agreement)->willReturn($agreementHistory);
        $agreementHistory->getId()->willReturn('1');

        $agreementHistory->setContext('registration_form')->shouldNotBeCalled();

        $this->setAgreementHistoryProperties(
            'registration_form',
            $order,
            $shopUser,
            $agreement
        )->shouldReturn($agreementHistory);
    }

    function it_sets_data_when_history_id_is_null(
        OrderInterface $order,
        ShopUserInterface $shopUser,
        AgreementInterface $agreement,
        AgreementHistoryResolverInterface $agreementHistoryResolver,
        AgreementHistoryInterface $agreementHistory
    ): void {
        $agreementHistoryResolver->resolveHistory($agreement)->willReturn($agreementHistory);
        $agreementHistory->getId()->willReturn(null);

        $agreementHistory->setContext('registration_form')->shouldBeCalled();
        $agreementHistory->setShopUser($shopUser)->shouldBeCalled();
        $agreementHistory->setOrder($order)->shouldBeCalled();
        $agreementHistory->setAgreement($agreement)->shouldBeCalled();

        $this->setAgreementHistoryProperties(
            'registration_form',
            $order,
            $shopUser,
            $agreement
        )->shouldReturn($agreementHistory);
    }

}
