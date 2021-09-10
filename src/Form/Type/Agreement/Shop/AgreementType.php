<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Form\Type\Agreement\Shop;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\Agreement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

final class AgreementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $constraints = [];
        if (true === $options['required']) {
            $constraints = [
                new IsTrue([
                    'groups' => [
                        'app',
                        'app_company_agreements',
                        'app_company_registration',
                        'app_company_registration_full_address',
                        'sylius_checkout_complete',
                        'app_contact',
                        'app_newsletter',
                    ],
                ]),
            ];
        }
        if (true === $options['read_only']) {
            $builder
                ->add('approved', AgreementHiddenType::class, [
                    'label' => $options['body'],
                    'extended_label' => $options['extended_body'],
                    'data' => true,
                    'translation_domain' => false,
                    'constraints' => $constraints,
                ])
            ;
        } else {
            $builder
                ->add('approved', AgreementCheckboxType::class, [
                    'required' => $options['required'],
                    'label' => $options['body'],
                    'extended_label' => $options['extended_body'],
                    'approved' => $options['approved'],
                    'translation_domain' => false,
                    'constraints' => $constraints,
                ])
            ;
        }
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        parent::buildView($view, $form, $options);
        $view->vars['code'] = $options['code'];
        $view->vars['required'] = $options['required'];
        $view->vars['mode'] = $options['mode'];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefault('data_class', Agreement::class)
            ->setDefault('label', false)
            ->setRequired('approved')
            ->setRequired('code')
            ->setRequired('mode')
            ->setDefault('required', false)
            ->setDefault('body', null)
            ->setDefault('extended_body', null)
            ->setDefault('read_only', false)
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'app_agreement_approval';
    }
}
