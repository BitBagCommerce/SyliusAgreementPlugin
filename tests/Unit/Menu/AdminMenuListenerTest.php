<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusAgreementPlugin\Unit\Menu;

use BitBag\SyliusAgreementPlugin\Menu\AdminMenuListener;
use Knp\Menu\ItemInterface;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuListenerTest extends TestCase
{
    public function testAddAgreementItems(): void
    {
        $event = $this->createMock(MenuBuilderEvent::class);
        $menu = $this->createMock(ItemInterface::class);

        $event->expects(self::once())->method('getMenu')->willReturn($menu);

        $configurationMenu = $this->createMock(ItemInterface::class);
        $menu->expects(self::once())->method('getChild')->with('configuration')->willReturn($configurationMenu);

        $configurationMenu
            ->expects(self::once())
            ->method('addChild')
            ->with('agreement', ['route' => 'bitbag_sylius_agreement_plugin_admin_agreement_index'])
            ->willReturnSelf();
        $configurationMenu
            ->expects(self::once())
            ->method('setLabel')
            ->with('bitbag_sylius_agreement_plugin.ui.agreements')
            ->willReturnSelf();
        $configurationMenu
            ->expects(self::once())
            ->method('setLabelAttribute')
            ->with('icon', 'file alternate outline')
            ->willReturnSelf();

        $subject = new AdminMenuListener();
        $subject->addAgreementItems($event);
    }
}
