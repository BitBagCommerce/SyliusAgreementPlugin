<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusAgreementPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementTranslationInterface;
use BitBag\SyliusAgreementPlugin\Repository\AgreementRepositoryInterface;
use Faker\Provider\Lorem;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class AgreementContext implements Context
{
    private FactoryInterface $agreementFactory;

    private FactoryInterface $agreementTranslationFactory;

    private AgreementRepositoryInterface $agreementRepository;

    public function __construct(
        FactoryInterface $agreementFactory,
        FactoryInterface $agreementTranslationFactory,
        AgreementRepositoryInterface $agreementRepository
    ) {
        $this->agreementFactory = $agreementFactory;
        $this->agreementRepository = $agreementRepository;
        $this->agreementTranslationFactory = $agreementTranslationFactory;
    }

    /**
     * @Given the store has agreement :code in context :context
     * @Given the store has agreement :code in context :context with order :orderOnView
     * @Given the store has agreement :code in context :context with order :orderOnView in :mode
     */
    public function theStoreHasAgreementInContext(
        string $code,
        string $context,
        int $orderOnView = 1,
        string $mode = AgreementInterface::MODE_REQUIRED
    ): void {
        /** @var AgreementInterface $agreement */
        $agreement = $this->agreementFactory->createNew();

        $agreement
            ->setCode($code);
        $agreement
            ->setContexts([$context]);
        $agreement
            ->setOrderOnView($orderOnView);
        $agreement
            ->setMode($mode);

        foreach (['en_US'] as $locale) {
            /** @var AgreementTranslationInterface $translation */
            $translation = $this->agreementTranslationFactory->createNew();
            $translation
                ->setLocale($locale);
            $translation
                ->setBody(Lorem::text(500));
            $translation
                ->setName(Lorem::text(50));
            $agreement
                ->addTranslation($translation);
        }

        $this->agreementRepository->add($agreement);
    }

}
