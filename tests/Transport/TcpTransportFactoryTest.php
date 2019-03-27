<?php

namespace PSR11GelfPHPTest\Transport;


use Gelf\Transport\SslOptions;
use Gelf\Transport\TcpTransport;
use PSR11GelfPHP\Transport\TcpTransportFactory;
use PHPUnit\Framework\TestCase;


class TcpTransportFactoryTest extends TestCase
{

    public function testInvoke()
    {
        $config = [
            'host' => 'localhost',
            'port' => 12201
        ];
        $factory = new TcpTransportFactory();
        $transport = $factory($config);
        $this->assertInstanceOf(TcpTransport::class, $transport);
    }

    public function testInstanceSsl()
    {
        $config = [
            'host' => 'localhost',
            'port' => 12201,
            'sslOptions' => new SslOptions()
        ];
        $factory = new TcpTransportFactory();
        $transport = $factory($config);
        $this->assertInstanceOf(TcpTransport::class, $transport);
    }

    public function testValidSsl()
    {
        $config = [
            'host' => 'localhost',
            'port' => 12201,
            'sslOptions' => [
                'caFile' => './cert.ca',
                'allowSelfSigned' => true,
                'verifyPeer' => false,
                'ciphers' => 'ALL:!ADH:@STRENGTH'
            ]
        ];
        $factory = new TcpTransportFactory();
        $transport = $factory($config);
        $this->assertInstanceOf(TcpTransport::class, $transport);

    }

    public function testEmptySsl()
    {
        $config = [
            'host' => 'localhost',
            'port' => 12201,
            'sslOptions' => []
        ];
        $factory = new TcpTransportFactory();
        $transport = $factory($config);
        $this->assertInstanceOf(TcpTransport::class, $transport);
    }
}
