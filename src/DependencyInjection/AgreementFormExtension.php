<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\DependencyInjection;

use BitBag\SyliusAgreementPlugin\Form\Extension\AgreementsTypeExtension;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class AgreementFormExtension implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        /** @var array $contexts */
        $contexts = $container->getParameter('sylius_agreement_plugin.contexts');

        foreach ($contexts as $types) {
            foreach ($types as $type) {
                $service = new Definition(AgreementsTypeExtension::class);
                $id = sprintf('bitbag_sylius_agreement_plugin.form.extension.agreements_type_extension_%s', str_replace(['/', '\\'], '_', $type));

                $service->addArgument(new Reference('bitbag_sylius_agreement_plugin.resolver.agreement'));
                $service->addArgument(new Reference('bitbag_sylius_agreement_plugin.resolver.agreement_approval'));
                $service->addArgument('%sylius_agreement_plugin.contexts%');
                $service->addArgument(new Reference('event_dispatcher'));
                $service->addTag('form.type_extension', ['extended_type' => $type]);

                $container->setDefinition($id, $service);
            }
        }
    }
}
