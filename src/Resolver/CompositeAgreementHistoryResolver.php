<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Resolver;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementHistoryInterface;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use BitBag\SyliusAgreementPlugin\Repository\AgreementHistoryRepositoryInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Resource\Factory\FactoryInterface;
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
        Security $security,
    ) {
        $this->agreementHistoryFactory = $agreementHistoryFactory;
        $this->agreementHistoryRepository = $agreementHistoryRepository;
        $this->cartContext = $cartContext;
        $this->security = $security;
    }

    public function resolveHistory(AgreementInterface $agreement): AgreementHistoryInterface
    {
        /** @var OrderInterface|null $order */
        $order = $this->cartContext->getCart();

        /** @var ShopUserInterface|null $shopUser */
        $shopUser = $this->security->getUser();

        $agreementHistory = null;

        if (null !== $shopUser) {
            /** @var AgreementHistoryInterface|null $agreementHistory */
            $agreementHistory = $this->agreementHistoryRepository->findOneForShopUser($agreement, $shopUser);
        } elseif (isset($order) && null !== $order->getId()) {
            /** @var AgreementHistoryInterface|null $agreementHistory */
            $agreementHistory = $this->agreementHistoryRepository->findOneForOrder($agreement, $order);
        }

        if (null === $agreementHistory) {
            /** @var AgreementHistoryInterface $agreementHistory */
            $agreementHistory = $this->agreementHistoryFactory->createNew();
        }

        return $agreementHistory;
    }
}
