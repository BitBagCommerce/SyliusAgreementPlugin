<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusAgreementPlugin\Resolver\AgreementHistory;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementHistoryInterface;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use BitBag\SyliusAgreementPlugin\Repository\AgreementHistoryRepositoryInterface;
use BitBag\SyliusAgreementPlugin\Resolver\AgreementHistory\OrderAgreementHistoryResolver;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Order\Context\CartContextInterface;

final class OrderAgreementHistoryResolverSpec extends ObjectBehavior
{
    function let(
        CartContextInterface $cartContext,
        AgreementHistoryRepositoryInterface $agreementHistoryRepository
    ): void {
        $this->beConstructedWith(
            $cartContext,
            $agreementHistoryRepository
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(OrderAgreementHistoryResolver::class);
    }

    function it_resolves_history_correctly(
        CartContextInterface $cartContext,
        AgreementInterface $agreement,
        OrderInterface $order,
        AgreementHistoryRepositoryInterface $agreementHistoryRepository,
        AgreementHistoryInterface $agreementHistory
    ): void {
        $cartContext->getCart()->willReturn($order);
        $agreement->getId()->willReturn('1');
        $order->getId()->willReturn('1');
        $agreementHistoryRepository->findOneForOrder($agreement, $order)->willReturn($agreementHistory);

        $this->resolveHistory($agreement)->shouldReturn($agreementHistory);
    }

    function it_didnt_resolve_history_correctly_when_id_is_null(
        CartContextInterface $cartContext,
        AgreementInterface $agreement,
        OrderInterface $order
    ): void {
        $cartContext->getCart()->willReturn($order);
        $agreement->getId()->willReturn('1');
        $order->getId()->willReturn('1');

        $this->resolveHistory($agreement)->shouldReturn(null);
    }

    function it_supports_correctly_when_instance_of_order(
        CartContextInterface $cartContext,
        AgreementInterface $agreement,
        OrderInterface $order
    ): void {
        $cartContext->getCart()->willReturn($order);

        $this->supports($agreement)->shouldReturn(true);
    }

}
