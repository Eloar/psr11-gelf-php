<?php declare(strict_types=1);

namespace PSR11GelfPHP\Transport;


use Blazon\PSR11MonoLog\FactoryInterface;
use Gelf\Transport\UdpTransport;


/**
 * Udp transport factory class
 *
 * @package PSR11GelfPHP\Transport
 * @author Janusz Paszyński "Eloar"
 */
class UdpTransportFactory implements FactoryInterface
{

    public function __invoke(array $options)
    {
        return new UdpTransport(
            $options['host']?? UdpTransport::DEFAULT_HOST,
            $options['port']?? UdpTransport::DEFAULT_PORT,
            $options['chunkSize']?? UdpTransport::CHUNK_SIZE_WAN
        );
    }
}
