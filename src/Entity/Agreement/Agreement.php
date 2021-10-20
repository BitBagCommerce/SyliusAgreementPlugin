<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Entity\Agreement;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Sylius\Component\Resource\Model\TimestampableTrait;
use Sylius\Component\Resource\Model\TranslatableTrait;
use Sylius\Component\Resource\Model\TranslationInterface;


class Agreement implements AgreementInterface
{

    use TimestampableTrait;

    use TranslatableTrait {
        __construct as protected initializeTranslationsCollection;
    }

    protected ?int $id = null;

    protected ?string $code = null;

    protected string $mode = self::MODE_REQUIRED;

    protected ?\DateTime $publishedAt = null;

    protected $enabled = true;

    protected $updatedAt = null;

    protected $createdAt = null;

    protected array $contexts = [];

    protected ?AgreementInterface $parent = null;

    protected int $orderOnView = 1;

    protected bool $approved = false;

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

    public function enable():void
    {
        $this->enabled = true;
    }

    public function disable():void
    {
        $this->enabled = false;
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
