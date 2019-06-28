<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: wanganlin <2797712@qq.com>
// +----------------------------------------------------------------------
declare (strict_types=1);

namespace think\encryption;

/**
 * 加密解密驱动接口
 * @package think\encryption
 */
interface EncrypterInterface
{
    /**
     * 加密
     *
     * @param mixed $value 待加密的数据
     * @param bool $serialize 是否序列化
     * @return string
     */
    public function encrypt($value, $serialize = true);

    /**
     * 解密
     *
     * @param string $payload 加密的数据
     * @param bool $unserialize 是否反序列化
     * @return mixed
     */
    public function decrypt($payload, $unserialize = true);
}