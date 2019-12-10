MatomoBundle
===========
[![Latest Stable Version](https://poser.pugx.org/core23/matomo-bundle/v/stable)](https://packagist.org/packages/core23/matomo-bundle)
[![Latest Unstable Version](https://poser.pugx.org/core23/matomo-bundle/v/unstable)](https://packagist.org/packages/core23/matomo-bundle)
[![License](https://poser.pugx.org/core23/matomo-bundle/license)](https://packagist.org/packages/core23/matomo-bundle)

[![Total Downloads](https://poser.pugx.org/core23/matomo-bundle/downloads)](https://packagist.org/packages/core23/matomo-bundle)
[![Monthly Downloads](https://poser.pugx.org/core23/matomo-bundle/d/monthly)](https://packagist.org/packages/core23/matomo-bundle)
[![Daily Downloads](https://poser.pugx.org/core23/matomo-bundle/d/daily)](https://packagist.org/packages/core23/matomo-bundle)

[![Continuous Integration](https://github.com/core23/MatomoBundle/workflows/Continuous%20Integration/badge.svg)](https://github.com/core23/MatomoBundle/actions)
[![Code Coverage](https://codecov.io/gh/core23/MatomoBundle/branch/master/graph/badge.svg)](https://codecov.io/gh/core23/MatomoBundle)

This bundle provides a wrapper for using the [matomo] (Piwik) statistic inside the symfony sonata-project.

## Installation

Open a command console, enter your project directory and execute the following command to download the latest stable version of this bundle:

```
composer require core23/matomo-bundle
composer require php-http/guzzle6-adapter # if you want to use Guzzle
```

### Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles in `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    Http\HttplugBundle\HttplugBundle::class     => ['all' => true],
    Core23\MatomoBundle\Core23MatomoBundle::class => ['all' => true],
];
```

### Assets

It is recommended to use [webpack](https://webpack.js.org/) / [webpack-encore](https://github.com/symfony/webpack-encore)
to include the `MatomoTable.js` file in your page. These file is located in the `assets` folder.

You can use [npm](https://www.npmjs.com/) or [yarn](https://yarnpkg.com/) to load the library:

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
