# Fracture\Routing

[![Build Status](https://travis-ci.org/fracture/routing.png?branch=master)](https://travis-ci.org/fracture/routing)
[![Code Coverage](https://scrutinizer-ci.com/g/fracture/fracture/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/fracture/fracture/?branch=master)
[![Scrutinizer](https://img.shields.io/scrutinizer/g/fracture/routing.svg)](https://scrutinizer-ci.com/g/fracture/fracture/?branch=master)
[![Packagist version](https://img.shields.io/packagist/v/fracture/routing.svg)](https://packagist.org/packages/fracture/routing)

##Introduction

This component is a simple routing library, that is made to be easily compatible with other libraries. It **does not include** any functionality for dispatching. Instead it focuses on "packaging" the user's input in an abstracted representation of request.


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

The `import()` method of the router expects a list of defined routes. Each route is an array containing `'notation'` elements. Routes also optionally have `'conditions'` and `'defaults'` fields.

```php

'primary' => [
    'notation' => ':resource[/:action]',
    'conditions' => [
        // list custom conditions
    ],
    'defaults' => [
        // list of fallback values
    ]
],
```

When the router attempts to match a URI to the list of defined routes, it iterates **starting from top element and continues until either a match is found, or the list has ended**. Therefore, to increase the priority of any of defined routes, you move it higher in the list.


####Notation format

The notation is a human-readable format for defining the structure of URI, which you are attempting to match. It can contain 3 identifiable sets of *parts*, all of which can be seen in this example:

    document[/view]/:name[.:extension]

These parts are:

- **tokens**

  Each token has starts with colon and name written using only letters from English alphabet (or `:[a-zA-Z]+` as a regular expression). This shape of the token is defined in [`Pattern::REGKEY`](https://github.com/fracture/routing/blob/master/src/Fracture/Routing/Pattern.php).

  In the above given example the defined tokens were `:name` and `:extension`.

- **static text**

  These are parts of notation, which does not have a direct computational value, but serve to structure the URI and make it easier to read and/or identify.

  In the above given example the static text is `document`, `/view`, `/` and `.` (dot).

- **optional element**

  If any part of notation is wrapped in `[]` (square brackets), it becomes non-mandatory. Any notation's part, or combination of parts, can be defined as optional element. This also means that optional elements can be nested.

  In the example above the part, `/view` and `.:extension` are declared as optional.


####Conditions

For each route it is possible to define custom conditions, that limit acceptable matches for tokens. By default, every token attempts to match a URI fragment that **does not** contain `/\.,;?`. To change this behavior, each route definition can optionally have a `conditions` element.

The conditions are set as array of `key => value` pairs, where keys correspond to names of tokens, and values contain regular expression fragments. This is demonstrated in the following example route:

```
'notation' => ':project/[:name]/:iteration',
'conditions' => [
    'name' => '[A-Z][a-z]+-[0-9]{2}',
    'iteration' => '[0-9]+',
],
```

In this example, a notation has three defined tokens. The token `:name` token is optional. Additionally, both `:name` and `:iteration` have required conditions.


####Defaults

When a URI pattern has optional parts, some requests will match where those parts were missing. In this case, by default, `Fracture\Request` will return `null` when trying to retrieve that parameter.

To override `null` and specify default values, a `defaults` array can be provided in the route definition:

```
'notation' => ':project/[:name]',
'defaults' => [
    'name' => 'unnamed',
],
```

In the example above, if `notation` is matched, but the corresponding `:name` was not present in URI, the request abstraction will receive `"unnamed"` as value for `'name'` parameter.



##Use of routed request

See documentation for [**fracture/http**](https://github.com/fracture/http).


##Various tips

###Cleaner configuration

Real-world applications will almost always have more than a few routes. This can result in extensive configuration, which would make the initialization phase of your project (like a bootstrap file) hard to read and filled with clutter.

To prevent that, you can move the configuration into a dedicated file.

```php
<?php
// other code

$configuration = json_decode(file_get_contents(__DIR__ . '/config/routes.json'), true);

$router = new Fracture\Routing\Router(new Fracture\Routing\RouteBuilder);
$router->import($configuration);
```

This can also be combined with environment variables, for differentiating between development, staging and production environments.


###Silent parameters

It is not required for parameters specified in `defaults` part of the route definition to appear in the `notation`. This provides the ability for a matched route to enhance request abstraction by providing additional parameters.


```
'notation' => 'verify/[:hash]',
'conditions' => [
    'hash' => '[a-z0-9]{32}',
],
'defaults' => [
    'resource' => 'registration',
    'action'   => 'complete',
],
```

By having these "silent parameters", your code is not restricted to only using values provided in the URI.
