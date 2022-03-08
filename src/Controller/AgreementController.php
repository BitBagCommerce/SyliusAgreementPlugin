<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

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

        /** @var AgreementRepositoryInterface $repository */
        $repository = $this->repository;

        /** @var AgreementInterface|null $agreement */
        $agreement = $repository->findOneByParent($configuration->getRequest()->attributes->getInt('id'));

        if (null !== $agreement) {
            return $this->redirectToRoute('bitbag_sylius_agreement_plugin_admin_agreement_update', ['id' => $agreement->getId()]);
        }

        return parent::updateAction($request);
    }
}
