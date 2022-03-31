<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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

    /** @return string */
    public function getState(): string;

    public function setState(string $state): void;

    /** @return string */
    public function getContext(): string;

    public function setContext(string $context): void;
}
