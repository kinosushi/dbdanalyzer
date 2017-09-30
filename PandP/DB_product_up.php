<?php
require_once('../db.php');
//include the following 2 files
require_once("../Classes/PHPExcel.php");
require_once ("../Classes/PHPExcel/IOFactory.php");

class DB_product_up extends DB{
	//データベースを操作する機能を提供するためのクラス
	
	public function upload2db($array){
		$sql ="INSERT INTO product VALUES(?,?,?,?,?, ?,?,?)";
		$res = parent::executeSQL3($sql, $array);
		
		return $res;
	}


	

	
	private function text($serial, $format){
		//エクセルのシリアル値をUnix Timestampに変換
		return gmdate($format, ($serial -25569)*60*60*24);
	}
	

	
}
?>
