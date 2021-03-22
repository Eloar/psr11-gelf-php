<?php

namespace PSR11GelfPHP;


use Blazon\PSR11MonoLog\Exception\InvalidConfigException;
use Blazon\PSR11MonoLog\Exception\InvalidContainerException;
use Gelf\MessageValidatorInterface;
use Gelf\Publisher;
use Gelf\Transport\TransportInterface;
use Psr\Container\ContainerInterface;


/**
 * Factory class for Gelf Publisher
 *
 * @package PSR11GelfPHP
 * @author Janusz Paszyński
 */
class PublisherFactory
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
        $config = $this->getConfigArray($container);
        $publisherConfig = $config['monolog']['publishers'][$name];
        return new Publisher(
            $this->getTransport($container, $publisherConfig),
            $this->getValidator($container, $publisherConfig)
        );
    }

    protected function getTransport(ContainerInterface $container, array $config)
    {
        $transport = $config['transport'];
        if ($transport instanceof  TransportInterface) {
            return $transport;
        }
        if (is_callable($transport)) {
            return call_user_func($transport, $container);
        }
        if (!$container->has($transport)) {
            throw new InvalidConfigException('Transport ' . $transport . ' not found in container. Check Your container config');
        }
        return $container->get($transport);
    }

    protected function getValidator(ContainerInterface $container, array $config)
    {
        $validator = $config['messageValidator']?? null;
        if (empty($validator) || $validator instanceof MessageValidatorInterface) {
            return $validator;
        }
        if (is_callable($validator)) {
            return call_user_func($validator, $container);
        }
        if ($container->has($validator)) {
            throw new InvalidConfigException('Validator ' . $validator . ' not present in container. Check Yor container config');
        }
        return $container->get($validator);
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

}
