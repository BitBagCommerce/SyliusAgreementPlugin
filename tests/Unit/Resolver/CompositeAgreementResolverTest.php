<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusAgreementPlugin\Unit\Resolver;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use BitBag\SyliusAgreementPlugin\Resolver\AgreementResolverInterface;
use BitBag\SyliusAgreementPlugin\Resolver\CompositeAgreementResolver;
use PHPUnit\Framework\TestCase;

final class CompositeAgreementResolverTest extends TestCase
{
    /**
     * @dataProvider resolve_data_provider
     */
    public function test_it_resolves(
        array $resolvers,
        array $output
    ): void
    {
        $subject = new CompositeAgreementResolver($resolvers);

        self::assertEquals($output, $subject->resolve('', []));
    }

    public function resolve_data_provider(): array
    {
        $agreement1 = $this->createMock(AgreementInterface::class);
        $agreement2 = $this->createMock(AgreementInterface::class);
        $agreement3 = $this->createMock(AgreementInterface::class);

        return [
            [
                [
                    $this->mock_resolver(false, [$agreement1, $agreement2]),
                    $this->mock_resolver(true, []),
                ],
                []
            ],
            [
                [
                    $this->mock_resolver(true, [$agreement1, $agreement2]),
                    $this->mock_resolver(true, []),
                ],
                [$agreement1, $agreement2]
            ],
            [
                [
                    $this->mock_resolver(true, [$agreement1, $agreement3]),
                    $this->mock_resolver(true, [$agreement2]),
                ],
                [$agreement1, $agreement3, $agreement2]
            ],
        ];
    }

    public function test_it_supports(): void
    {
        $subject = new CompositeAgreementResolver([]);

        self::assertTrue($subject->supports('', []));
    }

    private function mock_resolver(bool $supports, array $resolved): object
    {
        $mock = $this->createMock(AgreementResolverInterface::class);
        $mock->method('supports')->willReturn($supports);
        $mock->method('resolve')->willReturn($resolved);

        return $mock;
    }
}
