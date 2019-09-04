SherlockodeSonataAdvancedContentBundle
======================================

Sonata Admin integration for SherlockodeAdvancedContentBundle

## Installation

The best way to install this bundle is to rely on [Composer](https://getcomposer.org/):

```bash
$ composer require sherlockode/sonata-advanced-content-bundle
```

Register the bundle in your application's kernel:

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

```yaml
sonata_admin:
    assets:
        extra_javascripts:
            - bundles/sherlockodeadvancedcontent/js/content-type.js
            - bundles/sherlockodeadvancedcontent/js/speakingurl.min.js
        extra_stylesheets:
            - bundles/sherlockodeadvancedcontent/css/content-type.css
```

## Security

By default our admins are accessible by ROLE_SUPER_ADMIN. 
To configure your own role hierarchy, you can use the roles : 
- ROLE_SHERLOCKODE_ADVANCED_CONTENT_ADMIN_CONTENT_TYPE_[PERMISSION] : access to Content Types
- ROLE_SHERLOCKODE_ADVANCED_CONTENT_ADMIN_CONTENT_[PERMISSION] : access to Contents

For example, if you want to grant all permissions for ROLE_ADMIN, you can add : 
```yaml
# config/packages/security.yaml
security:
    role_hierarchy:
        ROLE_ADMIN:
            - ROLE_SHERLOCKODE_ADVANCED_CONTENT_ADMIN_CONTENT_TYPE_ALL
            - ROLE_SHERLOCKODE_ADVANCED_CONTENT_ADMIN_CONTENT_ALL
```
