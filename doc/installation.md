# Installation

## Overview:
GENERAL
- [Requirements](#requirements)
- [Composer](#composer)
- [Basic configuration](#basic-configuration)
- [Usage](#usage)
--- 
BACKEND
- [Entities](#entities)
    - [Attribute mapping](#attribute-mapping)
    - [XML mapping](#xml-mapping)
---
FRONTEND
- [Templates](#templates)
---
ADDITIONAL
- [Tests](#tests)
- [Known Issues](#known-issues)
---

## Requirements:
We work on stable, supported and up-to-date versions of packages. We recommend you to do the same.

| Package       | Version         |
|---------------|-----------------|
| PHP           | \>= 8.1-8.2     |
| sylius/sylius | 1.12.x - 1.13.x |
| MySQL         | \>= 5.7         |
| NodeJS        | \>= 14.x        |

## Composer:
```bash
composer require bitbag/agreement-plugin --no-scripts
```

## Basic configuration:
Add plugin dependencies to your `config/bundles.php` file:

```php
# config/bundles.php

return [
    ...
    BitBag\SyliusAgreementPlugin\BitBagSyliusAgreementPlugin::class => ['all' => true],
];
```

Import required config in your `config/packages/_sylius.yaml` file:

```yaml
# config/packages/_sylius.yaml

imports:
    ...
    - { resource: "@BitBagSyliusAgreementPlugin/Resources/config/config.yaml" }
```

Add routing to your `config/routes.yaml` file:
```yaml
# config/routes.yaml

bitbag_sylius_agreement_plugin:
    resource: "@BitBagSyliusAgreementPlugin/Resources/config/routing.yml"
```

## Usage

### The following procedure is not fixed for all the functionalities the plugin offers.
- In other words, depending on where you want the agreement checkbox to appear, you need to extend other entities and make other configuration changes.

- The configuration for the `registration form` has already been added:
```
vendor/bitbag/agreement-plugin/src/Resources/config/config.yaml
```

#### You can extend or change it in the `config/_sylius.yaml` file, for example.
- example for the configuration of the following form:
```yaml
bit_bag_sylius_agreement:
    contexts:
        checkout_form:
            - Sylius\Bundle\CoreBundle\Form\Type\Checkout\AddressType
```

Add a translation for the new form, e.g. in `translations/messages.en.yaml`:
```yaml
bitbag_sylius_agreement_plugin:
    ui:
        agreement:
            contexts:
                checkout_form: Checkout form
```

### Clear application cache by using command:
```bash
bin/console cache:clear
```
---
### `In the admin panel, create a new agreement and select context.`
---

## Entities
### Example for the agreement checkbox on the `registration form`.
- Before you start extending entities, first determine what entity the form uses (in this case - `Customer`). Then add to the entity:
```php
use AgreementsRequiredTrait;
```
...and interfaces.

You can implement entity configuration by using both xml-mapping and attribute-mapping.
Depending on your preference, choose either one or the other:

### Attribute mapping
- [Attribute mapping configuration](installation/attribute-mapping.md)
### XML mapping
- [XML mapping configuration](installation/xml-mapping.md)

### Update your database
First, please run legacy-versioned migrations by using command:
```bash
bin/console doctrine:migrations:migrate
```

After migration, please create a new diff migration and update database:
```bash
bin/console doctrine:migrations:diff
bin/console doctrine:migrations:migrate
```
**Note:** If you are running it on production, add the `-e prod` flag to this command.

### Clear application cache by using command:
```bash
bin/console cache:clear
```
**Note:** If you are running it on production, add the `-e prod` flag to this command.


## Templates
To add the agreement checkbox to the registration form, you need to add following code to the template with form:
```php
{% for agreement in form.agreements %}
    {{ form_row(agreement.approved) }}
{% endfor %}
```

For `registration form` you can copy the template from the plugin:

**ShopBundle** (`templates/bundles/SyliusShopBundle`):
```
vendor/bitbag/agreement-plugin/tests/Application/templates/bundles/SyliusShopBundle/Register/_form.html.twig
```

## Tests
To run the tests, execute the commands:
```bash
composer install
cd tests/Application
yarn install
yarn build
bin/console doctrine:database:create --env=test
bin/console doctrine:schema:create --env=test
bin/console sylius:fixtures:load --env=test
APP_ENV=test symfony server:start --dir=public/
cd ../..
vendor/bin/behat
```

## Known issues
### Translations not displaying correctly
For incorrectly displayed translations, execute the command:
```bash
bin/console cache:clear
```

