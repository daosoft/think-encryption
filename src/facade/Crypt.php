<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2019 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: wanganlin <2797712@qq.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace think\encryption\facade;

use think\encryption\Encrypter;
use think\Facade;

/**
 * @method static bool supported(string $key, string $cipher)
 * @method static string generateKey(string $cipher)
 * @method static string encrypt(mixed $value, bool $serialize = true)
 * @method static string encryptString(string $value)
 * @method static mixed decrypt(string $payload, bool $unserialize = true)
 * @method static string decryptString(string $payload)
 * @method static string getKey()
 *
 * @see \think\encryption\Encrypter
 */
class Crypt extends Facade
{
    protected static function getFacadeClass()
    {
        return Encrypter::class;
    }
}