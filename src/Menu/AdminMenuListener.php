<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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
