<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Handler;

use BitBag\SyliusAgreementPlugin\DataModifier\AgreementHistoryModifierInterface;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use BitBag\SyliusAgreementPlugin\Repository\AgreementHistoryRepositoryInterface;
use BitBag\SyliusAgreementPlugin\Repository\AgreementRepositoryInterface;
use BitBag\SyliusAgreementPlugin\Resolver\AgreementStateResolverInterface;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AgreementHandler
{
    private AgreementHistoryRepositoryInterface $agreementHistoryRepository;

    private AgreementRepositoryInterface $agreementRepository;

    private AgreementStateResolverInterface $agreementStateResolver;

    private AgreementHistoryModifierInterface $agreementHistoryModifier;

    public function __construct(
        AgreementHistoryRepositoryInterface $agreementHistoryRepository,
        AgreementRepositoryInterface $agreementRepository,
        AgreementStateResolverInterface $agreementStateResolver,
        AgreementHistoryModifierInterface $agreementHistoryModifier
    ) {
        $this->agreementHistoryRepository = $agreementHistoryRepository;
        $this->agreementRepository = $agreementRepository;
        $this->agreementStateResolver = $agreementStateResolver;
        $this->agreementHistoryModifier = $agreementHistoryModifier;
    }

    public function handleAgreements(
        Collection $submittedAgreements,
        string $context,
        ?OrderInterface $order,
        ?ShopUserInterface $shopUser
    ): void {
        $resolvedAgreements = $this->agreementRepository->findAgreementsByContext($context);

        if (0 === count($resolvedAgreements)) {
            throw new NotFoundHttpException(sprintf('No agreements found for context %s', $context));
        }

        /** @var AgreementInterface $resolvedAgreement */
        foreach ($resolvedAgreements as $resolvedAgreement) {
            $submittedAgreement = $this->getSubmittedAgreement($submittedAgreements, $resolvedAgreement);

            $agreementHistory = $this->agreementHistoryModifier->setAgreementHistoryProperties($context, $order, $shopUser, $resolvedAgreement);

            $resolvedAgreementHistoryState = $agreementHistory->getState();

            $agreementHistoryState = $this->agreementStateResolver->resolve(
                $agreementHistory,
                $submittedAgreement,
                $resolvedAgreementHistoryState
            );

            if (
                $agreementHistoryState !== $resolvedAgreementHistoryState &&
                null !== $agreementHistory->getId()
            ) {
                $agreementHistory = clone $agreementHistory;
            }

            $agreementHistory->setState($agreementHistoryState);
            $this->agreementHistoryRepository->add($agreementHistory);
        }
    }

    private function getSubmittedAgreement(Collection $submittedAgreements, AgreementInterface $resolvedAgreement): AgreementInterface
    {
        return $submittedAgreements->filter(
            static function (AgreementInterface $agreement) use ($resolvedAgreement): bool {
                return $agreement->getId() === $resolvedAgreement->getId();
            }
        )->first();
    }
}
