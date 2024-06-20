<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Validator;

use Symfony\Component\Validator\Constraint;

final class HasCorrectParent extends Constraint
{
    public const PARENT_ID_SAME_AS_AGREEMENT = 'Agreement parent cant be the same as agreement';

    public function validatedBy(): string
    {
        return 'bitbag_sylius_agreement_plugin_validator_has_correct_parent';
    }
}
