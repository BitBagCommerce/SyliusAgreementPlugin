# [![](https://bitbag.io/wp-content/uploads/2022/03/SyliusAgreementPlugin.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_agreement)

# BitBagSyliusAgreementPlugin

----

[ ![](https://img.shields.io/github/actions/workflow/status/BitBagCommerce/SyliusAgreementPlugin/build.yml?branch=master) ](https://github.com/BitBagCommerce/SyliusAgreementPlugin/actions "Build status")
[![Support](https://img.shields.io/badge/support-contact%20author-blue])](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_braintree)

----

<p>
 <img align="left" src="https://sylius.com/assets/badge-approved-by-sylius.png" width="85">
</p>

At BitBag we do believe in open source. However, we are able to do it just because of our awesome clients, who are kind enough to share some parts of our work with the community. Therefore, if you feel like there is a possibility for us to work  together, feel free to reach out. You will find out more about our professional services, technologies, and contact details at [https://bitbag.io/](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_cms).

Like what we do? Want to join us? Check out our job listings on our [career page](https://bitbag.io/career/?utm_source=github&utm_medium=referral&utm_campaign=career). Not familiar with Symfony & Sylius yet, but still want to start with us? Join our [academy](https://bitbag.io/pl/akademia?utm_source=github&utm_medium=url&utm_campaign=akademia)!

## Table of Content

***

* [Overview](#overview)
* [Support](#we-are-here-to-help)
* [Installation](#installation)
* [Usage](#usage)
* [Testing](#testing)
* [About us](#about-us)
    * [Community](#community)
* [Demo](#demo)
* [License](#license)
* [Contact](#contact)

# Overview

***

The SyliusAgreementPlugin adds the functionality of defining, managing and attaching the agreement clauses and checkboxes to forms in your Sylius store.

## We are here to help
This **open-source plugin was developed to help the Sylius community**. If you have any additional questions, would like help with installing or configuring the plugin, or need any assistance with your Sylius project - let us know!

[![](https://bitbag.io/wp-content/uploads/2020/10/button-contact.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_cms)

## Installation

***

1. Add the plugin to your project using:

```bash
$ composer require bitbag/agreement-plugin --no-scripts
```

2. Add plugin dependencies to your config/bundles.php file:

```php
return [
    ...

    BitBag\SyliusAgreementPlugin\BitBagSyliusAgreementPlugin::class => ['all' => true],
];
```

3. Import routing in your `config/routes.yaml` file:
```yaml

# config/routes.yaml
...

bitbag_sylius_agreement_plugin:
  resource: "@BitBagSyliusAgreementPlugin/Resources/config/routing.yml"
```

4. Import required config in your `config/packages/_sylius.yaml` file:
```yaml
# config/packages/_sylius.yaml

imports:
    ...
    
    - { resource: "@BitBagSyliusAgreementPlugin/Resources/config/config.yaml" }
```

5. Extend Customer entity:
```php
<?php

declare(strict_types=1);

namespace App\Entity\Customer;

use BitBag\SyliusAgreementPlugin\Entity\Agreement\AgreementsRequiredTrait;
use Tests\BitBag\SyliusAgreementPlugin\Entity\Customer\Customer as BaseCustomer;

class Customer extends BaseCustomer implements CustomerInterface
{
    use AgreementsRequiredTrait;
}
```

```php
<?php

namespace App\Entity\Customer;

use Tests\BitBag\SyliusAgreementPlugin\Entity\Customer\CustomerInterface as BaseCustomerInterface;

interface CustomerInterface extends BaseCustomerInterface
{
}
```

6. Update database schema:
##### Check for queries to execute
```
bin/console doctrine:schema:update --dump-sql
```
##### Execute database update
```
bin/console doctrine:schema:update --force
```

## Usage

***

1.Find a form/forms where you want to add agreements and set it in your config.yaml file, for example:
```yaml
bit_bag_sylius_agreement:
    contexts:
        registration_form:
            - Sylius\Bundle\CoreBundle\Form\Type\Customer\CustomerRegistrationType
        checkout_form:
            - Sylius\Bundle\CoreBundle\Form\Type\Checkout\AddressType
```

2.Find an entity which is used in your form and add trait to that class:
```php
use AgreementsRequiredTrait;
```

3.In the admin panel, create a new agreement and select context to it according to the configuration in the config.yaml file.

4.[Overwrite](https://symfony.com/doc/3.4/templating/overriding.html) templates used by yours extended form by adding:

````twig
{% for agreement in form.agreements %}
    {{ form_row(agreement.approved) }}
{% endfor %}
````
Examples are in the package you have downloaded under the path [tests/Application/templates/bundles/*](/tests/Application/templates/bundles/)

## Testing

***

```bash
$ composer install
$ cd tests/Application
$ yarn install
$ yarn build
$ bin/console doctrine:database:create --env=test 
$ bin/console doctrine:schema:create --env=test
$ bin/console sylius:fixtures:load --env=test
$ APP_ENV=test symfony server:start --dir=public/
$ cd ../..
$ vendor/bin/behat
```

# About us

---

BitBag is a company of people who **love what they do** and do it right. We fulfill the eCommerce technology stack with **Sylius**, Shopware, Akeneo, and Pimcore for PIM, eZ Platform for CMS, and VueStorefront for PWA. Our goal is to provide real digital transformation with an agile solution that scales with the **clients’ needs**. Our main area of expertise includes eCommerce consulting and development for B2C, B2B, and Multi-vendor Marketplaces.</br>
We are advisers in the first place. We start each project with a diagnosis of problems, and an analysis of the needs and **goals** that the client wants to achieve.</br>
We build **unforgettable**, consistent digital customer journeys on top of the **best technologies**. Based on a detailed analysis of the goals and needs of a given organization, we create dedicated systems and applications that let businesses grow.<br>
Our team is fluent in **Polish, English, German and, French**. That is why our cooperation with clients from all over the world is smooth.

**Some numbers from BitBag regarding Sylius:**
- 50+ **experts** including consultants, UI/UX designers, Sylius trained front-end and back-end developers,
- 120+ projects **delivered** on top of Sylius,
- 25+ **countries** of BitBag’s customers,
- 4+ **years** in the Sylius ecosystem.

**Our services:**
- Business audit/Consulting in the field of **strategy** development,
- Data/shop **migration**,
- Headless **eCommerce**,
- Personalized **software** development,
- **Project** maintenance and long term support,
- Technical **support**.

**Key clients:** Mollie, Guave, P24, Folkstar, i-LUNCH, Elvi Project, WestCoast Gifts.

---

If you need some help with Sylius development, don't be hesitated to contact us directly. You can fill the form on [this site](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_cms) or send us an e-mail at hello@bitbag.io!

---

[![](https://bitbag.io/wp-content/uploads/2021/08/sylius-badges-transparent-wide.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_cms)

## Community

---- 

For online communication, we invite you to chat with us & other users on [Sylius Slack](https://sylius-devs.slack.com/).

# Demo Sylius Shop

---

We created a demo app with some useful use-cases of plugins!
Visit [sylius-demo.bitbag.io](https://sylius-demo.bitbag.io/) to take a look at it. The admin can be accessed under
[sylius-demo.bitbag.io/admin/login](https://sylius-demo.bitbag.io/admin/login) link and `bitbag: bitbag` credentials.
Plugins that we have used in the demo:

| BitBag's Plugin | GitHub | Sylius' Store|
| ------ | ------ | ------|
| ACL Plugin | *Private. Available after the purchasing.*| https://plugins.sylius.com/plugin/access-control-layer-plugin/|
| Braintree Plugin | https://github.com/BitBagCommerce/SyliusBraintreePlugin |https://plugins.sylius.com/plugin/braintree-plugin/|
| CMS Plugin | https://github.com/BitBagCommerce/SyliusCmsPlugin | https://plugins.sylius.com/plugin/cmsplugin/|
| Elasticsearch Plugin | https://github.com/BitBagCommerce/SyliusElasticsearchPlugin | https://plugins.sylius.com/plugin/2004/|
| Mailchimp Plugin | https://github.com/BitBagCommerce/SyliusMailChimpPlugin | https://plugins.sylius.com/plugin/mailchimp/ |
| Multisafepay Plugin | https://github.com/BitBagCommerce/SyliusMultiSafepayPlugin |
| Wishlist Plugin | https://github.com/BitBagCommerce/SyliusWishlistPlugin | https://plugins.sylius.com/plugin/wishlist-plugin/|
| **Sylius' Plugin** | **GitHub** | **Sylius' Store** |
| Admin Order Creation Plugin | https://github.com/Sylius/AdminOrderCreationPlugin | https://plugins.sylius.com/plugin/admin-order-creation-plugin/ |
| Invoicing Plugin | https://github.com/Sylius/InvoicingPlugin | https://plugins.sylius.com/plugin/invoicing-plugin/ |
| Refund Plugin | https://github.com/Sylius/RefundPlugin | https://plugins.sylius.com/plugin/refund-plugin/ |

**If you need an overview of Sylius' capabilities, schedule a consultation with our expert.**

[![](https://bitbag.io/wp-content/uploads/2020/10/button_free_consulatation-1.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_cms)

## Additional resources for developers

---
To learn more about our contribution workflow and more, we encourage you to use the following resources:
* [Sylius Documentation](https://docs.sylius.com/en/latest/)
* [Sylius Contribution Guide](https://docs.sylius.com/en/latest/contributing/)
* [Sylius Online Course](https://sylius.com/online-course/)


## License

---

This plugin's source code is completely free and released under the terms of the MIT license.

[//]: # (These are reference links used in the body of this note and get stripped out when the markdown processor does its job. There is no need to format nicely because it shouldn't be seen.)

## Contact

---
If you want to contact us, the best way is to fill the form on [our website](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_cms) or send us an e-mail to hello@bitbag.io with your question(s). We guarantee that we answer as soon as we can!

[![](https://bitbag.io/wp-content/uploads/2021/08/badges-bitbag.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_cms)
