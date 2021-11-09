<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusAgreementPlugin\Unit\Menu;

use BitBag\SyliusAgreementPlugin\Menu\AdminMenuListener;
use Knp\Menu\ItemInterface;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuListenerTest extends TestCase
{
    /**
     * @var mixed|\PHPUnit\Framework\MockObject\MockObject|MenuBuilderEvent
     */
    private $event;
    /**
     * @var ItemInterface|mixed|\PHPUnit\Framework\MockObject\MockObject
     */
    private $configurationMenu;
    /**
     * @var ItemInterface|mixed|\PHPUnit\Framework\MockObject\MockObject
     */
    private $menu;

    public function setUp(): void
    {
        $this->event = $this->createMock(MenuBuilderEvent::class);
        $this->menu = $this->createMock(ItemInterface::class);
        $this->configurationMenu = $this->createMock(ItemInterface::class);

    }


    public function testAddAgreementItems(): void
    {


        $this->event->expects(self::once())->method('getMenu')->willReturn($this->menu);


        $this->menu->expects(self::once())->method('getChild')->with('configuration')->willReturn($this->configurationMenu);

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
