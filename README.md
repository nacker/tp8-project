# ThinkPHP 8 RESTful API 脚手架

这是一个基于 ThinkPHP 8 框架开发的 RESTful API 脚手架项目，实现了JWT用户认证、统一响应格式、全局异常处理等基础功能。本项目提供了一个可靠的起点，帮助你快速开发安全、规范的API服务。

## 项目特性

- **基于 ThinkPHP 8.0**：利用最新版本框架的高性能和新特性
- **RESTful API 设计**：符合REST规范的API设计，提供标准化的接口
- **JWT 认证机制**：安全的JSON Web Token用户认证和授权
- **统一的接口响应格式**：标准化的API响应结构，便于前端处理
- **全局异常处理**：集中处理各类异常，提供友好的错误信息
- **跨域支持**：内置CORS支持，轻松处理跨域请求
- **数据验证机制**：请求数据的自动验证，确保数据安全和有效性
- **基础用户管理**：包含用户注册、登录等基础功能
- **代码分层架构**：控制器、服务层、模型层清晰分离，便于维护和扩展

## 系统要求

- PHP >= 8.0
- MySQL >= 5.7
- Composer >= 2.0
- ThinkPHP 8.0

## 快速开始

### 1. 安装项目

```bash
# 克隆项目
git clone [项目地址] tp8-project
cd tp8-project

# 安装依赖
composer install
```

### 2. 配置项目

1. 复制 `.env.example` 为 `.env`
   ```bash
   cp .env.example .env
   ```

2. 配置数据库连接信息
   ```
   [DATABASE]
   TYPE = mysql
   HOSTNAME = 127.0.0.1
   DATABASE = tp8_api
   USERNAME = root
   PASSWORD = root
   HOSTPORT = 3306
   CHARSET = utf8mb4
   DEBUG = true
   ```

3. 配置 JWT 密钥
   ```
   [JWT]
   SECRET = your_jwt_secret_key
   TTL = 7200
   ```

### 3. 初始化数据库

```bash
php think migrate:run
```

## 项目结构

```
app/
├── controller/          # 控制器目录
│   └── UserController.php  # 用户控制器
├── model/              # 模型目录
│   └── UserModel.php   # 用户模型
├── service/            # 服务层目录
│   └── UserService.php # 用户服务
├── middleware/         # 中间件目录
│   └── JwtAuth.php    # JWT认证中间件
├── utils/             # 工具类目录
│   ├── JwtUtil.php    # JWT工具类
│   └── Result.php     # 统一响应工具类
├── constants/         # 常量定义目录
│   └── ErrorConstants.php  # 错误码常量
├── exception/         # 异常处理目录
│   └── CustomException.php # 自定义异常类
├── validate/          # 验证器目录
└── ExceptionHandle.php # 全局异常处理器
```

## API 文档

### 用户模块

#### 注册用户

```
POST /user/register

请求参数：
{
    "username": "string",
    "password": "string",
    "email": "string"
}

响应结果：
{
    "code": 200,
    "message": "注册成功",
    "data": {
        "token": "string",
        "user_info": {
            "id": "int",
            "username": "string",
            "email": "string"
        }
    }
}
```

#### 用户登录

```
POST /user/login

请求参数：
{
    "username": "string",
    "password": "string"
}

响应结果：
{
    "code": 200,
    "message": "登录成功",
    "data": {
        "token": "string",
        "user_info": {
            "id": "int",
            "username": "string",
            "email": "string"
        }
    }
}
```

#### 获取用户信息

```
GET /user

请求头：
Authorization: Bearer {token}

响应结果：
{
    "code": 200,
    "message": "success",
    "data": {
        "id": "int",
        "username": "string",
        "email": "string"
    }
}
```

## 统一响应格式

```json
{
    "code": 200,       // 状态码
    "message": "success", // 提示信息
    "data": null       // 响应数据
}
```

## 错误码说明

- 200: 成功
- 400: 请求参数错误
- 401: 未授权
- 403: 禁止访问
- 404: 资源不存在
- 422: 参数验证失败
- 500: 服务器内部错误

## 异常处理

系统实现了统一的异常处理机制，主要处理以下类型的异常：

1. HTTP异常
2. 验证器异常
3. 数据未找到异常
4. 自定义业务异常
5. 其他系统异常

## 中间件

### JWT认证中间件

- 验证请求头中的 token
- 解析并验证 token 的有效性
- 将用户信息注入到请求对象中

## 安全措施

1. JWT Token 认证
2. 密码加密存储
3. 跨域保护
4. 请求参数验证
5. 统一的异常处理

## 开发建议

1. 遵循 RESTful API 设计规范
2. 使用统一的响应格式
3. 合理使用中间件进行认证和授权
4. 做好参数验证和异常处理
5. 遵循 PSR 规范编写代码

## 部署说明

1. 确保 PHP 运行环境满足要求
2. 配置 Web 服务器（Apache/Nginx）
3. 设置正确的目录权限
4. 配置环境变量
5. 确保日志目录可写

## 贡献指南

1. Fork 项目
2. 创建特性分支
3. 提交代码
4. 创建 Pull Request

## 许可证

[MIT License](LICENSE)
