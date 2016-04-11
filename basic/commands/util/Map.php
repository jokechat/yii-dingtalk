<?php
namespace app\commands\util;
/**
 * 一些地图api接口
 * @description
 * @author jokechat
 * @date 2016年3月15日
 * @mail  jokechat@qq.com
 */
class Map
{
	//地理坐标转换
	public  function geoConv($coords)
	{
		$queryUrl 			= \Yii::$app->params['baidu_geoconv'];
		$param['ak'] 	 	= \Yii::$app->params['baidu_map_ak'];
		$param['from'] 	 	= 1;
		$param['to'] 	 	= 5;
		$param['coords'] 	= implode(',', $coords);

		$result 			= Curl::callWebServer($queryUrl,$param);
		
		
		
		$msg['error'] 		= "error";
		$msg['data'] 		= array();
		
		//坐标转换失败 返回合理状态标识
		if (isset($result['message']))
		{
			return $msg;
			die();
		}

		$geo['y'] 				= $result['result'][0]['y'];
		$geo['x'] 				= $result['result'][0]['x'];

		return $geo;
	}
	
	
	//逆地理解析
	public function geoCoder($geo)
	{
		$queryUrl 			= \Yii::$app->params['baidu_geocoder'];
		$param['ak'] 	 	= \Yii::$app->params['baidu_map_ak'];
		
		$param['location']  = implode(",", $geo);
		$param['output'] 	= "json";		
// 		$param['pois'] 		= 1; //周边poi数组


		$result 			= Curl::callWebServer($queryUrl,$param);

		return $result['result'];
		
	}
	

}
?>