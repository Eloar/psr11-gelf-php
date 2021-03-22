<?php declare(strict_types=1);

namespace PSR11GelfPHPTest;


/**
 * Configuration trait for generating configuration array
 *
 * @package PSR11GelfPHPTest
 * @author Janusz Paszyński "Eloar"
 */
trait ConfigTrait
{
    public function getConfigArray() : array
    {
        return [
            'monolog' => [
                'transports' => [
                    'my-amqp-transport' => [
                        'type' => 'amqp',
                        'options' => [
                            'exchange' => self::NAME_EXCHANGE,
                            'queue' => self::NAME_QUEUE
                        ]
                    ],
                    'my-http-transport' => [
                        'type' => 'http',
                        'options' => [
                            'host' => 'localhost',
                            'port' => 12201,
                            'sslOptions' => [
                                'allowSelfSigned' => true,
                                'verifyPeer' => false,
                            ]
                        ]
                    ],
                    'my-tcp-transport' => [
                        'type' => 'tcp',
                        'options' => [
                            'host' => 'localhost',
                            'port' => 12201,
                        ]
                    ],
                    'my-udp-transport' => [
                        'type' => 'udp',
                        'options' => [
                            'host' => 'localhost',
                            'port' => 12201
                        ]
                    ],
                    // invalid type transport definition
                    'invalid-type-transport' => [
                        'type' => 'invalid_type',
                        'options' => [
                            'option1' => 'value1',
                            'option2' => 'value2'
                        ]
                    ]
                ],
                'publishers' => [
                    'my-default-publisher' => [
                        'transport' => 'my-udp-transport'
                    ],
                    // invalid publisher - usning undefined transport
                    'my-invalid-publisher' => [
                        'transport' => 'invalid-trasport'
                    ]
                ],
            ]
        ];
    }

}
