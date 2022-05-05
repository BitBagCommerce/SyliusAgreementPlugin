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
use BitBag\SyliusAgreementPlugin\Repository\AgreementHistoryRepositoryInterface;
use BitBag\SyliusAgreementPlugin\Resolver\CompositeAgreementHistoryResolver;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\Security\Core\Security;

final class CompositeAgreementHistoryResolverSpec extends ObjectBehavior
{
    function let(
        FactoryInterface $agreementHistoryFactory,
        AgreementHistoryRepositoryInterface $agreementHistoryRepository,
        CartContextInterface $cartContext,
        Security $security
    ): void {
        $this->beConstructedWith(
            $agreementHistoryFactory,
            $agreementHistoryRepository,
            $cartContext,
            $security
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(CompositeAgreementHistoryResolver::class);
    }

    function it_resolves_history_correctly(
        CartContextInterface $cartContext,
        OrderInterface $order,
        Security $security,
        ShopUserInterface $shopUser,
        AgreementHistoryInterface $agreementHistory,
        AgreementInterface $agreement,
        AgreementHistoryRepositoryInterface $agreementHistoryRepository
    ): void {
        $cartContext->getCart()->willReturn($order);
        $security->getUser()->willReturn($shopUser);
        $agreementHistoryRepository->findOneForShopUser($agreement, $shopUser)->willReturn($agreementHistory);

        $this->resolveHistory($agreement)->shouldReturn($agreementHistory);
    }

    function it_resolves_history_correctly_for_shopuser(
        Security $security,
        ShopUserInterface $shopUser,
        AgreementInterface $agreement,
        AgreementHistoryRepositoryInterface $agreementHistoryRepository,
        AgreementHistoryInterface $agreementHistory,
        CartContextInterface $cartContext,
        OrderInterface $order
    ): void {
        $security->getUser()->willReturn($shopUser);
        $cartContext->getCart()->willReturn($order);
        $agreementHistoryRepository->findOneForShopUser($agreement, $shopUser)->willReturn($agreementHistory);

        $this->resolveHistory($agreement)->shouldReturn($agreementHistory);
    }

    function it_resolves_history_correctly_for_order(
        Security $security,
        ShopUserInterface $shopUser,
        AgreementInterface $agreement,
        AgreementHistoryRepositoryInterface $agreementHistoryRepository,
        AgreementHistoryInterface $agreementHistory,
        CartContextInterface $cartContext,
        OrderInterface $order
    ): void {
        $security->getUser()->willReturn(null);
        $cartContext->getCart()->willReturn($order);
        $order->getId()->willReturn('1');
        $agreementHistoryRepository->findOneForOrder($agreement, $order)->willReturn($agreementHistory);

        $this->resolveHistory($agreement)->shouldReturn($agreementHistory);
    }

    function it_creates_new_history_when_properties_are_null(
        Security $security,
        FactoryInterface $agreementHistoryFactory,
        ShopUserInterface $shopUser,
        AgreementInterface $agreement,
        AgreementHistoryRepositoryInterface $agreementHistoryRepository,
        AgreementHistoryInterface $agreementHistory,
        CartContextInterface $cartContext,
        OrderInterface $order
    ): void {
        $security->getUser()->willReturn(null);
        $cartContext->getCart()->willReturn($order);
        $order->getId()->willReturn(null);
        $agreementHistoryFactory->createNew()->willReturn($agreementHistory);

        $agreementHistoryRepository->findOneForOrder($agreement, $order)->shouldNotBeCalled();
        $agreementHistoryRepository->findOneForShopUser($agreement, $shopUser)->shouldNotBeCalled();

        $this->resolveHistory($agreement)->shouldReturn($agreementHistory);
    }

}
