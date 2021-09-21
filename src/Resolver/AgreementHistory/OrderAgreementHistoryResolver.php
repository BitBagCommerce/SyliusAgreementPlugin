<?php

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
    )
    {
        $this->cartContext = $cartContext;
        $this->agreementHistoryRepository = $agreementHistoryRepository;
    }

    public function resolveHistory(AgreementInterface $agreement): ?AgreementHistoryInterface
    {
        /** @var OrderInterface $order */
        $order = $this->cartContext->getCart();

        if ($agreement->getId() !== null && $order->getId() !== null) {
            return $this->agreementHistoryRepository->findOneForOrder($agreement, $order);
        }

        return null;
    }

    public function supports(AgreementInterface $agreement): bool
    {
        return $this->cartContext->getCart() instanceof OrderInterface;
    }
}
