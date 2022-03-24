<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusAgreementPlugin\Unit\Resolver\Agreement;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\Agreement;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementContexts;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use BitBag\SyliusAgreementPlugin\Repository\AgreementRepositoryInterface;
use BitBag\SyliusAgreementPlugin\Resolver\Agreement\RegistrationFormAgreementResolver;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\Assert;

class RegistrationFormAgreementResolverTest extends TestCase
{
    /** @var AgreementRepositoryInterface|mixed|MockObject*/
    private $agreementRepository;

    /** @var AgreementInterface */
    private $agreement;

    /** @var RegistrationFormAgreementResolver */
    private $registrationFormAgreement;

    public function setUp(): void
    {
        $this->agreementRepository = $this
            ->createMock(AgreementRepositoryInterface::class);

        $this->agreement = new Agreement();

        $this->registrationFormAgreement =
            new RegistrationFormAgreementResolver($this->agreementRepository);
    }

    public function test_it_resolves_agreement_form_correctly()
    {
        $this->agreementRepository
            ->expects(self::once())
            ->method('findAgreementsByContext')
            ->with(AgreementContexts::CONTEXT_REGISTRATION_FORM)
            ->willReturn([$this->agreement]);

        $result = $this->registrationFormAgreement
            ->resolve('',[]);

        Assert::same($result,[$this->agreement]);
    }

    public function test_it_supports_registration_context()
    {
        $support = new RegistrationFormAgreementResolver($this->agreementRepository);
        self::assertTrue($support->supports('sylius_customer_registration', []));
    }
}
