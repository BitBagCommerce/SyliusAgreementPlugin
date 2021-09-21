<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Resolver;

final class CompositeAgreementResolver implements AgreementResolverInterface
{
    /**
     * @var AgreementResolverInterface[]
     */
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
