<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Tests\Unit\Form\Type\Agreement\Shop;

use BitBag\SyliusAgreementPlugin\Form\Type\Agreement\Shop\AgreementCollectionType;
use BitBag\SyliusAgreementPlugin\Repository\AgreementRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Form\Type\FixedCollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class AgreementCollectionTypeTest extends TestCase
{
    public function testConfigureOptions(): void
    {
        $resolver = $this->createMock(OptionsResolver::class);

        $resolver->expects(self::exactly(3))->method('setDefault')->willReturnSelf();

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
