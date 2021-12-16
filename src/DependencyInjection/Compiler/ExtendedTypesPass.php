<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ExtendedTypesPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $parameter = $container->getParameter('sylius_agreement_plugin.extended_form_types');
    }
}
