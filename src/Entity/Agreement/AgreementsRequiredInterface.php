<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Entity\Agreement;

use Doctrine\Common\Collections\Collection;

interface AgreementsRequiredInterface
{
    /** @return Collection | ?Agreement[] */
    public function getAgreements(): ?Collection;

    /** @var Collection | ?Agreement[] $agreements */
    public function setAgreements(?Collection $agreements):void;
}
