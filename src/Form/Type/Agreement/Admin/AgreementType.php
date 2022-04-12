<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Form\Type\Agreement\Admin;

use BitBag\SyliusAgreementPlugin\Repository\AgreementRepositoryInterface;
use Sylius\Bundle\ResourceBundle\Form\DataTransformer\ResourceToIdentifierTransformer;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\ReversedTransformer;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class AgreementType extends AbstractResourceType
{
    private AgreementRepositoryInterface $agreementRepository;

    private array $modes;

    private array $contexts;

    public function __construct(
        string $dataClass,
        AgreementRepositoryInterface $agreementRepository,
        array $validationGroups = [],
        array $modes = [],
        array $contexts = []
    ) {
        parent::__construct($dataClass, $validationGroups);

        $this->agreementRepository = $agreementRepository;
        $this->modes = $modes;
        $this->contexts = $contexts;
    }

    protected function prepareModesData(): array
    {
        $modes = [];

        foreach ($this->modes as $mode) {
            $modes[\sprintf('bitbag_sylius_agreement_plugin.ui.agreement.modes.%s', $mode)] = $mode;
        }

        return $modes;
    }

    protected function prepareContextsData(): array
    {
        $contexts = [];

        foreach($this->contexts as $key=>$val)
        {
            $contexts[\sprintf('bitbag_sylius_agreement_plugin.ui.agreement.contexts.%s', $key)] = $key;
        }

        return $contexts;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'validation_groups' => [
                'Agreement',
            ],
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $contexts = $this->prepareContextsData();
        $modes = $this->prepareModesData();

        $builder
            ->add('parent', AgreementAutocompleteChoiceType::class, [
                'label' => 'bitbag_sylius_agreement_plugin.ui.agreement_parent',
                'required' => false,
            ])
            ->add('code', TextType::class, [
                'label' => 'bitbag_sylius_agreement_plugin.ui.code',
                'empty_data' => '',
            ])
            ->add('mode', ChoiceType::class, [
                'label' => 'bitbag_sylius_agreement_plugin.ui.mode',
                'choices' => $modes,
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'bitbag_sylius_agreement_plugin.ui.enabled',
            ])
            ->add('orderOnView', IntegerType::class, [
                'label' => 'bitbag_sylius_agreement_plugin.ui.order_on_view',
            ])
            ->add('contexts', ChoiceType::class, [
                'label' => 'bitbag_sylius_agreement_plugin.ui.contexts_label',
                'multiple' => true,
                'choices' => $contexts,
            ])
            ->add('publishedAt', DateType::class, [
                'label' => 'bitbag_sylius_agreement_plugin.ui.published_at',
                'required' => false,
                'format' => DateType::HTML5_FORMAT,
                'widget' => 'single_text',
            ])
            ->add('translations', ResourceTranslationsType::class, [
                'entry_type' => AgreementTranslationType::class,
                'entry_options' => [
                    'required' => true,
                ],
                'label' => 'bitbag_sylius_agreement_plugin.ui.translations',
            ]);

        $builder->get('parent')->addModelTransformer(
            new ReversedTransformer(
                new ResourceToIdentifierTransformer($this->agreementRepository, 'id')
            )
        )->addModelTransformer(
            new ResourceToIdentifierTransformer($this->agreementRepository, 'id')
        );
    }

    public function getBlockPrefix(): string
    {
        return 'bitbag_sylius_agreement_plugin_agreement';
    }
}
