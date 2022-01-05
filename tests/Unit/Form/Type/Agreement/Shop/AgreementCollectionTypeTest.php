<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusAgreementPlugin\Unit\Form\Type\Agreement\Shop;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementTranslationInterface;
use BitBag\SyliusAgreementPlugin\Form\Type\Agreement\Shop\AgreementType;
use BitBag\SyliusAgreementPlugin\Form\Type\Agreement\Shop\AgreementCollectionType;
use BitBag\SyliusAgreementPlugin\Repository\AgreementRepositoryInterface;
use Closure;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Form\Type\FixedCollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class AgreementCollectionTypeTest extends TestCase
{

    public function testConfigureOptions(): void
    {
        $resolver = $this->createMock(OptionsResolver::class);
        $resolver->expects(self::exactly(3))->method('setDefault')
            ->withConsecutive(
                ['entry_type', AgreementType::class],
                ['entry_name', self::isInstanceOf(Closure::class)],
                ['entry_options', self::isInstanceOf(Closure::class)]
                )
            ->willReturnSelf();

        $subject = new AgreementCollectionType($this->createMock(AgreementRepositoryInterface::class));
        $subject->configureOptions($resolver);
    }

    public function testBuildForm(): void
    {
        $builder = $this->createMock(FormBuilderInterface::class);
        $builder->expects(self::once())->method('addEventListener');

        $subject = new AgreementCollectionType($this->createMock(AgreementRepositoryInterface::class));
        $subject->buildForm($builder, []);
    }

    public function testGetBlockPrefix(): void
    {
        $subject = new AgreementCollectionType($this->createMock(AgreementRepositoryInterface::class));

        self::assertEquals('bitbag_sylius_agreement_plugin_agreement_approval_collection', $subject->getBlockPrefix());
    }

    public function testGetParent(): void
    {
        $subject = new AgreementCollectionType($this->createMock(AgreementRepositoryInterface::class));

        self::assertEquals(FixedCollectionType::class, $subject->getParent());
    }
}
