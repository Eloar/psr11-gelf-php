<?php

namespace PSR11GelfPHP\Transport;


use Gelf\Transport\HttpTransport;
use WShafer\PSR11MonoLog\FactoryInterface;


// todo: JP document
class HttpTransportFactory implements FactoryInterface
{
    use SslOptionsTraint;

    // todo: JP document
    public function __invoke(array $options)
    {
        $sslOptions = isset($options['sslOptions'])? $this->buildSslOptions($options['sslOptions']) : null;
        return new HttpTransport(
            $options['host']?? HttpTransport::DEFAULT_HOST,
            $options['port']?? HttpTransport::DEFAULT_PORT,
            $options['path']?? HttpTransport::DEFAULT_PATH,
            $sslOptions
        );
    }

}
