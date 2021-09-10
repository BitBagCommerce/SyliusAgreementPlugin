<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Resolver;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementHistoryInterface;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementHistoryStates;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use BitBag\SyliusAgreementPlugin\Entity\Company\CompanyInterface;
use BitBag\SyliusAgreementPlugin\Entity\Company\CompanyUserInterface;
use BitBag\SyliusAgreementPlugin\Entity\Customer\CustomerInterface;
use BitBag\SyliusAgreementPlugin\Entity\Order\OrderInterface;
use BitBag\SyliusAgreementPlugin\Entity\User\ShopUserInterface;
use BitBag\SyliusAgreementPlugin\Repository\AgreementHistoryRepositoryInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\Security\Core\Security;

final class AgreementApprovalResolver implements AgreementApprovalResolverInterface
{
    /** @var FactoryInterface */
    private $agreementHistoryFactory;

    /** @var Security */
    private $security;

    /** @var CartContextInterface */
    private $cartContext;

    /** @var AgreementHistoryRepositoryInterface */
    private $agreementHistoryRepository;

    public function __construct(
        FactoryInterface $agreementHistoryFactory,
        Security $security,
        CartContextInterface $cartContext,
        AgreementHistoryRepositoryInterface $agreementHistoryRepository
    ) {
        $this->agreementHistoryFactory = $agreementHistoryFactory;
        $this->security = $security;
        $this->cartContext = $cartContext;
        $this->agreementHistoryRepository = $agreementHistoryRepository;
    }

    public function resolveHistory(AgreementInterface $agreement): AgreementHistoryInterface
    {
        /** @var OrderInterface $order */
        $order = $this->cartContext->getCart();

        /** @var ShopUserInterface|null $shopUser */
        $shopUser = $this->security->getUser();

        $agreementHistory = null;

        if ($shopUser) {
            $agreementHistory = $this->resolveForShopUser($shopUser, $agreement);
        } elseif (null !== $order->getId()) {
            /** @var AgreementHistoryInterface|null $agreementHistory */
            $agreementHistory = $this->agreementHistoryRepository->findOneForOrder($agreement, $order);
        }

        if (null === $agreementHistory) {
            $agreementHistory = $this->agreementHistoryFactory->createNew();
        }

        return $agreementHistory;
    }

    public function resolve(AgreementInterface $agreement): bool
    {
        $agreementHistory = $this->resolveHistory($agreement);

        if (null !== $agreementHistory) {
            return AgreementHistoryStates::STATE_ACCEPTED === $agreementHistory->getState();
        }

        return false;
    }

    private function resolveForShopUser(ShopUserInterface $shopUser, AgreementInterface $agreement): ?AgreementHistoryInterface
    {
        /** @var CustomerInterface $customer */
        $customer = $shopUser->getCustomer();

        /** @var CompanyUserInterface|null $companyUser */
        $companyUser = $customer->getCompanyUser();

        if ($companyUser && $agreement->isInherited()) {
            /** @var CompanyInterface $company */
            $company = $companyUser->getCompany();

            /** @var AgreementHistoryInterface|null $agreementHistory */
            $agreementHistory = $this->agreementHistoryRepository->findOneForCompany($agreement, $company);
        } else {
            /** @var AgreementHistoryInterface|null $agreementHistory */
            $agreementHistory = $this->agreementHistoryRepository->findOneForShopUser($agreement, $shopUser);
        }

        return $agreementHistory;
    }
}
