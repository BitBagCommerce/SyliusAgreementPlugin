<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Controller\Action;

use BitBag\SyliusAgreementPlugin\Entity\Company\CompanyUserInterface;
use BitBag\SyliusAgreementPlugin\Entity\Customer\CustomerInterface;
use BitBag\SyliusAgreementPlugin\Event\CompanyUserAgreementsUpdateEvent;
use BitBag\SyliusAgreementPlugin\Form\Type\Account\CompanyUserAgreementsType;
use BitBag\SyliusAgreementPlugin\Resolver\RequiredAccountAgreementsResolverInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Twig\Environment as TwigEnvironment;

final class ShopUserAgreementsAction
{
    /** @var TwigEnvironment */
    private $twigEnvironment;

    /** @var CustomerContextInterface */
    private $customerContext;

    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var RouterInterface */
    private $router;

    /** @var EventDispatcherInterface */
    private $dispatcher;

    /** @var FlashBagInterface */
    private $flashBag;

    /** @var SessionInterface */
    private $session;

    public function __construct(
        TwigEnvironment $twigEnvironment,
        CustomerContextInterface $customerContext,
        FormFactoryInterface $formFactory,
        RouterInterface $router,
        EventDispatcherInterface $dispatcher,
        FlashBagInterface $flashBag,
        SessionInterface $session
    ) {
        $this->twigEnvironment = $twigEnvironment;
        $this->customerContext = $customerContext;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->dispatcher = $dispatcher;
        $this->flashBag = $flashBag;
        $this->session = $session;
    }

    public function __invoke(Request $request): Response
    {
        /** @var CustomerInterface|null $customer */
        $customer = $this->customerContext->getCustomer();

        if (null === $customer) {
            throw new AccessDeniedException();
        }

        /** @var CompanyUserInterface $companyUser */
        $companyUser = $customer->getCompanyUser();

        $form = $this->formFactory->create(CompanyUserAgreementsType::class, $companyUser);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event = new CompanyUserAgreementsUpdateEvent($companyUser);
            $this->dispatcher->dispatch($event);
            $this->flashBag->add('success', 'app.account.agreements_updated_successfully');

            return new RedirectResponse(
              $this->router->generate('app_shop_account_agreements_index')
            );
        }

        return new Response(
            $this->twigEnvironment->render('App/Account/Agreements/index.html.twig', [
                'form' => $form->createView(),
                'session_agreement_require_acceptation_identifiers' => $this->session->get(RequiredAccountAgreementsResolverInterface::SESSION_AGREEMENT_REQUIRE_ACCEPTATION_IDENTIFIERS),
            ])
        );
    }
}
