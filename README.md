MatomoBundle
===========
[![Latest Stable Version](https://poser.pugx.org/core23/matomo-bundle/v/stable)](https://packagist.org/packages/core23/matomo-bundle)
[![Latest Unstable Version](https://poser.pugx.org/core23/matomo-bundle/v/unstable)](https://packagist.org/packages/core23/matomo-bundle)
[![License](https://poser.pugx.org/core23/matomo-bundle/license)](https://packagist.org/packages/core23/matomo-bundle)

[![Total Downloads](https://poser.pugx.org/core23/matomo-bundle/downloads)](https://packagist.org/packages/core23/matomo-bundle)
[![Monthly Downloads](https://poser.pugx.org/core23/matomo-bundle/d/monthly)](https://packagist.org/packages/core23/matomo-bundle)
[![Daily Downloads](https://poser.pugx.org/core23/matomo-bundle/d/daily)](https://packagist.org/packages/core23/matomo-bundle)

[![Build Status](https://travis-ci.org/core23/MatomoBundle.svg)](https://travis-ci.org/core23/MatomoBundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/core23/MatomoBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/core23/MatomoBundle)
[![Code Climate](https://codeclimate.com/github/core23/MatomoBundle/badges/gpa.svg)](https://codeclimate.com/github/core23/MatomoBundle)
[![Coverage Status](https://coveralls.io/repos/core23/MatomoBundle/badge.svg)](https://coveralls.io/r/core23/MatomoBundle)

[![Donate to this project using Flattr](https://img.shields.io/badge/flattr-donate-yellow.svg)](https://flattr.com/profile/core23)
[![Donate to this project using PayPal](https://img.shields.io/badge/paypal-donate-yellow.svg)](https://paypal.me/gripp)

This bundle provides a wrapper for using the [matomo] (Piwik) statistic inside the symfony sonata-project.

## Installation

Open a command console, enter your project directory and execute the following command to download the latest stable version of this bundle:

```
composer require core23/matomo-bundle
composer require php-http/guzzle6-adapter # if you want to use Guzzle
```

### Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles in `bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    Http\HttplugBundle\HttplugBundle::class     => ['all' => true],
    Core23\MatomoBundle\Core23MatomoBundle::class => ['all' => true],
];
```

## Usage

Define a [HTTPlug] client in your configuration.

```yaml
# config/packages/httplug.yaml

httplug:
    classes:
        client: Http\Adapter\Guzzle6\Client
        message_factory: Http\Message\MessageFactory\GuzzleMessageFactory
        uri_factory: Http\Message\UriFactory\GuzzleUriFactory
        stream_factory: Http\Message\StreamFactory\GuzzleStreamFactory
```

```twig
{# template.twig #}

{{ sonata_block_render({ 'type': 'core23_matomo.block.statistic' }, {
    'host': 'http://matomo.example.com',
    'site': 1,
    'token': 'MATOMO_API_TOKEN'
}) }}
```

## License

This bundle is under the [MIT license](LICENSE.md).

[HTTPlug]: http://docs.php-http.org/en/latest/index.html
[matomo]: https://matomo.org
