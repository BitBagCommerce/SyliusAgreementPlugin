<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Form\Type\Agreement\Admin;


use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;


final class AgreementType extends AbstractResourceType
{
    private array $modes;

    private array $contexts;

    public function __construct(string $dataClass , array $validationGroups = [], array $modes, array $contexts)
    {
        parent::__construct($dataClass,$validationGroups);
        $this->dataClass="BitBag\SyliusAgreementPlugin\Entity\Agreement\Agreement";

        $this->modes = $modes;
        $this->contexts = $contexts;
    }

//    public function configureOptions(OptionsResolver $resolver): void
//    {
//        parent::configureOptions($resolver);
//        $resolver->setDefaults([
//            'data_class'=> Agreement::class
//        ]);
//    }

    private function prepareModesData(): array
    {

        $modes = [];

        foreach ($this->modes as $mode) {
            $modes[\sprintf('sylius_agreement_plugin.form.agreement.modes.%s', $mode)] = $mode;
        }

        return $modes;
    }

    private function prepareContextsData(): array
    {
        $contexts = [];

        foreach ($this->contexts as $context) {
            $contexts[\sprintf('sylius_agreement_plugin.form.agreement.contexts.%s', $context)] = $context;
        }

        return $contexts;
    }



    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $modes = $this->prepareModesData();
        $contexts = $this->prepareContextsData();

        $builder
            ->add('code', TextType::class, [
                'label' => 'sylius_agreement_plugin.form.agreement.code',
                'empty_data' => '',
            ])
            ->add('mode', ChoiceType::class, [
                'label' => 'sylius_agreement_plugin.form.agreement.mode',
                'choices' => $modes,
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'sylius_agreement_plugin.form.agreement.enabled',
            ])
            ->add('orderOnView', IntegerType::class, [
                'label' => 'sylius_agreement_plugin.form.agreement.order_on_view',
            ])
            ->add('contexts', ChoiceType::class, [
                'label' => 'sylius_agreement_plugin.form.agreement.contexts_label',
                'multiple' => true,
                'choices' => $contexts,
            ])
            ->add('publishedAt', DateType::class, [
                'label' => 'sylius_agreement_plugin.form.agreement.published_at',
                'required' => false,
                'format' => DateType::HTML5_FORMAT,
                'widget' => 'single_text',
            ])
            ->add('translations', ResourceTranslationsType::class, [
                'entry_type' => AgreementTranslationType::class,
                'entry_options' => [
                    'required' => true,
                ],
                'label' => 'app.form.agreement.translations',
            ]);

    }

    public function getBlockPrefix(): string
    {
        return 'bitbag_sylius_agreement_plugin_agreement';
    }
}
