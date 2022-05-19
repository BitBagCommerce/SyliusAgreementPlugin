<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

final class BitBagSyliusAgreementExtension extends Extension
{
    public function getConfiguration(array $config, ContainerBuilder $container): ConfigurationInterface
    {
        return new Configuration();
    }

    /**
     * @psalm-suppress UnusedVariable
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration($this->getConfiguration([], $container), $configs);
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        $formTypes = $this->prepareExtendedFormTypes($config['contexts']);

        $container->setParameter('sylius_agreement_plugin.extended_form_types', $formTypes);
        $container->setParameter('sylius_agreement_plugin.modes', $config['modes']);
        $container->setParameter('sylius_agreement_plugin.contexts', $config['contexts']);
    }

    private function prepareExtendedFormTypes(array $contexts): array
    {
        $extendedFormTypes = [];

        foreach ($contexts as $formTypes) {
            foreach ($formTypes as $formType) {
                $extendedFormTypes[] = $formType;
            }
        }

        return $extendedFormTypes;
    }
}
