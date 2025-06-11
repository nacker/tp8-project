<?php
declare (strict_types = 1);

namespace app\controller;

use app\BaseController;
use think\facade\Log;
use think\Request;
use app\model\UserModel;
use app\utils\JwtUtil;
use app\utils\Result;
use app\services\UserService;


class UserController extends BaseController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $userInfo = UserService::getUserInfoFomeToken();
        $user = UserModel::find($userInfo['user_id']);
        return Result::success($user);
    }


    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }

    /**
     * 用户注册
     *
     * @param Request $request
     * @return \think\response\Json
     */
    public function register()
    {
        $result = UserService::register();
        return Result::success($result, '注册成功');
    }

    /**
     * 用户登录
     *
     * @return \think\response\Json
     */
    public function login()
    {
        $result = UserService::login();
        return Result::success($result, '登录成功');
    }
}
