<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

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
