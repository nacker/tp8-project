<?php
namespace app\constants;

class ErrorConstants
{
    // 定义错误常量
    public const USERNAME_PASSWORD_EMPTY = 'USERNAME_PASSWORD_EMPTY';
    public const USERNAME_PASSWORD_WRONG = 'USERNAME_PASSWORD_WRONG';
    public const USERNAME_EXIST = 'USERNAME_EXIST';  // 用户已存在
    public const REGISTER_FAIL = 'REGISTER_FAIL'; // 注册失败，请稍后再试
    public const LOGIN_FAIL = 'LOGIN_FAIL'; // 登录失败，请稍后再试
    public const USER_NOT_FOUND = 'USER_NOT_FOUND'; //用户不存在
    public const USER_NOT_LOGIN = 'USER_NOT_LOGIN'; //用户未登录
    public const USER_NOT_AUTHORIZED = 'USER_NOT_AUTHORIZED'; //用户未授权
    public const TOKEN_EXPIRED = 'TOKEN_EXPIRED'; //token已过期
    public const TOKEN_INVALID = 'TOKEN_INVALID'; //token无效
    public const TOKEN_MISSING = 'TOKEN_MISSING'; //token缺失
    public const TOKEN_ERROR = 'TOKEN_ERROR'; //token错误
    public const SERVER_ERROR = 'SERVER_ERROR';//服务器错误


    // 错误信息映射数组
    private static array $errorMap = [
        self::USERNAME_PASSWORD_EMPTY => [
            'code' => 10001,
            'message' => '用户名或密码不能为空'
        ],
        self::USERNAME_PASSWORD_WRONG => [
            'code' => 10002,
            'message' => '用户名或密码错误'
        ],
        self::USERNAME_EXIST => [
            'code' => 10003,
           'message' => '用户已存在'
        ],
        self::REGISTER_FAIL => [
            'code' => 10004,
            'message' => '注册失败，请稍后再试'
        ],
        self::SERVER_ERROR => [
            'code' => 500,
           'message' => '未知错误'
        ]
    ];

    /**
     * 根据错误常量获取错误信息和错误码
     *
     * @param string $errorConstant 错误常量
     * @return array 包含错误码和错误信息的数组，若未找到则返回默认错误信息
     */
    public static function getErrorInfo(string $errorConstant): array
    {
        return self::$errorMap[$errorConstant] ?? [
            'code' => 500,
            'message' => '未知错误'
        ];
    }
}