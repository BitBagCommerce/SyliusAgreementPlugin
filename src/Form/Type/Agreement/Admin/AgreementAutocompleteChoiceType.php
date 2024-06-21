<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Form\Type\Agreement\Admin;

use Sylius\Bundle\ResourceBundle\Form\Type\ResourceAutocompleteChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AgreementAutocompleteChoiceType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label' => 'bitbag_sylius_agreement_plugin.ui.agreement',
            'resource' => 'bitbag_sylius_agreement_plugin.agreement',
            'choice_name' => 'code',
            'choice_value' => 'id',
        ]);
    }

    public function buildView(
        FormView $view,
        FormInterface $form,
        array $options,
    ): void {
        $view->vars['remote_criteria_type'] = 'contains';
        $view->vars['remote_criteria_name'] = 'phrase';
    }

    /**
     * @inheritdoc
     */
    public function getBlockPrefix(): string
    {
        return 'bitbag_sylius_agreement_plugin_parent_autocomplete_choice';
    }

    /**
     * @inheritdoc
     */
    public function getParent(): string
    {
        return ResourceAutocompleteChoiceType::class;
    }
}
