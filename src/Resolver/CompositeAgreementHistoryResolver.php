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
