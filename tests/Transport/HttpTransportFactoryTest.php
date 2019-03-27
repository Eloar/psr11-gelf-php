<?php
/**
 * Created by PhpStorm.
 * User: eloar
 * Date: 25.03.19
 * Time: 12:53
 */

namespace PSR11GelfPHPTest\Transport;

use Gelf\Transport\HttpTransport;
use PSR11GelfPHP\Transport\HttpTransportFactory;
use PHPUnit\Framework\TestCase;

class HttpTransportFactoryTest extends TestCase
{

    public function testInvoke()
    {
        $config = [
            'host' => 'localhost',
            'port' => 12201,
        ];
        $factory = new HttpTransportFactory();
        $transport = $factory($config);
        $this->assertInstanceOf(HttpTransport::class, $transport);
    }
}
