<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Entity\Agreement;

interface AgreementHistoryStates
{
    public const STATE_ASSIGNED = 'assigned';

    public const STATE_SHOWN = 'shown';

    public const STATE_ACCEPTED = 'accepted';

    public const STATE_WITHDRAWN = 'withdrawn';

    public const STATE_IGNORED = 'ignored';
}
