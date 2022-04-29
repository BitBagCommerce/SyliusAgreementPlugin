<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusAgreementPlugin\EventSubscriber;

use BitBag\SyliusAgreementPlugin\Event\AgreementCheckedEvent;
use BitBag\SyliusAgreementPlugin\EventSubscriber\AgreementSubscriber;
use BitBag\SyliusAgreementPlugin\Handler\AgreementHandler;
use Doctrine\Common\Collections\Collection;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\ShopUserInterface;
use Symfony\Component\Form\FormEvent;
use Tests\BitBag\SyliusAgreementPlugin\Entity\Order\OrderInterface;

final class AgreementSubscriberSpec extends ObjectBehavior
{
    function let(
        AgreementHandler $agreementHandler
    ): void {
        $this->beConstructedWith(
            $agreementHandler
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(AgreementSubscriber::class);
    }

    function it_quit_function_when_user_is_null(
        AgreementCheckedEvent $agreementCheckedEvent,
        FormEvent $formEvent,
        OrderInterface $order,
        ShopUserInterface $shopUser
    ): void {
        $agreementCheckedEvent->getEventDataUserId()->willReturn(null);

        $this->processAgreementsFromAnywhere($agreementCheckedEvent)
            ->shouldReturn(null);
    }

    function it_process_successfully(
        AgreementCheckedEvent $agreementCheckedEvent,
        FormEvent $formEvent,
        OrderInterface $order,
        ShopUserInterface $shopUser,
        Collection $userAgreements,
        AgreementHandler $agreementHandler
    ): void {
        $agreementCheckedEvent->getEvent()->willReturn($formEvent);
        $agreementCheckedEvent->getEventDataUserId()->willReturn('1');
        $formEvent->getData()->willReturn($order);
        $order->getUser()->willReturn($shopUser);
        $shopUser->getId()->willReturn('1');
        $order->getAgreements()->willReturn($userAgreements);
        $agreementCheckedEvent->getContext()->willReturn('checkout_form');
        $order->getId()->willReturn('1');

        $agreementHandler->handleAgreements(
            $userAgreements,
            'checkout_form',
            $order,
            $shopUser)
            ->shouldBeCalled();

        $this->processAgreementsFromAnywhere($agreementCheckedEvent);

    }
}
