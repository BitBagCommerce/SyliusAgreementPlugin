<?php

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

    /**
     * @psalm-suppress MissingPropertyType
     */
    public function buildView(
        FormView $view,
        FormInterface $form,
        array $options
    ): void {
        parent::buildView();
    }

    /**
     * @inheritdoc
     */
    public function getBlockPrefix(): string
    {
        return 'bitbag_sylius_agreement_plugin_autocomplete_choice';
    }

    /**
     * @inheritdoc
     */
    public function getParent(): string
    {
        return ResourceAutocompleteChoiceType::class;
    }
}
