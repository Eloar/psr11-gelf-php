<?php

namespace PSR11GelfPHPTest\Transport;


use AMQPExchange;
use AMQPQueue;
use Gelf\Transport\AmqpTransport;
use Psr\Container\ContainerInterface;
use PSR11GelfPHP\Transport\AmqpTransportFactory;
use PHPUnit\Framework\TestCase;


class AmqpTransortFactoryTest extends TestCase
{
    const NAME_EXCHANGE = 'amqp-exchange';
    const NAME_QUEUE = 'amqp-queue';

    /** @var ContainerInterface */
    protected $mockContainer;
    /** @var AmqpTransportFactory */
    protected $factory;

    public function setUp() : void
    {
        $this->factory = new AmqpTransportFactory();
        $this->mockContainer = $this->createMock(ContainerInterface::class);
        $this->factory->setContainer($this->mockContainer);

        $this->mockContainer
            ->method('has')
            ->withConsecutive(
                [$this->equalTo(self::NAME_EXCHANGE)],
                [$this->equalTo(self::NAME_QUEUE)]
            )
            ->willReturnOnConsecutiveCalls(
                true, true
            );

        $this->mockContainer
            ->method('get')
            ->withConsecutive(
                [$this->equalTo(self::NAME_EXCHANGE)],
                [$this->equalTo(self::NAME_QUEUE)]
            )
            ->willReturnOnConsecutiveCalls(
                $this->createMock(AMQPExchange::class),
                $this->createMock(AMQPQueue::class)
            );
    }

    public function testInvoke()
    {
        $config = [
            'exchange' => self::NAME_EXCHANGE,
            'queue' => self::NAME_QUEUE,
        ];
        $transport = ($this->factory)($config);
        $this->assertInstanceOf(AmqpTransport::class, $transport);
    }
}
