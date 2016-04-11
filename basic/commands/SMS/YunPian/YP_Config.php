<?php
namespace app\commands\SMS\YunPian;
class YP_Config
{
	/**
	 * 获取云片网络配置信息
	 * @return multitype:string
	 */
	public static function getConfig()
	{
		$config  = array(
				'jgg_apikey'=>'短信apikey',
		);
		return $config;
	}
	
	
	/**
	 * 获取云片网短信模板
	 * @return multitype:string
	 */
	public static function getSmsTpl()
	{
		$config  = array(
					'login_code'		=>"【靖哥哥】靖哥哥登录验证码为#content#，5分钟内有效，打死也不能告诉别人哦！",
					'delivery_notice'	=>'【靖哥哥】报告大王：您在靖哥哥猎获的山珍海味已被#express#镖局偷偷带走，镖号：#postcode#，请第一时间前往劫镖！欢迎您再次来狩猎！',
					'coupon_notice'		=>'【靖哥哥】您的优惠券{ #coupon# }已存入账户#phone#中,请关注微信公众账号{靖哥哥牛排}使用',
				    'multi_send'		=>'【靖哥哥】亲爱的#name#，新春将至，岁岁相长，感谢您一直以来的支持，值此年夜饭套餐上线之际，靖哥哥携所有员工祝您在新的一年，旗开得胜，五福临门。',
					'admin_waring'		=>'【靖哥哥】靖哥哥#point#服务器异常无法连接,请尽快查看!'
		);
		return $config;
	}
}
?>