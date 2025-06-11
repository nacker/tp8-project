<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2019 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\App;

// [ 应用入口文件 ]

require __DIR__ . '/../vendor/autoload.php';

// 加载 .env 配置（如果你使用 .env 的话）
//if (file_exists(__DIR__ . '/../.env')) {
//    Dotenv\Dotenv::createImmutable(__DIR__ . '/../')->load();
//    // 可添加调试信息确认是否加载成功
//    echo "Loaded .env file";
//    exit;
//} else {
//    echo ".env file not found";
//    exit;
//}

// 执行HTTP应用并响应
$http = (new App())->http;

$response = $http->run();

$response->send();

$http->end($response);
