<?php
namespace app\utils;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtUtil
{
    // 密钥，可在配置文件中定义，这里简单硬编码
    private static $key = 'your_secret_key';
    // 签名算法
    private static $alg = 'HS256';

    /**
     * 生成 JWT Token
     * @param array $payload 负载信息
     * @return string
     */
    public static function generateToken(array $payload): string
    {
        $token = JWT::encode($payload, self::$key, self::$alg);
        return $token;
    }

    /**
     * 验证并解析 JWT Token
     * @param string $token
     * @return object|false
     */
    public static function verifyToken(string $token)
    {
        try {
            $decoded = JWT::decode($token, new Key(self::$key, self::$alg));
            return $decoded;
        } catch (\Exception $e) {
            return false;
        }
    }
}