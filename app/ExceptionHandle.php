<?php
namespace app;

use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\Handle;
use think\exception\HttpException;
use think\exception\HttpResponseException;
use think\exception\ValidateException;
use think\Response;
use Throwable;

/**
 * 应用异常处理类
 */
class ExceptionHandle extends Handle
{
    /**
     * 不需要记录信息（日志）的异常类列表
     * @var array
     */
    protected $ignoreReport = [
        HttpException::class,
        HttpResponseException::class,
        ModelNotFoundException::class,
        DataNotFoundException::class,
        ValidateException::class,
    ];

    /**
     * 记录异常信息（包括日志或者其它方式记录）
     *
     * @access public
     * @param  Throwable $exception
     * @return void
     */
    public function report(Throwable $exception): void
    {
        // 使用内置的方式记录异常日志
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @access public
     * @param \think\Request   $request
     * @param Throwable $e
     * @return Response
     */
//    public function render($request, Throwable $e): Response
//    {
//        // 添加自定义异常处理机制
//
//        // 其他错误交给系统处理
//        return parent::render($request, $e);
//    }

    public function render($request, Throwable $e): Response
    {

        // 自定义 HTTP 异常处理
        if ($e instanceof HttpException) {
            return json([
                'code' => $e->getStatusCode(), // 使用 HTTP 状态码
                'message' => $e->getMessage() ?: '请求失败', // 自定义消息
                'data' => null,
            ], $e->getStatusCode());
        }

        // 验证器异常处理
        if ($e instanceof ValidateException) {
            return json([
                'code' => 422, // 统一验证错误码
                'message' => $e->getMessage() ?: '参数验证失败', // 自定义消息
                'data' => $e->getError(),
            ], 422);
        }

        // 数据未找到异常处理
        if ($e instanceof DataNotFoundException || $e instanceof ModelNotFoundException) {
            return json([
                'code' => 404, // 统一未找到数据的错误码
                'message' => '数据不存在', // 自定义消息
                'data' => null,
            ], 404);
        }

        // 生产环境隐藏详细错误信息
        if (env('APP_DEBUG')) {
            // 开发环境显示详细错误
            return parent::render($request, $e);
        } else {
            // 记录错误日志
            $this->report($e);

            // 返回友好错误信息
            return json([
                'code' => 500, // 统一服务器错误码
                'message' => '服务器内部错误', // 自定义消息
                'data' => null,
            ], 500);
        }
    }
}
