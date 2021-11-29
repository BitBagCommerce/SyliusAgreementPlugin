<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Resolver;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementHistoryInterface;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class CompositeAgreementHistoryResolver implements AgreementHistoryResolverInterface
{
    /** @var AgreementHistoryResolverInterface[] */
    private iterable $agreementHistoryResolvers;

    private FactoryInterface $agreementHistoryFactory;

    public function __construct(
        iterable $agreementHistoryResolvers,
        FactoryInterface $agreementHistoryFactory
    ) {
        $this->agreementHistoryResolvers = $agreementHistoryResolvers;
        $this->agreementHistoryFactory = $agreementHistoryFactory;
    }

    public function resolveHistory(AgreementInterface $agreement): ?AgreementHistoryInterface
    {
        $agreementHistory = null;
        foreach ($this->agreementHistoryResolvers as $agreementHistoryIterator) {
            if ($agreementHistoryIterator->supports($agreement)) {
                $agreementHistory = $agreementHistoryIterator->resolveHistory($agreement);
            }

            if ($agreementHistory instanceof AgreementHistoryInterface) {
                break;
            }
        }
        if (!$agreementHistory instanceof AgreementHistoryInterface) {
            /** @var AgreementHistoryInterface $agreementHistory */
            $agreementHistory = $this->agreementHistoryFactory->createNew();
        }

        return $agreementHistory;
    }

    public function supports(AgreementInterface $agreement): bool
    {
        return true;
    }
}
