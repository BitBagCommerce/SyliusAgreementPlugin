<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusAgreementPlugin\Handler;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementHistoryInterface;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use BitBag\SyliusAgreementPlugin\Handler\AgreementHandler;
use BitBag\SyliusAgreementPlugin\Repository\AgreementHistoryRepositoryInterface;
use BitBag\SyliusAgreementPlugin\Repository\AgreementRepositoryInterface;
use BitBag\SyliusAgreementPlugin\Resolver\AgreementHistoryResolverInterface;
use Doctrine\Common\Collections\Collection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ShopUserInterface;

final class AgreementHandlerSpec extends ObjectBehavior
{
    function let(
        AgreementHistoryRepositoryInterface $agreementHistoryRepository,
        AgreementHistoryResolverInterface $agreementHistoryResolver,
        AgreementRepositoryInterface $agreementRepository
    ): void {
        $this->beConstructedWith(
            $agreementHistoryRepository,
            $agreementHistoryResolver,
            $agreementRepository
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(AgreementHandler::class);
    }

    function it_handle_agreements(
        Collection $collection,
        OrderInterface $order,
        ShopUserInterface $shopUser,
        AgreementRepositoryInterface $agreementRepository,
        AgreementInterface $agreement,
        AgreementHistoryResolverInterface $agreementHistoryResolver,
        AgreementHistoryInterface $agreementHistory
    ): void {
        $agreementRepository->findAgreementsByContext('checkout_form')->willReturn([$agreement]);
        $agreementHistoryResolver->resolveHistory($agreement)->willReturn($agreementHistory);
        $agreement->isApproved()->willReturn(true);
        $collection->filter(Argument::any())->willReturn($collection);
        $collection->first()->willReturn($agreement);
        $agreementHistory->getState()->willReturn('assigned');
        $agreementHistory->getId()->willReturn('1');

        $agreementHistory->setState('accepted')->shouldBeCalled();

        $this->handleAgreements($collection, 'checkout_form', $order, $shopUser);
    }

    function it_throws_exception_when_agreement_history_is_not_istance_of_interface(
        Collection $collection,
        OrderInterface $order,
        ShopUserInterface $shopUser,
        AgreementRepositoryInterface $agreementRepository,
        AgreementInterface $agreement,
        AgreementHistoryResolverInterface $agreementHistoryResolver
    ): void {
        $agreementRepository->findAgreementsByContext('checkout_form')->willReturn([$agreement]);
        $agreementHistoryResolver->resolveHistory($agreement)->willReturn(null);
        $agreement->isApproved()->willReturn(true);
        $collection->filter(Argument::any())->willReturn($collection);
        $collection->first()->willReturn($agreement);

        $this->shouldThrow(\InvalidArgumentException::class)
            ->during('handleAgreements', [$collection, 'checkout_form', $order, $shopUser]);
    }
}
