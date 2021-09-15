<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Form\Type\Agreement\Shop;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementTranslationInterface;
use BitBag\SyliusAgreementPlugin\Repository\AgreementRepositoryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Sylius\Bundle\ResourceBundle\Form\Type\FixedCollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class AgreementCollectionType extends AbstractType
{
    /** @var AgreementRepositoryInterface */
    private $agreementRepository;

    public function __construct(AgreementRepositoryInterface $agreementRepository)
    {
        $this->agreementRepository = $agreementRepository;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefault('entry_type', AgreementType::class)
            ->setDefault('entry_name', static function (AgreementInterface $agreement) {
                return $agreement->getId();
            })
            ->setDefault('entry_options', static function (AgreementInterface $agreement) {
                /** @var AgreementTranslationInterface $translation */
                $translation = $agreement->getTranslation();

                return [
                    'required' => in_array(
                        $agreement->getMode(),
                        [
                            AgreementInterface::MODE_REQUIRED,
                            AgreementInterface::MODE_REQUIRED_AND_NON_CANCELLABLE,
                        ],
                        true
                    ),
                    'code' => $agreement->getCode(),
                    'approved' => $agreement->isApproved(),
                    'mode' => $agreement->getMode(),
                    'read_only' => $agreement->isReadOnly(),
                    'body' => $translation->getBody(),
                    'extended_body' => $translation->getExtendedBody(),
               ];
            });
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $self = $this;
        $builder->addEventListener(FormEvents::SUBMIT, static function (FormEvent $event) use ($self) {
            /** @var AgreementInterface[] $agreements */
            $agreements = $event->getData();
            $submittedAgreements = new ArrayCollection();
            foreach ($agreements as $agreementId => $agreement) {
                /** @var AgreementInterface $submittedAgreement */
                $submittedAgreement = $self->agreementRepository->find($agreementId);
                if ($submittedAgreement) {
                    $submittedAgreement->setApproved($agreement && $agreement->isApproved());
                }
                $submittedAgreements->add($submittedAgreement);
            }

            $event->setData($submittedAgreements);
        });
    }

    public function getBlockPrefix(): string
    {
        return 'bitbag_sylius_agreement_plugin_agreement_approval_collection';
    }

    public function getParent(): string
    {
        return FixedCollectionType::class;
    }
}
