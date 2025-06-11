<?php
declare(strict_types=1);

namespace app\utils;

use think\response\Json;

class Result
{
    /**
     * 成功响应
     * @param mixed $data 返回数据
     * @param string $message 提示信息
     * @param int $code 状态码
     * @return Json
     */
    public static function success($data = null, string $message = 'success', int $code = 200): Json
    {
        return json([
            'code' => $code,
            'message' => $message,
            'data' => $data,
        ]);
    }

    /**
     * 错误响应
     * @param string $message 错误信息
     * @param int $code 错误码
     * @param mixed $data 可选数据
     * @return Json
     */
    public static function error(string $message = 'error', int $code = 500, $data = null): Json
    {
        return json([
            'code' => $code,
            'message' => $message,
            'data' => $data,
        ]);
    }
}
