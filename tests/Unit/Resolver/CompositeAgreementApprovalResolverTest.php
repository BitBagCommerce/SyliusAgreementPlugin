<?php

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
    public function testResolve(array $resolvers, bool $result): void
    {
        $subject = new CompositeAgreementApprovalResolver($resolvers);

        self::assertEquals($result, $subject->resolve($this->createMock(AgreementInterface::class)));
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
    public function testResolveThrowsException(): void
    {
        self::expectException(AgreementNotSupportedException::class);
        $resolvers = [];

        $subject = new CompositeAgreementApprovalResolver($resolvers);
        $subject->resolve($this->createMock(AgreementInterface::class));
    }

    public function testSupports(): void
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
}
