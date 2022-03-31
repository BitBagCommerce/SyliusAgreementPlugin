<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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
