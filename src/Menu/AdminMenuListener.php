<?php

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
                ->setLabel('sylius.ui.agreements')
                ->setLabelAttribute('icon', 'file alternate outline');
        }
    }
}
