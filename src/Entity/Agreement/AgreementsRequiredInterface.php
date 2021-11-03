<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Entity\Agreement;

use Doctrine\Common\Collections\Collection;

interface AgreementsRequiredInterface
{
    public function getAgreements(): Collection;

    public function setAgreements(?Collection $agreements):void;
}
