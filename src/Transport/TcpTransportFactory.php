<?php

namespace PSR11GelfPHP\Transport;


use Gelf\Transport\TcpTransport;
use WShafer\PSR11MonoLog\FactoryInterface;


// todo: JP document
class TcpTransportFactory implements FactoryInterface
{
    use SslOptionsTraint;

    // todo: JP document
    public function __invoke(array $options)
    {
        $sslOptions = isset($options['sslOptions'])? $this->buildSslOptions($options['sslOptions']) : null;
        return new TcpTransport(
            $options['host']?? TcpTransport::DEFAULT_HOST,
            $options['port']?? TcpTransport::DEFAULT_PORT,
            $sslOptions
        );
    }
}
