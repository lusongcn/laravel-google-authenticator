Google 身份验证器与两步验证功能配合，可在您登录 Google 帐户时为您平添一重安全保障。启用两步验证之后，当您登录帐户时，需要提供密码和此应用生成的验证码。配置完成后，无需网络连接或蜂窝连接即可获得验证码。

# 为什么使用两步认证
1. 相对于验证码，安全很多；几乎是不会存在破解的方法
1. 验证码有时候无法识别，不方便操作
1. 一机一码，不会存在账号盗用的问题
1. 动态验证，每30秒生产一个验证码，安全更加保障

# 开发前的准备  
1. 安装Laravel  
1. 安装二维码生成器`QrCode`,没有安装也可以，接下来会安装

# 安装拓展
1、运行如下代码安装拓展包：
```
composer require "earnp/laravel-google-authenticator:dev-master"
# 安装二维码生成器
composer require simplesoftwareio/simple-qrcode 1.3.*

```
3.等待下载安装完成，需要在`config/app.php`中注册服务提供者同时注册下相应门面：
```php
'providers' => [
    //........
    Earnp\GoogleAuthenticator\GoogleAuthenticatorServiceprovider::class,
    SimpleSoftwareIO\QrCode\QrCodeServiceProvider::class,
],

'aliases' => [
     //..........
    'Google' => Earnp\GoogleAuthenticator\Facades\GoogleAuthenticator::class,
    'QrCode' => SimpleSoftwareIO\QrCode\Facades\QrCode::class
],
```
服务注入以后，如果要使用自定义的配置，还可以发布配置文件到config/views目录：
```php
php artisan vendor:publish
```

注意绑定视图位置为`resources/views/login/google/google.blade.php`，然后您可以在`config/google.php`中修改`账号名`和`绑定验证地址`。

# 使用
使用方法非常简单，主要为生成验证码和教研验证码
### 1、生产验证码
生产验证码使用`CreateSecret`即可，你需要将其内容生成二维码供手机APP扫描,具体内容在`google.blade.php`中已经配置成功
```
// 创建谷歌验证码
$createSecret = Google::CreateSecret();
// 您自定义的参数，随表单返回,用于绑定
$parameter = [["name"=>"usename","value"=>"123"],["name"=>"users_id","value"=>encrypt("123")]];
return view('login.google.google', ['createSecret' => $createSecret,"parameter" => $parameter]);
```

### 2、校验验证码
校验验证码一般用于绑定，登录认证中，使用`CheckCode`方法即可，需要传入`secrect`和`onecode`即验证码即可进行校验，第一个为`secrect`；返回`true`或`false`

```
if(Google::CheckCode($google,$request->onecode)) {
    // 绑定场景：绑定成功，向数据库插入google参数，跳转到登录界面让用户登录
    // 登录认证场景：认证成功，执行认证操作
    dd("认证成功");
}
else
{
    // 绑定场景：认证失败，返回重新绑定，刷新新的二维码
    return back()->with('msg','请正确输入手机上google验证码 ！')->withInput();
    // 登录认证场景：认证失败，返回重新绑定，刷新新的二维码
    return back()->with('msg','验证码错误，请输入正确的验证码 ！')->withInput();
}
```

这里有一个具体的实际事例：

```
use Google;


if ($request->isMethod('post')) {
    if (empty($request->onecode) && strlen($request->onecode) != 6) return back()->with('msg','请正确输入手机上google验证码 ！')->withInput();
    // google密钥，绑定的时候为生成的密钥；如果是绑定后登录，从数据库取以前绑定的密钥
    $google = $request->google;
    // 验证验证码和密钥是否相同
    if(Google::CheckCode($google,$request->onecode)) {
        // 绑定场景：绑定成功，向数据库插入google参数，跳转到登录界面让用户登录
        // 登录认证场景：认证成功，执行认证操作
        dd("认证成功");
    }
    else
    {
        // 绑定场景：认证失败，返回重新绑定，刷新新的二维码
        return back()->with('msg','请正确输入手机上google验证码 ！')->withInput();
        // 登录认证场景：认证失败，返回重新绑定，刷新新的二维码
        return back()->with('msg','验证码错误，请输入正确的验证码 ！')->withInput();
    }
}
else
{
    // 创建谷歌验证码
    $createSecret = Google::CreateSecret();
    // 您自定义的参数，随表单返回
    $parameter = [["name"=>"usename","value"=>"123"]];
    return view('login.google.google', ['createSecret' => $createSecret,"parameter" => $parameter]);
}
```

# 使用与帮助

拓展技术支持与问题反馈：[使用laravel-google-authenticator 拓展包为你的网站打造一个动态手机令牌](https://phpartisan.cn/news/49.html) 
