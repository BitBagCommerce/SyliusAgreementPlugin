<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Tests\Unit\Form\Type\Agreement\Admin;

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
    public function testBuildForm(): void
    {
        $builder = $this->createMock(FormBuilderInterface::class);
        $builder->expects(self::exactly(3))->method('add')
            ->withConsecutive(
                [
                    'name',
                    TextType::class,
                    [
                        'label' => 'sylius_agreement_plugin.form.agreement.name',
                        'empty_data' => '',
                        'required' => true,
                    ]
                ],
                [
                    'body',
                    TextareaType::class,
                    [
                        'label' => 'sylius_agreement_plugin.form.agreement.body',
                        'empty_data' => '',
                        'required' => true,
                    ]
                ],
                [
                    'extendedBody',
                    TextareaType::class,
                    [
                        'label' => 'sylius_agreement_plugin.form.agreement.extended_body',
                        'required' => false,
                    ]
                ]
            )
            ->willReturnSelf();

        $subject = new AgreementTranslationType('test', []);
        $subject->buildForm($builder, []);
    }

    public function testConfigureOptions()
    {
        $resolver = $this->createMock(OptionsResolver::class);
        $resolver->expects(self::once())->method('setDefault')->with('required', true);

        $subject = new AgreementTranslationType('test', []);

        $subject->configureOptions($resolver);
    }

    public function testGetBlockPrefix()
    {
        $agreementRepository = $this->createMock(AgreementRepositoryInterface::class);
        $agreementCollection = new AgreementCollectionType($agreementRepository);
        Assert::assertSame($agreementCollection->getBlockPrefix(),
            'bitbag_sylius_agreement_plugin_agreement_approval_collection');
    }
}
