<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusAgreementPlugin\Unit\Exception;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\Agreement;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use BitBag\SyliusAgreementPlugin\Exception\AgreementNotSupportedException;
use BitBag\SyliusAgreementPlugin\Resolver\AgreementApprovalResolverInterface;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AgreementNotSupportedExceptionTest extends TestCase
{
    public function test_it_throws_exception_when_agreement_is_not_supported()
    {
        $agreement = $this->mockAgreement();
        $agreementApprovalResolver = $this->mockAgreementApprovalResolver();

        $agreementApprovalResolver
            ->method('supports')
            ->with($agreement)
            ->willReturn(false);

        $agreementApprovalResolver
            ->method('resolve')
            ->with($agreement)
            ->willThrowException(new AgreementNotSupportedException(
                $agreement,'Agreement is not supported'));

        $this->expectExceptionObject(new AgreementNotSupportedException(
            $agreement, 'Agreement is not supported'));
        $agreementApprovalResolver->resolve($agreement);
    }

    /**
     * @return AgreementInterface|MockObject
     */
    private function mockAgreement(): object
    {
        return $this->createMock(AgreementInterface::class);
    }

    /**
     * @return AgreementApprovalResolverInterface|MockObject
     */
    private function mockAgreementApprovalResolver(): object
    {
        return $this->createMock(AgreementApprovalResolverInterface::class);
    }
}
