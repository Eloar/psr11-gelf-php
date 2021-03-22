<?php declare(strict_types=1);

namespace PSR11GelfPHP\Transport;


use Blazon\PSR11MonoLog\FactoryInterface;
use Gelf\Transport\TcpTransport;


/**
 * Tcp transport factory class
 *
 * @see TcpTransport
 * @package PSR11GelfPHP\Transport
 * @author Janusz Paszyński "Eloar"
 */
class TcpTransportFactory implements FactoryInterface
{
    use SslOptionsTraint;

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
