<?php declare (strict_types=1);

namespace lantern\encryption\command;

use lantern\encryption\Encrypter;
use RuntimeException;
use think\console\Command;
use think\console\Input;
use think\console\Output;

class KeyGenerate extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('key:generate')
            ->setDescription('Set the application key');
    }

    protected function execute(Input $input, Output $output)
    {
        $key = $this->generateRandomKey();

        if (!$this->setKeyInEnvironmentFile($key)) {
            return;
        }

        $output->writeln('Application key set successfully.');
    }

    /**
     * 生成随机密钥
     *
     * @return string
     * @throws \Exception
     */
    protected function generateRandomKey()
    {
        return 'base64:' . base64_encode(
                Encrypter::generateKey($this->app->config->get('crypt.cipher'))
            );
    }

    /**
     * 设置随机密钥
     *
     * @param string $key
     * @return bool
     */
    protected function setKeyInEnvironmentFile($key)
    {
        $currentKey = $this->app->config->get('crypt.key');

        if (strlen($currentKey) !== 0) {
            return false;
        }

        $this->writeNewEnvironmentFileWith($key);

        return true;
    }

    /**
     * 写入新的环境变量密钥值
     *
     * @param string $key
     * @return void
     */
    protected function writeNewEnvironmentFileWith($key)
    {
        $envFilePath = app()->getRootPath() . '.env';

        if (file_exists($envFilePath)) {
            $envContents = file_get_contents($envFilePath);

            if (strpos($envContents, 'APP_KEY') !== false) {
                file_put_contents($envFilePath, preg_replace(
                    $this->keyReplacementPattern(),
                    "APP_KEY = '{$key}'",
                    $envContents
                ));
            } else {
                file_put_contents($envFilePath, "APP_KEY = '{$key}'\n" . $envContents);
            }
        }
    }

    /**
     * 获取正则表达式
     *
     * @return string
     */
    protected function keyReplacementPattern()
    {
        return "/^APP_KEY.+/m";
    }
}
