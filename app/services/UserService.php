<?php
namespace app\services;

use app\model\UserModel;
use app\utils\JwtUtil;
use app\utils\Result;

use think\facade\Log;
use think\Request;

class UserService
{
    /**
     * 用户登录逻辑
     *
     * @param Request $request
     * @return \think\response\Json
     */
    public static function login(Request $request)
    {
        $data = $request->post();

        $username = $data['username'];
        $password = $data['password'];
        // 验证必填字段
        if (empty($username) || empty($password)) {
            return Result::error('用户名或密码不能为空', 400);
        }

        // 查询用户
        $user = UserModel::where('username', $username)->find();
//        if (!$user || !password_verify($data['password'], $user->hashed_password)) {
//            return Result::error('用户名或密码错误', 401);
//        }

        if (!$user || $password !== $user->hashed_password) {
            return Result::error('用户名或密码错误', 401);
        }

        // 生成 JWT Token
        $payload = [
            'user_id' => $user->id,
            'username' => $user->username,
            'exp' => time() + 3600, // Token 有效期为 1 小时
        ];
        $token = JwtUtil::generateToken($payload);

        return Result::success(['token' => $token,  'user' => $user], '登录成功');
    }

    /**
     * 用户注册逻辑
     *
     * @param Request $request
     * @return \think\response\Json
     */
    public static function register(Request $request)
    {
        $data = $request->post();

        // 验证必填字段
        if (empty($data['username']) || empty($data['password'])) {
            return Result::error('用户名或密码不能为空', 400);
        }

        // 检查用户名是否已存在
        if (!UserModel::isUsernameUnique($data['username'])) {
            return Result::error('用户名已存在', 400);
        }

        // 创建新用户
        $user = new UserModel();
        $user->username = $data['username'];
        $user->hashed_password = $data['password'];
        $user->email = $data['email'] ?? null;
        $user->phone = $data['phone'] ?? null;

        if ($user->save()) {
            $u = UserModel::where('username', $user->username)->find();
            $payload = [
                'user_id' => $u->id,
                'username' => $u->username,
                'exp' => time() + 3600, // Token 有效期为 1 小时
            ];
//            Log::info('用户保存成功，主键值：' . $u->id);
            $token = JwtUtil::generateToken($payload);
//            Log::info('用户保存成功，主键值：' . $user->id);
            return Result::success( ['token' => $token,  'user' => $u], '注册成功');
        } else {
//            Log::error('用户保存失败');
            return Result::error('注册失败，请稍后再试', 500);
        }
    }
}