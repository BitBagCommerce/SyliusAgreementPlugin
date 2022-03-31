<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Form\Type\Agreement\Shop;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class AgreementCheckboxType extends AbstractType
{
    public function getParent(): string
    {
        return CheckboxType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'bitbag_sylius_agreement_plugin_agreement_approval_checkbox';
    }

    public function buildView(
        FormView $view,
        FormInterface $form,
        array $options
    ): void {
        parent::buildView($view, $form, $options);
        $view->vars['extended_label'] = $options['extended_label'];
        $view->vars['approved'] = $options['approved'];
        /** @phpstan-ignore-next-line  */
        $view->vars['checked'] = null !== $form->getViewData() || ($options['approved'] && !$form->isSubmitted());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setRequired(['extended_label', 'approved']);
    }
}
