<?php
declare (strict_types = 1);

namespace app;

use OpenApi\Annotations as OA;
use think\App;
use think\exception\ValidateException;
use think\Validate;

/**
 * 控制器基础类
 */
abstract class BaseController
{
    /**
     * Request实例
     * @var \think\Request
     */
    protected $request;

    /**
     * 应用实例
     * @var \think\App
     */
    protected $app;

    /**
     * 是否批量验证
     * @var bool
     */
    protected $batchValidate = false;

    /**
     * 控制器中间件
     * @var array
     */
    protected $middleware = [];

    /**
     * 构造方法
     * @access public
     * @param  App  $app  应用对象
     */
    public function __construct(App $app)
    {
        $this->app     = $app;
        $this->request = $this->app->request;

        // 设置跨域响应头
//        $this->setCorsHeaders();

        // 控制器初始化
        $this->initialize();
    }

    /**
     * 设置跨域响应头
     */
    private function setCorsHeaders()
    {
        $origin = $this->request->header('Origin');
        // 允许的域名列表，* 表示允许所有域名，实际生产环境建议指定具体域名
        $allowOrigins = [
            '*'
        ];

        if (in_array('*', $allowOrigins) || in_array($origin, $allowOrigins)) {
            // 设置跨域响应头
            header('Access-Control-Allow-Origin: ' . $origin);
            header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
            header('Access-Control-Allow-Headers: Authorization, Content-Type, X-Requested-With, Accept, Origin, User-Agent, Cache-Control, Pragma');
            header('Access-Control-Allow-Credentials: true');
        }

        // 处理 OPTIONS 请求
        if ($this->request->method(true) === 'OPTIONS') {
            exit;
        }
    }

    // 初始化
    protected function initialize()
    {}

    /**
     * 验证数据
     * @access protected
     * @param  array        $data     数据
     * @param  string|array $validate 验证器名或者验证规则数组
     * @param  array        $message  提示信息
     * @param  bool         $batch    是否批量验证
     * @return array|string|true
     * @throws ValidateException
     */
    protected function validate(array $data, string|array $validate, array $message = [], bool $batch = false)
    {
        if (is_array($validate)) {
            $v = new Validate();
            $v->rule($validate);
        } else {
            if (strpos($validate, '.')) {
                // 支持场景
                [$validate, $scene] = explode('.', $validate);
            }
            $class = false !== strpos($validate, '\\') ? $validate : $this->app->parseClass('validate', $validate);
            $v     = new $class();
            if (!empty($scene)) {
                $v->scene($scene);
            }
        }

        $v->message($message);

        // 是否批量验证
        if ($batch || $this->batchValidate) {
            $v->batch(true);
        }

        return $v->failException(true)->check($data);
    }

}
