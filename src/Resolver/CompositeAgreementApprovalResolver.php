<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Resolver;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use BitBag\SyliusAgreementPlugin\Exception\AgreementNotSupportedException;

final class CompositeAgreementApprovalResolver implements AgreementApprovalResolverInterface
{
    /** @var AgreementApprovalResolverInterface[] */
    private iterable $agreementApprovalResolvers;

    public function __construct(iterable $agreementApprovalResolvers)
    {
        $this->agreementApprovalResolvers = $agreementApprovalResolvers;
    }

    public function resolve(AgreementInterface $agreement): bool
    {
        foreach ($this->agreementApprovalResolvers as $agreementApprovalResolver) {
            if ($agreementApprovalResolver->supports($agreement)) {
                return $agreementApprovalResolver->resolve($agreement);
            }
        }

        throw new AgreementNotSupportedException($agreement);
    }

    public function supports(AgreementInterface $agreement): bool
    {
        return true;
    }
}
