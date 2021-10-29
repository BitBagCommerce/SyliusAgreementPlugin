<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusAgreementPlugin\Unit\Form\Type\Agreement\Shop;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\Agreement;
use BitBag\SyliusAgreementPlugin\Form\Type\Agreement\Shop\AgreementCheckboxType;
use BitBag\SyliusAgreementPlugin\Form\Type\Agreement\Shop\AgreementHiddenType;
use BitBag\SyliusAgreementPlugin\Form\Type\Agreement\Shop\AgreementType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

final class AgreementTypeTest extends TestCase
{
    /** @dataProvider buildFormDataProvider  */
    public function testBuildForm
    (
        array $options, string $agreementClass,
        array $addWith, array $validationGroups
    ): void
    {

        $builder = $this->createMock(FormBuilderInterface::class);
        $builder->expects(self::once())->method('add')
        ->with('approved',
            $agreementClass, $addWith)
            ->willReturn($builder,[]);

        $resolver = new OptionsResolver();
        $subject = new AgreementType($validationGroups);
        $subject->configureOptions($resolver);
        $subject->buildForm($builder, $options);
    }

    public function testConfigureOptions(): void
    {
        $resolver = $this->createMock(OptionsResolver::class);
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

    public function buildFormDataProvider()
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
}
