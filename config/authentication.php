<?php
use Authentication\AuthenticationService;
use Authentication\AuthenticationServiceInterface;
use Authentication\Identifier\IdentifierInterface;
use Authentication\Middleware\AuthenticationMiddleware;

return [
    'Authentication' => [
        'default' => [
            'className' => AuthenticationService::class,
            'identifiers' => [
                'Authentication.Password' => [
                    'fields' => [
                        IdentifierInterface::CREDENTIAL_USERNAME => 'email', // Cambia 'username' a 'email'
                        IdentifierInterface::CREDENTIAL_PASSWORD => 'password',
                    ],
                    'resolver' => [
                        'className' => 'Authentication.Orm',
                        'userModel' => 'Users',
                    ],
                ],
            ],
            'authenticators' => [
                'Authentication.Session',
                'Authentication.Form' => [
                    'fields' => [
                        'username' => 'email', // Cambia 'username' a 'email'
                        'password' => 'password',
                    ],
                    'loginUrl' => '/users/login',
                ],
            ],
        ],
    ],
];
