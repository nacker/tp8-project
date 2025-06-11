<?php
// 应用公共文件

//use Firebase\JWT\JWT;
//use Firebase\JWT\Key;
//use think\facade\Redis as RedisFacade;
//
///**
// * 单点登录令牌生成（带自动续期）
// */
//function jwt_encode(array $payload): string
//{
//    $config = config('jwt');
//    $redis = RedisFacade::connection('jwt');
//
//    // 单设备登录控制
//    if ($userId = $payload['sub'] ?? null) {
//        if ($oldJti = $redis->get("sso:user:{$userId}")) {
//            $redis->del("sso:token:{$oldJti}");
//        }
//        $jti = md5(uniqid().$userId);
//        $redis->setex("sso:user:{$userId}", $config['expire'], $jti);
//        $redis->setex("sso:token:{$jti}", $config['expire'], $userId);
//        $payload['jti'] = $jti;
//    }
//
//    // 自动续期时间戳
//    $payload['exp'] = time() + $config['expire'];
//
//    return JWT::encode($payload, $config['secret'], $config['algorithm']);
//}
//
///**
// * 令牌验证与自动续期
// */
//function jwt_decode(string $token): array
//{
//    $config = config('jwt');
//    try {
//        $decoded = JWT::decode($token, new Key($config['secret'], $config['algorithm']));
//        $payload = (array)$decoded;
//
//        // Redis验证流程
//        $redis = Redis::connection('jwt');
//        $jti = $payload['jti'] ?? '';
//
//        if (empty($jti) || !$redis->exists("sso:token:{$jti}")) {
//            throw new \Exception('令牌已失效');
//        }
//
//        // 自动续期机制
//        if ($config['autorenew'] && ($redis->ttl("sso:token:{$jti}") < $config['expire']/2)) {
//            $redis->expire("sso:token:{$jti}", $config['expire']);
//            $redis->expire("sso:user:{$payload['sub']}", $config['expire']);
//        }
//
//        return $payload;
//    } catch (\Exception $e) {
//        throw new \Exception("鉴权失败: {$e->getMessage()}");
//    }
//}
//
///**
// * 主动注销令牌
// */
//function jwt_revoke(string $jti): bool
//{
//    $redis = Redis::connection('jwt');
//    if ($userId = $redis->get("sso:token:{$jti}")) {
//        $redis->del(["sso:user:{$userId}", "sso:token:{$jti}"]);
//        return true;
//    }
//    return false;
//}