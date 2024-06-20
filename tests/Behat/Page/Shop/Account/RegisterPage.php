<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace Tests\BitBag\SyliusAgreementPlugin\Behat\Page\Shop\Account;

use Sylius\Behat\Page\Shop\Account\RegisterPage as BaseRegisterPage;

class RegisterPage extends BaseRegisterPage implements RegisterPageInterface
{
    public function iCheckAgreement(string $agreementCode): void
    {
        $agreementSwitch = $this
            ->getElement('agreements')
            ->find('css', sprintf('input[data-test-agreement-%s]', mb_strtolower($agreementCode)));

        $agreementSwitch->setValue(1);
    }

    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'agreements' => '[data-test-agreement-registration_agreement]',
            'agreement' => '[data-test-agreement]',
        ]);
    }
}
