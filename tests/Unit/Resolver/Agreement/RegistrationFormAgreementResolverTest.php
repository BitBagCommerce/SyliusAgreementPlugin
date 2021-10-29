<?php
declare(strict_types=1);

namespace Tests\BitBag\SyliusAgreementPlugin\Unit\Resolver\Agreement;


use BitBag\SyliusAgreementPlugin\Entity\Agreement\Agreement;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementContexts;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use BitBag\SyliusAgreementPlugin\Repository\AgreementRepositoryInterface;
use BitBag\SyliusAgreementPlugin\Resolver\Agreement\RegistrationFormAgreementResolver;
use BitBag\SyliusAgreementPlugin\Resolver\AgreementResolverInterface;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\Assert;

class RegistrationFormAgreementResolverTest extends TestCase
{
    /**
     * @var AgreementRepositoryInterface|mixed|\PHPUnit\Framework\MockObject\MockObject
     */
    private $agreementRepository;
    /**
     * @var AgreementInterface|mixed|\PHPUnit\Framework\MockObject\MockObject
     */
    private $agreement;
    private RegistrationFormAgreementResolver $registrationFormAgreement;

    public function setUp(): void
    {
        $this->agreementRepository = $this
            ->createMock(AgreementRepositoryInterface::class);
        $this->agreement = new Agreement();
        $this->registrationFormAgreement =
            new RegistrationFormAgreementResolver($this->agreementRepository);
    }


    public function testResolve()
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

}