<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Form\Type\Agreement\Admin;

use BitBag\SyliusCmsPlugin\Form\Type\WysiwygType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class AgreementTranslationType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'app.form.agreement.name',
                'empty_data' => '',
                'required' => true,
            ])
            ->add('body', WysiwygType::class, [
                'label' => 'app.form.agreement.body',
                'config_name' => 'bitbag_sylius_only_links',
                'empty_data' => '',
                'required' => true,
            ])
            ->add('extendedBody', WysiwygType::class, [
                'label' => 'app.form.agreement.extended_body',
                'config_name' => 'bitbag_sylius_only_links',
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
        return 'app_agreement_translation';
    }
}
