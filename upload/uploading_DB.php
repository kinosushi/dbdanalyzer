<?php
require_once('db.php');


class uploading_DB extends DB{
	//データベースを操作する機能を提供するためのクラス

	public function upload2db($array){
		$sql ="INSERT INTO dbd_analyzer VALUES(?,?,?,?,?, ?,?,?,?,?, ?,?,?,?,?, ?,?,?,?,?, ?,?,?,?,?, ?,?,?,?,?, ?,?,?,?)";
		$array[12] = $this->text($array[12], $format = 'Y-m-d H:i:s');//Open Dateを変換
		$array[13] = $this->text($array[13], $format = 'Y-m-d H:i:s');//Close Dateを変換
		$array = $this->filltitlecode($array);
		//if($array[10] == "Japan" && $array[2] == "Y"){//"Country"がJapan 且つ"Valid SN"がYのみ登録
			$res1 = parent::executeSQL3($sql, $array);
		//}else{
			//return false;
		//}

		//プロダクトテーブルへの登録

		//プロダクトテーブルの項目
		//id, Product_Number, Product_Name, Product_Description, Product_Line, Name_Unique, Name_Blank_Flag, SP_Flag
			$Product_Number = $array[15];
			$Product_Description = $array[16];
			$Product_Line = $array[21];

			// 【レコードがあるか確認】
			$sql2 ="SELECT * FROM product WHERE Product_Number = ?";
			$array2 = array($Product_Number);
			$result = parent::executeSQL($sql2, $array2);

			$count = $result->fetchColumn();
			if($count==null) {
				$sql3 ="INSERT INTO product VALUES(?,?,?,?,?, ?,?,?)";
				$array3 = array(null,$Product_Number,'',$Product_Description,$Product_Line,null,null,null);
				$res2 = parent::executeSQL3($sql3, $array3);
			}
// 			else{
// 				++ $already_exist;
// 			}


			//一つのSQL文でまとめていますが、Insertが成功したかどうかの戻り値が全て1になってしまうため今回は使用しません。
// 			$sql3 = <<<eof
// 			INSERT INTO product (id, Product_Number, Product_Name, Product_Description, Product_Line, Name_Unique, Name_Blank_Flag, SP_Flag)
// 			SELECT ?,?,?,?,?, ?,?,? FROM product
// 			WHERE NOT EXISTS (
// 					SELECT * FROM product WHERE Product_Number = ?
// 					) LIMIT 1
// eof;

// 			$array3 = array(null,$Product_Number,'',$Product_Description,$Product_Line,null,null,null,$Product_Number);
// 			$res2 = parent::executeSQL3($sql3, $array3);
			//一つのSQL文、ここまで


			$res = array($res1, $res2);

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

		// 抽出したタイトルコードを元にデコード結果を配列に挿入
		if(strpos($array[11],'《7FDBD') !== false){//ケースタイトルに「《7FDBD」が含まれている場合
			$array[27] = $this->get7FDBDIssueformTitlecode($array[26]);//Issueに挿入
			$array[28] = $this->get7FDBDTimingformTitlecode($array[26]);//Timingに挿入
			$array[29] = $this->get7FDBDHowoftenformTitlecode($array[26]);//Howoftenに挿入
			$array[30] = $this->get7FDBDFlagformTitlecode($array[26]);//Flagに挿入
		}else if(strpos($array[11],'《ANDBD') !== false){//ケースタイトルに「《ANDBD」が含まれている場合
			$array[27] = $this->getANDBDIssueformTitlecode($array[26]);//Issueに挿入
			$array[28] = $this->getANDBDTimingformTitlecode($array[26]);//Timingに挿入
			$array[29] = $this->getANDBDHowoftenformTitlecode($array[26]);//Howoftenに挿入
			$array[30] = $this->getANDBDFlagformTitlecode($array[26]);//Flagに挿入
		}else if(strpos($array[11],'《7FCCC') !== false || strpos($array[11],'《ANCCC') !== false){//ケースタイトルに「《7FCCC」もしくは「《ANCCC」が含まれている場合
			$array[27] = $this->getCCCIssueformTitlecode($array[26]);//Issueに挿入
			$array[28] = $this->getCCCTimingformTitlecode($array[26]);//Timingに挿入
			$array[30] = $this->getCCCFlagformTitlecode($array[26]);//Flagに挿入
			$array[31] = $this->getCCCOsformTitlecode($array[26]);//Osに挿入
			$array[32] = $this->getCCCCategoryformTitlecode($array[26]);//Categoryに挿入
			$array[33] = $this->getCCCCustomerformTitlecode($array[26]);//Customerに挿入
		}

		return $array;
	}

