<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Entity\Agreement;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;
use Sylius\Component\Resource\Model\ToggleableInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;
use Sylius\Component\Resource\Model\TranslationInterface;

interface AgreementInterface extends ResourceInterface, TranslatableInterface, ToggleableInterface, TimestampableInterface
{
    public const MODE_REQUIRED_AND_NON_CANCELLABLE = 'required_and_non_cancellable';

    public const MODE_REQUIRED = 'required';

    public const MODE_ONLY_SHOW = 'only_show';

    public const MODE_NOT_REQUIRED = 'not_required';

    public function __clone();

    public function getCode(): ?string;

    public function setCode(?string $code): void;

    public function getMode(): string;

    public function setMode(string $mode): void;

    public function getPublishedAt(): ?\DateTime;

    public function setPublishedAt(?\DateTime $publishedAt): void;

    public function getContexts(): array;

    public function setContexts(array $contexts): void;

    /** @return ?AgreementInterface */
    public function getParent(): ?self;

    public function setParent(?self $parent): void;

    public function getOrderOnView(): int;

    public function setOrderOnView(int $orderOnView): void;

    public function isApproved(): bool;

    public function setApproved(bool $approved): void;

    public function isReadOnly(): bool;

    public function getTranslation(?string $locale = null): TranslationInterface;
}
