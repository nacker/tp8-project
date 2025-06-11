![](https://www.thinkphp.cn/uploads/images/20230630/300c856765af4d8ae758c503185f8739.png)

ThinkPHP 8
===============

## 特性

* 基于PHP`8.0+`重构
* 升级`PSR`依赖
* 依赖`think-orm`3.0+版本
* 全新的`think-dumper`服务，支持远程调试
* 支持`6.0`/`6.1`无缝升级

> ThinkPHP8的运行环境要求PHP8.0+

```shell
php think make:controller UserController
php think make:model UserModel
php think make:migration create_user_table
php think make:seeder UserSeeder

```

```bash
composer require vlucas/phpdotenv
```

```php
// 加载 .env 配置（如果你使用 .env 的话）
if (file_exists(__DIR__ . '/../.env')) {
    Dotenv\Dotenv::createImmutable(__DIR__ . '/../')->load();
}
```

```bash
# 多应用
composer require topthink/think-multi-app
```

```bash
# jwt
composer require firebase/php-jwt predis/predis
composer require firebase/php-jwt
```

## 缓存
```bash
composer require predis/predis
```

## 日志
```shell
composer require symfony/console
```

## 
swagger.json

```bash
composer require hg/apidoc
php think apidoc:publish
```


//        $user->hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);