<?php

namespace BitBag\SyliusAgreementPlugin\Tests\Unit\EventSubscriber;


use Tests\BitBag\SyliusAgreementPlugin\Entity\Customer\CustomerInterface;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\Agreement;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementContexts;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementHistoryInterface;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementHistoryStates;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use BitBag\SyliusAgreementPlugin\EventSubscriber\AgreementSubscriber;
use BitBag\SyliusAgreementPlugin\Repository\AgreementHistoryRepositoryInterface;
use BitBag\SyliusAgreementPlugin\Resolver\AgreementHistoryResolverInterface;
use BitBag\SyliusAgreementPlugin\Resolver\AgreementResolverInterface;
use Closure;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Core\Model\ShopUserInterface;
use PHPUnit\Framework\Assert;


class AgreementSubscriberTest extends TestCase
{
    /** @var mixed|\PHPUnit\Framework\MockObject\MockObject|ResourceControllerEvent*/
    private $resourceControllerEvent;

    /** @var AgreementHistoryRepositoryInterface|mixed|\PHPUnit\Framework\MockObject\MockObject */
    private $agreementHistoryRepository;

    /**@var AgreementResolverInterface|mixed|\PHPUnit\Framework\MockObject\MockObject*/
    private $agreementResolver;

    /** @var AgreementHistoryResolverInterface|mixed|\PHPUnit\Framework\MockObject\MockObject */
    private $agreementHistoryResolver;

    /** @var AgreementSubscriber */
    private AgreementSubscriber $agreementSubscriber;
    /**
     * @var CustomerInterface|mixed|\PHPUnit\Framework\MockObject\MockObject
     */
    private $customer;
    /**
     * @var mixed|\PHPUnit\Framework\MockObject\MockObject|ShopUserInterface
     */
    private $shopUser;
    /**
     * @var AgreementHistoryInterface|mixed|\PHPUnit\Framework\MockObject\MockObject
     */
    private $agreementHistory;
    /**
     * @var ArrayCollection|mixed|\PHPUnit\Framework\MockObject\MockObject
     */
    private $arrayCollection;
    /**
     * @var AgreementInterface|mixed|\PHPUnit\Framework\MockObject\MockObject
     */
    private $submittedAgreement;
    /**
     * @var AgreementInterface|mixed|\PHPUnit\Framework\MockObject\MockObject
     */
    private $resolvedAgreement;


    public function setUp(): void
    {   $this->agreementHistory = $this
        ->createMock(AgreementHistoryInterface::class);
        $this->agreementHistoryRepository = $this
             ->createMock(AgreementHistoryRepositoryInterface::class);
         $this->agreementResolver = $this
             ->createMock(AgreementResolverInterface::class);
         $this->agreementHistoryResolver = $this
             ->createMock(AgreementHistoryResolverInterface::class);
         $this->resourceControllerEvent = $this
             ->createMock(ResourceControllerEvent::class);
         $this->customer = $this
             ->createMock(CustomerInterface::class);
         $this->shopUser = $this
             ->createMock(ShopUserInterface::class);
         $this->arrayCollection = $this
             ->createMock(ArrayCollection::class);
         $this->submittedAgreement = $this
             ->createMock(AgreementInterface::class);
         $this->resolvedAgreement = $this
             ->createMock(AgreementInterface::class);
         $this->agreementSubscriber = new AgreementSubscriber(
             $this->agreementHistoryRepository,
             $this->agreementResolver,
             $this->agreementHistoryResolver
        );
    }
/** @dataProvider dataProviderprocessAgreementsFromUserRegister */
    public function testProcessAgreementFromUserRegister
    (
        string $agreementState,
        ArrayCollection $submittedAgreements
    )
    {
        $this->customer
            ->expects(self::exactly(1))
            ->method('getUser')
            ->willReturn($this->shopUser);

        $this->resourceControllerEvent
            ->expects(self::once())
            ->method('getSubject')
            ->willReturn($this->customer);

        $array = new ArrayCollection([new Agreement()]);

        $this->customer
           ->expects(self::once())
           ->method('getAgreements')
           ->willReturn($array);

        $agreement = $this->createMock(AgreementInterface::class);
        $this->agreementResolver
            ->expects(self::once())
            ->method('resolve')
            ->with(AgreementContexts::CONTEXT_REGISTRATION_FORM,[])
            ->willReturn([$agreement]);

        $this->agreementHistoryResolver
            ->expects(self::once())
            ->method('resolveHistory')
            ->with($agreement)
            ->willReturn($this->agreementHistory);

        $this->arrayCollection
            ->method('filter')
            ->with(self::isInstanceOf(Closure::class))
            ->willReturn($submittedAgreements);

        $this->agreementHistory
            ->expects(self::atLeast(1))
            ->method('getId')
            ->willReturn(null);

        $this->agreementHistory
            ->expects(self::once())
            ->method('setContext')
            ->with(AgreementContexts::CONTEXT_REGISTRATION_FORM);

        $this->agreementHistory
            ->expects(self::once())
            ->method('setShopUser')
            ->with($this->shopUser);

        $this->agreementHistory
            ->expects(self::once())
            ->method('setOrder')
            ->with(null);

        $this->agreementHistory
            ->expects(self::once())
            ->method('setAgreement')
            ->with($this->resolvedAgreement);

        $this->agreementHistory
            ->expects(self::once())
            ->method('getState')
            ->willReturn($agreementState);

        $this->agreementHistory
            ->expects(self::once())
            ->method('setState')
            ->with($agreementState);

        $this->agreementHistoryRepository
            ->expects(self::once())
            ->method('add')
            ->with($this->agreementHistory);

        $this->agreementSubscriber
            ->processAgreementsFromUserRegister($this->resourceControllerEvent);
    }
    public function testGetSubscribedEvents()
    {
        Assert::assertSame($this->agreementSubscriber::getSubscribedEvents(), [
            'sylius.customer.post_register' => [
                ['processAgreementsFromUserRegister', -5],
            ]]);
    }

    public function dataProviderprocessAgreementsFromUserRegister()
    {
       return[
           [
               AgreementHistoryStates::STATE_SHOWN,
               new ArrayCollection([new Agreement()])
           ],
           [
               AgreementHistoryStates::STATE_SHOWN,
               new ArrayCollection([])
           ]

            ];
    }




}