<?php
namespace app\commands\util\dd;
use app\commands\util\Dump;
use app\commands\util\dd\Prpcrypt;
class DingtalkCrypt
{
	private $m_token; //token令牌
	private $m_encodingAesKey;//加密key
	private $m_suiteKey;//套件key or 微应用corpid ??? 文档不清晰
	
	/**
	 * 构造函数  初始化信息
	 * @param string $token
	 * @param string $encodingAesKey
	 * @param string $suiteKey
	 */
	public function __construct($token, $encodingAesKey, $suiteKey)
	{
		$this->m_token 			= $token;
		$this->m_encodingAesKey = $encodingAesKey;
		$this->m_suiteKey 		= $suiteKey;
	}
	
	
	/**
	 * 对消息进行加密
	 * @param string $plain 需要加密的文本消息
	 * @param int $timeStamp 时间戳
	 * @param string $nonce 随机字符串
	 * @return array 
	 */
	public function EncryptMsg($plain, $timeStamp, $nonce)
	{

		$pc 			= new Prpcrypt($this->m_encodingAesKey);
		$encryp_result 	= $pc->encrypt($plain, $this->m_suiteKey);
		
		$ret 			= $encryp_result[0];
		if ($ret != 0)
		{
			return $ret;
		}
		
		$encrypt 		= $encryp_result[1];
		$sha1 			= new SHA1();
		$sha1_result 	= $sha1->getSHA1($this->m_token, $timeStamp, $nonce, $encrypt);
		
		$ret 			= $sha1_result[0];
		
		if ($ret != 0) {
			return $ret;
		}
		
		$signature = $sha1_result[1];
		$encryptMsg = array(
				"msg_signature" => $signature,
				"encrypt" => $encrypt,
				"timeStamp" => $timeStamp,
				"nonce" => $nonce
		);
		
		return $encryptMsg;
	}

 	/**
 	 * 对加密消息进行解密
 	 * @param string $signature 消息签名
 	 * @param int $timeStamp 时间戳
 	 * @param stringd $nonce 随机字符串
 	 * @param string $encrypt 加密内容
 	 * @return string 解密结果
 	 */
	public function DecryptMsg($signature, $timeStamp, $nonce, $encrypt)
	{
		if (strlen($this->m_encodingAesKey) != 43) {
			return ErrorCode::$IllegalAesKey;
		}
		
		$pc = new Prpcrypt($this->m_encodingAesKey);

		$sha1 = new SHA1;
		$array = $sha1->getSHA1($this->m_token, $timeStamp, $nonce, $encrypt);
		$ret = $array[0];
		
		if ($ret != 0) {
			return $ret;
		}
		
		$verifySignature = $array[1];
		if ($verifySignature != $signature) {
			return ErrorCode::$ValidateSignatureError;
		}
		
		$result = $pc->decrypt($encrypt, $this->m_suiteKey);
		if ($result[0] != 0) {
			return $result[0];
		}
		$decryptMsg = $result[1];
		
		return $decryptMsg;
	}
}
?>