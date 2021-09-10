<?php

declare(strict_types=1);

namespace BitBag\SyliusAgreementPlugin\CommandHandler\Core\Resource\Agreement;

use BitBag\SyliusAgreementPlugin\CommandHandler\Core\Resource\SingleResourceUpdateHandlerInterface;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Webmozart\Assert\Assert;

final class AgreementUpdateHandler implements SingleResourceUpdateHandlerInterface
{
    /** @var EntityManagerInterface */
    private $agreementManager;

    public function __construct(EntityManagerInterface $agreementManager)
    {
        $this->agreementManager = $agreementManager;
    }

    public function supports(ResourceInterface $resource): bool
    {
        if ($resource instanceof AgreementInterface) {
            $changeSets = [];
            $uow = $this->agreementManager->getUnitOfWork();
            $uow->computeChangeSets();
            foreach ($resource->getTranslations() as $translation) {
                $changeSets[] = $uow->getEntityChangeSet($translation);
            }
            if (!empty(array_filter($changeSets))) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param ResourceInterface|AgreementInterface $resource
     */
    public function handle(ResourceInterface $resource): void
    {
        Assert::isInstanceOf($resource, AgreementInterface::class);
        $manager = $this->agreementManager;
        $connection = $manager->getConnection();
        $connection->beginTransaction();

        try {
            $code = $resource->getCode();
            $resource->setCode(null);
            $resource->disable();
            $manager->flush();
            $clonedResource = clone $resource;
            $clonedResource->setParent($resource);
            $clonedResource->setCode($code);
            $clonedResource->enable();
            $manager->persist($clonedResource);
            $manager->flush();
            $connection->commit();
        } catch (Exception $exception) {
            $connection->rollBack();
        }
    }
}
