<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Entity\Agreement;

use Doctrine\Common\Collections\ArrayCollection;

trait AgreementsRequiredTrait
{
    /** @var ArrayCollection|AgreementInterface[] */
    protected $agreements;

    public function __construct()
    {
        $this->agreements = new ArrayCollection();
    }

    public function getAgreements(): ArrayCollection
    {
        return $this->agreements ?? new ArrayCollection();
    }

    public function setAgreements(ArrayCollection $agreements): void
    {
        $this->agreements = $agreements;
    }
}
