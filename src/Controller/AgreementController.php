<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Controller;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use BitBag\SyliusAgreementPlugin\Repository\AgreementRepository;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class AgreementController extends ResourceController
{
    public function updateAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        /** @var AgreementRepository $agreementRepository */
        $agreementRepository = $this->get("bitbag_sylius_agreement_plugin.repository.agreement");
        /** @var AgreementInterface|null $agreement */
        $agreement = $agreementRepository->findOneByParent($configuration->getRequest()->attributes->getInt('id'));

        if ($agreement) {
            return $this->redirectToRoute('app_admin_agreement_update', ['id' => $agreement->getId()]);
        }

        return parent::updateAction($request);
    }
}
