<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusAgreementPlugin\EventSubscriber;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use BitBag\SyliusAgreementPlugin\EventSubscriber\UserRegistrationAgreementSubscriber;
use BitBag\SyliusAgreementPlugin\Handler\AgreementHandler;
use Doctrine\Common\Collections\Collection;
use InvalidArgumentException;
use PhpSpec\ObjectBehavior;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Core\Model\ShopUserInterface;
use Tests\BitBag\SyliusAgreementPlugin\Entity\Customer\CustomerInterface;

final class UserRegistrationAgreementSubscriberSpec extends ObjectBehavior
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
        $this->shouldHaveType(UserRegistrationAgreementSubscriber::class);
    }

    function it_throws_exception_when_customer_is_not_instance_of_interface(
        ResourceControllerEvent $resourceControllerEvent
    ): void {
        $resourceControllerEvent->getSubject()->willReturn(null);

        $this->shouldThrow(InvalidArgumentException::class)
            ->during('processAgreementsFromUserRegister', [$resourceControllerEvent]);
    }

    function it_throws_exception_when_shopuser_is_not_instance_of_interface(
        ResourceControllerEvent $resourceControllerEvent,
        CustomerInterface $customer
    ): void {
        $resourceControllerEvent->getSubject()->willReturn($customer);
        $customer->getUser()->willReturn(null);

        $this->shouldThrow(InvalidArgumentException::class)
            ->during('processAgreementsFromUserRegister', [$resourceControllerEvent]);
    }

    function it_process_successfully(
        ResourceControllerEvent $resourceControllerEvent,
        CustomerInterface $customer,
        ShopUserInterface $shopUser,
        Collection $userAgreements,
        AgreementInterface $agreement,
        AgreementHandler $agreementHandler
    ): void {
        $resourceControllerEvent->getSubject()->willReturn($customer);
        $customer->getUser()->willReturn($shopUser);
        $customer->getAgreements()->willReturn($userAgreements);
        $userAgreements->first()->willReturn($agreement);
        $agreement->getContexts()->willReturn(['registration_form']);

        $agreementHandler->handleAgreements(
            $userAgreements,
            'registration_form',
            null,
            $shopUser)
        ->shouldBeCalled();

        $this->processAgreementsFromUserRegister($resourceControllerEvent);
    }
}
