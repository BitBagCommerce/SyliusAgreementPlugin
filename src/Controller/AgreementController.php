<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Controller;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use BitBag\SyliusAgreementPlugin\Repository\AgreementRepositoryInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class AgreementController extends ResourceController
{
    public function updateAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        /** @var AgreementInterface|null $agreement */
        $agreement = $this->repository->findOneByParent($configuration->getRequest()->attributes->getInt('id'));

        if (null !== $agreement) {
            return $this->redirectToRoute('bitbag_sylius_agreement_plugin_admin_agreement_update', ['id' => $agreement->getId()]);
        }

        return parent::updateAction($request);
    }
}
