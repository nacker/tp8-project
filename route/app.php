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
use app\utils\Result;
use think\facade\Route;

// 全局路由示例
Route::get('/', function () {
    return Result::success('Hello,ThinkPHP8');
});

Route::get('hello/:name', 'index/hello');


// 登录和注册接口
Route::group('user', function () {
    Route::post('register', 'UserController/register'); // 用户注册
    Route::post('login', 'UserController/login');       // 用户登录
});
