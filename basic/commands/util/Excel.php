<?php
namespace app\commands\util;

/**
 * excel 文件进一步读写操作的完善
 * @description
 * @author jokechat
 * @date 2016年4月8日
 * @mail  jokechat@qq.com
 */
class Excel
{
	/**
	 * 创建保存的excel文件
	 * @param array $sheet_title 文件表头数据 
	 * @param array $data 	内容填充数据
	 * @param string $filename 保存下载的文件名称
	 * @param string $is_save  可选  是否保存到report目录下
	 * @return string
	 */
	public static function create_excel($sheet_title,$data,$filename,$is_save=false)
	{
	
		require_once  '/../commands/PHPExcel.php';
		$objPHPExcel = new \PHPExcel ();
		$objPHPExcel->getProperties ()
					->setCreator ( "靖哥哥食品科技有限公司 @jokechat" )
					->setLastModifiedBy ( "jokechat" )
					->setTitle ( "report data" )
					->setSubject ( "Office 2007 XLSX Document" )
					->setDescription ( "订单导出数据" )
					->setKeywords ( "订单信息" )
					->setCategory ( "靖哥哥" );
		
		$objPHPExcel->getActiveSheet ()->setTitle ( 'report' );
		// 设置选中sheet
		$objPHPExcel->setActiveSheetIndex ( 0 );
	
		$s = "A B C D E F G H I J K L M N O P Q R S T U V W X Y Z";
		$col = explode ( " ", $s );
		// 设置标题头 信息
		foreach ($sheet_title as $index=>$title)
		{
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue ( $col[$index]."1", $title);
		}
	
		//设置内容信息
		foreach ( $data as $index => $value )
		{
			$new_array = self::restore_array ( $value );
			foreach ( $new_array as $key => $value2 )
			{
				if (is_array($value2))
				{
					$goodInfo = array();
					foreach($value2 as $value2_key => $value2_value)
					{
						$goodInfo[] 	= $value2[$value2_key]." ";
					}
					//设置填充数据内容
					$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueExplicit($col[$key].($index+2), implode("\n", $goodInfo),\PHPExcel_Cell_DataType::TYPE_STRING);
				}
				else
				{
					//设置填充数据内容
					$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueExplicit($col[$key].($index+2), (string)$value2,\PHPExcel_Cell_DataType::TYPE_STRING);
				}
				//设置自适应
				$objPHPExcel->getActiveSheet()
				->getColumnDimension($col[$key])
				->setAutoSize(TRUE);
	
				//设置居中样式
				$objPHPExcel->getActiveSheet()
				->getStyle($col[$key].($index+2))
				->getAlignment()
				->setHorizontal(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
			}
		}

		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		//存储保存文件 并且返回文件名称
		if ($is_save)
		{
			$absolute_path = dirname(dirname(dirname(__FILE__))) . "/web/report/";
			$objWriter->save($absolute_path.$filename.".xls");
			return $filename.".xls";
		}else
		{
			//浏览器响应文件头下载
			ob_end_clean();//清除缓冲区,避免乱码
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
			header('Cache-Control: max-age=0');
			$objWriter->save('php://output');
		}
	}
	
	
	
	
	/**
	 * 读取excel文件到数组
	 * @param string $filepath  excel 绝对路径
	 */
	public static function read_excel($filepath)
	{
		require_once  '/../commands/PHPExcel.php';
		include_once '/../commands/PHPExcel/Autoloader.php';
		include_once '/../commands/PHPExcel/IOFactory.php';
	
	
		if (!file_exists($filepath))
		{
			return false;
		}
	
		$info        = \PHPExcel_IOFactory::load($filepath);
	
		$sheet        = $info->getSheet(0);
		$rows 		  = $sheet->getHighestRow();//获取总行数
		$columns 	  = \PHPExcel_Cell::columnIndexFromString($sheet->getHighestColumn());//获取总列数 并转成数字列
	
		$sheet_data   = array();
		for ($i = 1; $i < $rows; $i++) {
				
			for ($j = 0; $j < $columns; $j++)
			{
			$sheet_data[$i][$j] 		= $sheet->getCellByColumnAndRow($j,$i)->getValue();
			}
			}
			return $sheet_data;
	}
	
	
	/**
	 * 对数组进行序列化 key进行从零排序
	 * @param array $arr
	 * @return unknown|multitype:multitype: Ambigous <unknown, multitype:NULL multitype: >
	 */
	public static function restore_array($arr)
	{
		if (!is_array($arr))
		{
			return $arr;
		}
		$c = 0;
		$new = array ();
		
		while ( list ( $key, $value ) = each ( $arr ) )
		{
			if (is_array($value))
			{
				$new[$c] = self::restore_array ( $value );
			} else
			{
				$new [$c] = $value;
			}
			$c ++;
		}
		return $new;
	}
	
}
?>