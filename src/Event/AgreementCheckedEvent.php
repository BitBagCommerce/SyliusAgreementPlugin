<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Event;

use Symfony\Component\Form\FormEvent;
use Symfony\Contracts\EventDispatcher\Event;

class AgreementCheckedEvent extends Event
{
    private string $context;

    private FormEvent $formEvent;

    public function __construct(
        string $context,
        FormEvent $formEvent
    ) {
        $this->context = $context;
        $this->formEvent = $formEvent;
    }

    public function getContext(): string
    {
        return $this->context;
    }

    public function getEvent(): FormEvent
    {
        return $this->formEvent;
    }
}
