<?php

use think\facade\Env;

return [
    'key' => Env::get('APP_KEY', ''),

    'cipher' => 'AES-256-CBC',
];
