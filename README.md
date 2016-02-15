# Fracture\Routing

[![Build Status](https://travis-ci.org/fracture/routing.png?branch=master)](https://travis-ci.org/fracture/routing)
[![Code Coverage](https://scrutinizer-ci.com/g/fracture/fracture/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/fracture/fracture/?branch=master)
[![Scrutinizer](https://img.shields.io/scrutinizer/g/fracture/routing.svg)](https://scrutinizer-ci.com/g/fracture/fracture/?branch=master)
[![Packagist version](https://img.shields.io/packagist/v/fracture/routing.svg)](https://packagist.org/packages/fracture/routing)

##Introduction

This component is a simple routing library, that is made to be easily compatible with other libs. It **does not include** any functionality for dispatching. Instead it focuses on "packaging" the user's input in an abstracted representation of request.


##Installation

You can add the library to your project using composer with following command:

```sh
composer require fracture/routing
```

It will also install `fracture/http` as a dependency.

##Usage

The following code illustates the process of initializing the abstraction of an HTTP request and routing said request.

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
 * Defining the config
 */

$configuration = [
    'primary' => [
        'notation' => '[:id]/:resource',
        'conditions' => [
            'id' => '[0-9]+',
        ],
    ],
    'fallback' => [
        'notation' => ':any',
        'conditions' => [
            'any' => '.*',
        ],
        'defaults' => [
            'resource' => 'landing',
        ],
    ],
];

/*
 * Routing the request
 */

$router = new Fracture\Routing\Router(new Fracture\Routing\RouteBuilder);
$router->import($configuration);

$router->route($request);

// The $request now is fully initialized.

var_dump($request->getParameter('resource'));
```

###Definition of routes

The `import()` method of the router expects a list of defined routes. Each route is an array containing `'notation'` element. It also can optionally have `'conditions'` and `'defaults'` fields.

```php
<?php
$configuration = [
    // other routes

    'primary' => [
        'notation' => ':resource[/:action]',
        'conditions' => [
            // list custom conditions
        ],
        'defaults' => [
            // list of fallback values
        ]
    ],

    // even more routes
];
```

When routers is attempting to match the URI to the list of defined routes, it iterates **starting from top element and continues till either a match is found or the list has ended**. Therefore to increase the priority of any of defined routes, you move it higher in the list.


####Notation format

The notation is a human-readable format for defining the structure of URI, which you are attempting to match. It can contain 3 identifiable sets of *parts*, all of which can be seen in this example:

    document[/view]/:name[.:extension]

These parts are:

- **tokens**

  Each token has starts with colon and name written using only letters from English alphabet (or `:[a-zA-Z]+` as a regular expression). This shape of the token is defined in [`Pattern::REGKEY`](https://github.com/fracture/routing/blob/master/src/Fracture/Routing/Pattern.php).

  In the above given example the defined tokens were `:name` and `:extension`.

- **static text**

  These are parts of notation, which has not direct computational value, but only serve to structure the URI and make it easier to read and/or identify.

  In the above given example the static text is `document`, `/view`, `/` and `.` (dot).

- **optional element** `[ ]`

  If any part of notation is wrapped in square brackets, it becomes non-mandatory. Any notation's part and combination of parts can be defined as optional element. This also means that optional elements can be nested.

  In the example above the part, that are defined as optional were `/view` and `.:extension`.
