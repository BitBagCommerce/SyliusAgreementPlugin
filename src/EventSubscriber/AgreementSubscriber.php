<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\EventSubscriber;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementContexts;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementHistoryStates;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use BitBag\SyliusAgreementPlugin\Entity\Company\CompanyUserInterface;
use BitBag\SyliusAgreementPlugin\Entity\Customer\CustomerInterface;
use BitBag\SyliusAgreementPlugin\Entity\Order\OrderInterface;
use BitBag\SyliusAgreementPlugin\Entity\User\ShopUserInterface;
use BitBag\SyliusAgreementPlugin\Event\CompanyUserAgreementsUpdateEvent;
use BitBag\SyliusAgreementPlugin\Repository\AgreementHistoryRepositoryInterface;
use BitBag\SyliusAgreementPlugin\Resolver\AgreementApprovalResolverInterface;
use BitBag\SyliusAgreementPlugin\Resolver\AgreementResolverInterface;
use BitBag\SyliusAgreementPlugin\Resolver\RequiredAccountAgreementsResolverInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Security;
use Webmozart\Assert\Assert;

final class AgreementSubscriber implements EventSubscriberInterface
{
    /** @var AgreementHistoryRepositoryInterface */
    private $agreementHistoryRepository;

    /** @var Security */
    private $security;

    /** @var AgreementResolverInterface */
    private $agreementResolver;

    /** @var AgreementApprovalResolverInterface */
    private $agreementApprovalResolver;

    /** @var SessionInterface */
    private $session;

    public function __construct(
        AgreementHistoryRepositoryInterface $agreementHistoryRepository,
        Security $security,
        AgreementResolverInterface $agreementResolver,
        AgreementApprovalResolverInterface $agreementApprovalResolver,
        SessionInterface $session
    ) {
        $this->agreementHistoryRepository = $agreementHistoryRepository;
        $this->security = $security;
        $this->agreementApprovalResolver = $agreementApprovalResolver;
        $this->agreementResolver = $agreementResolver;
        $this->session = $session;
    }

    public function processAgreementsFromUserRegister(ResourceControllerEvent $resourceControllerEvent): void
    {
        /** @var CompanyUserInterface $subject */
        $subject = $resourceControllerEvent->getSubject();
        Assert::isInstanceOf($subject, CompanyUserInterface::class);
        /** @var ShopUserInterface $shopUser */
        $shopUser = $subject->getCustomer()->getUser();
        Assert::isInstanceOf($shopUser, ShopUserInterface::class);
        $this->handleAgreements(
            $subject->getAgreements(),
            AgreementContexts::CONTEXT_REGISTRATION_FORM,
            null,
            $shopUser
        );
    }

    public function processAgreementsFromCheckout(ResourceControllerEvent $resourceControllerEvent): void
    {
        /** @var OrderInterface $subject */
        $subject = $resourceControllerEvent->getSubject();
        Assert::isInstanceOf($subject, OrderInterface::class);
        /** @var ShopUserInterface|null $shopUser */
        $shopUser = $this->security->getUser();
        $this->handleAgreements(
            $subject->getAgreements(),
            $shopUser ? AgreementContexts::CONTEXT_LOGGED_IN_ORDER_SUMMARY : AgreementContexts::CONTEXT_ANONYMOUS_ORDER_SUMMARY,
            $subject,
            $shopUser
        );
    }

    public function processCompanyUserAgreementsUpdate(CompanyUserAgreementsUpdateEvent $companyUserAgreementsUpdateEvent): void
    {
        $companyUser = $companyUserAgreementsUpdateEvent->getCompanyUser();
        /** @var CustomerInterface $customer */
        $customer = $companyUser->getCustomer();
        /** @var ShopUserInterface $shopUser */
        $shopUser = $customer->getUser();
        $this->handleAgreements(
           $companyUser->getAgreements(),
           AgreementContexts::CONTEXT_ACCOUNT,
           null, $shopUser
       );

        if (null !== $this->session->get(RequiredAccountAgreementsResolverInterface::SESSION_AGREEMENT_REQUIRE_ACCEPTATION_IDENTIFIERS)) {
            $this->session->remove(RequiredAccountAgreementsResolverInterface::SESSION_AGREEMENT_REQUIRE_ACCEPTATION_IDENTIFIERS);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'app.company_user.post_register' => [
                ['processAgreementsFromUserRegister', 10],
            ],
            'sylius.order.pre_complete' => [
                ['processAgreementsFromCheckout', 10],
            ],
            CompanyUserAgreementsUpdateEvent::class => [
                ['processCompanyUserAgreementsUpdate', 10],
            ],
        ];
    }

    private function handleAgreements(
        ArrayCollection $submittedAgreements,
        string $context,
        ?OrderInterface $order,
        ?ShopUserInterface $shopUser
    ): void {
        $resolvedAgreements = $this->agreementResolver->resolve($context);

        /** @var AgreementInterface $resolvedAgreement */
        foreach ($resolvedAgreements as $resolvedAgreement) {
            $agreementHistory = $this->agreementApprovalResolver->resolveHistory($resolvedAgreement);

            $submittedAgreement = $submittedAgreements->filter(static function (AgreementInterface $agreement) use ($resolvedAgreement) {
                return $agreement->getId() === $resolvedAgreement->getId();
            })->first();

            if (null === $agreementHistory->getId()) {
                $agreementHistory->setContext($context);
                $agreementHistory->setShopUser($shopUser);
                $agreementHistory->setOrder($order);
                $agreementHistory->setAgreement($resolvedAgreement);
            }

            $agreementHistoryState = AgreementHistoryStates::STATE_SHOWN;
            $resolvedAgreementHistoryState = $agreementHistory->getState();
            if ($submittedAgreement instanceof AgreementInterface && true === $submittedAgreement->isApproved()) {
                $agreementHistoryState = AgreementHistoryStates::STATE_ACCEPTED;
            } elseif ($resolvedAgreementHistoryState !== AgreementHistoryStates::STATE_SHOWN && null !== $agreementHistory->getId()) {
                $agreementHistoryState = AgreementHistoryStates::STATE_WITHDRAWN;
            }

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
}
