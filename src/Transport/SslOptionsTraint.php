<?php declare(strict_types=1);

namespace PSR11GelfPHP\Transport;


use Blazon\PSR11MonoLog\Exception\InvalidConfigException;
use Gelf\Transport\SslOptions;


/**
 * Trait for handling SSL options in transport initialization
 *
 * @package PSR11GelfPHP\Transport
 * @author Janusz Paszyński "Eloar"
 */
trait SslOptionsTraint
{
    protected function buildSslOptions($sslOptions)
    {
        if ($sslOptions === null || $sslOptions instanceof SslOptions) {
            return $sslOptions;
        }

        if (!is_array($sslOptions)) {
            throw new InvalidConfigException('SslOptions config is invalid. Check Your config');
        }

        $ret = new SslOptions();
        $refClass = new \ReflectionClass($ret);
        foreach ($sslOptions as $k => $v) {
            $method = 'set' . ucfirst($k);
            if (!$refClass->hasMethod($method)) {
                throw new InvalidConfigException('There is no ' . $k . ' option for SSL');
            }
            call_user_func([$ret, $method], $v);
        }
        return $ret;
    }
}
