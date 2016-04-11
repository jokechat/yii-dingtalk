<?php
namespace app\commands\util;

/**
 * 日志操作操作
 * @description
 * @author jokechat
 * @date 2016年3月26日
 * @mail  jokechat@qq.com
 */
class Log
{
    public static function i($msg)
    {
        self::write('I', $msg);
    }
    
    public static function e($msg)
    {
        self::write('E', $msg);
    }
    
    private static function write($level, $msg)
    {
        $logDir = "../backup/log/";
        if (!file_exists($logDir)) {
    	    mkdir($logDir);
    	}
        $logFile = fopen($logDir . "/test.log", "aw");
        fwrite($logFile, $level . "/" . date(" Y-m-d h:i:s") . "  " . $msg . "\n");
        fclose($logFile);    
    }
}
?>