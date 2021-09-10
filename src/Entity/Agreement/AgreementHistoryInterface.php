<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Entity\Agreement;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;

interface AgreementHistoryInterface extends TimestampableInterface, ResourceInterface
{
    public function getAgreement(): ?AgreementInterface;

    public function setAgreement(?AgreementInterface $agreement): void;

    public function getShopUser(): ?ShopUserInterface;

    public function setShopUser(?ShopUserInterface $shopUser): void;

    public function getOrder(): ?OrderInterface;

    public function setOrder(?OrderInterface $order): void;

    public function getState(): string;

    public function setState(string $state): void;

    public function getContext(): string;

    public function setContext(string $context): void;
}
