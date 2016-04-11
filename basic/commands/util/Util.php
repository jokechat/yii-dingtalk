<?php
namespace app\commands\util;

/**
 * 自定义通用的一些函数方法
 * @author jokechat
 * @data 2016年3月12日
 */
class Util
{
/**
	 *返回格式化时间
	 * @return string Y-m-d H:i:s
	 */
	public static function getTime()
	{
		return $time = date ( 'Y-m-d H:i:s', time ());
	}
	
	/**
	 *返回格式化时间
	 * @return string YmdHis
	 */
	public static function getTimestamp()
	{
		return $time = date ( 'Ymd', time ());
	}
	
	/**
	 * @param  $min 最小值
	 * @param  $max 最大值
	 * @return 生成的随机数
	 */
	public static function getRandom($min,$max)
	{
		$random  = rand($min, $max);
		$result  = $random <= $max ? $random : $min;
		return $result;
	}
	
	
	/**
	 * 返回昨日起止时间
	 * @return multitype:string
	 */
	public static function getYesterdayTimeStamp()
	{
		$result 				= array();
	
		$beginYesterday 		= mktime(0,0,0,date('m'),date('d')-1,date('Y'));
		$endYesterday 			= mktime(0,0,0,date('m'),date('d'),date('Y'))-1;
	
		$result["start_time"] 	= date ( 'Y-m-d H:i:s', $beginYesterday);
		$result["end_time"] 	= date ( 'Y-m-d H:i:s', $endYesterday);
	
		return $result;
	}
	
	/**
	 * 返回上周起止时间
	 * @return multitype:string
	 */
	public static function getLastweekTimeStamp()
	{
		$result 				= array();
	
		$beginLastweek 			= mktime(0,0,0,date('m'),date('d')-date('w')+1-7,date('Y'));
		$endLastweek 			= mktime(23,59,59,date('m'),date('d')-date('w')+7-7,date('Y'));
	
		$result["start_time"] 	= date ( 'Y-m-d H:i:s', $beginLastweek);
		$result["end_time"] 	= date ( 'Y-m-d H:i:s', $endLastweek);
	
		return $result;
	}
	
	/**
	 * 返回上本月起止时间
	 * @return multitype:string
	 */
	public static function getThismonthTimeStamp()
	{
		$result 				= array();
	
		$beginThismonth 		= mktime(0,0,0,date('m'),1,date('Y'));
		$endThismonth 			= mktime(23,59,59,date('m'),date('t'),date('Y'));
	
		$result["start_time"] 	= date ( 'Y-m-d H:i:s', $beginThismonth);
		$result["end_time"] 	= date ( 'Y-m-d H:i:s', $endThismonth);
	
		return $result;
	}
	
	

}
?>