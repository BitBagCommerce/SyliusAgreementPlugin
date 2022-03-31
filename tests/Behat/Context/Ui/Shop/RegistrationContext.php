<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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
