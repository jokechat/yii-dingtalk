<?php
namespace app\commands\util;
/**
 * 与钉钉服务器交互的api接口
 * @author jokechat
 * @data 2016年3月12日
 */
class Dingtalk
{
	
	//伪需求 设置curl请求头
	private static $headers  = array("Content-type: application/json;charset='utf-8'","Accept: application/json");
	
	/**
	 * 获取access_token 优先从缓存中获取
	 * @return \yii\caching\mixed
	 */
	public static function getAccessToken()
	{
		$cache 			= \Yii::$app->cache;
		$access_token 	= $cache->get('dd_access_token');
		
		if (!$access_token)
		{
			$queryUrl 						= \Yii::$app->params['gettoken_url'];
			$param["corpid"] 				= \Yii::$app->params['corpid'];
			$param["corpsecret"] 			= \Yii::$app->params['corpsecret'];
			$param 							= http_build_query ($param);
			$access_token 					= Curl::callWebServer($queryUrl,$param);
			$access_token['create_time']	= Util::getTime();
			$cache->set("dd_access_token", $access_token,7000);
		}
		return $access_token;
	}
	
	/**
	 * 获取开放应用access_token 此应用暂时仅作为授权可用
	 * @return \yii\caching\mixed
	 * array(4) {
				  ["access_token"] => string(32) "dfd3f8afd8263ede9f7c2c5c26def071"
				  ["errcode"] => int(0)
				  ["errmsg"] => string(2) "ok"
				  ["create_time"] => string(19) "2016-04-07 04:20:12"
				}
	 */
	public static function getSnsAccessToken()
	{
		$cache 			= \Yii::$app->cache;
		$access_token 	= $cache->get('sns_access_token');
		
		if (!$access_token)
		{
			$queryUrl 						= \Yii::$app->params['get_sns_token_url'];
			$param["appid"] 				= \Yii::$app->params['snsappid'];
			$param["appsecret"] 			= \Yii::$app->params['snsappsecret'];
			$param 							= http_build_query ($param);
			$access_token 					= Curl::callWebServer($queryUrl,$param);
			$access_token['create_time']	= Util::getTime();
			$cache->set("sns_access_token", $access_token,7000);
		}
		return $access_token;
	}
	
	/**
	 * 通过临时授权码 换取持久授权码 暂无过期时间
	 * @param string $tmp_auth_code 临时授权码 
	 * @return array
	 * 成功时返回 array(5) {
				  ["errcode"] => int(0)
				  ["errmsg"] => string(2) "ok"
				  ["openid"] => string(13) "V9VOt5gsHLQiE"
				  ["persistent_code"] => string(64) "eXSO2j1C6RkZO9hYmZsbio-IhYgaf3sktwJPYGH4-eMI9bV84lH9RhycxLTR-c_-"
				  ["unionid"] => string(15) "hk0iPcveAKiikiE"
				}
		失败时
			array(2) {
					  ["errcode"] => int(40078)
					  ["errmsg"] => string(27) "不存在的临时授权码"
					}
	 */
	public static function getSnsPersistentCode($tmp_auth_code)
	{
		$flag 							= false;
		$access_token 					= self::getSnsAccessToken()['access_token'];
		
		$queryUrl 						= \Yii::$app->params['get_persistent_code']."access_token=$access_token";
		$param["tmp_auth_code"] 		= $tmp_auth_code;
		$headers['10023']  				= self::$headers;
		
		curl::setOption($headers);
		$auth_info 						= Curl::callWebServer($queryUrl,json_encode($param),'POST');
		
		if ($auth_info['errcode'] ==  0)
		{
			$flag 	= $auth_info ;
		}
		
		return $flag;
	}
	
	
	/**
	 * 获取用户授权的SNS_TOKEN 有效期仅有两个小时
	 * @param string $openid
	 * @param string $persistent_code
	 * @return array
	 * array(4) {
				  ["errcode"] => int(0)
				  ["errmsg"] => string(2) "ok"
				  ["expires_in"] => int(7200)
				  ["sns_token"] => string(32) "d206fdd7b9ad3bcb9918800a324be339"
				}
	 */
	public static function getSnsToken($openid,$persistent_code)
	{
		$access_token 					= self::getSnsAccessToken()['access_token'];
		
		$queryUrl 						= \Yii::$app->params['get_sns_token']."access_token=$access_token";
		$param["openid"] 				= $openid;
		$param["persistent_code"] 		= $persistent_code;
		
		$headers['10023']  				= self::$headers;
		curl::setOption($headers);
		
		$snsToken 						= Curl::callWebServer($queryUrl,json_encode($param),'POST');
		return $snsToken;
	}
	
