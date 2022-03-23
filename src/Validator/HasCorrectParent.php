<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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