	//7FDBD
	private function get7FDBDIssueformTitlecode($titlecode){
		if(mb_substr($titlecode, 3, 2) != false && strpos (mb_substr($titlecode, 3, 2), "》") === false){
			$code = mb_substr($titlecode, 3, 2);//コードを抜き出す　サンプル《7FDBD_QD1u02》「1u」の部分
			$sql = "SELECT issue FROM 7f_titlecode WHERE code = ?";
			$array = array($code);
			$res = parent::executeSQL($sql,$array);
			$ans = $res->fetchColumn();
			return $ans;
		}else{
			return null;
		}
	}

	private function get7FDBDTimingformTitlecode($titlecode){
		if(mb_substr($titlecode, 5, 1) != false && strpos (mb_substr($titlecode, 5, 1), "》") === false){
			$code = mb_substr($titlecode, 5, 1);//コードを抜き出す　サンプル《7FDBD_QD1u02》「0」の部分
			$sql = "SELECT timing FROM 7f_titlecode WHERE code = ?";
			$array = array($code);
			$res = parent::executeSQL($sql,$array);
			$ans = $res->fetchColumn();
			return $ans;
		}else{
			return null;
		}
	}

	private function get7FDBDHowoftenformTitlecode($titlecode){
		if(mb_substr($titlecode, 6, 1) != false && strpos (mb_substr($titlecode, 6, 1), "》") === false){
			$code = mb_substr($titlecode, 6, 1);//コードを抜き出す　サンプル《7FDBD_QD1u02》「2」の部分
			$sql = "SELECT howoften FROM 7f_titlecode WHERE code = ?";
			$array = array($code);
			$res = parent::executeSQL($sql,$array);
			$ans = $res->fetchColumn();
			return $ans;
		}else{
			return null;
		}
	}

	private function get7FDBDFlagformTitlecode($titlecode){
		if(mb_substr($titlecode, 7, 1) != false && strpos (mb_substr($titlecode, 7, 1), "》") === false){
			$code = mb_substr($titlecode, 7, 1);//コードを抜き出す　サンプル《7FDBD_QD1u02》このサンプルには無い
			$sql = "SELECT flag FROM 7f_titlecode WHERE code = ?";
			$array = array($code);
			$res = parent::executeSQL($sql,$array);
			$ans = $res->fetchColumn();
			return $ans;
		}else{
			return null;
		}
	}

	//ANDBD
	private function getANDBDIssueformTitlecode($titlecode){
		if(mb_substr($titlecode, 3, 2) != false && strpos (mb_substr($titlecode, 3, 2), "》") === false){
			$code = mb_substr($titlecode, 3, 2);//コードを抜き出す　サンプル《7FDBD_QD1u02》「1u」の部分
			$sql = "SELECT issue FROM an_titlecode WHERE code = ?";
			$array = array($code);
			$res = parent::executeSQL($sql,$array);
			$ans = $res->fetchColumn();
			return $ans;
		}else{
			return null;
		}
	}

	private function getANDBDTimingformTitlecode($titlecode){
		if(mb_substr($titlecode, 5, 1) != false && strpos (mb_substr($titlecode, 5, 1), "》") === false){
			$code = mb_substr($titlecode, 5, 1);//コードを抜き出す　サンプル《7FDBD_QD1u02》「0」の部分
			$sql = "SELECT timing FROM an_titlecode WHERE code = ?";
			$array = array($code);
			$res = parent::executeSQL($sql,$array);
			$ans = $res->fetchColumn();
			return $ans;
		}else{
			return null;
		}
	}

	private function getANDBDHowoftenformTitlecode($titlecode){
		if(mb_substr($titlecode, 6, 1) != false && strpos (mb_substr($titlecode, 6, 1), "》") === false){
			$code = mb_substr($titlecode, 6, 1);//コードを抜き出す　サンプル《7FDBD_QD1u02》「2」の部分
			$sql = "SELECT howoften FROM an_titlecode WHERE code = ?";
			$array = array($code);
			$res = parent::executeSQL($sql,$array);
			$ans = $res->fetchColumn();
			return $ans;
		}else{
			return null;
		}
	}

