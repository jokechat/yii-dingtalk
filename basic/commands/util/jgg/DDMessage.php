<?php
namespace app\commands\util\jgg;

/**
 * @description 完成钉钉会话消息类型的封装
 * @author jokechat
 * @date 2016年3月12日
 * @mail  jokechat@qq.com
 */
class DDMessage
{
	/**
	 * 文本消息内容
	 * @param unknown $text
	 * @return multitype:string unknown
	 */
	public static function textMessage($text)
	{
		$content  			= array();
		$content['content'] = $text;
		$content['type']	= "text";
		return $content;
	}
	
	/**
	 * link消息体格式
	 * @param string $messageUrl
	 * @param string $picUrl
	 * @param string $title
	 * @param string $text
	 * @return multitype:string unknown
	 */
	public static function linkMessage($messageUrl,$picUrl,$title,$text)
	{
		$content  					= array();
		$content['type']			= "link";
		$content['messageUrl']		= $messageUrl;
		$content['picUrl']			= $picUrl;
		$content['title']			= $title;
		$content['text']			= $text;
		return $content;
	
	}
	
	/**
	 * oa消息
	 * @param string $messageUrl
	 * @param string $head_text
	 * @param string $body_title
	 * @param unknown $body_form
	 * @param unknown $body_content
	 * @param string $body_image
	 * @param string $body_file_count
	 * @param string $body_author
	 * @param string $rich_num
	 * @param string $rich_unit
	 * @return array
	 */
	public static function oaMessage($messageUrl,$head_text,$body_title,$body_form,$body_content,$body_image,$body_file_count,$body_author,$rich_num,$rich_unit)
	{
		$content  						= array();
		$content['type']				= "oa";
		$content['message_url']			= $messageUrl;
		$content['head']['bgcolor']		= "FFBBBBBB";
		$content['head']['text']		= $head_text;
	
	
		$content['body']['title'] 		= $body_title;
		$content['body']['form'] 		= $body_form;
		
		$content['body']['content'] 	= $body_content;
		$content['body']['image'] 		= $body_image;
		$content['body']['file_count'] 	= $body_file_count;
		$content['body']['author'] 		= $body_author;
		$content['body']['rich']['num'] = $rich_num;
		$content['body']['rich']['unit'] 	= $rich_unit;
	
		return $content;
	}
}
?>