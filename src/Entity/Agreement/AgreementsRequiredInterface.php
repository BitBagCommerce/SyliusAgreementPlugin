<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Entity\Agreement;

use Doctrine\Common\Collections\ArrayCollection;

interface AgreementsRequiredInterface
{
    public function getAgreements();

    public function setAgreements(ArrayCollection $agreements): void;
}
