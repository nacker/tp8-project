<?php
namespace app\services;

use app\model\UserModel;
use app\utils\JwtUtil;
use think\Exception;
use think\facade\Request;
use DateInterval;
use DateTime;
use think\facade\Request as FacadeRequest;

class UserService
{
    /**
     * 用户登录逻辑
     *
     * @param Request $request
     * @return array|\think\response\Json
     */
    public static function login()
    {
        $request = Request::instance();
        $data = $request->post();

        $username = $data['username'];
        $password = $data['password'];
        // 验证必填字段
        if (empty($username) || empty($password)) {
            throw new Exception('用户名或密码不能为空');
        }


        // 查询用户
        $user = UserModel::where('username', $username)->find();
//        if (!$user || !password_verify($data['password'], $user->hashed_password)) {
//            return Result::error('用户名或密码错误', 401);
//        }

        if (!$user || $password !== $user->hashed_password) {
            throw new Exception('用户名或密码错误', 401);
        }

        // 生成 Token
        $expiry = (new DateTime())->add(new DateInterval('P6M'))->getTimestamp();
        $payload = [
            'user_id' => $user->id,
            'username' => $user->username,
            'exp' => $expiry,
        ];
        $token = 'Bearer ' . JwtUtil::generateToken($payload);

        return [
            'token' => $token,
            'user' => $user,
        ];
    }

    /**
     * 用户注册逻辑
     *
     * @return array|\think\response\Json
     */
    public static function register()
    {
        $request = Request::instance();
        $data = $request->post();

        // 验证必填字段
        if (empty($data['username']) || empty($data['password'])) {
            throw new Exception('用户名或密码不能为空', 400);
        }

        // 检查用户名是否已存在
        if (!UserModel::isUsernameUnique($data['username'])) {
            throw new Exception('用户名已存在', 400);
        }

        // 创建新用户
        $user = new UserModel();
        $user->username = $data['username'];
        $user->hashed_password = $data['password'];
        $user->email = $data['email'] ?? null;
        $user->phone = $data['phone'] ?? null;

        if ($user->save()) {
            $u = UserModel::where('username', $user->username)->find();
            $expiry = (new DateTime())->add(new DateInterval('P6M'))->getTimestamp();
            $payload = [
                'user_id' => $u->id,
                'username' => $u->username,
                'exp' => $expiry, // Token 有效期为 1 小时
            ];
            $token = 'Bearer ' . JwtUtil::generateToken($payload);
            return [
                'token' => $token,
                'user' => $u,
            ];
        } else {
            throw new Exception('注册失败，请稍后再试', 500);
        }

    }

    public static function getUserInfoFomeToken()
    {
        // 1. 从 Header 获取 Token
        $token = Request::header('Authorization');

        $userId = JwtUtil::getUserIdFromToken($token);
        $username = JwtUtil::getUsernameFromToken($token);
        return [
            'user_id' => $userId,
            'username' => $username,
        ];
    }
}