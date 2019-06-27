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
     * ���ܸ�����ֵ
     *
     * @param string $value �����ܵ�����
     * @param bool $unserialize �Ƿ���Ҫ�����л�
     * @return mixed
     */
    function decrypt($value, $unserialize = true)
    {
        return app('encrypter')->decrypt($value, $unserialize);
    }
}

if (!function_exists('encrypt')) {
    /**
     * ���ܸ�����ֵ
     *
     * @param mixed $value �����ܵ�����
     * @param bool $serialize �Ƿ���Ҫ���л�
     * @return string
     */
    function encrypt($value, $serialize = true)
    {
        return app('encrypter')->encrypt($value, $serialize);
    }
}