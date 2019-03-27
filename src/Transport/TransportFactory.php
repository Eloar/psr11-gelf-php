<?php

namespace PSR11GelfPHP\Transport;


use Psr\Container\ContainerInterface;
use WShafer\PSR11MonoLog\ContainerAwareInterface;
use WShafer\PSR11MonoLog\Exception\InvalidConfigException;
use WShafer\PSR11MonoLog\Exception\InvalidContainerException;
use WShafer\PSR11MonoLog\Exception\MissingConfigException;


// todo: JP document
class TransportFactory
{
    public static function __callStatic($name, $arguments)
    {
        if (empty($arguments[0]) || !$arguments[0] instanceof ContainerInterface) {
            throw new InvalidContainerException('Argument 0 must be an instance of a PSR-11 container');
        }

        $factory = new static();
        return $factory($arguments[0], $name);
    }

    public function __invoke(ContainerInterface $container, string $name = 'default')
    {
        // todo: JP implement
        $config = $this->getConfigArray($container);
        if (!is_array($config['monolog'])) {
            throw new MissingConfigException('No monolog config found. Check your config');
        }
        if (!array_key_exists('transports', $config['monolog'])) {
            throw new MissingConfigException('No transports config in monolog config. Check your config');
        }
        if (!is_array($config['monolog']['transports']) || !array_key_exists($name, $config['monolog']['transports'])) {
            throw new MissingConfigException('No config found for ' . $name . ' transport. Check your config');
        }
        $transportConfig = $config['monolog']['transports'][$name];
        $factory = $this->getFactory($container, $transportConfig['type']);
        return $factory($transportConfig['options']);

    }

    protected function getConfigArray(ContainerInterface $container)
    {
        // Symfony config is parameters. //
        if (method_exists($container, 'getParameter')
            && method_exists($container, 'hasParameter')
            && $container->hasParameter('monolog')
        ) {
            return ['monolog' => $container->getParameter('monolog')];
        }

        // Zend uses config key
        if ($container->has('config')) {
            return $container->get('config');
        }

        // Slim Config comes from "settings"
        if ($container->has('settings')) {
            return ['monolog' => $container->get('settings')['monolog']];
        }

        return [];
    }

    protected function getFactory(ContainerInterface $container, string $type)
    {
        $class = 'PSR11GelfPHP\\Transport\\' . ucfirst($type) . 'TransportFactory';
        if (!class_exists($class)) {
            throw new InvalidConfigException($type . ' is not valid transport type. Check your config');
        }

        $factory = new $class();
        if ($factory instanceof ContainerAwareInterface) {
            $factory->setContainer($container);
        }
        return $factory;
    }

}
