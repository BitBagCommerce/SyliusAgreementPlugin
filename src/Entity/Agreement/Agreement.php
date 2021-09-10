<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Entity\Agreement;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Sylius\Component\Resource\Model\TimestampableTrait;
use Sylius\Component\Resource\Model\ToggleableTrait;
use Sylius\Component\Resource\Model\TranslatableTrait;
use Sylius\Component\Resource\Model\TranslationInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="bitbag_sylius_agreement_plugin_agreement")
 */
class Agreement implements AgreementInterface
{
    use ToggleableTrait;

    use TimestampableTrait;

    use TranslatableTrait {
        __construct as protected initializeTranslationsCollection;
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", name="id")
     */
    protected ?int $id = null;

    /**
     * @ORM\Column(type="string", name="agreement_code", unique=true, length=120, nullable=true)
     */
    protected ?string $code = null;

    /**
     * @ORM\Column(type="string", name="agreement_mode")
     */
    protected string $mode = self::MODE_REQUIRED;

    /**
     * @ORM\Column(type="datetime", nullable=true, name="published_at")
     */
    protected ?\DateTime $publishedAt = null;

    /**
     * @var bool
     * @ORM\Column(type="boolean", name="enabled")
     */
    protected $enabled = true;

    /**
     * @var \DateTimeInterface|null
     * @ORM\Column(type="datetime", nullable=true, name="updated_at")
     * @Gedmo\Timestampable(on="update")
     */
    protected $updatedAt = null;

    /**
     *
     * @var \DateTimeInterface|null
     * @ORM\Column(type="datetime", name="created_at")
     * @Gedmo\Timestampable(on="create")
     */
    protected $createdAt = null;

    /**
     * @ORM\Column(type="array")
     */
    protected array $contexts = [];

    /**
     * @ORM\OneToOne(targetEntity="BitBag\SyliusAgreementPlugin\Entity\Agreement\Agreement")
     * @ORM\JoinColumn(name="parent_id", nullable=true, referencedColumnName="id")
     */
    protected ?AgreementInterface $parent = null;

    /**
     * @ORM\Column(type="integer", name="order_on_view", options={"default": 1})
     */
    protected int $orderOnView = 1;

    /** @var bool */
    protected bool $approved = false;

    /**
     * @ORM\Column(type="datetime", name="archived_at", nullable=true)
     */
    protected ?\DateTime $archivedAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->initializeTranslationsCollection();
    }

    public function __clone()
    {
        if ($this->id) {
            $this->id = null;
            $clonedTranslations = new ArrayCollection();
            foreach ($this->translations as $translation) {
                $clonedTranslation = clone $translation;
                $clonedTranslation->setTranslatable($this);
                $clonedTranslations->add($clonedTranslation);
            }
            $this->translations = $clonedTranslations;
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getMode(): string
    {
        return $this->mode;
    }

    public function setMode(string $mode): void
    {
        $this->mode = $mode;
    }

    public function getPublishedAt(): ?\DateTime
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTime $publishedAt): void
    {
        if (null !== $publishedAt) {
            $publishedAt->setTime(0, 0, 0);
        }
        $this->publishedAt = $publishedAt;
    }

    public function getContexts(): array
    {
        return $this->contexts;
    }

    public function setContexts(array $contexts): void
    {
        $this->contexts = $contexts;
    }

    public function getParent(): ?AgreementInterface
    {
        return $this->parent;
    }

    public function setParent(?AgreementInterface $parent): void
    {
        $this->parent = $parent;
    }

    public function getOrderOnView(): int
    {
        return $this->orderOnView;
    }

    public function setOrderOnView(int $orderOnView): void
    {
        $this->orderOnView = $orderOnView;
    }

    public function isApproved(): bool
    {
        return $this->approved;
    }

    public function setApproved(bool $approved): void
    {
        $this->approved = $approved;
    }

    public function getArchivedAt(): ?\DateTime
    {
        return $this->archivedAt;
    }

    public function setArchivedAt(?\DateTime $archivedAt): void
    {
        $this->archivedAt = $archivedAt;
    }

    public function getEdiumAgreementType(): ?string
    {
        return $this->ediumAgreementType;
    }

    public function setEdiumAgreementType(?string $ediumAgreementType): void
    {
        $this->ediumAgreementType = $ediumAgreementType;
    }

    public function isReadOnly(): bool
    {
        return AgreementInterface::MODE_ONLY_SHOW === $this->mode;
    }

    protected function createTranslation(): TranslationInterface
    {
        return new AgreementTranslation();
    }
}
