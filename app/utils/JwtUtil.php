<?php
namespace app\utils;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use think\Exception;
use think\Response;

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
        // 2. 验证 Token 格式（Bearer Token）
        if (!preg_match('/Bearer\s(\S+)/', $token, $matches)) {
            throw new Exception('Token 格式错误', 401);
        }
        $token = $matches[1];

        try {
            $decoded = JWT::decode($token, new Key(self::$key, self::$alg));
            return $decoded;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 从 JWT Token 中获取用户 ID
     * @param string $token
     * @return int|false 用户 ID 或验证失败返回 false
     */
    public static function getUserIdFromToken(string $token)
    {
        $decoded = self::verifyToken($token);
        if ($decoded && isset($decoded->user_id)) {
            return (int) $decoded->user_id;
        }
        return false;
    }

    /**
     * 从 JWT Token 中获取用户名
     * @param string $token
     * @return string|false 用户名或验证失败返回 false
     */
    public static function getUsernameFromToken(string $token)
    {
        $decoded = self::verifyToken($token);
        if ($decoded && isset($decoded->username)) {
            return (string) $decoded->username;
        }
        return false;
    }
}