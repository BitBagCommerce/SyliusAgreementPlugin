<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusAgreementPlugin\Unit\Form\Type\Agreement\Admin;

use BitBag\SyliusAgreementPlugin\Form\Type\Agreement\Admin\AgreementTranslationType;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class AgreementTranslationTypeTest extends TestCase
{
    public function test_it_builds_form_correctly(): void
    {
        $subject = new AgreementTranslationType('test', []);

        $builder = $this->mock_form_builder();
        $builder->expects(self::exactly(3))->method('add')
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

        $subject->buildForm($builder, []);
    }

    public function test_it_configures_correctly()
    {
        $resolver = $this->createMock(OptionsResolver::class);
        $resolver->expects(self::once())->method('setDefault')->with('required', true);

        $subject = new AgreementTranslationType('test', []);

        $subject->configureOptions($resolver);
    }

    public function test_it_has_correct_block_prefix(): void
    {
        $form = new AgreementTranslationType('CLASS', []);
        self::assertEquals('bitbag_sylius_agreement_plugin_agreement_translation', $form->getBlockPrefix());
    }

    /**
     * @return MockObject|FormBuilderInterface
     */
    private function mock_form_builder(): object
    {
        return $this->createMock(FormBuilderInterface::class);
    }
}
