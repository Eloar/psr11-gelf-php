<?php

namespace PSR11GelfPHPTest;


use Blazon\PSR11MonoLog\Exception\InvalidConfigException;
use Gelf\Publisher;
use Gelf\Transport\UdpTransport;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use PSR11GelfPHP\PublisherFactory;


class PublisherFactoryTest extends TestCase
{
    use ConfigTrait;

    const NAME_EXCHANGE = 'my-exchange';
    const NAME_QUEUE = 'my-queue';

    protected $mockContainer;

    public function setUp(): void
    {
        $this->mockContainer = $this->createMock(ContainerInterface::class);
        $this->mockContainer
            ->method('has')
            ->will($this->returnCallback([$this, 'mockHas']));

        $this->mockContainer
            ->method('get')
            ->will($this->returnCallback([$this, 'mockGet']));
    }

    public function testValidCallStatic()
    {
        $publisher = PublisherFactory::__callStatic('my-default-publisher', [$this->mockContainer]);
        $this->assertInstanceOf(Publisher::class, $publisher);
    }

    public function testValidInvoke()
    {
        $factory = new PublisherFactory();
        $publisher = $factory->__invoke($this->mockContainer, 'my-default-publisher');
        $this->assertInstanceOf(Publisher::class, $publisher);
    }

    public function testInvalidCallStatic()
    {
        $this->expectException(InvalidConfigException::class);
        PublisherFactory::__callStatic('my-invalid-publisher', [$this->mockContainer]);
    }

    public function testInvalidInvoke()
    {
        $factory = new PublisherFactory();
        $this->expectException(InvalidConfigException::class);
        $factory->__invoke($this->mockContainer, 'my-invalid-publisher');
    }

    public function mockHas($name)
    {
        switch ($name) {
            case 'config':
            case 'my-udp-transport':
                return true;
            default:
                return false;
        }
    }

    public function mockGet($name)
    {
        switch ($name) {
            case 'config':
                return $this->getConfigArray();
            case 'my-udp-transport':
                return $this->createMock(UdpTransport::class);
            default:
                return null;
        }
    }
}
