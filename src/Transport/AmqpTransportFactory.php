<?php

namespace PSR11GelfPHP\Transport;


use Gelf\Transport\AmqpTransport;
use WShafer\PSR11MonoLog\ContainerAwareInterface;
use WShafer\PSR11MonoLog\FactoryInterface;
use WShafer\PSR11MonoLog\ServiceTrait;

// todo: JP document
class AmqpTransportFactory implements FactoryInterface, ContainerAwareInterface
{
    use ServiceTrait;

    // todo: JP document
    public function __invoke(array $options)
    {
        $exchange = $this->getService($options['exchange']);
        $queue = $this->getService($options['queue']);
        return new AmqpTransport($exchange, $queue);
    }
}
