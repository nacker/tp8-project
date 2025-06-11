<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use app\controller\UserController;
use app\services\UserService;
use app\utils\Result;
use think\facade\Request as FacadeRequest;
use think\facade\Route;
use app\middleware\JwtAuth;

// 全局路由示例
Route::get('/', function () {
    // 1. 获取 GET 参数
    $getParams = FacadeRequest::get();

    // 2. 获取 Header
    $token = FacadeRequest::header('Authorization');

    $userInfo = UserService::getUserInfoFomeToken();

    $userAgent = FacadeRequest::header('user-agent');

    // 3. 获取 JSON Body
    $jsonData = json_decode(FacadeRequest::getContent(), true);

    return Result::success([
        'get_params' => $getParams,
        'headers' => [
            'token' => $token,
            'user_agent' => $userAgent
        ],
        'body' => $jsonData,
        'user_info' => $userInfo,
    ] , 'PHP 是世界上最好的语言!');
});

Route::get('hello/:name', 'index/hello');


// 登录和注册接口
Route::group('user', function () {
    Route::post('register', 'UserController/register'); // 用户注册
    Route::post('login', 'UserController/login');       // 用户登录
    Route::get('', [UserController::class, 'index'])->middleware(JwtAuth::class); // 获取用户信息
});