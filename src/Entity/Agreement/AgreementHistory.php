<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Entity\Agreement;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Sylius\Component\Resource\Model\TimestampableTrait;


class AgreementHistory implements AgreementHistoryInterface
{
    use TimestampableTrait;

    protected ?int $id = null;

    protected $agreement;

    protected $shopUser;

    protected $order;

    protected $state = AgreementHistoryStates::STATE_ASSIGNED;

    protected $context = AgreementContexts::CONTEXT_UNKNOWN;

    protected $updatedAt;

    protected $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function __clone()
    {
        $this->updatedAt = null;
        $this->createdAt = null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAgreement(): ?AgreementInterface
    {
        return $this->agreement;
    }

    public function setAgreement(?AgreementInterface $agreement): void
    {
        $this->agreement = $agreement;
    }

    public function getShopUser(): ?ShopUserInterface
    {
        return $this->shopUser;
    }

    public function setShopUser(?ShopUserInterface $shopUser): void
    {
        $this->shopUser = $shopUser;
    }

    public function getOrder(): ?OrderInterface
    {
        return $this->order;
    }

    public function setOrder(?OrderInterface $order): void
    {
        $this->order = $order;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): void
    {
        $this->state = $state;
    }

    public function getContext(): string
    {
        return $this->context;
    }

    public function setContext(string $context): void
    {
        $this->context = $context;
    }
}
