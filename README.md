# Welcome to Webstatt - perhaps your next Content Management System

Webstatt is written in PHP and made use of a flat-file database system. 
Perhaps you're asking yourself, what it is, what webstat makes special. Actually more or less it's a typical CMS. But perhaps the approach is lightly different. Instead of 
define your website, webstatt allows you to 
create the website by using the admin control panel and a few methods. Wann start? See the setup section. 

## Features

In the following I try to explain the features webstatt comes with. 

The features at the glance:

* User Management
* Content Management with version-support
* Files Management

### User Management

Webstatt comes with a basic user management system. Users can login, logout, update there profile. The systems know three user roles: user, admin and super admin.

#### User-roles 

The idea behind the three roles is, to create a simple system where operations with a huge impact are only allowed to admin/superadmins. 

## Setup

Its quite easy to setup Webstatt as your new website. Just require the package via composer:

```bash
composer require basteyy/website
```

Next step is to create a new index-file for your website. For example `public/index.php`. In that file you need to include the composer autploader and than call Webstatt:

```php
<?php declare(strict_types=1);

namespace Webstatt;

use basteyy\Webstatt\Webstatt as Webstatt;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteCollectorProxy;

include dirname(__DIR__) . '/vendor/autoload.php';

$website = new Webstatt();

$website->getApp()->group('', function (RouteCollectorProxy $proxy) {
    $proxy->get('/', function (
            ResponseInterface $response, 
            RequestInterface $request
    ) : ResponseInterface {
        $response->getBody()->write('Hello my friend!');
        return $response;
    });
});

$website->run();
```

In case you wanna use templates, you need to create a template file. The project strucutre could be something like:

```text
public/index.php
src/templates/home.php
```

Updating the example from above:


```php
<?php declare(strict_types=1);

namespace Webstatt;

use basteyy\Webstatt\Webstatt as Webstatt;
use League\Plates\Engine;                           // <<< new
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteCollectorProxy;

include dirname(__DIR__) . '/vendor/autoload.php';

$website = new Webstatt();

$website->addWebsiteTemplateFolder(dirname(__DIR__) . '/src/templates');       // <<< new

$website->getApp()->group('', function (RouteCollectorProxy $proxy) {
    $proxy->get('/', function (
            ResponseInterface $response, 
            RequestInterface $request, 
            Engine $engine                          // <<< new
    ) : ResponseInterface {
        $response->getBody()->write(
            $engine->render('home')
        );
        return $response;
    });
});

$website->run();
```

## F.A.Q.

### Overwrite config.ini

By default, the config.ini from the package is loaded. To overwrite that, you need to create a config.ini inside your project root (for example: /var/www/config.ini). The file 
will be loaded automatically and overwrite the default config.

### Adding a new Admin Controller

Extend the controller for that:

```php
<?php

declare(strict_types=1);

namespace Webstatt;

class EditPageController                            // Thats your own controller
extends \basteyy\Webstatt\Controller\Controller      // Extend the controller from Webstatt
{
    // Define the access for the controller
    protected \basteyy\Webstatt\Enums\UserRole $minimum_user_role = UserRole::USER;
    
    // Invoke the controller
    public function __invoke(): \Psr\Http\Message\ResponseInterface
    {
        return $this->render('content_overview');
    }
}
```

Make sure, that you have registered the controller as a route: 

```php
$website                                            // The Webstatt-Class (aka Slim Class)
    ->getApp()                                      // Get the app
    ->any(                                          // Patch the route to the scope
        '/admin/content',                           // Name the route
        \Webstatt\EditPageController::class          // Define the called controller
    );
```

### Adding a new item to the admin navbar

Use the Helper `AdminNavbarItem` for that. 

```php
$website->addAdminNavbarItem(
    (new AdminNavbarItem())
        ->addUrl('/examnple/url')
        ->addValue('Example Link')
        ->addTitle('CLick here')
);
```

### Use a layout for content pages

Content Pages are the "pages" of your website. You can use layouts, while adding them to webstatt-class. The layouts are expected inside your template folder. The following 
could be your website:

```bash
/var/www/public/index.php
/var/www/src/templates/layouts/my_layout.php
/var/www/src/templates/layouts/my_other_layout.php
```

Inside the `index.php` you register the path to your template and the layouts:
```php

// Inside /var/www/public/index.php
use basteyy\Webstatt\Webstatt as Webstatt;

$website = new Webstatt();

$website->addWebsiteTemplateFolder(dirname(__DIR__) . '/src/templates');

$website->addWebsiteTemplateLayouts([
    'layouts/my_layout', 'layouts/my_other_layout'
]);

```

While creating and editing your content pages, you can select one of the layouts. On that way, the content page content will be dispatched into the layout. Be aware, that you 
have access inside the layout to the content page variables by using `$page`. `$page` is a instance of the [PageAbstraction](https://github.com/basteyy/webstatt/blob/master/src/Models/Abstractions/PageAbstraction.php). 