yii-dingtalk 使用说明
===========

### 1.配置yii2 开发运行环境,具体请移步yii2 查看
[yii权威文档](http://www.yiichina.com/doc/guide/2.0)

### 2.配置config/web.php
* cookieValidationKey 必须配置
* basic/modules/test 是测试用例模块,一些使用测试用例详细可以看 test模块下的controller


```
	 // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '你的github密匙',
```

```
//配置modules
	'modules' => [
			//test 充当测试
			'test' => [
					'class' => 'app\modules\test\test',
			],
	],
	
```

### 3.配置钉钉 ,钉钉api及其key配置在config目录下的dingtalk.php文件中,这些信息从钉钉后台微应用中获取
######corpid,corpsecret,ssosecret,agentid


```
		//钉钉配置信息
		'corpid' 				=>'',//企业id
		'corpsecret'			=>'',//企业应用的凭证密钥
		'ssosecret'				=>'',//后台管理应用密匙
		'agentid'				=>'',//微应用id	
		
		'snsappid' 				=>'',//开放应用id
```

### 4.一切配置ok后,直接访问测试就可以了
```
		localhost/项目名称/test/tsuite
```


# 关于作者
* Email： jokechat@qq.com
* 有任何建议或者使用中遇到问题都可以给我发邮件, 共同学习进步
* 因为实际项目需求,加入了Excel,Mail,sms,有兴趣的初学者可以参考研究一下
