<?php
namespace app\services;

use app\constants\Constants;
use app\constants\ErrorConstants;
use app\model\UserModel;
use app\utils\JwtUtil;
use Exception;
use think\facade\Log;
use think\facade\Request;
use DateInterval;
use DateTime;
use app\exception\CustomException;
use think\facade\Cache;

/**
 * 用户服务类，提供用户登录、注册以及从 Token 获取用户信息的功能
 */
class UserService
{
    /**
     * 用户登录逻辑
     *
     * 该方法处理用户登录请求，验证用户输入的用户名和密码，若验证通过则生成 JWT Token
     *
     * @return array|\think\response\Json 返回包含 Token 和用户信息的数组，若验证失败则抛出异常
     */
    public static function login()
    {
        // 获取当前请求实例
        $request = Request::instance();
        // 获取 POST 请求数据
        $data = $request->post();

        // 从请求数据中提取用户名
        $username = $data['username'];
        // 从请求数据中提取密码
        $password = $data['password'];

        // 验证必填字段：用户名和密码是否为空
        if (empty($username) || empty($password)) {
            // 若为空，抛出用户名或密码不能为空的自定义异常
            throw new CustomException(ErrorConstants::USERNAME_PASSWORD_EMPTY);
        }

        // 根据用户名从数据库中查询用户信息
        $user = UserModel::where('username', $username)->find();

        // 验证用户是否存在以及密码是否正确
        if (!$user || $password !== $user->hashed_password) {
            // 若验证失败，抛出用户名或密码错误的自定义异常
            throw new CustomException(ErrorConstants::USERNAME_PASSWORD_WRONG);
        }

        // 创建一个 DateTime 对象并添加 6 个月的时间间隔，获取
        // 构建 JWT Token 负载信息
        $payload = [
            'user_id' => $user->id, // 用户 ID
            'username' => $user->username, // 用户名
//            'exp' => (new DateTime())->add(new DateInterval('P6M'))->getTimestamp()
        ];
        // 生成 JWT Token 并添加 Bearer 前缀
        $token = 'Bearer ' . JwtUtil::generateToken($payload);

        // 将 token 存入 Redis，不设置过期时间
        Cache::store('redis')->set(Constants::USER_TOKEN_PREFIX . $user->id, $token);
        // 返回包含 Token 和用户信息的数组
        return [
            'token' => $token,
            'user' => $user,
        ];
    }

    /**
     * 用户注册逻辑
     *
     * 该方法处理用户注册请求，验证用户输入的信息，若信息有效则创建新用户并生成 JWT Token
     *
     * @return array|\think\response\Json 返回包含 Token 和用户信息的数组，若注册失败则抛出异常
     */
    public static function register()
    {
        try {
            // 获取当前请求实例和POST数据
            $request = Request::instance();
            $data = $request->post();

            // 验证必填字段
            if (empty($data['username']) || empty($data['password'])) {
                throw new CustomException(ErrorConstants::USERNAME_PASSWORD_EMPTY);
            }

            // 检查用户名唯一性 - 更明确的逻辑表达
            if (!UserModel::isUsernameUnique($data['username'])) {
                Log::warning('注册失败：用户名已存在', ['username' => $data['username']]);
                throw new CustomException(ErrorConstants::USERNAME_EXIST);
            }

            // 创建并保存新用户
            $newUser = new UserModel();
            $newUser->username = $data['username'];
            $newUser->hashed_password = $data['password'];
            $newUser->email = $data['email'] ?? null;
            $newUser->phone = $data['phone'] ?? null;

            if (!$newUser->save()) {
                throw new CustomException(ErrorConstants::REGISTER_FAIL);
            }

            $u = UserModel::where('username', $data['username'])->find();

            // 生成JWT Token
            $token = 'Bearer ' . JwtUtil::generateToken([
                    'user_id' => $u->id,
                    'username' => $u->username,
//                    'exp' => (new DateTime())->add(new DateInterval('P6M'))->getTimestamp()
                ]);

            // 将 token 存入 Redis，不设置过期时间
            Cache::store('redis')->set(Constants::USER_TOKEN_PREFIX . $u->id, $token);

            return [
                'token' => $token,
                'user' => $u,
            ];

        } catch (CustomException $e) {
            // 直接抛出自定义异常，确保不会被转换为500错误
            throw $e;
        } catch (Exception $e) {
            Log::error('注册过程异常: ' . $e->getMessage(), ['exception' => $e]);
            throw new CustomException(ErrorConstants::SERVER_ERROR);
        }
    }

    /**
     * 从 Token 获取用户信息
     *
     * 该方法从请求头中获取 JWT Token，并从中提取用户 ID 和用户名
     *
     * @return array|true
     */
    public static function logout()
    {
        $token = Request::header('Authorization');
        
        if (empty($token)) {
            throw new CustomException(ErrorConstants::TOKEN_MISSING);
        }

        $userId = JwtUtil::getUserIdFromToken($token);
        
        // 从 Redis 中删除用户的 token
        Cache::store('redis')->delete(Constants::USER_TOKEN_PREFIX . $userId);

        return true;
    }
    public static function getUserInfoFromToken()
    {
        // 从请求头中获取 Authorization 字段的值，即 JWT Token
        $token = Request::header('Authorization');

        // 检查 token 是否为空
        if (empty($token)) {
            throw new CustomException(ErrorConstants::TOKEN_MISSING);
        }
  
          // 从 Token 中提取用户 ID
          $userId = JwtUtil::getUserIdFromToken($token);
          $username = JwtUtil::getUsernameFromToken($token);
        // 返回包含用户 ID 和用户名的数组
        return [
            'user_id' => $userId,
            'username' => $username,
        ];
    }
}