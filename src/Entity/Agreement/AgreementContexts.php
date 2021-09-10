<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Entity\Agreement;

interface AgreementContexts
{
    public const CONTEXT_UNKNOWN = 'unknown';

    public const CONTEXT_REGISTRATION_FORM = 'registration_form';

    public const CONTEXT_ACCOUNT = 'account';

    public const CONTEXT_LOGGED_IN_ORDER_SUMMARY = 'logged_in_order_summary';

    public const CONTEXT_ANONYMOUS_ORDER_SUMMARY = 'anonymous_order_summary';

    public const CONTEXT_CONTACT_FORM = 'contact_form';

    public const CONTEXT_NEWSLETTER_FORM_SUBSCRIBE = 'newsletter_form_subscribe';

    public const CONTEXT_NEWSLETTER_FORM_UNSUBSCRIBE = 'newsletter_form_unsubscribe';
}