	private function getANDBDFlagformTitlecode($titlecode){
		if(mb_substr($titlecode, 7, 1) != false && strpos (mb_substr($titlecode, 7, 1), "》") === false){
			$code = mb_substr($titlecode, 7, 1);//コードを抜き出す　サンプル《7FDBD_QD1u02》このサンプルには無い
			$sql = "SELECT flag FROM an_titlecode WHERE code = ?";
			$array = array($code);
			$res = parent::executeSQL($sql,$array);
			$ans = $res->fetchColumn();
			return $ans;
		}else{
			return null;
		}
	}

	//CCC
	// コードの順序  timing-os-issue-category-flag-customer
	private function getCCCIssueformTitlecode($titlecode){
		if(mb_substr($titlecode, 5, 1) != false && strpos (mb_substr($titlecode, 5, 1), "》") === false){
			$code = mb_substr($titlecode, 5, 1);//コードを抜き出す　サンプル《ANCCC_YK963362》「3」の部分
			$sql = "SELECT issue FROM ccc_titlecode WHERE code = ?";
			$array = array($code);
			$res = parent::executeSQL($sql,$array);
			$ans = $res->fetchColumn();
			return $ans;
		}else{
			return null;
		}
	}

	private function getCCCTimingformTitlecode($titlecode){
		if(mb_substr($titlecode, 3, 1) != false && strpos (mb_substr($titlecode, 3, 1), "》") === false){
			$code = mb_substr($titlecode, 3, 1);//コードを抜き出す　サンプル《ANCCC_YK963362》「9」の部分
			$sql = "SELECT timing FROM ccc_titlecode WHERE code = ?";
			$array = array($code);
			$res = parent::executeSQL($sql,$array);
			$ans = $res->fetchColumn();
			return $ans;
		}else{
			return null;
		}
	}

	private function getCCCFlagformTitlecode($titlecode){
		if(mb_substr($titlecode, 7, 1) != false && strpos (mb_substr($titlecode, 7, 1), "》") === false){
			$code = mb_substr($titlecode, 7, 1);//コードを抜き出す　サンプル《ANCCC_YK963362》「6」の部分
			$sql = "SELECT flag FROM ccc_titlecode WHERE code = ?";
			$array = array($code);
			$res = parent::executeSQL($sql,$array);
			$ans = $res->fetchColumn();
			return $ans;
		}else{
			return null;
		}
	}

	private function getCCCOsformTitlecode($titlecode){
		if(mb_substr($titlecode, 4, 1) != false && strpos (mb_substr($titlecode, 4, 1), "》") === false){
			$code = mb_substr($titlecode, 4, 1);//コードを抜き出す　サンプル《ANCCC_YK963362》「6」の部分
			$sql = "SELECT os FROM ccc_titlecode WHERE code = ?";
			$array = array($code);
			$res = parent::executeSQL($sql,$array);
			$ans = $res->fetchColumn();
			return $ans;
		}else{
			return null;
		}
	}

	private function getCCCCategoryformTitlecode($titlecode){
		if(mb_substr($titlecode, 6, 1) != false && strpos (mb_substr($titlecode, 6, 1), "》") === false){
			$code = mb_substr($titlecode, 6, 1);//コードを抜き出す　サンプル《ANCCC_YK963362》「3」の部分
			$sql = "SELECT category FROM ccc_titlecode WHERE code = ?";
			$array = array($code);
			$res = parent::executeSQL($sql,$array);
			$ans = $res->fetchColumn();
			return $ans;
		}else{
			return null;
		}
	}

	private function getCCCCustomerformTitlecode($titlecode){
		if(mb_substr($titlecode, 8, 1) != false && strpos (mb_substr($titlecode, 8, 1), "》") === false){
			$code = mb_substr($titlecode, 8, 1);//コードを抜き出す　サンプル《ANCCC_YK963362》「2」の部分
			$sql = "SELECT customer FROM ccc_titlecode WHERE code = ?";
			$array = array($code);
			$res = parent::executeSQL($sql,$array);
			$ans = $res->fetchColumn();
			return $ans;
		}else{
			return null;
		}
	}


}
