<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Tests\Unit\Form\Type\Agreement\Shop;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use BitBag\SyliusAgreementPlugin\Form\Type\Agreement\Shop\AgreementCheckboxType;

final class AgreementCheckboxTypeTest extends TestCase
{
    public function testGetParent(): void
    {
        $subject = new AgreementCheckboxType();

        self::assertEquals(CheckboxType::class, $subject->getParent());
    }

    public function testGetBlockPrefix(): void
    {
        $subject = new AgreementCheckboxType();

        self::assertEquals('bitbag_sylius_agreement_plugin_agreement_approval_checkbox', $subject->getBlockPrefix());
    }

    /**
     * @dataProvider buildViewDataProvider
     * @param FormInterface $form
     */
    public function testBuildView(
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

    public function buildViewDataProvider(): array
    {
        return [
            [
                [
                    'extended_label' => 'label1',
                    'approved' => true
                ],
                $this->mockForm( true, null),
                'label1',
                true,
                false
            ],
            [
                [
                    'extended_label' => 'label2',
                    'approved' => true
                ],
                $this->mockForm( false, null),
                'label2',
                true,
                true
            ],
        ];
    }

    private function mockForm(bool $submitted, $viewData = null): object
    {
        $mock = $this->createMock(FormInterface::class);

        $mock->method('getViewData')->willReturn($viewData);
        $mock->method('isSubmitted')->willReturn($submitted);

        return $mock;
    }

    public function testConfigureOptions()
    {
        $resolver = $this
            ->createMock(OptionsResolver::class);
        $resolver->expects(self::once())
            ->method('setRequired')
            ->with(['extended_label', 'approved']);
        $agreementCheckboxType = new AgreementCheckboxType();
        $agreementCheckboxType->configureOptions($resolver);

    }
}
