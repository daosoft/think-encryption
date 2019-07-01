<?php declare (strict_types=1);

namespace lantern\encryption;

use RuntimeException;

/**
 * 加密解密类
 * @package lantern\encryption
 */
class Encrypter implements EncrypterInterface
{
    /**
     * 加密密钥
     *
     * @var string
     */
    protected $key;

    /**
     * 加密算法
     *
     * @var string
     */
    protected $cipher;

    /**
     * 构造函数
     *
     * @param string $key 加密密钥
     * @param string $cipher 加密算法
     * @return void
     * @throws \Exception
     */
    public function __construct($key, $cipher = 'AES-128-CBC')
    {
        $key = (string)$key;
        if (static::supported($key, $cipher)) {
            $this->key = $key;
            $this->cipher = $cipher;
        } else {
            throw new RuntimeException('不支持的加密算法');
        }
    }

    /**
     * 检查加密密钥及算法是否有效
     *
     * @param string $key 加密密钥
     * @param string $cipher 加密算法
     * @return bool
     */
    public static function supported($key, $cipher)
    {
        $length = mb_strlen($key, '8bit');

        return ($cipher === 'AES-128-CBC' && $length === 16) ||
            ($cipher === 'AES-256-CBC' && $length === 32);
    }

    /**
     * 为给定加密算法创建新的加密密钥
     *
     * @param string $cipher 加密算法
     * @return string
     * @throws \Exception
     */
    public static function generateKey($cipher)
    {
        return random_bytes($cipher === 'AES-128-CBC' ? 16 : 32);
    }

    /**
     * 加密给定的值
     *
     * @param mixed $value 待加密的数据
     * @param bool $serialize 是否序列化
     * @return string
     * @throws \Exception
     */
    public function encrypt($value, $serialize = true)
    {
        $iv = random_bytes(openssl_cipher_iv_length($this->cipher));

        // 使用OpenSSL加密
        $value = \openssl_encrypt(
            $serialize ? serialize($value) : $value,
            $this->cipher, $this->key, 0, $iv
        );

        if ($value === false) {
            throw new RuntimeException('无法加密数据');
        }

        // 对向量进行base64_encode，并为加密值创建MAC，以便验证其真实性。
        $mac = $this->hash($iv = base64_encode($iv), $value);

        $json = json_encode(compact('iv', 'value', 'mac'));

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException('无法加密数据');
        }

        return base64_encode($json);
    }

    /**
     * 加密字符串而不进行序列化
     *
     * @param string $value 待加密字符串
     * @return string
     * @throws \Exception
     */
    public function encryptString($value)
    {
        return $this->encrypt($value, false);
    }

    /**
     * 解密给定的值
     *
     * @param string $payload 加密的数据
     * @param bool $unserialize 是否反序列化
     * @return mixed|string
     * @throws \Exception
     */
    public function decrypt($payload, $unserialize = true)
    {
        $payload = $this->getJsonPayload($payload);

        $iv = base64_decode($payload['iv']);

        $decrypted = \openssl_decrypt(
            $payload['value'], $this->cipher, $this->key, 0, $iv
        );

        if ($decrypted === false) {
            throw new RuntimeException('Could not decrypt the data.');
        }

        return $unserialize ? unserialize($decrypted) : $decrypted;
    }

    /**
     * 解密给定字符串而不进行反序列化
     *
     * @param string $payload 加密的字符串
     * @return mixed|string
     * @throws \Exception
     */
    public function decryptString($payload)
    {
        return $this->decrypt($payload, false);
    }

    /**
     * 为给定值创建MAC
     *
     * @param string $iv
     * @param mixed $value
     * @return string
     */
    protected function hash($iv, $value)
    {
        return hash_hmac('sha256', $iv . $value, $this->key);
    }

    /**
     * 从Payload中获取有效数组
     *
     * @param string $payload
     * @return array
     * @throws \Exception
     */
    protected function getJsonPayload($payload)
    {
        $payload = json_decode(base64_decode($payload), true);

        if (!$this->validPayload($payload)) {
            throw new RuntimeException('The payload is invalid.');
        }

        if (!$this->validMac($payload)) {
            throw new RuntimeException('The MAC is invalid.');
        }

        return $payload;
    }

    /**
     * 验证Payload是否有效
     *
     * @param mixed $payload
     * @return bool
     */
    protected function validPayload($payload)
    {
        return is_array($payload) && isset($payload['iv'], $payload['value'], $payload['mac']) &&
            strlen(base64_decode($payload['iv'], true)) === openssl_cipher_iv_length($this->cipher);
    }

    /**
     * 验证Payload的MAC是否有效
     *
     * @param array $payload
     * @return bool
     * @throws \Exception
     */
    protected function validMac(array $payload)
    {
        $calculated = $this->calculateMac($payload, $bytes = random_bytes(16));

        return hash_equals(
            hash_hmac('sha256', $payload['mac'], $bytes, true), $calculated
        );
    }

    /**
     * 生成带有密钥的哈希值
     *
     * @param array $payload
     * @param string $bytes
     * @return string
     */
    protected function calculateMac($payload, $bytes)
    {
        return hash_hmac(
            'sha256', $this->hash($payload['iv'], $payload['value']), $bytes, true
        );
    }

    /**
     * 获取加密密钥
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }
}