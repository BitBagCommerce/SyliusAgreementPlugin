<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\CommandHandler\Core\Resource\Agreement;

use BitBag\SyliusAgreementPlugin\CommandHandler\Core\Resource\SingleResourceDeleteHandlerInterface;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Webmozart\Assert\Assert;

final class AgreementDeleteHandler implements SingleResourceDeleteHandlerInterface
{
    /** @var EntityManagerInterface */
    private $agreementManager;

    public function __construct(EntityManagerInterface $agreementManager)
    {
        $this->agreementManager = $agreementManager;
    }

    public function supports(ResourceInterface $resource): bool
    {
        return $resource instanceof AgreementInterface;
    }

    /**
     * @param ResourceInterface|AgreementInterface $resource
     */
    public function handle(ResourceInterface $resource): void
    {
        Assert::isInstanceOf($resource, AgreementInterface::class);
        $resource->disable();
        $resource->setCode(null);
        $this->agreementManager->flush();
    }
}
