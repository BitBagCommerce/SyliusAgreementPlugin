<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\EventSubscriber;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementsRequiredInterface;
use BitBag\SyliusAgreementPlugin\Handler\AgreementHandler;
use Doctrine\Common\Collections\Collection;
use Sylius\Resource\Symfony\EventDispatcher\GenericEvent;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
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

    public function processAgreementsFromUserRegister(GenericEvent $genericEvent): void
    {
        /** @var ?CustomerInterface $customer */
        $customer = $genericEvent->getSubject();
        Assert::isInstanceOf($customer, CustomerInterface::class);
        Assert::isInstanceOf($customer, AgreementsRequiredInterface::class);

        /** @var ?ShopUserInterface $shopUser */
        $shopUser = $customer->getUser();
        Assert::isInstanceOf($shopUser, ShopUserInterface::class);

        /** @var Collection $userAgreements */
        $userAgreements = $customer->getAgreements();
        if ($userAgreements->isEmpty()) {
            return;
        }

        $context = $userAgreements->first()->getContexts()[0];

        $this->agreementHandler->handleAgreements(
            $userAgreements,
            $context,
            null,
            $shopUser,
        );
    }
}
