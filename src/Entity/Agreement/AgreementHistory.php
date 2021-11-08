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
    /** @var AgreementInterface | null */
    protected $agreement;

    /** @var ShopUserInterface | null */
    protected $shopUser;

    /** @var OrderInterface | null  */
    protected $order;

    /** @var string  */
    protected $state = AgreementHistoryStates::STATE_ASSIGNED;

    /** @var string  */
    protected $context = AgreementContexts::CONTEXT_UNKNOWN;

    /** @var \DateTime | null */
    protected $updatedAt;

    /** @var \DateTime | null */
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
    /** @return ?AgreementInterface */
    public function getAgreement(): ?AgreementInterface
    {
        return $this->agreement;
    }
    /** @var ?AgreementInterface $agreement */
    public function setAgreement(?AgreementInterface $agreement): void
    {
        $this->agreement = $agreement;
    }

    /** @return ?ShopUserInterface */
    public function getShopUser(): ?ShopUserInterface
    {
        return $this->shopUser;
    }

    /** @var ?ShopUserInterface $shopUser */
    public function setShopUser(?ShopUserInterface $shopUser): void
    {
        $this->shopUser = $shopUser;
    }

    /** @return ?OrderInterface */
    public function getOrder(): ?OrderInterface
    {
        return $this->order;
    }

    /** @var ?OrderInterface $order */
    public function setOrder(?OrderInterface $order): void
    {
        $this->order = $order;
    }

    /** @return string */
    public function getState(): string
    {
        return $this->state;
    }

    /** @var string $state */
    public function setState(string $state): void
    {
        $this->state = $state;
    }

    /** @return string */
    public function getContext(): string
    {
        return $this->context;
    }

    /** @var string $context */
    public function setContext(string $context): void
    {
        $this->context = $context;
    }
}
