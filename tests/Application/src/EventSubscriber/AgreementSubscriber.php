<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\App\EventSubscriber;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementContexts;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementHistoryInterface;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementHistoryStates;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use BitBag\SyliusAgreementPlugin\Repository\AgreementHistoryRepositoryInterface;
use BitBag\SyliusAgreementPlugin\Resolver\AgreementHistoryResolverInterface;
use BitBag\SyliusAgreementPlugin\Resolver\AgreementResolverInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Security;
use Webmozart\Assert\Assert;

final class AgreementSubscriber implements EventSubscriberInterface
{
    private AgreementHistoryRepositoryInterface $agreementHistoryRepository;

    private Security $security;

    private AgreementResolverInterface $agreementResolver;

    private AgreementHistoryResolverInterface $agreementHistoryResolver;

    private SessionInterface $session;

    public function __construct(
        AgreementHistoryRepositoryInterface $agreementHistoryRepository,
        Security $security,
        AgreementResolverInterface $agreementResolver,
        AgreementHistoryResolverInterface $agreementHistoryResolver,
        SessionInterface $session
    )
    {
        $this->agreementHistoryRepository = $agreementHistoryRepository;
        $this->security = $security;
        $this->agreementHistoryResolver = $agreementHistoryResolver;
        $this->agreementResolver = $agreementResolver;
        $this->session = $session;
    }

    public function processAgreementsFromUserRegister(ResourceControllerEvent $resourceControllerEvent): void
    {
        /** @var CustomerInterface $subject */
        $customer = $resourceControllerEvent->getSubject();
        Assert::isInstanceOf($customer, CustomerInterface::class);
        /** @var ShopUserInterface $shopUser */
        $shopUser = $customer->getUser();
        Assert::isInstanceOf($shopUser, ShopUserInterface::class);

        $this->handleAgreements(
            $customer->getAgreements(),
            AgreementContexts::CONTEXT_REGISTRATION_FORM,
            null,
            $shopUser
        );
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.customer.post_register' => [
                ['processAgreementsFromUserRegister', 10],
            ]
        ];
    }

    private function handleAgreements(
        ArrayCollection $submittedAgreements,
        string $context,
        ?OrderInterface $order,
        ?ShopUserInterface $shopUser
    ): void
    {
        $resolvedAgreements = $this->agreementResolver->resolve($context, []);

        /** @var AgreementInterface $resolvedAgreement */
        foreach ($resolvedAgreements as $resolvedAgreement) {
            $agreementHistory = $this->agreementHistoryResolver->resolveHistory($resolvedAgreement);

            $submittedAgreement = $submittedAgreements->filter(static function (AgreementInterface $agreement) use ($resolvedAgreement) {
                return $agreement->getId() === $resolvedAgreement->getId();
            })->first();

            if (null === $agreementHistory->getId()) {
                $agreementHistory->setContext($context);
                $agreementHistory->setShopUser($shopUser);
                $agreementHistory->setOrder($order);
                $agreementHistory->setAgreement($resolvedAgreement);
            }

            $resolvedAgreementHistoryState = $agreementHistory->getState();
            $agreementHistoryState = $this->determineState(
                $agreementHistory,
                $submittedAgreement,
                $resolvedAgreementHistoryState
            );

            if (
                $agreementHistoryState !== $resolvedAgreementHistoryState &&
                null !== $agreementHistory->getId()
            ) {
                $agreementHistory = clone $agreementHistory;
            }
            $agreementHistory->setState($agreementHistoryState);
            $this->agreementHistoryRepository->add($agreementHistory);
        }
    }

    private function determineState(
        AgreementHistoryInterface $agreementHistory,
        AgreementInterface $submittedAgreement,
        string $resolvedAgreementHistoryState
    ): string
    {
        $agreementHistoryState = AgreementHistoryStates::STATE_SHOWN;
        if ($submittedAgreement instanceof AgreementInterface && true === $submittedAgreement->isApproved()) {
            $agreementHistoryState = AgreementHistoryStates::STATE_ACCEPTED;
        } elseif ($resolvedAgreementHistoryState !== AgreementHistoryStates::STATE_SHOWN && null !== $agreementHistory->getId()) {
            $agreementHistoryState = AgreementHistoryStates::STATE_WITHDRAWN;
        }

        return $agreementHistoryState;
    }
}
