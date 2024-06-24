<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
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
        Throwable $previous = null,
    ) {
        $this->agreement = $agreement;

        parent::__construct($message, $code, $previous);
    }

    public function getAgreement(): AgreementInterface
    {
        return $this->agreement;
    }
}
