SphinxSearchBundle
==================

This bundle integrates [SphinxSearch](https://github.com/A-S-Kozienko/SphinxSearch) into Symfony2 project

Installation
============

Add in your composer.json:

```js
{
    "require": {
        "ask/sphinx-search-bundle": "dev-master"
    },
    "repositories": {
        "ask/sphinx-search-bundle": {
            "type": "vcs",
            "url": "git://github.com/A-S-Kozienko/SphinxSearchBundle.git"
        },
        "ask/sphinx-search": {
            "type": "vcs",
            "url": "git://github.com/A-S-Kozienko/SphinxSearch.git"
        },
        "googlecode/sphinxsearch":  {
            "type": "package",
            "package": {
                "name": "googlecode/sphinxsearch",
                "version": "2.0.0",
                "source": {
                    "url": "http://sphinxsearch.googlecode.com/svn/",
                    "type": "svn",
                    "reference": "branches/rel20"
                },
                "autoload": {
                    "classmap": ["api/sphinxapi.php"]
                }
            }
        }
    }
}
```

## Add SphinxSearchBundle to your application kernel

```php
// app/AppKernel.php
public function registerBundles()
{
    return array(
        // ...
        new ASK\SphinxSearchBundle\ASKSphinxSearchBundle(),
        // ...
    );
}
```

and run the command

```sh
$ php composer.phar update ask/sphinx-search-bundle
```

## Configuration

```yaml
# app/config/config.yml
ask_sphinx_search:
    indexes:
        MainAlias:        index_main
        DeltaAlias:       index_delta
    connect_timeout:      0
    retry_delay:          0
    retry_count:          0
    port:                 9312
    host:                 localhost
    debug:                %kernel.debug%
```

## Basic usage

```php
$manager = $container->get('ask_sphinx_search.manager');

$query = $manager->createQuery(array(
    'MainAlias',
    'DeltaAlias',
));

$query
    ->addMatch('hello :placeholder')
    ->setMatchParameter('placeholder', 'word')
;

$result = $query->execute();
```