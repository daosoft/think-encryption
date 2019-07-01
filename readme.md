ThinkPHP Crypt 扩展
===============

ThinkPHP 的加密机制使用的是 OpenSSL 所提供的 AES-256 和 AES-128 加密。强烈建议你使用内建的加密工具，而不是用其它的加密算法。所有 ThinkPHP 加密之后的结果都会使用消息认证码 (MAC) 签名，使其底层值不能在加密后再次修改。

## 安装

使用 Composer 安装

```
composer require ext-think/encryption -vvv
```

## 配置

在使用加密工具之前，你必须先设置 config/crypt.php 配置文件中的 key 选项。你应当使用 php think key:generate 命令来生成密钥，这条命令会使用 PHP 的安全随机字节生成器来构建密钥。如果这个 key 的值没有被正确设置，则所有由 ThinkPHP 加密的值都会是不安全的。

## 使用方法

- 加密一个值

你可以使用辅助函数 encrypt 来加密一个值。所有加密的值都使用 OpenSSL 的 AES-256-CBC 来进行加密。此外，所有加密过的值都会使用消息认证码 (MAC) 来签名，以检测加密字符串是否被篡改过：

```
<?php

namespace app\controller;

use app\model\User;

class UserController
{
    /**
     * 存储用户的保密信息
     */
    public function storeSecret()
    {
        $user = User::find(1);
        $user->secret = encrypt('secret');
        $user->save();
    }
}
```

- 无序列化加密

加密过程中，加密的值 serialize 序列化后传递，允许加密对象和数组。因此，接收加密值的非 PHP 客户端需要对数据进行 unserialize 反序列化。如果想要在不序列化的情况下加密解密值，你可以使用 Crypt Facade 的 encryptString 和 decryptString 方法：

```
use think\lantern\encryption\facade\Crypt;

$encrypted = Crypt::encryptString('Hello world.');

$decrypted = Crypt::decryptString($encrypted);
```

- 解密一个值
你可以使用辅助函数 decrypt 来进行解密。如果该值不能被正确解密，例如 MAC 无效时会抛出异常：

```
use RumtimeException;

try {
    $decrypted = decrypt($encryptedValue);
} catch (RumtimeException $e) {
    //
}
```

## 参考

[illuminate/encryption](https://github.com/illuminate/encryption)

## License

Apache-2.0