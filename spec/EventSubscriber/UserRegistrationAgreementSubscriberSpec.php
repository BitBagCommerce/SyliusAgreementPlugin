<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace spec\BitBag\SyliusAgreementPlugin\EventSubscriber;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use BitBag\SyliusAgreementPlugin\EventSubscriber\UserRegistrationAgreementSubscriber;
use BitBag\SyliusAgreementPlugin\Handler\AgreementHandler;
use Doctrine\Common\Collections\Collection;
use InvalidArgumentException;
use PhpSpec\ObjectBehavior;
use Sylius\Resource\Symfony\EventDispatcher\GenericEvent;
use Sylius\Component\Core\Model\ShopUserInterface;
use Tests\BitBag\SyliusAgreementPlugin\Entity\Customer\CustomerInterface;

final class UserRegistrationAgreementSubscriberSpec extends ObjectBehavior
{
    function let(
        AgreementHandler $agreementHandler,
    ): void {
        $this->beConstructedWith(
            $agreementHandler,
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(UserRegistrationAgreementSubscriber::class);
    }

    function it_throws_exception_when_customer_is_not_instance_of_interface(
        GenericEvent $genericEvent,
    ): void {
        $genericEvent->getSubject()->willReturn(null);

        $this->shouldThrow(InvalidArgumentException::class)
            ->during('processAgreementsFromUserRegister', [$genericEvent]);
    }

    function it_throws_exception_when_shopuser_is_not_instance_of_interface(
        GenericEvent $genericEvent,
        CustomerInterface $customer,
    ): void {
        $genericEvent->getSubject()->willReturn($customer);
        $customer->getUser()->willReturn(null);

        $this->shouldThrow(InvalidArgumentException::class)
            ->during('processAgreementsFromUserRegister', [$genericEvent]);
    }

    function it_process_successfully(
        GenericEvent $genericEvent,
        CustomerInterface $customer,
        ShopUserInterface $shopUser,
        Collection $userAgreements,
        AgreementInterface $agreement,
        AgreementHandler $agreementHandler,
    ): void {
        $genericEvent->getSubject()->willReturn($customer);
        $customer->getUser()->willReturn($shopUser);
        $customer->getAgreements()->willReturn($userAgreements);
        $userAgreements->isEmpty()->willReturn(false);
        $userAgreements->first()->willReturn($agreement);
        $agreement->getContexts()->willReturn(['registration_form']);

        $agreementHandler->handleAgreements(
            $userAgreements,
            'registration_form',
            null,
            $shopUser,
        )
        ->shouldBeCalled();

        $this->processAgreementsFromUserRegister($genericEvent);
    }
}
