<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusAgreementPlugin\Unit\Form\Type\Agreement\Admin;

use BitBag\SyliusAgreementPlugin\Form\Type\Agreement\Admin\AgreementAutocompleteChoiceType;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AgreementAutocompleteChoiceTypeTest extends TestCase
{
    public function test_it_builds_view_correctly(): void
    {
        $type = new AgreementAutocompleteChoiceType();
        $view = new FormView();

        $type->buildView($view, $this->mock_form(), []);
        self::assertEquals('contains', $view->vars['remote_criteria_type']);
        self::assertEquals('phrase', $view->vars['remote_criteria_name']);
    }

    public function test_it_configures_options_correctly(): void
    {
        $optionsResolver = $this->mock_options_resolver();
        $optionsResolver
            ->expects(self::once())
            ->method('setDefaults')
            ->with(self::equalTo(
                [
                    'label' => 'bitbag_sylius_agreement_plugin.ui.agreement',
                    'resource' => 'bitbag_sylius_agreement_plugin.agreement',
                    'choice_name' => 'code',
                    'choice_value' => 'id',
                ]
            ))
            ->willReturnSelf()
        ;

        $type = new AgreementAutocompleteChoiceType();
        $type->configureOptions($optionsResolver);
    }

    public function test_it_has_correct_block_prefix(): void
    {
        $type = new AgreementAutocompleteChoiceType();

        self::assertEquals(
            'bitbag_sylius_agreement_plugin_parent_autocomplete_choice',
            $type->getBlockPrefix()
        );
    }

    public function test_it_has_correct_parent(): void
    {
        $type = new AgreementAutocompleteChoiceType();

        self::assertEquals(
            'Sylius\Bundle\ResourceBundle\Form\Type\ResourceAutocompleteChoiceType',
            $type->getParent()
        );
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
