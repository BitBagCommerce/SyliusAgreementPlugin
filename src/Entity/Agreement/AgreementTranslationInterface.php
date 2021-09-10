<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Entity\Agreement;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TranslationInterface;

interface AgreementTranslationInterface extends TranslationInterface, ResourceInterface
{
    public function getName(): string;

    public function setName(string $name): void;

    public function getBody(): string;

    public function setBody(string $body): void;

    public function getExtendedBody(): ?string;

    public function setExtendedBody(?string $extendedBody): void;
}
