<?php declare(strict_types=1);

namespace PSR11GelfPHP\Transport;


use Blazon\PSR11MonoLog\FactoryInterface;
use Gelf\Transport\HttpTransport;


/**
 * Http transport factory class
 *
 * @see HttpTransport
 * @package PSR11GelfPHP\Transport
 * @author Janusz Paszyński "Eloar"
 */
class HttpTransportFactory implements FactoryInterface
{
    use SslOptionsTraint;

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
