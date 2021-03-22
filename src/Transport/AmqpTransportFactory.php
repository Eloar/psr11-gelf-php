<?php declare(strict_types=1);

namespace PSR11GelfPHP\Transport;


use Blazon\PSR11MonoLog\ContainerAwareInterface;
use Blazon\PSR11MonoLog\FactoryInterface;
use Blazon\PSR11MonoLog\ServiceTrait;
use Gelf\Transport\AmqpTransport;


/**
 * AMPQ transport factory class
 *
 * @see AmqpTransport
 * @package PSR11GelfPHP\Transport
 * @author Janusz Paszyński "Eloar"
 */
class AmqpTransportFactory implements FactoryInterface, ContainerAwareInterface
{
    use ServiceTrait;

    public function __invoke(array $options)
    {
        $exchange = $this->getService($options['exchange']);
        $queue = $this->getService($options['queue']);
        return new AmqpTransport($exchange, $queue);
    }

}
