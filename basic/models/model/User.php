<?php
namespace app\models\model;
use yii\db\ActiveRecord;


class User extends ActiveRecord
{
	//AR类的字段  用途暂时不明晰
// 	public $id;
// 	public $name;
	
	
	/**
	 * AR类对应的表名
	 * @return string
	 */
	public static function tableName()
	{
		return "dingtalk_user";
	}
	
	/**
	 * 需要连接的数据库
	 * @return \yii\db\Connection
	 */
	public static function getDb()
	{
		return \Yii::$app->db;
	}

}
?>