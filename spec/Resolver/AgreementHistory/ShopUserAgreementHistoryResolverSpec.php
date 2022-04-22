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
use BitBag\SyliusAgreementPlugin\Resolver\AgreementHistory\ShopUserAgreementHistoryResolver;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\ShopUserInterface;
use Symfony\Component\Security\Core\Security;

final class ShopUserAgreementHistoryResolverSpec extends ObjectBehavior
{
    function let(
        Security $security,
        AgreementHistoryRepositoryInterface $agreementHistoryRepository
    ): void {
        $this->beConstructedWith(
            $security,
            $agreementHistoryRepository
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(ShopUserAgreementHistoryResolver::class);
    }

    function it_resolves_history_correctly(
        Security $security,
        ShopUserInterface $shopUser,
        AgreementInterface $agreement,
        AgreementHistoryRepositoryInterface $agreementHistoryRepository,
        AgreementHistoryInterface $agreementHistory
    ): void {
        $security->getUser()->willReturn($shopUser);
        $agreement->getId()->willReturn('1');
        $shopUser->getId()->willReturn('1');
        $agreementHistoryRepository->findOneForShopUser($agreement, $shopUser)->willReturn($agreementHistory);

        $this->resolveHistory($agreement)->shouldReturn($agreementHistory);
    }

    function it_didnt_resolve_history_correctly_when_id_is_null(
        Security $security,
        ShopUserInterface $shopUser,
        AgreementInterface $agreement
    ): void {
        $security->getUser()->willReturn($shopUser);
        $agreement->getId()->willReturn(null);
        $shopUser->getId()->willReturn(null);

        $this->resolveHistory($agreement)->shouldReturn(null);
    }

    function it_supports_correctly_when_instance_of_order(
        Security $security,
        ShopUserInterface $shopUser,
        AgreementInterface $agreement
    ): void {
        $security->getUser()->willReturn($shopUser);

        $this->supports($agreement)->shouldReturn(true);
    }

}
