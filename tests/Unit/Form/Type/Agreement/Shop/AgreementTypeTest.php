<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusAgreementPlugin\Unit\Form\Type\Agreement\Shop;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\Agreement;
use BitBag\SyliusAgreementPlugin\Form\Type\Agreement\Shop\AgreementCheckboxType;
use BitBag\SyliusAgreementPlugin\Form\Type\Agreement\Shop\AgreementHiddenType;
use BitBag\SyliusAgreementPlugin\Form\Type\Agreement\Shop\AgreementType;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

final class AgreementTypeTest extends TestCase
{
    /** @dataProvider build_form_data_provider  */
    public function test_it_builds_correctly
    (
        array $options,
        string $agreementClass,
        array $addWith,
        array $validationGroups
    ): void
    {

        $builder = $this->mock_form_builder();
        $builder->expects(self::once())->method('add')
        ->with('approved',
            $agreementClass, $addWith)
            ->willReturn($builder,[]);

        $resolver = new OptionsResolver();
        $subject = new AgreementType($validationGroups);
        $subject->configureOptions($resolver);
        $subject->buildForm($builder, $options);
    }

    public function test_it_configures_options(): void
    {
        $resolver = $this->mock_options_resolver();
        $resolver
            ->expects(self::exactly(6))
            ->method('setDefault')
            ->withConsecutive(
                ['data_class', Agreement::class],
                ['label', false],
                ['required', false],
                ['body', null],
                ['extended_body', null],
                ['read_only', false]
            )->willReturnSelf();

        $resolver
            ->expects(self::exactly(3))
            ->method('setRequired')
            ->withConsecutive(
                ['approved'],
                ['code'],
                ['mode'],
            )->willReturnSelf();


        $subject = new AgreementType([]);
        $subject->configureOptions($resolver);
    }

    public function test_it_builds_view(): void
    {
        $formView = new FormView();
        $type = new AgreementType([]);
        $type->buildView(
            $formView,
            $this->mock_form(), [
                'code' => 'CODE',
                'required' => true,
                'mode' => 'MODE',
            ]
        );
        self::assertEquals('CODE', $formView->vars['code']);
        self::assertEquals(true, $formView->vars['required']);
        self::assertEquals('MODE', $formView->vars['mode']);
    }

    public function test_it_has_correct_block_prefix(): void
    {
        $type = new AgreementType([]);
        self::assertEquals('bitbag_sylius_agreement_plugin_agreement_approval', $type->getBlockPrefix());
    }

    public function build_form_data_provider(): array
    {
        return
            [
                [
                    [
                        'required'=>false,
                        'read_only'=>true,
                        'body' => 'body_test',
                        'extended_body' => 'extended_body_test',
                    ],
                    AgreementHiddenType::class,
                    [
                        'label' => 'body_test',
                        'extended_label' => 'extended_body_test',
                        'data' => true,
                        'translation_domain' => false,
                        'constraints' => [],
                    ],
                    ['group_test1']
                ],
                [
                    [
                        'required'=>true,
                        'read_only'=>false,
                        'body'=>'body2',
                        'extended_body'=>'extended_body_test2',
                        'approved'=>false

                    ],
                    AgreementCheckboxType::class,
                    [
                        'required' => true,
                        'label' => 'body2',
                        'extended_label' => 'extended_body_test2',
                        'approved' => false,
                        'translation_domain' => false,
                        'constraints' => [new IsTrue([
                            'groups' => 'validation_group_test2',
                        ])],
                    ],
                    ['validation_group_test2']
                ]

            ];

    }

    /**
     * @return MockObject|FormBuilderInterface
     */
    private function mock_form_builder(): object
    {
        return $this->createMock(FormBuilderInterface::class);
    }

    /**
     * @return MockObject|FormInterface
     */
    private function mock_form(): object
    {
        return $this->createMock(FormInterface::class);
    }

    /**
     * @return MockObject|OptionsResolver
     */
    private function mock_options_resolver(): object
    {
        return $this->createMock(OptionsResolver::class);
    }
}
