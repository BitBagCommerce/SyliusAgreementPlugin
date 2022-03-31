<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Resolver;

final class CompositeAgreementResolver implements AgreementResolverInterface
{
    /** @var AgreementResolverInterface[] */
    private iterable $agreementResolvers;

    public function __construct(iterable $agreementResolvers)
    {
        $this->agreementResolvers = $agreementResolvers;
    }

    public function resolve(string $context, array $options): array
    {
        $agreements = [];

        foreach ($this->agreementResolvers as $resolver) {
            if ($resolver->supports($context, $options)) {
                $agreements = array_merge($resolver->resolve($context, $options), $agreements);
            }
        }

        return $agreements;
    }

    public function supports(string $context, array $options): bool
    {
        return true;
    }
}
