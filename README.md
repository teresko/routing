# Fracture\Routing

[![Build Status](https://travis-ci.org/fracture/routing.png?branch=master)](https://travis-ci.org/fracture/routing)
[![Code Coverage](https://scrutinizer-ci.com/g/fracture/fracture/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/fracture/fracture/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/fracture/fracture/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/fracture/fracture/?branch=master)


##Introduction

Fracture\Routing is a simple routing library, that is made to be easily compatible with other libs.
It has **does not include** any type of functionality for dispatching. Instead it focuses on "packaging"
the user's input in and abstracted representation of request, as long as it implements the required interface.


##Installation

Since the library is still in development, the recommended version to install would be "the latest".
You can do it by running following command:

```sh
composer require fracture/routing:dev-master
```

##Usage

```php
<?php

require '/path/to/vendor/autoload.php';

/*
 * Setting up request abstraction
 */

$builder = new Fracture\Http\RequestBuilder;
$request = $builder->create([
    'get'    => $_GET,
    'files'  => $_FILES,
    'server' => $_SERVER,
    'post'   => $_POST,
    'cookies'=> $_COOKIE,
]);

$uri = isset($_SERVER['REQUEST_URI'])
           ? $_SERVER['REQUEST_URI']
           : '/';

$request->setUri($uri);

/*
 * Routing the request
 */

$configuration = [
    "optional" => [
        "notation" => "[:key]",
        "conditions" => [
            "key" => "id-[0-9]+"  // looking for values like "id-5162" or "id-42"
        ],
        "defaults" => [

        ],
    ],
    "main" => [
        "notation" => ":resource",
    ],
];

$router = new Fracture\Routing\Router(new Fracture\Routing\RouteBuilder);
$router->import($configuration);

$router->route($request);

// There $request now is fully initialized.
```
