<?php

declare(strict_types=1);

/*
 * This file is part of the `liip/LiipImagineBundle` project.
 *
 * (c) https://github.com/liip/LiipImagineBundle/graphs/contributors
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace BitBag\SyliusAgreementPlugin\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ExtendedTypesPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $parameter = $container->getParameter('sylius_agreement_plugin.extended_form_types');
//        dd($parameter);
//        if (!$container->hasDefinition($liipImagineDriver)) {
//            throw new InvalidConfigurationException(sprintf("Specified driver '%s' is not defined.", $liipImagineDriver));
//        }
    }
}
