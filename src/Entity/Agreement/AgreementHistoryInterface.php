<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Entity\Agreement;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;

interface AgreementHistoryInterface extends TimestampableInterface, ResourceInterface
{
    /** @return ?AgreementInterface */
    public function getAgreement(): ?AgreementInterface;

    /** @var ?AgreementInterface $agreement */
    public function setAgreement(?AgreementInterface $agreement): void;

    /** @return ?ShopUserInterface */
    public function getShopUser(): ?ShopUserInterface;

    /** @var ?ShopUserInterface $shopUser */
    public function setShopUser(?ShopUserInterface $shopUser): void;

    /** @return ?OrderInterface */
    public function getOrder(): ?OrderInterface;

    /** @var ?OrderInterface $order */
    public function setOrder(?OrderInterface $order): void;

    /** @return string */
    public function getState(): string;

    /** @var string $state */
    public function setState(string $state): void;

    /** @return string */
    public function getContext(): string;

    /** @var string $context */
    public function setContext(string $context): void;
}
