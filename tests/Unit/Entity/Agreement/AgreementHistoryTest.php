<?php

namespace BitBag\SyliusAgreementPlugin\Tests\Unit\Entity\Agreement;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\Agreement;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementHistory;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Model\Order;
use Sylius\Component\Core\Model\ShopUser;

class AgreementHistoryTest extends TestCase
{
    public function testGetId()
    {
        $agreementHistory = new AgreementHistory();
        Assert::assertSame($agreementHistory->getId(),null);
    }

    function testGetAgreement()
    {
        $agreement = new Agreement();
        $agreementHistory = new AgreementHistory();
        $agreementHistory->setAgreement($agreement);

        Assert::assertSame($agreementHistory->getAgreement(),$agreement);
    }
    function testGetShopUser()
    {
        $shopUser = new ShopUser();
        $agreementHistory = new AgreementHistory();
        $agreementHistory->setShopUser($shopUser);

        Assert::assertSame($agreementHistory->getShopUser(),$shopUser);
    }
    function testGetOrder()
    {
        $order = new Order();
        $agreementHistory = new AgreementHistory();
        $agreementHistory->setOrder($order);

        Assert::assertSame($agreementHistory->getOrder(),$order);
    }
    function testSetState()
    {
        $agreementHistory = new AgreementHistory();
        $agreementHistory->setState("test");

        Assert::assertSame($agreementHistory->getState(),"test");
    }
    function testGetContext()
    {
        $agreementHistory = new AgreementHistory();
        $agreementHistory->setContext('test');

        Assert::assertSame($agreementHistory->getContext(),'test');
    }

    public function testClone()
    {
        $agreementHistory = new AgreementHistory();
        $agreementHistory2 = clone $agreementHistory;
        Assert::assertEquals($agreementHistory2->getUpdatedAt(),null);
        Assert::assertEquals($agreementHistory2->getCreatedAt(),null);
    }




}