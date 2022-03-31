<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Entity\Agreement;

use Sylius\Component\Resource\Model\AbstractTranslation;

class AgreementTranslation extends AbstractTranslation implements AgreementTranslationInterface
{
    protected int $id;

    protected string $name = '';

    protected string $body = '';

    protected ?string $extendedBody = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    public function getExtendedBody(): ?string
    {
        return $this->extendedBody;
    }

    public function setExtendedBody(?string $extendedBody): void
    {
        $this->extendedBody = $extendedBody;
    }
}
