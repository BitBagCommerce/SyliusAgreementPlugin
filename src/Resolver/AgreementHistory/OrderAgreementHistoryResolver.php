<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Resolver\AgreementHistory;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementHistoryInterface;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use BitBag\SyliusAgreementPlugin\Repository\AgreementHistoryRepositoryInterface;
use BitBag\SyliusAgreementPlugin\Resolver\AgreementHistoryResolverInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Order\Context\CartContextInterface;

final class OrderAgreementHistoryResolver implements AgreementHistoryResolverInterface
{
    private CartContextInterface $cartContext;

    private AgreementHistoryRepositoryInterface $agreementHistoryRepository;

    public function __construct(
        CartContextInterface $cartContext,
        AgreementHistoryRepositoryInterface $agreementHistoryRepository
    ) {
        $this->cartContext = $cartContext;
        $this->agreementHistoryRepository = $agreementHistoryRepository;
    }

    public function resolveHistory(AgreementInterface $agreement): ?AgreementHistoryInterface
    {
        /** @var OrderInterface $order */
        $order = $this->cartContext->getCart();

        if (null !== $agreement->getId() && null !== $order->getId()) {
            return $this->agreementHistoryRepository->findOneForOrder($agreement, $order);
        }

        return null;
    }

    public function supports(AgreementInterface $agreement): bool
    {
        return $this->cartContext->getCart() instanceof OrderInterface;
    }
}
