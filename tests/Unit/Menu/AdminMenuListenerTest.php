<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusAgreementPlugin\Unit\Menu;

use BitBag\SyliusAgreementPlugin\Menu\AdminMenuListener;
use Knp\Menu\ItemInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuListenerTest extends TestCase
{
    /**
     * @var mixed|MockObject|MenuBuilderEvent
     */
    private $event;
    /**
     * @var ItemInterface|mixed|MockObject
     */
    private $configurationMenu;
    /**
     * @var ItemInterface|mixed|MockObject
     */
    private $menu;

    public function setUp(): void
    {
        $this->event = $this->createMock(MenuBuilderEvent::class);
        $this->menu = $this->createMock(ItemInterface::class);
        $this->configurationMenu = $this->createMock(ItemInterface::class);
    }

    public function test_it_adds_agreement_items(): void
    {
        $this->event
            ->expects(self::once())
            ->method('getMenu')
            ->willReturn($this->menu);

        $this->menu
            ->expects(self::once())
            ->method('getChild')
            ->with('configuration')
            ->willReturn($this->configurationMenu);

        $this->configurationMenu
            ->expects(self::once())
            ->method('addChild')
            ->with('agreement', ['route' => 'bitbag_sylius_agreement_plugin_admin_agreement_index'])
            ->willReturnSelf();

        $this->configurationMenu
            ->expects(self::once())
            ->method('setLabel')
            ->with('bitbag_sylius_agreement_plugin.ui.agreements')
            ->willReturnSelf();

        $this->configurationMenu
            ->expects(self::once())
            ->method('setLabelAttribute')
            ->with('icon', 'file alternate outline')
            ->willReturnSelf();

        $subject = new AdminMenuListener();
        $subject->addAgreementItems($this->event);
    }
}
