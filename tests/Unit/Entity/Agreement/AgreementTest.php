<?php

namespace BitBag\SyliusAgreementPlugin\Tests\Unit\Entity\Agreement;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\Agreement;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class AgreementTest extends TestCase
{

    function testGetId()
    {
        $agreement = new Agreement();
        $agreement->setCode('test1');
        Assert::assertSame($agreement->getCode(),'test1');
    }

    function testGetMode()
    {
        $agreement = new Agreement();
        $agreement->setMode('test2');
        Assert::assertSame($agreement->getMode(),'test2');
        $agreement->setMode(Agreement::MODE_ONLY_SHOW);
        Assert::assertSame($agreement->isReadOnly(),true);
    }

    function testGetPublishedAt()
    {
        $time = new \DateTime();
        $agreement = new Agreement();
        $agreement->setPublishedAt($time->setTime(12,24,48));
        Assert::assertSame($agreement->getPublishedAt(),$time->setTime(12,24,48));
    }

    function testGetContexts()
    {
        $agreement = new Agreement();
        $agreement->setContexts(['test4','5']);
        Assert::assertSame($agreement->getContexts(),['test4','5']);
    }

    function testSetParent()
    {
        $agreement = new Agreement();
        $agreementParent = new Agreement();

        $agreement->setParent($agreementParent);
        Assert::assertSame($agreementParent, $agreement->getParent());
    }

    function testGetOrderOnView()
    {
        $agreement = new Agreement();
        $agreement->setOrderOnView(5);

        Assert::assertSame($agreement->getOrderOnView(),5);
    }

    function testIsApproved()
    {
        $agreement = new Agreement();
        $agreement->setApproved(true);
        Assert::assertSame($agreement->isApproved(),true);
    }
    function testGetArchived()
    {
        $time = new \DateTime();
        $agreement = new Agreement();
        $agreement->setArchivedAt($time->setTime(12,24,48));
        Assert::assertSame($agreement->getArchivedAt(),$time->setTime(12,24,48));
    }

    function testSetEdiumAgreementType()
    {
        $agreement = new Agreement();
        $ediumAgreementType = "test1";
        $agreement->setEdiumAgreementType($ediumAgreementType);
        Assert::assertSame($agreement->getEdiumAgreementType(),$ediumAgreementType);
    }
}