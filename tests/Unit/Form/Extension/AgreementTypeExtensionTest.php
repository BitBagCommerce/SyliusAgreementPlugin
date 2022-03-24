<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusAgreementPlugin\Unit\Form\Extension;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\Agreement;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementContexts;
use BitBag\SyliusAgreementPlugin\Form\Extension\AgreementsTypeExtension;
use BitBag\SyliusAgreementPlugin\Form\Type\Agreement\Shop\AgreementCollectionType;
use BitBag\SyliusAgreementPlugin\Resolver\AgreementApproval\AgreementApprovalResolver;
use BitBag\SyliusAgreementPlugin\Resolver\AgreementApprovalResolverInterface;
use BitBag\SyliusAgreementPlugin\Resolver\AgreementHistoryResolverInterface;
use BitBag\SyliusAgreementPlugin\Resolver\AgreementResolverInterface;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;

final class AgreementTypeExtensionTest extends TestCase
{

    /**
     * @var mixed|MockObject|FormBuilder
     */
    private $builder;
    /**
     * @var AgreementApprovalResolverInterface|mixed|MockObject
     */
    private $agreementApprovalResolver;
    /**
     * @var AgreementResolverInterface|mixed|MockObject
     */
    private $agreementResolver;

    /** @var Agreement*/
    private $agreement;

    /** @var AgreementHistoryResolverInterface|mixed|MockObject */
    private $agreementHistoryResolver;

    /** @var AgreementsTypeExtension */
    private $subject;

    public function setUp():void
    {
        $this->builder = $this
            ->createMock(FormBuilderInterface::class);
        $this->agreementResolver = $this
            ->createMock(AgreementResolverInterface::class);
        $this->agreementHistoryResolver = $this
            ->createMock(AgreementHistoryResolverInterface::class);

        $this->agreementApprovalResolver = new AgreementApprovalResolver($this->agreementHistoryResolver);
        $this->agreement = new Agreement();

        $this->subject = new AgreementsTypeExtension(
            $this->agreementResolver,
            $this->agreementApprovalResolver
        );

    }

    public function test_it_builds_form_correctly()
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

        $this->subject->buildForm($this->builder,[]);
    }
}
