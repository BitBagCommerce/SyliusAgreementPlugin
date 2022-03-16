<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusAgreementPlugin\Unit\Form\Type\Agreement\Shop;

use BitBag\SyliusAgreementPlugin\Form\Type\Agreement\Shop\AgreementHiddenType;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class AgreementHiddenTypeTest extends TestCase
{
    public function test_it_has_correct_parent(): void
    {
        $subject = new AgreementHiddenType();

        self::assertEquals(HiddenType::class, $subject->getParent());
    }

    public function test_it_has_correct_block_prefix(): void
    {
        $subject = new AgreementHiddenType();

        self::assertEquals('bitbag_sylius_agreement_plugin_agreement_approval_hidden', $subject->getBlockPrefix());
    }

    public function test_it_builds_view_correctly(): void
    {
        $subject = new AgreementHiddenType();
        $view = new FormView();

        $subject->buildView($view, $this->mock_form(), [
            'extended_label' => 'test1'
        ]);

        self::assertEquals('test1', $view->vars['extended_label'],);
    }

    public function test_it_configures_correctly(): void
    {
        $subject = new AgreementHiddenType();
        $resolver = $this->mock_resolver();

        $resolver
            ->expects(self::once())
            ->method('setRequired')
            ->with('extended_label');

        $subject->configureOptions($resolver);
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
