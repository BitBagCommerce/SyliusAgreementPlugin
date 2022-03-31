<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusAgreementPlugin\Unit\EventSubscriber;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use BitBag\SyliusAgreementPlugin\EventSubscriber\AgreementSubscriber;
use BitBag\SyliusAgreementPlugin\Repository\AgreementHistoryRepositoryInterface;
use BitBag\SyliusAgreementPlugin\Resolver\AgreementHistoryResolverInterface;
use BitBag\SyliusAgreementPlugin\Resolver\AgreementResolverInterface;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Core\Model\ShopUserInterface;
use Tests\BitBag\SyliusAgreementPlugin\Entity\Customer\CustomerInterface;

final class AgreementSubscriberTest extends TestCase
{
    public function test_it_throws_exception_when_customer_user_is_wrong_object(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $subscriber = new AgreementSubscriber(
            $this->mockAgreementHistoryRepository(),
            $this->mockAgreementResolver(),
            $this->mockAgreementHistoryResolver()
        );

        $subscriber->processAgreementsFromUserRegister(new ResourceControllerEvent(new \stdClass()));
    }

    public function test_it_throws_exception_when_shop_user_is_null(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $subscriber = new AgreementSubscriber(
            $this->mockAgreementHistoryRepository(),
            $this->mockAgreementResolver(),
            $this->mockAgreementHistoryResolver()
        );

        $customer = $this->mockCustomer();

        $customer
            ->expects(self::once())
            ->method('getUser')
            ->willReturn(null);

        $subscriber->processAgreementsFromUserRegister(new ResourceControllerEvent($customer));
    }

    public function test_it_processes_agreements_correctly(): void
    {
        $customer = $this->mockCustomer();
        $shopUser = $this->mockShopUser();
        $agreementsCollection = $this->mockCollectionOfAgreements();

        $subscriber = new AgreementSubscriber(
            $this->mockAgreementHistoryRepository(),
            $this->mockAgreementResolver(),
            $this->mockAgreementHistoryResolver()
        );

        $customer
            ->expects(self::once())
            ->method('getUser')
            ->willReturn($shopUser);

        $customer
            ->expects(self::once())
            ->method('getAgreements')
            ->willReturn($agreementsCollection);

        $subscriber->processAgreementsFromUserRegister(new ResourceControllerEvent($customer));
    }

    public function test_it_subscribe_correct_events(): void
    {
        self::assertEquals([
            'sylius.customer.post_register' => [
                ['processAgreementsFromUserRegister', -5],
            ]
        ], AgreementSubscriber::getSubscribedEvents());
    }

    /**
     * @return ShopUserInterface|MockObject
     */
    private function mockShopUser(): object
    {
        return $this->createMock(ShopUserInterface::class);
    }

    /**
     * @return AgreementResolverInterface|MockObject
     */
    private function mockAgreementResolver(): object
    {
        return $this->createMock(AgreementResolverInterface::class);
    }

    /**
     * @return CustomerInterface|MockObject
     */
    private function mockCustomer(): object
    {
        return $this->createMock(CustomerInterface::class);
    }

    /**
     * @return AgreementHistoryRepositoryInterface|MockObject
     */
    private function mockAgreementHistoryRepository(): object
    {
        return $this->createMock(AgreementHistoryRepositoryInterface::class);
    }

    /**
     * @return AgreementHistoryResolverInterface|MockObject
     */
    private function mockAgreementHistoryResolver(): object
    {
        return $this->createMock(AgreementHistoryResolverInterface::class);
    }

    /**
     * @return ArrayCollection|MockObject[]|AgreementInterface[]
     */
    private function mockCollectionOfAgreements(): ArrayCollection
    {
        $collection = new ArrayCollection();

        for ($i = 1; 5 >= $i; ++$i) {
            $agreement = $this->mockAgreement();

            if (5 === $i) {
                $agreement
                    ->method('isApproved')
                    ->willReturn(false);
            } else {
                $agreement
                    ->method('isApproved')
                    ->willReturn(true);
            }

            $agreement
                ->method('getId')
                ->willReturn($i);
            $collection->set($i, $agreement);
        }

        return $collection;
    }

    /**
     * @return AgreementInterface|MockObject
     */
    private function mockAgreement(): object
    {
        return $this->createMock(AgreementInterface::class);
    }
}
