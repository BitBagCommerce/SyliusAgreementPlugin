<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusAgreementPlugin\Unit\Resolver\AgreementHistory;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementHistory;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use BitBag\SyliusAgreementPlugin\Repository\AgreementHistoryRepositoryInterface;
use BitBag\SyliusAgreementPlugin\Resolver\AgreementHistory\ShopUserAgreementHistoryResolver;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\ShopUserInterface;
use Symfony\Component\Security\Core\Security;

final class ShopUserAgreementHistoryResolverTest extends TestCase
{
    /**
     * @dataProvider resolveHistoryDataProvider
     */
    public function testResolveHistory(?int $agreementId = null, ?int $shopUserId = null, ?object $agreementHistory = null, ?object $output = null): void
    {
        $security = $this->createMock(Security::class);
        $agreementHistoryRepository = $this->createMock(AgreementHistoryRepositoryInterface::class);

        $agreement = $this->createMock(AgreementInterface::class);
        $agreement->expects(self::once())->method('getId')->willReturn($agreementId);

        $shopUser = $this->createMock(ShopUserInterface::class);
        $shopUser->expects(self::atMost(1))->method('getId')->willReturn($shopUserId);

        $agreementHistoryRepository
            ->method('findOneForShopUser')
            ->with($agreement, $shopUser)
            ->willReturn($agreementHistory);
        $security->expects(self::once())->method('getUser')->willReturn($shopUser);

        $subject = new ShopUserAgreementHistoryResolver($security, $agreementHistoryRepository);
        self::assertEquals($output, $subject->resolveHistory($agreement));
    }

    public function resolveHistoryDataProvider(): array
    {
        $agreement = $this->createMock(AgreementHistory::class);
        return [
          [1, 2, $agreement, $agreement],
          [1, null, $agreement, null],
          [null, 2, $agreement, null],
          [1, 2, $agreement, $agreement],
          [null, null, null, null],
        ];
    }

    public function testSupports(): void
    {
        $security = $this->createMock(Security::class);
        $agreementHistoryRepository = $this->createMock(AgreementHistoryRepositoryInterface::class);

        $agreement = $this->createMock(AgreementInterface::class);
        $subject = new ShopUserAgreementHistoryResolver($security, $agreementHistoryRepository);
        self::assertFalse($subject->supports($agreement));

        $shopUser = $this->createMock(ShopUserInterface::class);
        $security->expects(self::once())->method('getUser')->willReturn($shopUser);
        self::assertTrue($subject->supports($agreement));
    }
}
