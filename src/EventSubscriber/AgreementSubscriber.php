<?php
declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\EventSubscriber;

use BitBag\SyliusAgreementPlugin\Event\AgreementCheckedEvent;
use BitBag\SyliusAgreementPlugin\Handler\AgreementHandler;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Webmozart\Assert\Assert;

class AgreementSubscriber implements EventSubscriberInterface
{
    private AgreementHandler $agreementHandler;

    public function __construct(AgreementHandler $agreementHandler) {
        $this->agreementHandler = $agreementHandler;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AgreementCheckedEvent::class => [
                ['processAgreementsFromAnywhere', 10]
            ],
        ];
    }

    public function processAgreementsFromAnywhere(AgreementCheckedEvent $agreementCheckedEvent): void
    {
        if (null === $agreementCheckedEvent->getEvent()->getData()->getUser()->getId()) {
            return;
        }

        $data = $agreementCheckedEvent->getEvent()->getData();
        Assert::notNull($data);

        /** @var ?ShopUserInterface $shopUser */
        $shopUser = $data->getUser();
        Assert::isInstanceOf($shopUser, ShopUserInterface::class);

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
