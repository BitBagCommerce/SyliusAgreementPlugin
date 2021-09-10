<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Entity\Agreement;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Sylius\Component\Resource\Model\TimestampableTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="bitbag_sylius_agreement_plugin_agreementhistory")
 */
class AgreementHistory implements AgreementHistoryInterface
{
    use TimestampableTrait;

    /**
     * @var int|null
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected ?int $id = null;

    /**
     * @var AgreementInterface|null
     * @ORM\ManyToOne(targetEntity="BitBag\SyliusAgreementPlugin\Entity\Agreement\Agreement")
     */
    protected $agreement;

    /**
     * @var ShopUserInterface|null
     * @ORM\ManyToOne(targetEntity="BitBag\SyliusAgreementPlugin\Entity\User\ShopUser", inversedBy="agreementsHistory")
     * @ORM\JoinColumn(name="shop_user_id", referencedColumnName="id")
     */
    protected $shopUser;

    /**
     * @var OrderInterface|null
     * @ORM\ManyToOne(targetEntity="BitBag\SyliusAgreementPlugin\Entity\Order\Order", inversedBy="agreementsHistory")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id")
     */
    protected $order;

    /**
     * @var string
     * @ORM\Column(type="string", length=80, name="agreement_state")
     */
    protected $state = AgreementHistoryStates::STATE_ASSIGNED;

    /**
     * @var string
     * @ORM\Column(type="string", length=120, name="agreement_context")
     */
    protected $context = AgreementContexts::CONTEXT_UNKNOWN;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime", nullable=true, name="updated_at")
     * @Gedmo\Timestampable(on="update")
     */
    protected $updatedAt;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", name="created_at")
     * @Gedmo\Timestampable(on="create")
     */
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
