<?php 
namespace Earnp\GoogleAuthenticator;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Earnp\GoogleAuthenticator\Librarys\GoogleAuthenticator as GoogleSecretAuthenticator;

class GoogleAuthenticator
{
    /**
     * Show the application welcome screen to the user.
     *
     * @return Response
     */

    public static function CheckCode($secret,$oneCode)
    {
        $google = new GoogleSecretAuthenticator();
        $checkResult = $google->verifyCode($secret,$oneCode, 2);//对传入的参数进行校验
        if ($checkResult) return true;//校验成功
        return false;//校验失败
        
    }

    public static function CreateSecret()
    {
        $google = new GoogleSecretAuthenticator();
        $secret = $google->createSecret();//创建一个Secret
        $qrCodeUrl="otpauth://totp/".config("google.authenticatorname")."?secret=".$secret;//二维码中填充的内容
        $googlesecret = array('secret' =>$secret ,'codeurl'=>$qrCodeUrl);
        return $googlesecret;
    }

}