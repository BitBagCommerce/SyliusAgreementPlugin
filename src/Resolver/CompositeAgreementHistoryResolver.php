<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Resolver;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementHistoryInterface;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use BitBag\SyliusAgreementPlugin\Repository\AgreementHistoryRepositoryInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\Security\Core\Security;

final class CompositeAgreementHistoryResolver implements AgreementHistoryResolverInterface
{
    private FactoryInterface $agreementHistoryFactory;

    private AgreementHistoryRepositoryInterface $agreementHistoryRepository;

    private CartContextInterface $cartContext;

    private Security $security;

    public function __construct(
        FactoryInterface $agreementHistoryFactory,
        AgreementHistoryRepositoryInterface $agreementHistoryRepository,
        CartContextInterface $cartContext,
        Security $security
    ) {
        $this->agreementHistoryFactory = $agreementHistoryFactory;
        $this->agreementHistoryRepository = $agreementHistoryRepository;
        $this->cartContext = $cartContext;
        $this->security = $security;
    }

    public function resolveHistory(AgreementInterface $agreement): ?AgreementHistoryInterface
    {
        /** @var OrderInterface|null $order */
        $order = $this->cartContext->getCart();

        /** @var ShopUserInterface $shopUser */
        $shopUser = $this->security->getUser();

        $agreementHistory = null;

        if ($shopUser) {
            /** @var AgreementHistoryInterface|null $agreementHistory */
            $agreementHistory = $this->agreementHistoryRepository->findOneForShopUser($agreement, $shopUser);
        } elseif (null !== $order->getId()) {
            /** @var AgreementHistoryInterface|null $agreementHistory */
            $agreementHistory = $this->agreementHistoryRepository->findOneForOrder($agreement, $order);
        }

        if (null === $agreementHistory) {
            $agreementHistory = $this->agreementHistoryFactory->createNew();
        }

        return $agreementHistory;
    }
}
