<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusAgreementPlugin\Unit\Resolver;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementHistoryInterface;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use BitBag\SyliusAgreementPlugin\Resolver\AgreementHistoryResolverInterface;
use BitBag\SyliusAgreementPlugin\Resolver\CompositeAgreementHistoryResolver;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class CompositeAgreementHistoryResolverTest extends TestCase
{
    /**
     * @dataProvider resolveHistoryDataProvider
     */
    public function test_it_resolves_history_correctly(
        array $agreementHistoryResolvers,
        ?object $factoryNewHistory = null,
        ?object $output = null
    ): void
    {
        $agreementHistoryFactory = $this->createMock(FactoryInterface::class);
        $subject = new CompositeAgreementHistoryResolver($agreementHistoryResolvers, $agreementHistoryFactory);

        $agreementHistoryFactory->expects(self::atMost(1))->method('createNew')->willReturn($factoryNewHistory);

        self::assertEquals($output, $subject->resolveHistory($this->createMock(AgreementInterface::class)));
    }

    public function test_it_supports_agreement(): void
    {
        $agreementHistoryFactory = $this->createMock(FactoryInterface::class);
        $subject = new CompositeAgreementHistoryResolver([], $agreementHistoryFactory);

        self::assertTrue($subject->supports($this->createMock(AgreementInterface::class)));
    }

    private function mockHistoryResolver(bool $supports, ?object $history = null): object
    {
        $mock = $this->createMock(AgreementHistoryResolverInterface::class);
        $mock->method('supports')->willReturn($supports);
        $mock->method('resolveHistory')->willReturn($history);

        return $mock;
    }

    public function resolveHistoryDataProvider(): array
    {
        $agreement1 = $this->createMock(AgreementHistoryInterface::class);
        $agreement2 = $this->createMock(AgreementHistoryInterface::class);

        $agreementHistory1 = $this->createMock(AgreementHistoryInterface::class);
        $agreementHistory2 = $this->createMock(AgreementHistoryInterface::class);
        return [
            [
                [
                    $this->mockHistoryResolver(false, $agreementHistory2),
                    $this->mockHistoryResolver(true, $agreementHistory1),
                ],
                null,
                $agreementHistory1
            ],
            [
                [
                    $this->mockHistoryResolver(false, $agreementHistory2),
                    $this->mockHistoryResolver(false, $agreementHistory1),
                ],
                $agreementHistory2,
                $agreementHistory2
            ],
        ];
    }
}
