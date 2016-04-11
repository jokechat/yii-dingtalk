<?php
namespace app\commands\SMS\YunPian;
use app\commands\util\Curl;

class YPClient
{
	
	private function get_user()
	{
		$queryUrl = "https://sms.yunpian.com/v1/user/get.json?apikey=";
		
		$yp_config = YP_Config::getConfig ();
		return Curl::callWebServer ( $queryUrl . $yp_config ['jgg_apikey'] );
		
	}
	
	
	
	/**
	 * 发送登录短信
	 * @param string $phone 手机号码
	 * @param int $code 验证码
	 */
	public function sendAuthCode($phone,$code)
	{
		//发送短信网址
		$queryUrl 				= "https://sms.yunpian.com/v1/sms/send.json";
		$yp_config 				= YP_Config::getConfig ();
		$yp_tpl 				= YP_Config::getSmsTpl();
		$yp_tpl['login_code'] 	= str_replace("#content#", $code, $yp_tpl['login_code']);
		$query_data['apikey'] 	= $yp_config['jgg_apikey'];
		$query_data['mobile'] 	= $phone;
		$query_data['text']  	= $yp_tpl['login_code'];
		$result 			 	= Curl::callWebServer($queryUrl,http_build_query($query_data),"post");
		return $result;
	}
	

	/**
	 * 发货通知短信
	 * @param string $phone  手机号码
	 * @param string $express 物流公司
	 * @param unknown $postcode 快递单号
	 */
	public function delivery_notice($phone,$express,$postcode)
	{
		//发送短信网址
		$queryUrl 						= "https://sms.yunpian.com/v1/sms/send.json";
		$yp_config 						= YP_Config::getConfig ();
		$yp_tpl 						= YP_Config::getSmsTpl();
		$yp_tpl['delivery_notice']		= str_replace("#express#", $express, $yp_tpl['delivery_notice']);
		$yp_tpl['delivery_notice'] 		= str_replace("#postcode#", $postcode, $yp_tpl['delivery_notice']);
		$query_data['apikey'] 			= $yp_config['jgg_apikey'];
		$query_data['mobile'] 			= $phone;
		$query_data['text']  			= $yp_tpl['delivery_notice'];
		$result 			 			= Curl::callWebServer($queryUrl,http_build_query($query_data),"post");
		return $result;
	}
	
	
	/**
	 * 
	 * @param unknown $coupon
	 * @param unknown $phone
	 * @return Ambigous <boolean, mixed>
	 * json 格式化后的正确结果
	 * {
    	 "code": 0,
   		 "msg": "OK",
   		 "result": {
	        "count": 1,
	        "fee": 0.055,
		    "sid": 4533077930
	     }
		}
	 */
	public function couponSmsNotice($coupon,$phone)
	{
		//发送短信网址
		$queryUrl 						= "https://sms.yunpian.com/v1/sms/send.json";
		$yp_config 						= YP_Config::getConfig ();
		$yp_tpl 						= YP_Config::getSmsTpl();
		$yp_tpl['coupon_notice']		= str_replace("#coupon#", $coupon, $yp_tpl['coupon_notice']);
		$yp_tpl['coupon_notice'] 		= str_replace("#phone#", $phone, $yp_tpl['coupon_notice']);
		$query_data['apikey'] 			= $yp_config['jgg_apikey'];
		$query_data['mobile'] 			= $phone;
		$query_data['text']  			= $yp_tpl['coupon_notice'];
		$result 			 			= Curl::callWebServer($queryUrl,http_build_query($query_data),"post");
		return $result;
	}
	
	/**
	 * 批量发送个性化短信 手机短信&短信内容 数量必须一致
	 * @param array $phone 多个手机号码
	 * @param array $content 多个内容 数量必须与手机号码数量一致
	 */
	public function multi_send($name,$phone)
	{
		if (is_array($phone))
		{
			$phone  = implode(",", $phone);
		}
		$queryUrl 	  					= "https://sms.yunpian.com/v1/sms/multi_send.json";
		$yp_config 	  					= YP_Config::getConfig ();
		//$yp_tpl 	  					= YP_Config::getSmsTpl();

		$contentArr 					= array();
		
		
		foreach ($name as $key=>$index)
		{
			$yp_tpl 	  			    = YP_Config::getSmsTpl();
			$yp_tpl['multi_send']		= str_replace("#name#", $index, $yp_tpl['multi_send']);
// 			$yp_tpl['multi_send']		= str_replace("#end_time#", "1月25日", $yp_tpl['multi_send']);
// 			$yp_tpl['multi_send'] 		= str_replace("#url#", "www.jinggege.com", $yp_tpl['multi_send']);
			$contentArr[] 				= urlencode($yp_tpl['multi_send']);
		}
		$query_data['apikey'] 			= $yp_config['jgg_apikey'];
		$query_data['mobile'] 			= $phone;
		$query_data['text']  			= implode(",", $contentArr);
		
		$result 			 			= Curl::callWebServer($queryUrl,http_build_query($query_data),"post");
		return $result;
		
	}
	
	
	/**
	 * 某台服务器异常警告信息
	 * @param  $phone
	 * @param  $point
	 * @return Ambigous <boolean, mixed>
	 */
	public function admin_waring($phone,$point)
	{
		$queryUrl 	  					= "https://sms.yunpian.com/v1/sms/multi_send.json";
		$yp_config 	  					= YP_Config::getConfig ();
		$contentArr 					= array();
		foreach ($phone as $key=>$index)
		{
			$yp_tpl 	  					= YP_Config::getSmsTpl();
			$yp_tpl['admin_waring']			= str_replace("#point#", $point, $yp_tpl['admin_waring']);
			$contentArr[] 				    = urlencode($yp_tpl['admin_waring']);
		}
		
		$query_data['apikey'] 			= $yp_config['jgg_apikey'];
		$query_data['mobile'] 			= implode(",", $phone);
		$query_data['text']  			= implode(",", $contentArr);
		$result 			 			= Curl::callWebServer($queryUrl,http_build_query($query_data),"post");
		return $result;
	}
}
?>