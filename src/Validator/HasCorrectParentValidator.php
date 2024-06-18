<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Validator;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\Agreement;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class HasCorrectParentValidator extends ConstraintValidator
{
    /**
     * @param Agreement|mixed $agreement
     * @param HasCorrectParent|Constraint $constraint
     */
    public function validate($agreement, Constraint $constraint): void
    {
        if (null !== $agreement && null !== $agreement->getParent()) {
            if ($agreement->getId() === $agreement->getParent()->getId()) {
                $this->context->addViolation(HasCorrectParent::PARENT_ID_SAME_AS_AGREEMENT);
            }
        }
    }
}
