<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('bitbag_sylius_agreement_plugin');
        $rootNode = $treeBuilder->getRootNode();
        /** @phpstan-ignore-next-line  */
        $rootNode
            ->children()
            ->arrayNode('extended_form_types')
            ->scalarPrototype()
            ->end()
            ->end()
            ->arrayNode('modes')
            ->scalarPrototype()
            ->end()
            ->end()
            ->arrayNode('contexts')
            ->scalarPrototype()
            ->end()
            ->end();

        return $treeBuilder;
    }
}
