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
     * @dataProvider resolve_history_data_provider
     */
    public function test_it_resolves_history(
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

    public function resolve_history_data_provider(): array
    {
        $agreement1 = $this->createMock(AgreementHistoryInterface::class);
        $agreement2 = $this->createMock(AgreementHistoryInterface::class);

        $agreementHistory1 = $this->createMock(AgreementHistoryInterface::class);
        $agreementHistory2 = $this->createMock(AgreementHistoryInterface::class);
        return [
            [
                [
                    $this->mock_history_resolver(false, $agreementHistory2),
                    $this->mock_history_resolver(true, $agreementHistory1),
                ],
                null,
                $agreementHistory1
            ],
            [
                [
                    $this->mock_history_resolver(false, $agreementHistory2),
                    $this->mock_history_resolver(false, $agreementHistory1),
                ],
                $agreementHistory2,
                $agreementHistory2
            ],
        ];
    }

    public function test_it_supports(): void
    {
        $agreementHistoryFactory = $this->createMock(FactoryInterface::class);
        $subject = new CompositeAgreementHistoryResolver([], $agreementHistoryFactory);

        self::assertTrue($subject->supports($this->createMock(AgreementInterface::class)));
    }

    private function mock_history_resolver(bool $supports, ?object $history = null): object
    {
        $mock = $this->createMock(AgreementHistoryResolverInterface::class);
        $mock->method('supports')->willReturn($supports);
        $mock->method('resolveHistory')->willReturn($history);

        return $mock;
    }
}
