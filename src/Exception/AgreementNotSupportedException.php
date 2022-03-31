<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Exception;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use Throwable;

final class AgreementNotSupportedException extends \Exception
{
    private AgreementInterface $agreement;

    public function __construct(
        AgreementInterface $agreement,
        string $message = '',
        int $code = 0,
        Throwable $previous = null
    ) {
        $this->agreement = $agreement;

        parent::__construct($message, $code, $previous);
    }

    public function getAgreement(): AgreementInterface
    {
        return $this->agreement;
    }
}
