<?php
// 全局中间件定义文件
return [
    // 全局请求缓存
    // \think\middleware\CheckRequestCache::class,
    // 多语言加载
    // \think\middleware\LoadLangPack::class,
    // Session初始化
    // \think\middleware\SessionInit::class

    // 全局中间件
    \app\middleware\PrettyRequestLogger::class,
    \think\middleware\AllowCrossDomain::class,
    // 使用完整类名
//    \app\middleware\JwtAuth::class
//    'JwtAuth' => app\middleware\JwtAuth::class

];
