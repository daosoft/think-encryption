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

namespace think\encryption;

use RuntimeException;
use think\Service;

class EncryptionService extends Service
{
    public function register()
    {
        $this->app->bind('encrypter', function () {
            $config = $this->app->config;

            // 如果密钥以"base64:"开头，那么在将密钥交给加密程序之前，我们需要对密钥进行解码。
            // 密钥可能是base-64编码的，我们希望确保在加密之前将它们转换回原始字节。
            if ($this->startsWith($key = $this->key($config), 'base64:')) {
                $key = base64_decode(substr($key, 7));
            }

            return new Encrypter($key, $config['crypt.cipher']);
        });
    }

    /**
     * 从给定配置中提取加密密钥
     *
     * @param array $config
     * @return string
     * @throws RuntimeException
     */
    protected function key(array $config)
    {
        return tap($config['crypt.key'], function ($key) {
            if (empty($key)) {
                throw new RuntimeException(
                    '未指定加密密钥'
                );
            }
        });
    }

    /**
     * 检查字符串是否以某些字符串开头
     *
     * @param  string       $haystack
     * @param  string|array $needles
     * @return bool
     */
    public function startsWith($haystack, $needles)
    {
        foreach ((array) $needles as $needle) {
            if ($needle != '' && mb_strpos($haystack, $needle) === 0) {
                return true;
            }
        }

        return false;
    }
}
