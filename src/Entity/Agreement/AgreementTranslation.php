<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
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
