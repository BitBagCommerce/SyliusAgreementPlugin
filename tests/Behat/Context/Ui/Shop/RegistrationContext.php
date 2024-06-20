<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace Tests\BitBag\SyliusAgreementPlugin\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use Tests\BitBag\SyliusAgreementPlugin\Behat\Page\Shop\Account\RegisterPageInterface;

final class RegistrationContext implements Context
{
    private RegisterPageInterface $registerPage;

    public function __construct(RegisterPageInterface $registerPage)
    {
        $this->registerPage = $registerPage;
    }

    /**
     * @Given I check agreement :agreementCode during registration
     */
    public function iCheckAgreement(string $agreementCode): void
    {
        $this->registerPage->iCheckAgreement($agreementCode);
    }
}
