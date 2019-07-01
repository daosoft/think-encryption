<?php declare (strict_types=1);

namespace think\support\encryption;

use RuntimeException;
use think\helper\Str;
use think\Service;
use think\support\encryption\command\KeyGenerate;

class EncryptionService extends Service
{
    public function register()
    {
        $this->app->bind('encrypter', function () {
            $config = $this->app->config->get('crypt');

            // 如果密钥以"base64:"开头，那么在将密钥交给加密程序之前，我们需要对密钥进行解码。
            // 密钥可能是base-64编码的，我们希望确保在加密之前将它们转换回原始字节。
            if (Str::startsWith($key = $this->key($config), 'base64:')) {
                $key = base64_decode(substr($key, 7));
            }

            return new Encrypter($key, $config['cipher']);
        });
    }

    public function boot()
    {
        $this->commands([
            KeyGenerate::class
        ]);
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
        return tap($config['key'], function ($key) {
            if (empty($key)) {
                throw new RuntimeException(
                    '未指定加密密钥'
                );
            }
        });
    }
}
