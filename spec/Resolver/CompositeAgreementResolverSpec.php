<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusAgreementPlugin\Resolver;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementInterface;
use BitBag\SyliusAgreementPlugin\Repository\AgreementRepositoryInterface;
use BitBag\SyliusAgreementPlugin\Resolver\CompositeAgreementResolver;
use PhpSpec\ObjectBehavior;

final class CompositeAgreementResolverSpec extends ObjectBehavior
{
    function let(
        AgreementRepositoryInterface $agreementRepository
    ): void {
        $this->beConstructedWith(
            $agreementRepository
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(CompositeAgreementResolver::class);
    }

    function it_resolves_correctly(
        AgreementRepositoryInterface $agreementRepository,
        AgreementInterface $agreement
    ): void {
        $agreementRepository->findAgreementsByContext('checkout_form')->willReturn([$agreement]);

        $this->resolve('checkout_form', [])->shouldReturn([$agreement]);
    }

}
