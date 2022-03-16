<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusAgreementPlugin\Unit\Form\Type\Agreement\Shop;

use BitBag\SyliusAgreementPlugin\Form\Type\Agreement\Shop\AgreementCollectionType;
use BitBag\SyliusAgreementPlugin\Repository\AgreementRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Form\Type\FixedCollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class AgreementCollectionTypeTest extends TestCase
{

    public function test_it_configures_correctly(): void
    {
        $type = new AgreementCollectionType($this->mock_agreement_repository());
        $resolver = $this->mock_resolver();

        $resolver
            ->expects(self::exactly(3))
            ->method('setDefault')
            ->withConsecutive(
                [
                    self::equalTo('entry_type'),
                    'BitBag\SyliusAgreementPlugin\Form\Type\Agreement\Shop\AgreementType',
                ],
                [
                    self::equalTo('entry_name'),
                    self::anything(),
                ],
                [
                    self::equalTo('entry_options'),
                    self::anything(),
                ]
            )
            ->willReturnSelf();

        $type->configureOptions($resolver);
    }

    public function test_it_builds_correctly(): void
    {
        $type = new AgreementCollectionType($this->mock_agreement_repository());
        $formBuilder = $this->mock_builder();

        $formBuilder
            ->expects(self::once())
            ->method('addEventListener')
            ->with(self::equalTo('form.submit'), self::anything());
        $type->buildForm($formBuilder, []);
    }

    public function test_it_has_correct_block_prefix(): void
    {
        $subject = new AgreementCollectionType($this->createMock(AgreementRepositoryInterface::class));

        self::assertEquals('bitbag_sylius_agreement_plugin_agreement_approval_collection', $subject->getBlockPrefix());
    }

    public function test_it_has_correct_parent(): void
    {
        $subject = new AgreementCollectionType($this->createMock(AgreementRepositoryInterface::class));

        self::assertEquals(FixedCollectionType::class, $subject->getParent());
    }

    /**
     * @return OptionsResolver|MockObject
     */
    private function mock_resolver(): object
    {
        return $this->createMock(OptionsResolver::class);
    }

    /**
     * @return AgreementRepositoryInterface|MockObject
     */
    private function mock_agreement_repository(): object
    {
        return $this->createMock(AgreementRepositoryInterface::class);
    }

    /**
     * @return FormBuilderInterface|MockObject
     */
    private function mock_builder(): object
    {
        return $this->createMock(FormBuilderInterface::class);
    }
}