	/**
	 * 通过sns_token  获取授权用户信息
	 * @param array $sns_token
	 * @return unknown
	 */
	public static function getSnsUserInfo($sns_token)
	{
		$queryUrl 						= \Yii::$app->params['sns_getuserinfo']."sns_token=$sns_token";
		$sns_userinfo 					= Curl::callWebServer($queryUrl);
		return $sns_userinfo;
	}
	
	/**
	 * 获取SSO token(仅供后台免登使用)
	 * @return \yii\caching\mixed
	 */
	public static function getSSOToken()
	{
		$cache 							= \Yii::$app->cache;
		$sso_token  					= $cache->get('dd_sso_token');
		if (!$sso_token)
		{
			$queryUrl 					= \Yii::$app->params['get_sso_token_url'];
			$param["corpid"] 			= \Yii::$app->params['corpid'];
			$param["corpsecret"] 		= \Yii::$app->params['ssosecret'];
			$param 						= http_build_query ($param);
			$sso_token 					= Curl::callWebServer($queryUrl,$param);
			$sso_token['create_time']	= Util::getTime();
			$cache->set("dd_sso_token",$sso_token,7000);
		}
		return $sso_token ;
	}
	
	
	/**
	 * 获取jsapi票据
	 * @return \yii\caching\mixed
	 */
	public static function getJsapiTicket()
	{
		$cache 							= \Yii::$app->cache;
		$jsapi_ticket  					=$cache->get("old_jsapi_ticket");
		if (!$jsapi_ticket)
		{
			$queryUrl 					= \Yii::$app->params['get_jsapi_ticket'];
			$param["access_token"] 		= self::getAccessToken()['access_token'];
			$param 						= http_build_query ($param);
			$jsapi_ticket 				= Curl::callWebServer($queryUrl,$param);
			$jsapi_ticket['create_time']= Util::getTime();
			$cache->set("old_jsapi_ticket",$jsapi_ticket,7000);
		}
		return $jsapi_ticket ;
	}
	
	
	/**
	 * 获取jsapi票据配置信息
	 * @return multitype:string number multitype:
	 */
	public static function getConfig()
	{
		$nonceStr 			= 'jgg';//随机字符串
		$timeStamp 			= time();
		$url 				= 'http://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"];
	
		$jsapiticket 		= self::getJsapiTicket();
		$ticket 			= $jsapiticket['ticket'];
	
		$signature 			= self::sign($ticket, $nonceStr, $timeStamp, $url);
		$config 			= array(
				'url' => $url,
				'nonceStr' => $nonceStr,
				'timeStamp' => $timeStamp,
				'corpId' => \Yii::$app->params['corpid'],
				'signature' => $signature);
	
		return $config;
	}
	

	/**
	 * 进行哈希加密
	 * @param string $ticket
	 * @param string $nonceStr
	 * @param string $timeStamp
	 * @param string $url
	 * @return string
	 */
	private static function sign($ticket, $nonceStr, $timeStamp, $url)
	{
		$plain = 'jsapi_ticket=' . $ticket .
		'&noncestr=' . $nonceStr .
		'&timestamp=' . $timeStamp .
		'&url=' . $url;
		return sha1($plain);
	}
	
	
	
