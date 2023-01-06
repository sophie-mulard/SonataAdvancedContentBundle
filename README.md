SherlockodeSonataAdvancedContentBundle
======================================

Sonata Admin integration for SherlockodeAdvancedContentBundle

## Installation

The best way to install this bundle is to rely on [Composer](https://getcomposer.org/):

```bash
$ composer require sherlockode/sonata-advanced-content-bundle
```

Register the bundle in your application's kernel and make sure that this bundle is added
after the [AdvancedContentBundle](https://github.com/sherlockode/advanced-content-bundle)

```php
// config/bundles.php
<?php

return [
    /* ... */
    Sherlockode\AdvancedContentBundle\SherlockodeAdvancedContentBundle::class => ['all' => true],
    Sherlockode\SonataAdvancedContentBundle\SherlockodeSonataAdvancedContentBundle::class => ['all' => true],
];
```

You will need to add our specific CSS and JS files to your application via webpack and Sonata configuration.

```js
// assets/js/app.js

// sherlockode/advanced-content-bundle configuration
import '../../vendor/sherlockode/advanced-content-bundle/Resources/public/css/index.scss';
import '../../vendor/sherlockode/advanced-content-bundle/Resources/js/index.js';

// font awesome (optional)
import '@fortawesome/fontawesome-free/css/fontawesome.css';
import '@fortawesome/fontawesome-free/css/solid.css';

// sherlockode/sonata-advanced-content-bundle configuration
import '../../vendor/sherlockode/sonata-advanced-content-bundle/Resources/js/acb-sonata.js';
import '../../vendor/sherlockode/sonata-advanced-content-bundle/Resources/css/acb-sonata.scss';
```

You can then add the following lines to your Sonata configuration:

```yaml
# config/packages/sonata_admin.yaml
sonata_admin:
    assets:
        extra_javascripts:
            - bundles/sherlockodeadvancedcontent/js/speakingurl.min.js
            - build/admin/admin-entry.js # Include here the path to your webpack generated admin js file
        extra_stylesheets:
            - build/admin/admin-entry.css # Include here the path to your webpack generated admin css file
```

## Security

By default our admins are accessible by ROLE_SUPER_ADMIN. 
To configure your own role hierarchy, you can use the roles : 
- ROLE_SHERLOCKODE_ADVANCED_CONTENT_ADMIN_CONTENT_[PERMISSION] : access to Contents
- ROLE_SHERLOCKODE_ADVANCED_CONTENT_ADMIN_PAGE_TYPE_[PERMISSION] : access to Page Types
- ROLE_SHERLOCKODE_ADVANCED_CONTENT_ADMIN_PAGE_[PERMISSION] : access to Pages

For example, if you want to grant all permissions for ROLE_ADMIN, you can add : 
```yaml
# config/packages/security.yaml
security:
    role_hierarchy:
        ROLE_ADMIN:
            - ROLE_SHERLOCKODE_ADVANCED_CONTENT_ADMIN_CONTENT_ALL
            - ROLE_SHERLOCKODE_ADVANCED_CONTENT_ADMIN_PAGE_TYPE_ALL
            - ROLE_SHERLOCKODE_ADVANCED_CONTENT_ADMIN_PAGE_ALL
```
