<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Tests\Unit\Form\Type\Agreement\Shop;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\Agreement;
use BitBag\SyliusAgreementPlugin\Form\Type\Agreement\Shop\AgreementType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class AgreementTypeTest extends TestCase
{
    public function testBuildForm(): void
    {
        $builder = $this->createMock(FormBuilderInterface::class);
        $builder->expects(self::once())->method('add');

        $subject = new AgreementType([]);
        $subject->buildForm($builder, []);
    }

    public function testConfigureOptions(): void
    {
        $resolver = $this->createMock(OptionsResolver::class);
        $resolver
            ->expects(self::exactly(6))
            ->method('setDefault')
            ->withConsecutive(
                ['data_class', Agreement::class],
                ['label', false],
                ['required', false],
                ['body', null],
                ['extended_body', null],
                ['read_only', false]
            )->willReturnSelf();

        $resolver
            ->expects(self::exactly(3))
            ->method('setRequired')
            ->withConsecutive(
                ['approved'],
                ['code'],
                ['mode'],
            )->willReturnSelf();


        $subject = new AgreementType([]);
        $subject->configureOptions($resolver);
    }
}
