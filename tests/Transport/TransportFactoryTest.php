<?php

namespace PSR11GelfPHPTest\Transport;


use Gelf\Transport\AmqpTransport;
use Gelf\Transport\HttpTransport;
use Gelf\Transport\TcpTransport;
use Gelf\Transport\UdpTransport;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use PSR11GelfPHP\Transport\TransportFactory;
use PSR11GelfPHPTest\ConfigTrait;
use WShafer\PSR11MonoLog\Exception\InvalidConfigException;
use WShafer\PSR11MonoLog\Exception\MissingConfigException;


class TransportFactoryTest extends TestCase
{
    use ConfigTrait;

    const NAME_EXCHANGE = 'my-exchange';
    const NAME_QUEUE = 'my-queue';

    /** @var ContainerInterface */
    protected $container;
    /** @var TransportFactory */
    protected $factory;

    public function setUp()
    {
        $this->container = $this->createMock(ContainerInterface::class);
        $this->container
            ->method('has')
            ->withConsecutive(
                ['config'],
                [self::NAME_EXCHANGE],
                [self::NAME_QUEUE]
            )
            ->willReturnOnConsecutiveCalls(
                true,
                true,
                true
            );

        $this->container
            ->method('get')
            ->withConsecutive(
                ['config'],
                [self::NAME_EXCHANGE],
                [self::NAME_QUEUE]
            )
            ->willReturnOnConsecutiveCalls(
                $this->getConfigArray(),
                $this->createMock(\AMQPExchange::class),
                $this->createMock(\AMQPQueue::class)
            );

        $this->factory = new TransportFactory();
    }

    public function testCallStaticAmqp()
    {
        $transport = TransportFactory::__callStatic('my-amqp-transport', [$this->container]);
        $this->assertInstanceOf(AmqpTransport::class, $transport);
    }

    public function testInvokeAmqp()
    {
        $transport = $this->factory->__invoke($this->container, 'my-amqp-transport');
        $this->assertInstanceOf(AmqpTransport::class, $transport);
    }

    public function testCallStaticHttp()
    {
        $transport = TransportFactory::__callStatic('my-http-transport', [$this->container]);
        $this->assertInstanceOf(HttpTransport::class, $transport);
    }

    public function testInvokeHttp()
    {
        $transport = $this->factory->__invoke($this->container, 'my-http-transport');
        $this->assertInstanceOf(HttpTransport::class, $transport);
    }

    public function testCallStaticTcp()
    {
        $transport = TransportFactory::__callStatic('my-tcp-transport', [$this->container]);
        $this->assertInstanceOf(TcpTransport::class, $transport);
    }

    public function testInvokeTcp()
    {
        $transport = $this->factory->__invoke($this->container, 'my-tcp-transport');
        $this->assertInstanceOf(TcpTransport::class, $transport);
    }

    public function testCallStaticUdp()
    {
        $transport = TransportFactory::__callStatic('my-udp-transport', [$this->container]);
        $this->assertInstanceOf(UdpTransport::class, $transport);
    }

    public function testInvokeUdp()
    {
        $transport = $this->factory->__invoke($this->container, 'my-udp-transport');
        $this->assertInstanceOf(UdpTransport::class, $transport);
    }

    public function testInvokeInvalidType()
    {
        $this->expectException(InvalidConfigException::class);
        $this->factory->__invoke($this->container, 'invalid-type-transport');
    }

    public function testCallStaticInvalidType()
    {
        $this->expectException(InvalidConfigException::class);
        TransportFactory::__callStatic('invalid-type-transport', [$this->container]);
    }

    public function testInvokeMissing()
    {
        $this->expectException(MissingConfigException::class);
        $this->factory->__invoke($this->container, 'missing-transport');
    }

    public function testCallStaticMissingType()
    {
        $this->expectException(MissingConfigException::class);
        TransportFactory::__callStatic('missing-transport', [$this->container]);

    }

}
