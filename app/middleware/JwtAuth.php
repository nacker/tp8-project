<?php
declare (strict_types = 1);

namespace app\middleware;

use app\constants\Constants;
use app\utils\JwtUtil;
use think\facade\Cache;
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

        $decoded = JwtUtil::verifyToken($token);

        if (!$decoded) {
            return json(['code' => 401, 'message' => '无效的 Token'], 401);
        }

//        redis 获取 token
        $redisToken = Cache::store('redis')->get(Constants::USER_TOKEN_PREFIX . $decoded->user_id);
        if ($redisToken !== $token) {
            return json(['code' => 401, 'message' => 'Token 已过期'], 401);
        }


        // 将解析后的用户信息附加到请求对象
        $request->user = (array)$decoded;

        return $next($request);
    }
}
