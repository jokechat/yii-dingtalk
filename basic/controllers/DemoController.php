<?php

namespace app\controllers;

use Yii;
use app\commands\util\Curl;
use yii\web\Controller;
use app\commands\util\simple_html_dom;
use app\commands\util\Dump;
use app\commands\util\str_get_html;
use app\commands\util\HtmlHelper;
use app\commands\util\Util;

/**
 * 抓取指定网站  文本信息  并且存储文件信息
 * @description
 * @author jokechat
 * @date 2016年3月31日
 * @mail  jokechat@qq.com
 */
class DemoController extends Controller
{
    public function actionIndex()
    {
    	
    	set_time_limit(0);
    	
    	
    	
    	for ($i =41; $i<57;$i++)
    	{
    		//分类页码
    		$param["page"] 		= $i;
    		$category 			= "http://www.feiniu.com/category/C18709";
    		 
    		$headers['10022']  	= "cart_token=8b59b4a50453eb4d8b48ab032433a66f_1459222630; guid=Vuvl7CpC-6CLN-4w0h-8iwi-vz3LpqpllIf0; first_login_time=1459222631786; access=2; Hm_lvt_7f78a821324600a0f059acdb24cf0937=1459222632; Hm_lpvt_7f78a821324600a0f059acdb24cf0937=1459223146; C_dist=CPG1_CS000018; C_dist_area=CS000018_320100_320103_3201030001; CNZZDATA1256288985=1624260230-1459219351-%7C1459219351; CNZZDATA1256279840=1173695278-1459218857-http%253A%252F%252Fwww.feiniu.com%252F%7C1459218857; abToken=99";
    		curl::setOption($headers);
    		$result 			= Curl::callWebServer($category,$param,"get",false);
    		 
    		$category_list 		= array();
    		$a_list 			= array();
    		
    		$html 				= new HtmlHelper();
    		 
    		preg_match_all('/<div class="listDescript">(.*?)<(\/div.*?)>/sim', $result,$category_list);
    		 
    		$good_name 					= array();
    		 
    		foreach ($category_list[0] as $index=>$value)
    		{
    			$good_info 				= $html->str_get_html($value);
    			$good_name[$index]['name'] 	= $good_info->find("a")[0]->innertext;
    			$good_name[$index]['url'] 	= $good_info->find("a")[0]->attr['href'];
    		
    			//以下为商品详情
    			$queryUrl 			= $good_info->find("a")[0]->attr['href'];
    			curl::setOption($headers);
    			$result 			= Curl::callWebServer($queryUrl,"","get",false);
    			 
    			 
    			$good_price = array();
    			preg_match('/<strong class="fn-rmb-num">(.*?)<(\/strong.*?)>/si', $result,$good_price);
    			preg_match('/<h1 class="superboler">(.*?)<(\/h1.*?)>/si', $result,$good_info);
    		
    			$good_name[$index]['price'] 	= $good_price[1];
    			unset($good_name[$index]['url']);
    		}
    		$sheet_title =  array('0'  => '商品名称','1'  => '价格');
    		$filename   = time()."--".$param['page'];
    		$result 	=  util::create_excel($sheet_title, $good_name, $filename,true);
    		Dump::dump($result);
    	}
    	
    	
    	
    	

    	
    }

    public function actionTest()
    {
    	set_time_limit(0);
    	$company 			= array();
    	$html 				= new HtmlHelper();
    	for ($i = 210 ;$i < 15316;$i++)
    	{
    		
    		$queryUrl 		= "http://www.evrong.com/index.php/qiye/info/".$i;
    		

    		$result 		= Curl::callWebServer($queryUrl,"","get",false);
    		$category_list  = array();
    		 
    		preg_match_all('/<table>(.*?)<(\/table.*?)>/sim', $result,$category_list);
    		 

    		$qiye_info 			= $category_list[0][0];

    		
    		$info 				= $html->str_get_html($qiye_info);
    		 
    		$company[$i]['name']   	= $info->find("tr",1)->children(3)->plaintext;
    		$company[$i]['address']   = $info->find("tr",7)->children(1)->plaintext;
    		$company[$i]['faren']   = $info->find("tr",8)->children(1)->plaintext;
    		$company[$i]['phone']   = $info->find("tr",9)->children(1)->plaintext;
    	}
    	

    	
    	$company 					= Util::restore_array($company);
    	$sheet_title =  ['公司名称','地址','法人','电话'];
    	$filename   = time();
    	$result 	=  util::create_excel($sheet_title, $company, $filename,true);
    	Dump::dump($company);
    	Dump::dump($result);
    }
}
