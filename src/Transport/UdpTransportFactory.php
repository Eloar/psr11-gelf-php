<?php

namespace PSR11GelfPHP\Transport;


use Gelf\Transport\UdpTransport;
use WShafer\PSR11MonoLog\FactoryInterface;


// todo: JP document
class UdpTransportFactory implements FactoryInterface
{
    // todo: jp document
    public function __invoke(array $options)
    {
        return new UdpTransport(
            $options['host']?? UdpTransport::DEFAULT_HOST,
            $options['port']?? UdpTransport::DEFAULT_PORT,
            $options['chunkSize']?? UdpTransport::CHUNK_SIZE_WAN
        );
    }
}
