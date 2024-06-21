<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Menu;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuListener
{
    public function addAgreementItems(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        $configurationMenu = $menu->getChild('configuration');
        if (null !== $configurationMenu) {
            $configurationMenu
                ->addChild('agreement', ['route' => 'bitbag_sylius_agreement_plugin_admin_agreement_index'])
                ->setLabel('bitbag_sylius_agreement_plugin.ui.agreements')
                ->setLabelAttribute('icon', 'file alternate outline');
        }
    }
}
