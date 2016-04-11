<?php
namespace app\commands\util;
/**
 * 邮箱通用工具类 
 * 完成基本的邮件发送功能
 * @description
 * @author jokechat
 * @date 2016年4月7日
 * @mail  jokechat@qq.com
 */
class MyMail
{
	//邮件编码
	private $Charset  	= "utf8";
	private $Host 		= "smtp.ym.163.com";
	private $SMTPAuth 	= true;
// 	private $SMTPSecure = "tls";
	private $Username   = "username@domain.com";
	private $Password 	= "password";
	private $Port 		= "25";
	private $XMailer    = "jokechat@qq.com make by PHPMailer";
	private $FormName   = "管理员";
	
	protected  $MyMail 	= null;
	
	public $MailFile 	= array();//需要上传的文件
	

	/**
	 * 收件人地址
	 * @var string
	 */
	public  $Address 	= "user@local.com";
	
	public  $Subject 	= "主题";
	
	public function __construct()
	{
		require_once  '/../commands/PHPMailer/PHPMailerAutoload.php';
		
		$mail 		= new \PHPMailer();	
			
		$mail->SMTPDebug= false;
		$mail->isSMTP();
		$mail->CharSet="utf-8";
		$mail->Host 			= $this->Host;
		$mail->SMTPAuth 		= true;
		$mail->Username 		= $this->Username;
		$mail->Password 		= $this->Password;
// 		$mail->SMTPSecure 		= $this->SMTPSecure; //需要配置ssl
		$mail->Port 			= $this->Port;
		$mail->XMailer 			= $this->XMailer;
		$mail->setFrom($this->Username, $this->FormName);//发送人
		$mail->isHTML(true);
		
		$this->MyMail 			= $mail;

	}
	
	/**
	 * 发送邮件
	 * @param string $address 邮件地址
	 * @param string $subject 邮件主题
	 * @param string $body 	  邮件主体
	 * @return Ambigous <boolean, string>
	 * 发送成功 返回true
	 * 发送方失败 返回错误消息
	 */
	public function sendMail($address,$subject,$body)
	{

		$mail 			= $this->MyMail;
		$mail->Subject 	= $subject;
		$mail->Body 	= $body;
		
		$mail->addAddress($address,"jokechat");
		
		//添加邮件附件
		$mailFile 		= $this->MailFile ;
		if (count($mailFile)>0)
		{
			foreach ($mailFile as $file)
			{
				if (file_exists($file))
				{
					$mail->addAttachment($file, basename($file));    // 添加附件
				}
			}
		}

		$send_result 	= $mail->send() ? true:$mail->ErrorInfo;
		return $send_result;
	}
	
	
}

?>