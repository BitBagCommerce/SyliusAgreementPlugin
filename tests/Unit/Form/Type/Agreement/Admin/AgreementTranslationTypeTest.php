<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusAgreementPlugin\Unit\Form\Type\Agreement\Admin;

use BitBag\SyliusAgreementPlugin\Form\Type\Agreement\Admin\AgreementTranslationType;
use BitBag\SyliusAgreementPlugin\Form\Type\Agreement\Shop\AgreementCollectionType;
use BitBag\SyliusAgreementPlugin\Repository\AgreementRepositoryInterface;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class AgreementTranslationTypeTest extends TestCase
{
    /**
     * @var mixed|\PHPUnit\Framework\MockObject\MockObject|FormBuilderInterface
     */
    private $builder;

    public function setUp(): void
    {
        $this->builder = $this->createMock(FormBuilderInterface::class);

    }


    public function testBuildForm(): void
    {

        $this->builder->expects(self::exactly(3))->method('add')
            ->withConsecutive(
                [
                    'name',
                    TextType::class,
                    [
                        'label' => 'bitbag_sylius_agreement_plugin.ui.name',
                        'empty_data' => '',
                        'required' => true,
                    ]
                ],
                [
                    'body',
                    TextareaType::class,
                    [
                        'label' => 'bitbag_sylius_agreement_plugin.ui.body',
                        'empty_data' => '',
                        'required' => true,
                    ]
                ],
                [
                    'extendedBody',
                    TextareaType::class,
                    [
                        'label' => 'bitbag_sylius_agreement_plugin.ui.extended_body',
                        'required' => false,
                    ]
                ]
            )
            ->willReturnSelf();

        $subject = new AgreementTranslationType('test', []);
        $subject->buildForm($this->builder, []);
    }

    public function testConfigureOptions()
    {
        $resolver = $this->createMock(OptionsResolver::class);
        $resolver->expects(self::once())->method('setDefault')->with('required', true);

        $subject = new AgreementTranslationType('test', []);

        $subject->configureOptions($resolver);
    }

}