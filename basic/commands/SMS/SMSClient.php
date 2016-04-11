<?php
namespace app\commands\SMS;
use app\commands\SMS\YunPian\YPClient;
/**
 * 统一对外的短信接口api
 * @description
 * @author jokechat
 * @date 2016年4月8日
 * @mail  jokechat@qq.com
 */
class SMSClient
{
	
	private function getSmsClient()
	{
		$client     = new YPClient();
		return $client;
	}
	
	 /**
     * 发送短信验证码
     * @param string $phone 手机号码
     * @param string $code  验证码
     * @return Ambigous
     */
	public  function sendAuthCode($mobile, $code)
	{
	
		$client     = self::getSmsClient();
		$send_info  = $client->sendAuthCode($mobile, $code);
		return $send_info;
	}
	
	/**
	 * 发货通知短信
	 * @param string $phone 手机号码
	 * @param string $express 物流公司
	 * @param string $postcode 快递号码
	 * @return string Ambigous <boolean, mixed>
	 */
	public function delivery_notice($phone, $express, $postcode)
	{
		$client     = self::getSmsClient();
		$send_info  = $client->delivery_notice($phone, $express, $postcode);
		return $send_info;
	}
	
	
	/**
	 * 发送优惠券通知
	 * @param string $coupon 优惠券名称
	 * @param string $phone  手机号码
	 * @return Ambigous
	 */
	public  function couponSmsNotice($coupon,$phone)
	{
		$client 			= self::getSmsClient();
		$send_info          = $client->couponSmsNotice($coupon, $phone);
		return $send_info;
	}
	
	/**
	 * 批量发送短信 仅仅适用于云片网络
	 * @param array $name 用户名数组
	 * @param array $phone 手机号码数组
	 * @param string $content
	 */
	public function multi_send($name,$phone)
	{
		$client 			= self::getSmsClient();
		$send_info          = $client->multi_send($name,$phone);
		return $send_info;
	}

	/**
	 * 发送短信 提醒服务器异常信息校验
	 * @param  $phone
	 * @param  $point
	 */
	public function admin_waring($phone,$point)
	{
		$client 			= self::getSmsClient();
		$send_info 			= $client->admin_waring($phone,$point);
		return $send_info;
	}
}
?>