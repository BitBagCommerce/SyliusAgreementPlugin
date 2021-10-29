<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusAgreementPlugin\Unit\Form\Type\Agreement\Shop;

use BitBag\SyliusAgreementPlugin\Form\Type\Agreement\Shop\AgreementHiddenType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class AgreementHiddenTypeTest extends TestCase
{
    public function testGetParent(): void
    {
        $subject = new AgreementHiddenType();

        self::assertEquals(HiddenType::class, $subject->getParent());
    }

    public function testGetBlockPrefix(): void
    {
        $subject = new AgreementHiddenType();

        self::assertEquals('bitbag_sylius_agreement_plugin_agreement_approval_hidden', $subject->getBlockPrefix());
    }

    public function testBuildView(): void
    {
        $subject = new AgreementHiddenType();

        $view = new FormView();

        $subject->buildView($view, $this->createMock(FormInterface::class), ['extended_label' => 'test1']);
        self::assertEquals('test1', $view->vars['extended_label'],);
    }

    public function testConfigureOptions(): void
    {
        $resolver = $this->createMock(OptionsResolver::class);
        $resolver
            ->expects(self::once())
            ->method('setRequired')
            ->with('extended_label');

        $subject = new AgreementHiddenType();
        $subject->configureOptions($resolver);
    }
}
