<?php

namespace app\modules\test\controllers;

use yii\web\Controller;
use app\commands\util\Dingtalk;
use app\commands\util\Dump;
use app\modules\v1\models\User;
use app\commands\util\dd\DingtalkCrypt;
use app\commands\util\MyMail;
use app\commands\SMS\SMSClient;
use app\commands\util\Util;
use app\commands\util\Excel;


class TsuiteController extends Controller
{
    public function actionIndex()
    {
        echo "测试信息";
        
        $result 		=Dingtalk::getConfig();
        Dump::dump($result);
    }
    
    //微应用创建
    public function actionMicroapp()
    {
    	$agentId 		= 7883439;
    
    	
    	$appIcon 		= "@lALOCLxYjmTNAZA";
    	$appName 		= "靖哥哥";
    	$appDesc 		= "开发测试";
    	$homepageUrl 	= "http://yii.jinggege.com";
    	
 //   	$result 		= Dingtalk::getMicroappInfo($agentId);
 //   	$result 		= Dingtalk::createMicroapp($appIcon, $appName, $appDesc, $homepageUrl);
    	
    	
    	Dump::dump($result);
    }
    
    
    //愚人节小脚本
    public function actionTest()
    {
    	//愚人节小脚本
    	
    	$name 			= "4.1放假安排";
    	$owner 			= "171631584283";
    	$useridlist 	= ['manager9167','171631584283'];
    	 
    	//    		$result 		= Dingtalk::chat_create($name, $owner, $useridlist);
    	//    		Dump::dump($result);
    	 
    	$chatid 		= "chatfd7bf1d11b09720758dad0cb5413b44c";
    	$content 		= "愚人节快乐,楼下保持队形!";
    	 
    	//		$result 		= Dingtalk::chat_send($chatid, $owner, $content);
    	
    	 
    	$user 			= new User();
    	$user_list      = $user->getAllUser();
    	 
    	$list 			= array();
    	foreach ($user_list as $myuser)
    	{
    		$list[]     = $myuser['userid'];
    	}
    	 
    	
    	$result 		= Dingtalk::chat_update($chatid, $list);
    	Dump::dump($result);
    	 
    	 
    	foreach ($user_list as $myuser)
    	{
    		//     		$result 		= Dingtalk::chat_send($chatid, $myuser['userid'], $content);
    		Dump::dump($result);
    	}
    }
    
    
    //聊天会话测试
    public function actionTestsuite()
    {
    	$token 			= "ding9f7f3d8d92150999";
    	$encodingAesKey = "h7mibo2k7eof5xqoq2q2gnvtem125zo7d3eniq0f3kg";
    	$suiteKey 		= "suite4xxxxxxxxxxxxxxx";

    	$dcrypt 		= new DingtalkCrypt($token, $encodingAesKey, $suiteKey);
    	
    	
    	$signature 		= "548f6284c77f266dd4500ffe4eba93b7fd6cf8da";
    	$timeStamp 		= "1459503482918";
    	$nonce 			= "4ms9FKHD";
    	$encrypt 		= "Rus+8AsZySe02BG8tVtT3J3oLBUkoL\/\/4wMIVPcrIJ0BfBMgXvaoWzAmI8Rxr9LCwzDZ28IUREZR9Uar8qBIDg==";
    	
    	
    	$decryptMsg 	= "";
    	$result 		= $dcrypt->DecryptMsg($signature, $timeStamp, $nonce, $encrypt, $decryptMsg);
    	
    	Dump::dump($result);
    	Dump::dump($decryptMsg);
    }

    //开放应用测试 /扫码登录/ 授权/  获取信息
    public function actionTestSns()
    {

    	$tmp_auth_code  = "1971379041c837f98fb426ae6b0c0e7e";
    	$persistentCode = Dingtalk::getSnsPersistentCode($tmp_auth_code);

    	if ($persistentCode)
    	{
    		$openid 		= $persistentCode['openid'];
    		$persistent_code= $persistentCode['persistent_code'];
    		$sns_token 		= Dingtalk::getSnsToken($openid, $persistent_code);
    		
    		$result 		= Dingtalk::getSnsUserInfo($sns_token['sns_token']);
    		Dump::dump($result);
    	}
    	else 
    	{
    		echo "不存在的授权码";
    	}
    	
    	
    }
    
    //邮件发送测试
    public function actionTestMail()
    {
    	$mail 		= new MyMail();
    	
    	$address 	= "jokechat@qq.com";
    	$subject 	= "测试邮件";
    	$body 		= "文本内容";

    	$file 			= $_SERVER['DOCUMENT_ROOT']."css/site.css";
    	$file1 			= $_SERVER['DOCUMENT_ROOT']."css/site1.css";
    	$mail->MailFile = [$file,$file1];
    	
    	$send_result 	= $mail->sendMail($address,$subject,$body);
    	
    	Dump::dump($send_result);
    }
    
    
    //短信发送测试
    public function actionTestSms()
    {
    	$sms 		= new SMSClient();
    	
    	$mobile 	= "17757865247";
    	$code 		= "5678";
    	
    	$result 	= $sms->sendAuthCode($mobile, $code);
    	Dump::dump($result);
    }
    
    public function actionTestExcel()
    {
    	$filepath 		= "D:/WWW/Apache24/htdocs/yii2/basic/web/report/1.xls";
    	
    	$excel 			= new Excel();
    	
    	$result 		= $excel->read_excel($filepath);
    	Dump::dump($result);
    }
    
}
