<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Form\Type\Agreement\Admin;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementContexts;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
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
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $modes = [
            'app.form.agreement.modes.required_and_non_cancellable' => AgreementInterface::MODE_REQUIRED_AND_NON_CANCELLABLE,
            'app.form.agreement.modes.required' => AgreementInterface::MODE_REQUIRED,
            'app.form.agreement.modes.only_show' => AgreementInterface::MODE_ONLY_SHOW,
            'app.form.agreement.modes.not_required' => AgreementInterface::MODE_NOT_REQUIRED,
        ];

        $contexts = [
            'app.form.agreement.contexts.unknown' => AgreementContexts::CONTEXT_UNKNOWN,
            'app.form.agreement.contexts.registration_form' => AgreementContexts::CONTEXT_REGISTRATION_FORM,
            'app.form.agreement.contexts.account' => AgreementContexts::CONTEXT_ACCOUNT,
            'app.form.agreement.contexts.logged_in_order_summary' => AgreementContexts::CONTEXT_LOGGED_IN_ORDER_SUMMARY,
            'app.form.agreement.contexts.anonymous_order_summary' => AgreementContexts::CONTEXT_ANONYMOUS_ORDER_SUMMARY,
            'app.form.agreement.contexts.contact_form' => AgreementContexts::CONTEXT_CONTACT_FORM,
            'app_update_1.form.agreement.contexts.newsletter_form_subscribe' => AgreementContexts::CONTEXT_NEWSLETTER_FORM_SUBSCRIBE,
            'app_update_1.form.agreement.contexts.newsletter_form_unsubscribe' => AgreementContexts::CONTEXT_NEWSLETTER_FORM_UNSUBSCRIBE,
        ];

        $ediumAgreementType = [
            'app.form.agreement.edium_agreements.' . strtolower(AgreementInterface::COMPANY_SOLE_TRADER) => AgreementInterface::COMPANY_SOLE_TRADER,
            'app.form.agreement.edium_agreements.' . strtolower(AgreementInterface::INFORMATION_OBLIGATION) => AgreementInterface::INFORMATION_OBLIGATION,
            'app.form.agreement.edium_agreements.' . strtolower(AgreementInterface::EMAIL_MARKETING) => AgreementInterface::EMAIL_MARKETING,
            'app.form.agreement.edium_agreements.' . strtolower(AgreementInterface::COOPERATION_CONDITIONS) => AgreementInterface::COOPERATION_CONDITIONS,
        ];

        $builder
            ->add('code', TextType::class, [
                'label' => 'app.form.agreement.code',
                'empty_data' => '',
            ])
            ->add('mode', ChoiceType::class, [
                'label' => 'app.form.agreement.mode',
                'choices' => $modes,
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'app.form.agreement.enabled',
            ])
            ->add('inherited', CheckboxType::class, [
                'label' => 'app.form.agreement.inherited',
                'required' => false,
            ])
            ->add('orderOnView', IntegerType::class, [
                'label' => 'app.form.agreement.order_on_view',
            ])
            ->add('contexts', ChoiceType::class, [
                'label' => 'app.form.agreement.contexts_label',
                'multiple' => true,
                'choices' => $contexts,
            ])
            ->add('ediumAgreementType', ChoiceType::class, [
                'label' => 'app.form.agreement.edium_agreement_type',
                'choices' => $ediumAgreementType,
                'required' => false,
            ])
            ->add('publishedAt', DateType::class, [
                'label' => 'app.form.agreement.published_at',
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
            ])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'app_agreement';
    }
}
