<?php

namespace Tests\BitBag\SyliusAgreementPlugin\Unit\Form\Extension;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementContexts;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use BitBag\SyliusAgreementPlugin\Form\Extension\AgreementsTypeExtension;
use BitBag\SyliusAgreementPlugin\Form\Type\Agreement\Shop\AgreementCollectionType;
use BitBag\SyliusAgreementPlugin\Resolver\AgreementApproval\AgreementApprovalResolver;
use BitBag\SyliusAgreementPlugin\Resolver\AgreementApprovalResolverInterface;
use BitBag\SyliusAgreementPlugin\Resolver\AgreementResolverInterface;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Security;

final class AgreementTypeExtensionTest extends TestCase
{

    /**
     * @var mixed|\PHPUnit\Framework\MockObject\MockObject|FormBuilder
     */
    private $builder;
    /**
     * @var AgreementApprovalResolverInterface|mixed|\PHPUnit\Framework\MockObject\MockObject
     */
    private $agreementApprovalResolver;
    /**
     * @var AgreementResolverInterface|mixed|\PHPUnit\Framework\MockObject\MockObject
     */
    private $agreementResolver;
    /**
     * @var mixed|\PHPUnit\Framework\MockObject\MockObject|Security
     */
    private $security;
    private $agreement;

    public function setUp():void
    {
        $this->builder = $this
            ->createMock(FormBuilderInterface::class);
        $this->agreementResolver = $this
            ->createMock(AgreementResolverInterface::class);
        $this->agreementApprovalResolver = $this
            ->createMock(AgreementApprovalResolverInterface::class);
        $this->security = $this->createMock(Security::class);
        $this->agreement = $this->createMock(AgreementInterface::class);
    }

    public function testBuildForm()
    {

        $this->builder
            ->method('getName')
            ->willReturn(AgreementContexts::CONTEXT_REGISTRATION_FORM);

        $this->agreementResolver
            ->method('supports')
            ->with(AgreementContexts::CONTEXT_REGISTRATION_FORM,[])
            ->willReturn(true);

        $this->agreementResolver
            ->method('resolve')
            ->with(AgreementContexts::CONTEXT_REGISTRATION_FORM,[])
            ->willReturn([$this->agreement]);

        $this->agreementApprovalResolver
            ->method('resolve')
            ->with($this->agreement)
            ->willReturn(true);

        $this->agreement->method('setApproved')
            ->with(true);




        $this->builder
            ->expects(self::atLeast(1))
            ->method('add')
            ->withConsecutive(
                [
                    'agreements', AgreementCollectionType::class, [
                    'entries' => [$this->agreement],
                    'required' => false,
                    'label' => false,
                ]
                ]
            )
            ->willReturn($this->builder, []);

        $subject =
            new AgreementsTypeExtension($this->agreementResolver,
                $this->agreementApprovalResolver);

        $subject->buildForm($this->builder,[]);
    }

    public function testReturnType()
    {
        $subject =
            new AgreementsTypeExtension($this->agreementResolver,
                $this->agreementApprovalResolver);

        Assert::assertSame([],$subject->getExtendedTypes());
    }

}