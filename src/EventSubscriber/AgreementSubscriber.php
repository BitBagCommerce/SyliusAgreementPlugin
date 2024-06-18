<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\EventSubscriber;

use BitBag\SyliusAgreementPlugin\Event\AgreementCheckedEvent;
use BitBag\SyliusAgreementPlugin\Handler\AgreementHandler;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AgreementSubscriber implements EventSubscriberInterface
{
    private AgreementHandler $agreementHandler;

    public function __construct(AgreementHandler $agreementHandler)
    {
        $this->agreementHandler = $agreementHandler;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AgreementCheckedEvent::class => [
                ['processAgreementsFromAnywhere', 10],
            ],
        ];
    }

    public function processAgreementsFromAnywhere(AgreementCheckedEvent $agreementCheckedEvent): void
    {
        if (null === $agreementCheckedEvent->getEventDataUserId()) {
            return;
        }

        $data = $agreementCheckedEvent->getEvent()->getData();

        /** @var ?ShopUserInterface $shopUser */
        $shopUser = $data->getUser();

        /** @var Collection $userAgreements */
        $userAgreements = $data->getAgreements();

        $order = null;

        if (null !== $data->getId() && $data instanceof OrderInterface) {
            $order = $data;
        }

        $this->agreementHandler->handleAgreements(
            $userAgreements,
            $agreementCheckedEvent->getContext(),
            $order,
            $shopUser,
        );
    }
}
