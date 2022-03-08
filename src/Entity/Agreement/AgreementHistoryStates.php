<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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
