<?php
namespace app\middleware;

use app\utils\JwtUtil;
use think\Response;

class JwtAuth
{
    public function handle($request, \Closure $next): Response
    {
        // 从请求头中获取 Token
        $token = $request->header('Authorization');
        if (!$token) {
            return response()->code(401)->data(['message' => '未提供 Token']);
        }

        // 去除 Token 前缀
        $token = str_replace('Bearer ', '', $token);

        // 验证 Token
        $payload = JwtUtil::verifyToken($token);
        if (!$payload) {
            return response()->code(401)->data(['message' => '无效的 Token']);
        }

        // 将解析后的负载信息添加到请求对象中，方便后续使用
        $request->jwtPayload = $payload;

        return $next($request);
    }
}