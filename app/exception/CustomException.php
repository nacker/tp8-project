<?php
declare (strict_types = 1);

namespace app\exception;

use app\constants\ErrorConstants;
use think\Exception;

class CustomException extends Exception
{
    /**
     * @param string $errorConstant 错误常量，来自 ErrorConstants 类
     */
    public function __construct(string $errorConstant)
    {
        $errorInfo = ErrorConstants::getErrorInfo($errorConstant);
        $message = $errorInfo['message'];
        $code = $errorInfo['code'];
        parent::__construct($message, $code);
    }
}