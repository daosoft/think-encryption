<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2019 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: wanganlin <2797712@qq.com>
// +----------------------------------------------------------------------

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
        return app('encrypter')->decrypt($value, $unserialize);
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
        return app('encrypter')->encrypt($value, $serialize);
    }
}