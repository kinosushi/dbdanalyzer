<?php
require_once('db.php');


class uploading_DB extends DB{
	//データベースを操作する機能を提供するためのクラス


	public function upload2db($array){
		$sql ="INSERT INTO dbd_analyzer VALUES(?,?,?,?,?, ?,?,?,?,?, ?,?,?,?,?, ?,?,?,?,?, ?,?,?,?,?, ?,?,?,?,?, ?)";
		$array[12] = $this->text($array[12], $format = 'Y-m-d H:i:s');//Open Dateを変換
		$array[13] = $this->text($array[13], $format = 'Y-m-d H:i:s');//Close Dateを変換
		$array = $this->filltitlecode($array);
		if($array[10] == "Japan" && $array[2] == "Y"){//"Country"がJapan 且つ"Valid SN"がYのみ登録
			$res = parent::executeSQL3($sql, $array);
		}else{
			return false;
		}
		return $res;
	}
	
	private function text($serial, $format){
		//エクセルのシリアル値をUnix Timestampに変換
		return gmdate($format, ($serial -25569)*60*60*24);
	}
	
	private function filltitlecode($array){
		//ケースタイトルからタイトルコードを抽出し配列に格納
		//$array[11]はケースタイトル
		//$array[26]はタイトルコード
		$string = $array[11];
		// 最大で8桁の英数字
		preg_match('/_[0-9A-Za-z]{4}[0-9A-Za-z]?[0-9A-Za-z]?[0-9]?[0-9]?》/', $string, $matches);
		$array[26] = $matches[0];
		return $array;
	}


}
