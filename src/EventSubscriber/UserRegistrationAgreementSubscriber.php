<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\EventSubscriber;

use BitBag\SyliusAgreementPlugin\Handler\AgreementHandler;
use Doctrine\Common\Collections\Collection;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Core\Model\ShopUserInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Tests\BitBag\SyliusAgreementPlugin\Entity\Customer\CustomerInterface;
use Webmozart\Assert\Assert;

class UserRegistrationAgreementSubscriber implements EventSubscriberInterface
{
    private AgreementHandler $agreementHandler;

    public function __construct(AgreementHandler $agreementHandler)
    {
        $this->agreementHandler = $agreementHandler;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.customer.post_register' => [
                ['processAgreementsFromUserRegister', 10],
            ],
        ];
    }

    public function processAgreementsFromUserRegister(ResourceControllerEvent $resourceControllerEvent): void
    {
        /** @var ?CustomerInterface $customer */
        $customer = $resourceControllerEvent->getSubject();
        Assert::isInstanceOf($customer, CustomerInterface::class);

        /** @var ?ShopUserInterface $shopUser */
        $shopUser = $customer->getUser();
        Assert::isInstanceOf($shopUser, ShopUserInterface::class);

        /** @var Collection $userAgreements */
        $userAgreements = $customer->getAgreements();

        $context = $userAgreements->first()->getContexts()[0];

        $this->agreementHandler->handleAgreements(
            $userAgreements,
            $context,
            null,
            $shopUser
        );
    }
}
