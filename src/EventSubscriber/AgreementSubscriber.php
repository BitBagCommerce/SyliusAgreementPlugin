<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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
            $shopUser
        );
    }
}
