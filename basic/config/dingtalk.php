<?php
return [
		//钉钉配置信息
		'corpid' 				=>'',//企业id
		'corpsecret'			=>'',//企业应用的凭证密钥
		'ssosecret'				=>'',//后台管理应用密匙
		'agentid'				=>'',//微应用id	
		
		'snsappid' 				=>'',//开放应用id
		'snsappsecret'			=>'',//开放应用密匙
		'get_sns_token_url'  	=>'https://oapi.dingtalk.com/sns/gettoken?',//开放应用获取token url
		'get_persistent_code'	=>'https://oapi.dingtalk.com/sns/get_persistent_code?',
		'get_sns_token'			=>'https://oapi.dingtalk.com/sns/get_sns_token?',//获取用户授权的SNS_TOKEN
		'sns_getuserinfo'		=>'https://oapi.dingtalk.com/sns/getuserinfo?',//获取授权用户信息
		
		//请求参数url
		'gettoken_url'  		=>'https://oapi.dingtalk.com/gettoken?',
		'get_sso_token_url' 	=>'https://oapi.dingtalk.com/sso/gettoken?',//后台免登token
		'get_jsapi_ticket'		=>'https://oapi.dingtalk.com/get_jsapi_ticket?',//获取jsapi_ticket
		
		'department_list'		=>'https://oapi.dingtalk.com/department/list?',//获取部门列表
		'department_list_detail'=>'https://oapi.dingtalk.com/department/get??',//获取部门列表(详细信息)
		'user_simplelist'		=>'https://oapi.dingtalk.com/user/simplelist?',//获取部门成员列表
		'user_list'				=>'https://oapi.dingtalk.com/user/list?',//获取部门成员列表(带详细信息)
		'user_getinfo' 			=>'https://oapi.dingtalk.com/user/get?',//通过用户id获取用户信息
		'user_getinfo_by_code'	=>'https://oapi.dingtalk.com/user/getuserinfo?',//通过code获取用户信息

		//微应用url
		'microapp_visible_scopes' 	=>'https://oapi.dingtalk.com/microapp/visible_scopes?',//微应用可见范围
		'microapp_create'			=>'https://oapi.dingtalk.com/microapp/create?',//创建微应用

		//群会话接口
		'chat_create'				=>'https://oapi.dingtalk.com/chat/create?',//群会话创建接口
		'chat_update'				=>'https://oapi.dingtalk.com/chat/update?',//群会话修改接口
		'chat_get'					=>'https://oapi.dingtalk.com/chat/get?',//群会话获取接口
		'chat_send'					=>'https://oapi.dingtalk.com/chat/send?',//群会话获取接口
		
		/*****会话消息接口*****/
		'message_send'			=>'https://oapi.dingtalk.com/message/send?',//发送企业会话消息
		'media_upload'			=>'https://oapi.dingtalk.com/media/upload?',//上传文件
		
];
?>