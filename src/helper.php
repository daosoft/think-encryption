<?php

use lantern\encryption\facade\Crypt;

if (!function_exists('decrypt')) {
    /**
     * 解密给定的值
     *
     * @param string $value 待解密的数据
     * @param bool $unserialize 是否需要反序列化
     * @return mixed
     */
    function decrypt($value, $unserialize = true)
    {
        return Crypt::decrypt($value, $unserialize);
    }
}

if (!function_exists('encrypt')) {
    /**
     * 加密给定的值
     *
     * @param mixed $value 待加密的数据
     * @param bool $serialize 是否需要序列化
     * @return string
     */
    function encrypt($value, $serialize = true)
    {
        return Crypt::encrypt($value, $serialize);
    }
}