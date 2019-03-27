<?php

namespace PSR11GelfPHPTest\Transport;

use Gelf\Transport\UdpTransport;
use PSR11GelfPHP\Transport\UdpTransportFactory;
use PHPUnit\Framework\TestCase;

class UdpTransportFactoryTest extends TestCase
{
    public function testInvoke()
    {
        $options = [
            'host' => 'localhost',
            'port' => 12201,
            'chunkSize' => 4096
        ];
        $factory = new UdpTransportFactory();
        /** @var UdpTransport $transport */
        $transport = $factory($options);
        $this->assertInstanceOf(UdpTransport::class, $transport);
    }
}
