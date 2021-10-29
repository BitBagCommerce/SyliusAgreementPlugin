<?php
declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\Tests\Unit\Entity\Agreement;

use Tests\BitBag\SyliusAgreementPlugin\Entity\Customer\Customer;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\Agreement;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class AgreementsRequiredTraitTest extends TestCase
{

    public function testAgreements()
    {
        $customer = new Customer();
        $agreements = new ArrayCollection([new Agreement()]);
        $customer->setAgreements($agreements);
        Assert::assertSame($customer->getAgreements(),$agreements);

    }

}