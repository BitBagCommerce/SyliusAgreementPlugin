<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Form\Type\Agreement\Admin;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class AgreementTranslationType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'bitbag_sylius_agreement_plugin.ui.name',
                'empty_data' => '',
                'required' => true,
            ])
            ->add('body', TextareaType::class, [
                'label' => 'bitbag_sylius_agreement_plugin.ui.body',
                'empty_data' => '',
                'required' => true,
            ])
            ->add('extendedBody', TextareaType::class, [
                'label' => 'bitbag_sylius_agreement_plugin.ui.extended_body',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefault('required', true);
    }

    public function getBlockPrefix(): string
    {
        return 'bitbag_sylius_agreement_plugin_agreement_translation';
    }
}