	////以下为对钉钉会话消息的函数方法
	
	//发送会话消息
	public static function message_send($agent_id,$touser,$content)
	{
		$queryUrl 						= \Yii::$app->params['message_send'];
		$param["access_token"] 			= self::getAccessToken()['access_token'];
		
		
		$data['agentid']				= $agent_id;
		$data['touser']					= $touser;

		$data['msgtype']				= $content['type'];
		$msg_type 						= $content['type'];
		$data[$msg_type] 				= $content;
		
		$headers['10023']  				= self::$headers;
		curl::setOption($headers);
		$result 						= Curl::callWebServer($queryUrl.http_build_query($param),json_encode($data,true),'post');
		return $result;
	}

	/**
	 * 上传媒体文件
	 * @param string $media_type image/voice/file
	 * @param string $media_path 文件名
	 * @return
	 array(5) {
	 ["created_at"] => float(1456372507039)
	 ["errcode"] => int(0)
	 ["errmsg"] => string(2) "ok"
	 ["media_id"] => string(13) "@lALOCGt_9CUj"
	 ["type"] => string(5) "image"
	 }
	 */
	public static function media_upload($media_type,$media_path)
	{
		$queryUrl 						= \Yii::$app->params['media_upload'];
		$param["access_token"] 			= self::getAccessToken()['access_token'];
		$param['type'] 					= $media_type;
	
		$data 		   					= array();
		//考虑php5.4 php5.6版本兼容性
		if (class_exists('\CURLFile')) {
			$file 		   				= new \CURLFile(realpath($media_path));;
			$data['media'] 				=$file;
		} else {
			$data['media'] 				='@'.realpath($media_path);
		}
	
		$queryUrl 	   					= $queryUrl.http_build_query($param);
		return Curl::callWebServer($queryUrl, $data, 'POST', 1 , 0);
	}
	
	/**
	 * 每一个授权码 仅且仅能使用一次
	 * @param string  $code
	 * @return 返回成功   返回失败时 errcode 大于0
	 * 	array(6) {
				  ["deviceId"] => string(32) "a7faf132233d4934d32b3666ccc7e26a"
				  ["errcode"] => int(0)
				  ["errmsg"] => string(2) "ok"
				  ["is_sys"] => bool(true)
				  ["sys_level"] => int(1)
				  ["userid"] => string(11) "manager9167"
				}
	 */
	public static function getUserInfoByCode($code)
	{
		$queryUrl 						= \Yii::$app->params['user_getinfo_by_code'];
		$param["access_token"] 			= self::getAccessToken()['access_token'];
		$param['code'] 					= $code;
		
		
		$result 						= Curl::callWebServer($queryUrl.http_build_query($param));
		return $result;
	}
	
	
	/**
	 * 获取微应用可见信息
	 * @param int $agentId 钉钉微应用id
	 * @return Ambigous <boolean, \app\commands\util\mixed>
	 */
	public static function getMicroappInfo($agentId)
	{
		$access_token 					= self::getAccessToken()['access_token'];
		$queryUrl 						= \Yii::$app->params['microapp_visible_scopes']."access_token=$access_token";
		$param['agentId'] 				= $agentId;

		
		$headers['10023']  				= self::$headers; 
		curl::setOption($headers);
		
		$result 						= Curl::callWebServer($queryUrl,json_encode($param,true),"POST");
		return $result;
	}
	
	
	/**
	 * 提交创建微应用 每一次创建都会新建应用 
	 * @param string $appIcon 微应用的图标。需要调用上传接口将图标上传到钉钉服务器后获取到的mediaId
	 * @param string $appName 微应用的名称。长度限制为1~10个字符
	 * @param string $appDesc 微应用的描述。长度限制为1~20个字符
	 * @param string $homepageUrl 微应用的移动端主页，必须以http开头或https开头
	 * @param string $pcHomepageUrl 微应用的PC端主页，必须以http开头或https开头，如果不为空则必须与homepageUrl的域名一致
	 * @param string $ompLink 微应用的OA后台管理主页，必须以http开头或https开头
	 * @return 
				array(3) {
				  ["agentid"] => int(18036933)
				  ["errcode"] => int(0)
				  ["errmsg"] => string(2) "ok"
				}
	 */
	public static function  createMicroapp($appIcon,$appName,$appDesc,$homepageUrl,$pcHomepageUrl = "",$ompLink = "")
	{
		$access_token 					= self::getAccessToken()['access_token'];
		$queryUrl 						= \Yii::$app->params['microapp_create']."access_token=$access_token";
		
		$param['appIcon'] 				= $appIcon;
		$param['appName'] 				= $appName;
		$param['appDesc'] 				= $appDesc;
		$param['homepageUrl'] 			= $homepageUrl;
		$param['pcHomepageUrl'] 		= $pcHomepageUrl;
		$param['ompLink'] 				= $ompLink;
		
		
		$headers['10023']  				= self::$headers;
		curl::setOption($headers);
		
		$result 						= Curl::callWebServer($queryUrl,json_encode($param,true),"POST");
		return $result;
	}
	
