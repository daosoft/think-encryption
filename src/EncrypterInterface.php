<?php declare (strict_types=1);

namespace think\lantern\encryption;

/**
 * 加密解密驱动接口
 * @package lantern\encryption
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