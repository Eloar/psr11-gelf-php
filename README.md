# PSR-11 Gelf PHP

This library is extension for [PSR-11 Monolog](https://github.com/wshafer/psr11-monolog) library which provides similar
config diriven initialization for Gelf classes. It allows use `GelfHandler` without writing factories yourself. 

## Instalation

```bash
composer require Eloar/psr11-gelf-php
```

## Usage

Below code is copied directly from [PSR-11 Monolog](https://gitlab.com/blazon/psr11-monolog) documentation. To use it 
`Gelf\Publisher` has to be defined in container under `my-service` name or instance has to be provided in config (in 
place of `my-service` key). 

```php
<?php

return [
    'monolog' => [
        'handlers' => [
            'myHandlerName' => [
                'type' => 'gelf',
                'formatter' => 'formatterName', // Optional: Formatter for the handler.  Default for the handler will be used if not supplied
                'options' => [
                    'publisher' => 'my-service', // A Gelf\PublisherInterface object.  Must be a valid service.
                    'level'     => \Monolog\Logger::DEBUG, // Optional: The minimum logging level at which this handler will be triggered
                    'bubble'    => true, // Optional: Whether the messages that are handled can bubble up the stack or not
                ],
            ],
        ],
    ],
];
```

**PSR-11 Gelf PHP** allows to provide configuration of publishers and transport in similar fashion. Using it above
example might be extended as below:

```php
<?php

return [
    'monolog' => [
        // transports definitions
        'transports' => [
            'defaultTransport' => [
                'type' => 'udp',
                'options' => [
                    'host' => 'example.com',
                    'port' => 12201
                 ]
            ]
        ],
        // publishers definition
        'publishers' => [
            'defaultPublisher' => [
                'transport' => 'defaultTransport'
             ]
        ],
        // handlers definition as from PSR-11 Monolog
        'handlers' => [
            'myHandlerName' => [
                'type' => 'gelf',
                'formatter' => 'formatterName', // Optional: Formatter for the handler.  Default for the handler will be used if not supplied
                'options' => [
                    'publisher' => 'defaultPublisher',
                    'level'     => \Monolog\Logger::DEBUG, // Optional: The minimum logging level at which this handler will be triggered
                    'bubble'    => true, // Optional: Whether the messages that are handled can bubble up the stack or not
                ],
            ],
        ],
    ]
];
```

Additionally for each transport there need to be factory defined in container. Same applies to publishers.

### ZendServiceManager

For above example of config You should add below definition to `dependencies.global.php` or any `ConfigProvider.php` in 
Your application.

```php
<?php

return [
    'dependencies' => [
        'factories' => [
            // ...
            'defaultPublisher' => \PSR11GelfPHP\PublisherFactory::class,
            'defaultTransport' => \PSR11GelfPHP\Transport\TransportFactory::class,
        ]
    ]
];
```

### Pimple

Pimple Container is passing only itself to factory when initializing services, so factory definition is bit different.
You need to use closure inside which name of transport or publisher will be used to properly initialize factorized
service (transport or publisher);

```php
<?php

$container = new \Xtreamwayz\Pimple\Container([
    // using factory instance
    'defaultPublisher' => function($c) {
        return (new \PSR11GelfPHP\PublisherFactory)($c, 'defaultPublisher');
    },
    // using static call
    'defaultTransport' => function($c) {
        return \PSR11GelfPHP\Transport\TransportFactory::defaultTransport($c);
    },
]);
```

## Configuration

PSR-11 Gelf PHP introduces 2 new keys inside `monolog` config array:
- `transports`
- `publishers`

### Publishers configuration

Publisher is pretty simple thing so its configuration reflets it. `\Gelf\Publisher` constructor has only 2 arguments and
only one is required: transport. Publisher configuration allowed keys:
- `transport` - points to any `TransportInterface` instance
- `messageValidator` - points to any `MessageValidatorInterface` instance

Value for each key above might be one from below:
- object - instance of proper Interface
- callable - any function that accepts `ContainerInterface` as only required argument and returns proper Interface 
    instance
- name of service in container

### Transports configuration

Library is providing factory for each transport class from PHP Gelf. Transport configuration consist of two parts:
- `type` - string value from below list
    - `amqp`
    - `http`
    - `tcp`
    - `udp`
- `options` - array of options for transport construction

Each type may accept different set of options. Below sections contain description of options for each type

#### AmqpTransport

- `exchange` - name of service extending `\AMQPExchange`
- `queue` - name of service extending `\AMQPQueue`

#### HttpTransport

- `host` - hostname to which transport will be sending data
- `port` - port using which transport will be sending data
- `path` - path on hostname to which data will be sent
- `sslOptions` - sslOptons for connections. Value of this option might be either `SslOptions` instance or array of
    key/value pairs where each key should be name of parameter in `SslOptions` class
    
#### TcpTransport

- `host` - hostname to which data will be sent
- `port` - tcp port to which data will be sent
- `sslOptions` - same as for `HttpTransport` above 

#### UdpTransport

- `host` - hostname to which data will be sent
- `port` - udp port to which data will be sent
- `chunkSize` - (optional) - limit of size of data sent in single udp frame to server 