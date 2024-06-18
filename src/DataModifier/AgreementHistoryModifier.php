<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\DataModifier;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementHistoryInterface;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use BitBag\SyliusAgreementPlugin\Resolver\AgreementHistoryResolverInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ShopUserInterface;

class AgreementHistoryModifier implements AgreementHistoryModifierInterface
{
    private AgreementHistoryResolverInterface $agreementHistoryResolver;

    public function __construct(
        AgreementHistoryResolverInterface $agreementHistoryResolver,
    ) {
        $this->agreementHistoryResolver = $agreementHistoryResolver;
    }

    public function setAgreementHistoryProperties(
        string $context,
        ?OrderInterface $order,
        ?ShopUserInterface $shopUser,
        AgreementInterface $resolvedAgreement,
    ): AgreementHistoryInterface {
        $agreementHistory = $this->agreementHistoryResolver->resolveHistory($resolvedAgreement);

        if (null === $agreementHistory->getId()) {
            $agreementHistory->setContext($context);
            $agreementHistory->setShopUser($shopUser);
            $agreementHistory->setOrder($order);
            $agreementHistory->setAgreement($resolvedAgreement);
        }

        return $agreementHistory;
    }
}