	/**
	 * 
	 * @param string $name 群聊名称
	 * @param string $owner 群主
	 * @param array $useridlist 用户列表
	 * @return 
	 * 			array(3) {
						  ["chatid"] => string(36) "chated21c22486496eb445ce97e48379f3d1"
						  ["errcode"] => int(0)
						  ["errmsg"] => string(2) "ok"
						}
	 */
	public static function chat_create($name,$owner,$useridlist)
	{
		$access_token 					= self::getAccessToken()['access_token'];
		$queryUrl 						= \Yii::$app->params['chat_create']."access_token=$access_token";
		
		$param['name'] 					= $name;
		$param['owner'] 				= $owner;
		$param['useridlist'] 			= $useridlist;
		
		$headers['10023']  				= self::$headers;
		curl::setOption($headers);
		
		$result 						= Curl::callWebServer($queryUrl,json_encode($param,true),"POST");
		return $result;
		
	}
	
	
	/**
	 * 发送群会话消息
	 * @param string $chatid  群会话id
	 * @param string $sender 发送者is
	 * @param string $content 发送内容
	 * @param string $msgtype 消息类型  暂时仅支持文本消息
	 * @return array
	 * 	发送成功时			array(2) {
								  ["errcode"] => int(0)
								  ["errmsg"] => string(2) "ok"
								}
	 */
	public static function chat_send($chatid,$sender,$content,$msgtype="text")
	{
		$access_token 					= self::getAccessToken()['access_token'];
		$queryUrl 						= \Yii::$app->params['chat_send']."access_token=$access_token";
		
		$param['chatid'] 				= $chatid;
		$param['sender'] 				= $sender;
		$param['msgtype'] 				= $msgtype;
		$param[$msgtype]['content'] 		= $content;
		
		$headers['10023']  				= self::$headers;
		curl::setOption($headers);
		
		$result 						= Curl::callWebServer($queryUrl,json_encode($param,true),"POST");
		return $result;
	}
	
	
	public static function chat_update($chatid,$add_useridlist,$del_useridlist = null,$name= null,$owner = null)
	{
		$access_token 					= self::getAccessToken()['access_token'];
		$queryUrl 						= \Yii::$app->params['chat_update']."access_token=$access_token";
		
		$param['chatid'] 				= $chatid;
		$param['name'] 					= $name;
		$param['add_useridlist'] 		= $add_useridlist;
		
		$headers['10023']  				= self::$headers;
		curl::setOption($headers);
		
		$result 						= Curl::callWebServer($queryUrl,json_encode($param,true),"POST");
		return $result;
	}
}
?>