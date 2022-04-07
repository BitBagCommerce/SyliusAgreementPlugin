<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Form\Extension;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use BitBag\SyliusAgreementPlugin\Event\AgreementCheckedEvent;
use BitBag\SyliusAgreementPlugin\Form\Type\Agreement\Shop\AgreementCollectionType;
use BitBag\SyliusAgreementPlugin\Resolver\AgreementApprovalResolverInterface;
use BitBag\SyliusAgreementPlugin\Resolver\AgreementResolverInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

final class AgreementsTypeExtension extends AbstractTypeExtension
{
    private AgreementResolverInterface $agreementResolver;

    private AgreementApprovalResolverInterface $agreementApprovalResolver;

    private array $contexts;

    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        AgreementResolverInterface $agreementResolver,
        AgreementApprovalResolverInterface $agreementApprovalResolver,
        array $contexts,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->agreementResolver = $agreementResolver;
        $this->agreementApprovalResolver = $agreementApprovalResolver;
        $this->contexts = $contexts;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $context = $this->getFormClass($builder);

        $agreements = $this->getAgreements($context, $options);

        $builder
            ->add('agreements', AgreementCollectionType::class, [
                'entries' => $agreements,
                'required' => false,
                'label' => false,
            ])
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $formEvent) use ($context, $agreements){
                $event = new AgreementCheckedEvent($context, $formEvent);
                $this->eventDispatcher->dispatch($event);
            })
        ;
    }

    /**
     * Moved to configuration using \BitBag\SyliusAgreementPlugin\DependencyInjection\DependencyInjectionExtension and %sylius_agreement_plugin.extended_form_types% parameter
     */
    public static function getExtendedTypes(): array
    {
        return [];
    }

    private function getAgreements(string $formName, array $options): array
    {
        $agreements = $this->agreementResolver->resolve($formName, $options);

        /** @var AgreementInterface $agreement */
        foreach ($agreements as $agreement) {
            $agreement->setApproved($this->agreementApprovalResolver->resolve($agreement));
        }

        return $agreements;
    }

    private function getFormClass(FormBuilderInterface $builder)
    {
        $formName = get_class($builder->getType()->getInnerType());

        foreach ($this->contexts as $context=>$val)
        {
            if(in_array($formName, $val))
            {
                return $context;
            }
        }
        return null;
    }
}
