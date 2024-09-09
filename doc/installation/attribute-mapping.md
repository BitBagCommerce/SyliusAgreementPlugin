# Attribute-mapping

Check the mapping settings in `config/packages/doctrine.yaml` and, if necessary, change them accordingly.
```yaml
doctrine:
    ...
    orm:
        entity_managers:
            default:
                ...
                mappings:
                    App:
                        ...
                        type: attribute
```

Extend entities with parameters and methods using attributes and traits:

- `Customer` entity:

```php
<?php
// src/Entity/Customer/Customer.php

declare(strict_types=1);

namespace App\Entity\Customer;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementsRequiredInterface;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementsRequiredTrait;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\Customer as BaseCustomer;
use Sylius\Component\Core\Model\CustomerInterface as BaseCustomerInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_customer")
 */
#[ORM\Entity]
#[ORM\Table(name: 'sylius_customer')]
class Customer extends BaseCustomer implements BaseCustomerInterface, AgreementsRequiredInterface
{
    use AgreementsRequiredTrait;
}
```
