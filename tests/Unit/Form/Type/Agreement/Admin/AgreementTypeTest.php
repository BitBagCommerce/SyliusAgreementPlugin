<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusAgreementPlugin\Unit\Form\Type\Agreement\Admin;

use BitBag\SyliusAgreementPlugin\Form\Type\Agreement\Admin\AgreementType;
use BitBag\SyliusAgreementPlugin\Repository\AgreementRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormBuilderInterface;

final class AgreementTypeTest extends TestCase
{
    /**
     * @dataProvider build_form_data_provider
     */
    public function test_it_builds_form_correctly(array $modes, array $preparedModes, array $contexts, array $preparedContexts): void
    {
        $builder = $this->mock_builder();
        $agreementRepository = $this->mock_agreement_repository();

        $builder
            ->method('add')
            ->willReturnSelf();

        $builder
            ->expects(self::once())
            ->method('get')
            ->with('parent')
            ->willReturnSelf();

        $builder
            ->expects(self::exactly(2))
            ->method('addModelTransformer')
            ->willReturnSelf();

        $subject = new AgreementType('test', $agreementRepository, [], $modes, $contexts);
        $subject->buildForm($builder, []);
    }

    public function build_form_data_provider(): array
    {
        return [
            [
                [
                    'test1',
                    'test2'
                ],
                [
                    'bitbag_sylius_agreement_plugin.ui.agreement.modes.test1' => 'test1',
                    'bitbag_sylius_agreement_plugin.ui.agreement.modes.test2' => 'test2'

            ],
                [
                    'test5',
                    'test55'
                ],
                [
                    'bitbag_sylius_agreement_plugin.ui.agreement.contexts.test5' => 'test5',
                    'bitbag_sylius_agreement_plugin.ui.agreement.contexts.test55' => 'test55'
                ]
            ]
        ];
    }

    /**
     * @dataProvider build_form_data_provider
     */
    public function test_it_has_correct_block_prefix(array $modes, array $preparedModes, array $contexts, array $preparedContexts): void
    {
        $agreementRepository = $this->mock_agreement_repository();

        $form = new AgreementType('test', $agreementRepository, [], $modes, $contexts);
        self::assertEquals('bitbag_sylius_agreement_plugin_agreement', $form->getBlockPrefix());
    }

    /**
     * @return AgreementRepositoryInterface|MockObject
     */
    private function mock_agreement_repository(): object
    {
        return $this->createMock(AgreementRepositoryInterface::class);
    }

    /**
     * @return FormBuilderInterface|MockObject
     */
    private function mock_builder(): object
    {
        return $this->createMock(FormBuilderInterface::class);
    }
}
