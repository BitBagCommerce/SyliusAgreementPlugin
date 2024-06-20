<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Form\Type\Agreement\Shop;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class AgreementHiddenType extends AbstractType
{
    public function getParent(): string
    {
        return HiddenType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'bitbag_sylius_agreement_plugin_agreement_approval_hidden';
    }

    public function buildView(
        FormView $view,
        FormInterface $form,
        array $options,
    ): void {
        parent::buildView($view, $form, $options);
        $view->vars['extended_label'] = $options['extended_label'];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setRequired('extended_label');
    }
}
