<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Form\Extension;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementContexts;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use BitBag\SyliusAgreementPlugin\Entity\User\ShopUserInterface;
use BitBag\SyliusAgreementPlugin\Form\Type\Account\CompanyUserAgreementsType;
use BitBag\SyliusAgreementPlugin\Form\Type\Agreement\Shop\AgreementCollectionType;
use BitBag\SyliusAgreementPlugin\Form\Type\Company\Registration\CompanyUserType;
use BitBag\SyliusAgreementPlugin\Form\Type\ContactType;
use BitBag\SyliusAgreementPlugin\Form\Type\Newsletter\NewsletterType;
use BitBag\SyliusAgreementPlugin\Resolver\AgreementApprovalResolverInterface;
use BitBag\SyliusAgreementPlugin\Resolver\AgreementResolverInterface;
use Sylius\Bundle\CoreBundle\Form\Type\Checkout\CompleteType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Security;

final class AgreementsTypeExtension extends AbstractTypeExtension
{
    /** @var AgreementResolverInterface */
    private $agreementResolver;

    /** @var Security */
    private $security;

    /** @var AgreementApprovalResolverInterface */
    private $agreementApprovalResolver;

    public function __construct(
        AgreementResolverInterface $agreementResolver,
        Security $security,
        AgreementApprovalResolverInterface $agreementApprovalResolver
    ) {
        $this->agreementResolver = $agreementResolver;
        $this->security = $security;
        $this->agreementApprovalResolver = $agreementApprovalResolver;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $agreements = $this->getAgreements($builder->getName(), $options);
        $builder
            ->add('agreements', AgreementCollectionType::class, [
                'entries' => $agreements,
                'required' => false,
                'label' => false,
            ]);
    }

    public static function getExtendedTypes(): array
    {
        return [
            CompleteType::class,
            CompanyUserType::class,
            CompanyUserAgreementsType::class,
            ContactType::class,
            NewsletterType::class,
        ];
    }

    private function getAgreements(string $formName, array $options): array
    {
        switch ($formName) {
            case 'app_company_user':
                $agreements = $this->agreementResolver->resolve(AgreementContexts::CONTEXT_REGISTRATION_FORM);

                break;
            case 'sylius_checkout_complete':
                /** @var ShopUserInterface|null $shopUser */
                $shopUser = $this->security->getUser();
                $agreements = $this->agreementResolver->resolve($shopUser ? AgreementContexts::CONTEXT_LOGGED_IN_ORDER_SUMMARY : AgreementContexts::CONTEXT_ANONYMOUS_ORDER_SUMMARY);

                break;
            case 'app_company_user_agreements':
                $agreements = $this->agreementResolver->resolve(AgreementContexts::CONTEXT_ACCOUNT);

                break;
            case 'app_contact':
                $agreements = $this->agreementResolver->resolve(AgreementContexts::CONTEXT_CONTACT_FORM);

                break;
            case 'app_newsletter':
                $agreements = $this->agreementResolver->resolve(
                    $this->resolveNewsletterContext($options['data']->isSubscribed())
                );

                break;
            default:
                $agreements = [];
        }

        /** @var AgreementInterface $agreement */
        foreach ($agreements as $agreement) {
            $agreement->setApproved($this->agreementApprovalResolver->resolve($agreement));
        }

        return $agreements;
    }

    private function resolveNewsletterContext(bool $subscribed): string
    {
        return $subscribed ?
            AgreementContexts::CONTEXT_NEWSLETTER_FORM_SUBSCRIBE :
            AgreementContexts::CONTEXT_NEWSLETTER_FORM_UNSUBSCRIBE
        ;
    }
}
