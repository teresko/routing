<?php

/*
 * :alpha - first named parameter in pattern
 * :beta  - second parameter
 * :gamma - third parameter
 *
 * static - specified part of the pattern
 *
 * foo    - first unspecified segment in URL
 * bar    - second segment
 * buz    - third segment
 *
 * qux    - default value
 */

return [
    #notation:      '/static'
    [
        'expression' => '#^/static$#',
        'url'        => '/static',
        'expected'   => [],
    ],

    #notation:      '/static/static'
    [
        'expression' => '#^/static/static$#',
        'url'        => '/static/static',
        'expected'   => [],
    ],

    #notation:      '/:alpha'
    [
        'expression' => '#^/(?P<alpha>[^/\\\\.,;?\n]+)$#',
        'url'        => '/foo-bar',
        'expected'   => [
                            'alpha' => 'foobar',
                        ],
    ],

    #notation:      '/:alpha'
    [
        'expression' => '#^/(?P<alpha>[^/\\\\.,;?\n]+)$#',
        'url'        => '/foo---bar-',
        'expected'   => [
                            'alpha' => 'foobar',
                        ],
    ],

    #notation:      '/:alpha'
    [
        'expression' => '#^/(?P<alpha>[^/\\\\.,;?\n]+)$#',
        'url'        => '/foo__bar',
        'expected'   => [
                            'alpha' => 'foobar',
                        ],
    ],

    #notation:      '/:alpha/static/:beta'
    [
        'expression' => '#^/(?P<alpha>[^/\\\\.,;?\n]+)/static/(?P<beta>[^/\\\\.,;?\n]+)$#',
        'url'        => '/lorem-ipsum/static/sit-dolor_amet',
        'expected'   => [
                            'alpha' => 'loremipsum',
                            'beta'  => 'sitdoloramet',
                        ],
    ],
];
