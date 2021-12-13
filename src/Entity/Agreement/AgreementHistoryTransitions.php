<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Entity\Agreement;

interface AgreementHistoryTransitions
{
    public const GRAPH = 'app_agreement_history';

    public const TRANSITION_ACCEPT = 'accept';

    public const TRANSITION_IGNORE = 'ignore';

    public const TRANSITION_WITHDRAW = 'withdraw';
}
