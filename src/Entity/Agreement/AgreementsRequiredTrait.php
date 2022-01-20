<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Entity\Agreement;

use Doctrine\Common\Collections\Collection;

trait AgreementsRequiredTrait
{
    /** @var ?Collection|AgreementInterface[] */
    protected $agreements;

    public function getAgreements(): ?Collection
    {
        return $this->agreements;
    }

    public function setAgreements(?Collection $agreements): void
    {
        $this->agreements = $agreements;
    }
}
