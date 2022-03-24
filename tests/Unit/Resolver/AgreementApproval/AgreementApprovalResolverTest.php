<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusAgreementPlugin\Unit\Resolver\AgreementApproval;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementHistoryInterface;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementHistoryStates;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use BitBag\SyliusAgreementPlugin\Resolver\AgreementApproval\AgreementApprovalResolver;
use BitBag\SyliusAgreementPlugin\Resolver\AgreementHistoryResolverInterface;
use PHPUnit\Framework\TestCase;

final class AgreementApprovalResolverTest extends TestCase
{
    /**
     * @dataProvider resolveDataProvider
     */
    public function test_it_resolves_history_correctly(string $agreementHistoryState, bool $result): void
    {
        $agreement = $this->createMock(AgreementInterface::class);

        $agreementHistory = $this->createMock(AgreementHistoryInterface::class);
        $agreementHistory
            ->expects(self::once())
            ->method('getState')
            ->willReturn($agreementHistoryState);

        $historyResolver = $this->createMock(AgreementHistoryResolverInterface::class);
        $historyResolver
            ->expects(self::once())
            ->method('resolveHistory')
            ->with($agreement)
            ->willReturn($agreementHistory);

        $subject = new AgreementApprovalResolver($historyResolver);
        self::assertEquals($result, $subject->resolve($agreement));
    }

    /**
     * @dataProvider supportsDataProvider
     */
    public function test_it_supports_correct_agreement(bool $historyResolverSupports, bool $supports): void
    {
        $agreement = $this->createMock(AgreementInterface::class);

        $historyResolver = $this->createMock(AgreementHistoryResolverInterface::class);
        $historyResolver
            ->expects(self::once())
            ->method('supports')
            ->with($agreement)
            ->willReturn($historyResolverSupports);

        $subject = new AgreementApprovalResolver($historyResolver);
        self::assertEquals($supports, $subject->supports($agreement));
    }

    public function supportsDataProvider(): array
    {
        return [
            [true, true],
            [false, false]
        ];
    }

    public function resolveDataProvider(): array
    {
        return [
            [AgreementHistoryStates::STATE_ACCEPTED, true],
            [AgreementHistoryStates::STATE_ASSIGNED, false],
        ];
    }
}
