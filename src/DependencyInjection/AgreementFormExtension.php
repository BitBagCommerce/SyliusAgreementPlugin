<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
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
    public const TYPE_EXTENSION = 'bitbag_sylius_agreement_plugin.form.extension.agreements_type_extension';

    public function process(ContainerBuilder $container): void
    {
        /** @var array $contexts */
        $contexts = $container->getParameter('sylius_agreement_plugin.contexts');

        foreach ($contexts as $types) {
            foreach ($types as $type) {
                $service = new Definition(AgreementsTypeExtension::class);
                $id = sprintf(self::TYPE_EXTENSION . '_%s', str_replace(['/', '\\'], '_', $type));

                $service->addArgument(new Reference('bitbag_sylius_agreement_plugin.repository.agreement'));
                $service->addArgument(new Reference('bitbag_sylius_agreement_plugin.checker.agreement_history'));
                $service->addArgument('%sylius_agreement_plugin.contexts%');
                $service->addArgument(new Reference('event_dispatcher'));
                $service->addTag('form.type_extension', ['extended_type' => $type]);

                $container->setDefinition($id, $service);
            }
        }
    }
}
