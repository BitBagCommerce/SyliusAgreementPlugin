<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusAgreementPlugin\Unit\Resolver;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use BitBag\SyliusAgreementPlugin\Exception\AgreementNotSupportedException;
use BitBag\SyliusAgreementPlugin\Resolver\AgreementApprovalResolverInterface;
use BitBag\SyliusAgreementPlugin\Resolver\CompositeAgreementApprovalResolver;
use PHPUnit\Framework\TestCase;

final class CompositeAgreementApprovalResolverTest extends TestCase
{
    /**
     * @dataProvider resolveDataProvider
     */
    public function test_it_resolves_correct_agreement(array $resolvers, bool $result): void
    {
        $subject = new CompositeAgreementApprovalResolver($resolvers);

        self::assertEquals($result, $subject->resolve($this->createMock(AgreementInterface::class)));
    }

    public function test_it_doesnt_resolve_but_throw_exception(): void
    {
        self::expectException(AgreementNotSupportedException::class);
        $resolvers = [];

        $subject = new CompositeAgreementApprovalResolver($resolvers);
        $subject->resolve($this->createMock(AgreementInterface::class));
    }

    public function test_it_supports_agreement(): void
    {
        $subject = new CompositeAgreementApprovalResolver([]);
        self::assertTrue($subject->supports($this->createMock(AgreementInterface::class)));
    }

    private function mockResolver(bool $supports, bool $resolve): object
    {
        $mock = $this->createMock(AgreementApprovalResolverInterface::class);
        $mock->method('supports')->willReturn($supports);
        $mock->method('resolve')->willReturn($resolve);

        return $mock;
    }

    public function resolveDataProvider(): array
    {
        return [
            [
                [
                    $this->mockResolver(false, true),
                    $this->mockResolver(true, false),
                    $this->mockResolver(true, true),
                ],
                false
            ],
            [
                [
                    $this->mockResolver(false, true),
                    $this->mockResolver(true, true),
                ],
                true
            ],
            [
                [
                    $this->mockResolver(true, true),
                ],
                true
            ],
        ];
    }
}
