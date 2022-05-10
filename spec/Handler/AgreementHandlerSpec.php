<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusAgreementPlugin\Handler;

use BitBag\SyliusAgreementPlugin\DataModifier\AgreementHistoryModifierInterface;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementHistoryInterface;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementHistoryStates;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use BitBag\SyliusAgreementPlugin\Handler\AgreementHandler;
use BitBag\SyliusAgreementPlugin\Repository\AgreementHistoryRepositoryInterface;
use BitBag\SyliusAgreementPlugin\Repository\AgreementRepositoryInterface;
use BitBag\SyliusAgreementPlugin\Resolver\AgreementHistoryResolverInterface;
use BitBag\SyliusAgreementPlugin\Resolver\AgreementStateResolverInterface;
use Doctrine\Common\Collections\Collection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class AgreementHandlerSpec extends ObjectBehavior
{
    function let(
        AgreementHistoryRepositoryInterface $agreementHistoryRepository,
        AgreementRepositoryInterface $agreementRepository,
        AgreementStateResolverInterface $agreementStateResolver,
        AgreementHistoryModifierInterface $agreementHistoryModifier
    ): void {
        $this->beConstructedWith(
            $agreementHistoryRepository,
            $agreementRepository,
            $agreementStateResolver,
            $agreementHistoryModifier
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(AgreementHandler::class);
    }

    function it_handle_agreements_correctly(
        Collection $collection,
        OrderInterface $order,
        ShopUserInterface $shopUser,
        AgreementRepositoryInterface $agreementRepository,
        AgreementInterface $agreement,
        AgreementHistoryResolverInterface $agreementHistoryResolver,
        AgreementHistoryInterface $agreementHistory,
        AgreementStateResolverInterface $agreementStateResolver,
        AgreementHistoryModifierInterface $agreementHistoryModifier
    ): void {
        $agreementRepository->findAgreementsByContext('checkout_form')->willReturn([$agreement]);
        $agreementHistoryResolver->resolveHistory($agreement)->willReturn($agreementHistory);
        $agreement->isApproved()->willReturn(true);
        $collection->filter(Argument::any())->willReturn($collection);
        $collection->first()->willReturn($agreement);
        $agreementHistoryModifier->setAgreementHistoryProperties(
            'checkout_form',
            $order,
            $shopUser,
            $agreement
        )->willReturn($agreementHistory);
        $agreementStateResolver->resolve(
            $agreementHistory,
            $agreement,
            AgreementHistoryStates::STATE_ASSIGNED
        )->willReturn(AgreementHistoryStates::STATE_ACCEPTED);
        $agreementHistory->getState()->willReturn(AgreementHistoryStates::STATE_ASSIGNED);
        $agreementHistory->getId()->willReturn('1');

        $agreementHistory->setState('accepted')->shouldBeCalled();

        $this->handleAgreements($collection, 'checkout_form', $order, $shopUser);
    }

    function it_should_throw_exception_when_no_agreements_returned(
        Collection $collection,
        OrderInterface $order,
        ShopUserInterface $shopUser,
        AgreementRepositoryInterface $agreementRepository
    ): void {
        $agreementRepository->findAgreementsByContext('checkout_form')->willReturn([]);

        $this->shouldThrow(NotFoundHttpException::class)
            ->during('handleAgreements', [$collection, 'checkout_form', $order, $shopUser]);
    }

    function it_handle_agreements_correctly_when_agreement_history_is_null(
        Collection $collection,
        OrderInterface $order,
        ShopUserInterface $shopUser,
        AgreementRepositoryInterface $agreementRepository,
        AgreementInterface $agreement,
        AgreementHistoryResolverInterface $agreementHistoryResolver,
        AgreementHistoryInterface $agreementHistory,
        AgreementStateResolverInterface $agreementStateResolver,
        AgreementHistoryModifierInterface $agreementHistoryModifier
    ): void {
        $agreementRepository->findAgreementsByContext('checkout_form')->willReturn([$agreement]);
        $agreementHistoryResolver->resolveHistory($agreement)->willReturn($agreementHistory);
        $agreement->isApproved()->willReturn(true);
        $collection->filter(Argument::any())->willReturn($collection);
        $collection->first()->willReturn($agreement);
        $agreementHistoryModifier->setAgreementHistoryProperties(
            'checkout_form',
            $order,
            $shopUser,
            $agreement
        )->willReturn($agreementHistory);
        $agreementStateResolver->resolve(
            $agreementHistory,
            $agreement,
            AgreementHistoryStates::STATE_ASSIGNED
        )->willReturn(AgreementHistoryStates::STATE_ACCEPTED);
        $agreementHistory->getState()->willReturn(AgreementHistoryStates::STATE_ASSIGNED);
        $agreementHistory->getId()->willReturn(null);

        $agreementHistory->setState('accepted')->shouldBeCalled();

        $this->handleAgreements($collection, 'checkout_form', $order, $shopUser);
    }

    function it_handle_agreements_correctly_when_state_is_other(
        Collection $collection,
        OrderInterface $order,
        ShopUserInterface $shopUser,
        AgreementRepositoryInterface $agreementRepository,
        AgreementInterface $agreement,
        AgreementHistoryResolverInterface $agreementHistoryResolver,
        AgreementHistoryInterface $agreementHistory,
        AgreementStateResolverInterface $agreementStateResolver,
        AgreementHistoryModifierInterface $agreementHistoryModifier
    ): void {
        $agreementRepository->findAgreementsByContext('checkout_form')->willReturn([$agreement]);
        $agreementHistoryResolver->resolveHistory($agreement)->willReturn($agreementHistory);
        $agreement->isApproved()->willReturn(true);
        $collection->filter(Argument::any())->willReturn($collection);
        $collection->first()->willReturn($agreement);
        $agreementHistoryModifier->setAgreementHistoryProperties(
            'checkout_form',
            $order,
            $shopUser,
            $agreement
        )->willReturn($agreementHistory);
        $agreementStateResolver->resolve(
            $agreementHistory,
            $agreement,
            AgreementHistoryStates::STATE_ASSIGNED
        )->willReturn(AgreementHistoryStates::STATE_SHOWN);
        $agreementHistory->getState()->willReturn(AgreementHistoryStates::STATE_ASSIGNED);
        $agreementHistory->getId()->willReturn('1');

        $agreementHistory->setState('shown')->shouldBeCalled();

        $this->handleAgreements($collection, 'checkout_form', $order, $shopUser);
    }
}
