<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusAgreementPlugin\Unit\Form\Type\Agreement\Shop;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use BitBag\SyliusAgreementPlugin\Form\Type\Agreement\Shop\AgreementCheckboxType;

final class AgreementCheckboxTypeTest extends TestCase
{
    public function test_it_has_correct_parent(): void
    {
        $subject = new AgreementCheckboxType();

        self::assertEquals(CheckboxType::class, $subject->getParent());
    }

    public function test_it_has_correct_block_prefix(): void
    {
        $subject = new AgreementCheckboxType();

        self::assertEquals('bitbag_sylius_agreement_plugin_agreement_approval_checkbox', $subject->getBlockPrefix());
    }

    /**
     * @dataProvider build_view_data_provider
     * @param FormInterface $form
     */
    public function test_it_builds_view_correctly(
        array $options,
        object $form,
        string $extendedLabel,
        bool $approved,
        bool $checked
    ): void
    {
        $subject = new AgreementCheckboxType();
        $view = new FormView();

        $subject->buildView(
            $view,
            $form,
            $options
        );

        self::assertEquals($extendedLabel, $view->vars['extended_label']);
        self::assertEquals($approved, $view->vars['approved']);
        self::assertEquals($checked, $view->vars['checked']);
    }

    public function build_view_data_provider(): array
    {
        return [
            [
                [
                    'extended_label' => 'label1',
                    'approved' => true
                ],
                $this->mock_form_extension( true, null),
                'label1',
                true,
                false
            ],
            [
                [
                    'extended_label' => 'label2',
                    'approved' => true
                ],
                $this->mock_form_extension( false, null),
                'label2',
                true,
                true
            ],
        ];
    }

    private function mock_form_extension(bool $submitted, $viewData = null): object
    {
        $mock = $this->mock_form();

        $mock->method('getViewData')->willReturn($viewData);
        $mock->method('isSubmitted')->willReturn($submitted);

        return $mock;
    }

    public function test_it_configures_correctly()
    {
        $agreementCheckboxType = new AgreementCheckboxType();
        $resolver = $this->mock_resolver();

        $resolver->expects(self::once())
            ->method('setRequired')
            ->with(['extended_label', 'approved']);

        $agreementCheckboxType->configureOptions($resolver);

    }

    /**
     * @return FormInterface|MockObject
     */
    private function mock_form(): object
    {
        return $this->createMock(FormInterface::class);
    }

    /**
     * @return OptionsResolver|MockObject
     */
    private function mock_resolver(): object
    {
        return $this->createMock(OptionsResolver::class);
    }
}
