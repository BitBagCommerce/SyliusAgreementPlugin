<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
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
        FormEvent $formEvent,
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

    public function getEventDataUserId(): ?int
    {
        return $this->formEvent->getData()?->getUser()?->getId();
    }
}
