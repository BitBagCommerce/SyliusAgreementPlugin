# XML-mapping

Check the mapping settings in `config/packages/doctrine.yaml` and, if necessary, change them accordingly.
```yaml
doctrine:
    ...
doctrine:
    ...
    orm:
        entity_managers:
            default:
                ...
                mappings:
                    App:
                type: xml
                dir: '%kernel.project_dir%/src/Resources/config/doctrine'
```

- `Customer` entity:

```php
<?php
// src/Entity/Customer/Customer.php

declare(strict_types=1);

namespace App\Entity\Customer;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementsRequiredInterface;
use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementsRequiredTrait;
use Sylius\Component\Core\Model\Customer as BaseCustomer;
use Sylius\Component\Core\Model\CustomerInterface as BaseCustomerInterface;

class Customer extends BaseCustomer implements  BaseCustomerInterface, AgreementsRequiredInterface
{
    use AgreementsRequiredTrait;
}
```

Define new Entity mapping inside `src/Resources/config/doctrine` directory.

- `Customer` entity:

`src/Resources/config/doctrine/Customer.orm.xml`

```xml
<?xml version="1.0" encoding="UTF-8"?>

<doctrine-mapping xmlns="http://doctrine-project.org/schemas/orm/doctrine-mapping"
                  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                  xsi:schemaLocation="http://doctrine-project.org/schemas/orm/doctrine-mapping
                                      http://doctrine-project.org/schemas/orm/doctrine-mapping.xsd">
    <entity name="App\Entity\Customer"
            table="sylius_customer">

    </entity>
</doctrine-mapping>
```
