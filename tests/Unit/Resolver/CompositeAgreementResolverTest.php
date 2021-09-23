<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Tests\Unit\Resolver;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use BitBag\SyliusAgreementPlugin\Resolver\AgreementResolverInterface;
use BitBag\SyliusAgreementPlugin\Resolver\CompositeAgreementResolver;
use PHPUnit\Framework\TestCase;

final class CompositeAgreementResolverTest extends TestCase
{
    /**
     * @dataProvider resolveDataProvider
     */
    public function testResolve(
        array $resolvers,
        array $output
    ): void
    {
        $subject = new CompositeAgreementResolver($resolvers);

        self::assertEquals($output, $subject->resolve('', []));
    }

    public function resolveDataProvider(): array
    {
        $agreement1 = $this->createMock(AgreementInterface::class);
        $agreement2 = $this->createMock(AgreementInterface::class);
        $agreement3 = $this->createMock(AgreementInterface::class);

        return [
            [
                [
                    $this->mockResolver(false, [$agreement1, $agreement2]),
                    $this->mockResolver(true, []),
                ],
                []
            ],
            [
                [
                    $this->mockResolver(true, [$agreement1, $agreement2]),
                    $this->mockResolver(true, []),
                ],
                [$agreement1, $agreement2]
            ],
            [
                [
                    $this->mockResolver(true, [$agreement1, $agreement3]),
                    $this->mockResolver(true, [$agreement2]),
                ],
                [$agreement1, $agreement3, $agreement2]
            ],
        ];
    }

    public function testSupports(): void
    {
        $subject = new CompositeAgreementResolver([]);

        self::assertTrue($subject->supports('', []));
    }

    private function mockResolver(bool $supports, array $resolved): ?object
    {
        $mock = $this->createMock(AgreementResolverInterface::class);
        $mock->method('supports')->willReturn($supports);
        $mock->method('resolve')->willReturn($resolved);

        return $mock;
    }
}
