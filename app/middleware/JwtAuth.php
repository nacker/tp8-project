<?php
declare (strict_types = 1);

namespace app\middleware;

use app\utils\JwtUtil;
use think\facade\Request;
use think\Response;

class JwtAuth
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response|\think\response\Json
     */
    public function handle($request, \Closure $next)
    {
        // 1. 从 Header 获取 Token
        $token = Request::header('Authorization');
        if (!$token) {
            return Response::create(['code' => 401, 'msg' => 'Token 缺失'], 'json', 401);
        }

        // 2. 验证 Token 格式（Bearer Token）
        if (!preg_match('/Bearer\s(\S+)/', $token, $matches)) {
            return Response::create(['code' => 401, 'msg' => 'Token 格式错误'], 'json', 401);
        }
        $token = $matches[1];

        $decoded = JwtUtil::verifyToken($token);

        if (!$decoded) {
            return json(['code' => 401, 'message' => '无效的 Token'], 401);
        }

        // 将解析后的用户信息附加到请求对象
        $request->user = (array)$decoded;

        return $next($request);
    }
}
