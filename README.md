SherlockodeSonataAdvancedContentBundle
======================================

Sonata Admin integration for SherlockodeAdvancedContentBundle

## Installation

```bash
composer require sherlockode/sonata-advanced-content-bundle
```

Register the bundle in your application's kernel class:

```php
// app/AppKernel.php
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            // ...
            new Sherlockode\SonataAdvancedContentBundle\SherlockodeSonataAdvancedContentBundle(),
            // ...
        ];
    }
}
```

Add the specific CSS and JS files from the bundle to the Sonata configuration:
```
sonata_admin:
    assets:
        extra_javascripts:
            - bundles/sherlockodeadvancedcontent/js/content-type.js
            - bundles/sherlockodeadvancedcontent/js/speakingurl.min.js
        extra_stylesheets:
            - bundles/sherlockodeadvancedcontent/css/content-type.css
```
