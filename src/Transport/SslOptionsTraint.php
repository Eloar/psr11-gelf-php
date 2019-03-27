<?php
/**
 * Created by PhpStorm.
 * User: eloar
 * Date: 25.03.19
 * Time: 15:50
 */

namespace PSR11GelfPHP\Transport;


// todo: JP document
use Gelf\Transport\SslOptions;
use WShafer\PSR11MonoLog\Exception\InvalidConfigException;

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
