<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Validator;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\Agreement;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Webmozart\Assert\Assert;

class HasCorrectParentValidator extends ConstraintValidator
{
    /**
     * @param Agreement|mixed $value
     * @param HasCorrectParent|Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        Assert::isInstanceOf($value, Agreement::class);

        if ($value->getId() === $value->getParent()->getId()) {
            $this->context->addViolation(HasCorrectParent::PARENT_ID_SAME_AS_AGREEMENT);
        }
    }
}
