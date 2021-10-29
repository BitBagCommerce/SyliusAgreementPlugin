<?php

namespace Tests\BitBag\SyliusAgreementPlugin\Unit\Exception;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\Agreement;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use BitBag\SyliusAgreementPlugin\Exception\AgreementNotSupportedException;
use BitBag\SyliusAgreementPlugin\Resolver\AgreementApprovalResolverInterface;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class AgreementNotSupportedExceptionTest extends TestCase
{
    public function testItThrowsException()
    {
        $agreement = $this->createMock(AgreementInterface::class);
        $agreementApprovalResolver = $this
            ->createMock(AgreementApprovalResolverInterface::class);

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
    public function testGetAgreement()
    {
        $agreement = new Agreement();
        $exception = new AgreementNotSupportedException($agreement);
        Assert::assertSame($agreement,$exception->getAgreement());
    }

}