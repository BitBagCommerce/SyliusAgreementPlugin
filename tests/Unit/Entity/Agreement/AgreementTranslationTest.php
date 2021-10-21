<?php

namespace BitBag\SyliusAgreementPlugin\Tests\Unit\Entity\Agreement;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementTranslation;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class AgreementTranslationTest extends TestCase
{

    function testGetName()
    {
        $agreementTranslation = new AgreementTranslation();
        $agreementTranslation->setName('test');

        Assert::assertSame($agreementTranslation->getName(),'test');
    }
    function testGetBody()
    {
        $agreementTranslation = new AgreementTranslation();
        $agreementTranslation->setBody('test');

        Assert::assertSame($agreementTranslation->getBody(),'test');
    }
    function testGetExtendedBody()
    {
        $agreementTranslation = new AgreementTranslation();
        $agreementTranslation->setExtendedBody('test');

        Assert::assertSame($agreementTranslation->getExtendedBody(),'test');

    }

}