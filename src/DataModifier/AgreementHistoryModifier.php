<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\DataModifier;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementHistoryInterface;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use BitBag\SyliusAgreementPlugin\Resolver\AgreementHistoryResolverInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Webmozart\Assert\Assert;

class AgreementHistoryModifier implements AgreementHistoryModifierInterface
{
    private AgreementHistoryResolverInterface $agreementHistoryResolver;

    public function __construct(
        AgreementHistoryResolverInterface $agreementHistoryResolver
    ) {
        $this->agreementHistoryResolver = $agreementHistoryResolver;
    }

    public function setAgreementHistoryProperties(
        string $context,
        ?OrderInterface $order,
        ?ShopUserInterface $shopUser,
        AgreementInterface $resolvedAgreement
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
