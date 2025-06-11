<?php
declare (strict_types = 1);

namespace app\controller;

use think\facade\Log;
use think\Request;
use app\model\UserModel;
use app\utils\JwtUtil;
use app\utils\Result;
use app\services\UserService;


class UserController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $userInfo = UserService::getUserInfo();
        $user = UserModel::find($userInfo['user_id']);
        return Result::success($user);
    }


    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
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
    public function register(Request $request)
    {
        return UserService::register($request);
    }

    /**
     * 用户登录
     *
     * @param Request $request
     * @return \think\response\Json
     */
    public function login(Request $request)
    {
        return UserService::login($request);
    }
}
