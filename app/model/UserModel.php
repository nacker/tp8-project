<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * 用户模型类
 * @mixin \think\Model
 */
class UserModel extends Model
{
    // 设置表名
    protected $table = 'users';

    // 设置主键
    protected $pk = 'id';

    // 设置字段信息
    protected $schema = [
        'id'             => 'bigint',         // 用户ID，自增主键
        'username'       => 'varchar(50)',    // 用户名，允许为空
        'email'          => 'varchar(100)',   // 电子邮箱，允许为空
        'hashed_password'=> 'varchar(100)',   // 加密后的密码，允许为空
        'phone'          => 'varchar(20)',    // 手机号码，允许为空
        'create_time'    => 'timestamp',      // 创建时间
        'update_time'    => 'timestamp',      // 更新时间
    ];

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    // 时间字段的类型
    protected $dateFormat = 'Y-m-d H:i:s';

    // 创建时间字段
    const CREATED_AT = 'create_time';

    // 更新时间字段
    const UPDATED_AT = 'update_time';

    // 设置不允许输出的字段
    protected $hidden = ['hashed_password'];

    /**
     * 检查用户名是否唯一
     * @param string $username
     * @return bool
     */
    public static function isUsernameUnique(string $username): bool
    {
        return !self::where('username', $username)->find();
    }

    /**
     * 检查邮箱是否唯一
     * @param string $email
     * @return bool
     */
    public static function isEmailUnique(string $email): bool
    {
        return !self::where('email', $email)->find();
    }

    /**
     * 检查手机号是否唯一
     * @param string $phone
     * @return bool
     */
    public static function isPhoneUnique(string $phone): bool
    {
        return !self::where('phone', $phone)->find();
    }
}
