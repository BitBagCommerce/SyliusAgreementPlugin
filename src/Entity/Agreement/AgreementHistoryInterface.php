<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

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

    public function setAgreement(?AgreementInterface $agreement): void;

    /** @return ?ShopUserInterface */
    public function getShopUser(): ?ShopUserInterface;

    public function setShopUser(?ShopUserInterface $shopUser): void;

    /** @return ?OrderInterface */
    public function getOrder(): ?OrderInterface;

    public function setOrder(?OrderInterface $order): void;

    public function getState(): string;

    public function setState(string $state): void;

    public function getContext(): string;

    public function setContext(string $context): void;
}
