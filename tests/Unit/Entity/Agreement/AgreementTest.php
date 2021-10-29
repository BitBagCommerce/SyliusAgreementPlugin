<?php

namespace Tests\BitBag\SyliusAgreementPlugin\Unit\Entity\Agreement;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\Agreement;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementTranslation;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementTranslationInterface;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class AgreementTest extends TestCase
{

    public function testGetId()
    {
        $agreement = new Agreement();
        $agreement->setCode('test1');
        Assert::assertSame($agreement->getCode(),'test1');
    }

    public function testGetMode()
    {
        $agreement = new Agreement();
        $agreement->setMode('test2');
        Assert::assertSame($agreement->getMode(),'test2');
        $agreement->setMode(Agreement::MODE_ONLY_SHOW);
        Assert::assertSame($agreement->isReadOnly(),true);
    }

    public function testGetPublishedAt()
    {
        $time = new \DateTime();
        $agreement = new Agreement();
        $agreement->setPublishedAt($time->setTime(12,24,48));
        Assert::assertSame($agreement->getPublishedAt(),$time->setTime(12,24,48));
    }

    public function testGetContexts()
    {
        $agreement = new Agreement();
        $agreement->setContexts(['test4','5']);
        Assert::assertSame($agreement->getContexts(),['test4','5']);
    }

    public function testSetParent()
    {
        $agreement = new Agreement();
        $agreementParent = new Agreement();

        $agreement->setParent($agreementParent);
        Assert::assertSame($agreementParent, $agreement->getParent());
    }

    public function testGetOrderOnView()
    {
        $agreement = new Agreement();
        $agreement->setOrderOnView(5);

        Assert::assertSame($agreement->getOrderOnView(),5);
    }

    public function testIsApproved()
    {
        $agreement = new Agreement();
        $agreement->setApproved(true);
        Assert::assertSame($agreement->isApproved(),true);
    }
    public function testGetArchived()
    {
        $time = new \DateTime();
        $agreement = new Agreement();
        $agreement->setArchivedAt($time->setTime(12,24,48));
        Assert::assertSame($agreement->getArchivedAt(),$time->setTime(12,24,48));
    }

    public function testSetEdiumAgreementType()
    {
        $agreement = new Agreement();
        $ediumAgreementType = "test1";
        $agreement->setEdiumAgreementType($ediumAgreementType);
        Assert::assertSame($agreement->getEdiumAgreementType(),$ediumAgreementType);
    }
    
}