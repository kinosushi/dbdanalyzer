<?php
require_once('db.php');
//include the following 2 files
require_once("../Classes/PHPExcel.php");
require_once ("../Classes/PHPExcel/IOFactory.php");

class upload_DB extends DB{
	//データベースを操作する機能を提供するためのクラス


	//sample from https://stackoverflow.com/questions/8221096/import-an-excel-file-into-a-mysql-table-with-phpexcel


	public function testExcel($filepath){
		
		$path = $filepath;
		
// 		$objPHPExcel = PHPExcel_IOFactory::load($path);
		$objPHPExcel = PHPExcel_IOFactory::load($path);
		foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
			$worksheetTitle     = $worksheet->getTitle();
			$highestRow         = $worksheet->getHighestRow(); // e.g. 10
			// 			$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
			$highestColumn      = 'X'; // コラム数は固定
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
			$nrColumns = ord($highestColumn) - 64;
			
		}
		
		$cell_b = $worksheet->getCellByColumnAndRow(10, 1);
		$b = $cell_b->getValue();
			
		return $b;
	}
	
	
	public function getweekformExcel($filepath){

		$path = $filepath;
		$week = array();

		$objPHPExcel = PHPExcel_IOFactory::load($path);
		foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
			$worksheetTitle     = $worksheet->getTitle();
			$highestRow         = $worksheet->getHighestRow(); // e.g. 10
			// 			$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
			$highestColumn      = 'X'; // コラム数は固定
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
			$nrColumns = ord($highestColumn) - 64;

			//Weekperiodを全種類抜き出して配列に格納

			for($row=2; $row<=$highestRow; $row++){
				$cell2 = $worksheet->getCellByColumnAndRow(21, $row);
				$cell_week = $cell2->getValue();
				if(!in_array($cell_week, $week)){
					$week[] = $cell_week;
				}
			}
		}
		return $week;
	}
	public function getweekformDB($week){

		//テーブル上の各Weekの登録数をカウント

		$weekcount = array();//カウントした数を配列に格納
		for($i=0; $i < count($week); ++$i){
			$sql = "SELECT COUNT(*) FROM dbd_analyzer WHERE Dataperiod=?";
			$array = array($week[$i]);
			$res = parent::executeSQL($sql,$array);
			$weekcount[$i] = $res->fetchColumn();
		}


		return $weekcount;


	}


	public function excelreadcheck($filepath){
		
		ini_set("max_execution_time",600);//タイムアウト時間を10分に設定
		ini_set('memory_limit', '1G');//PHPが使用するメモリのサイズを変更

// 		$path = "./snic.xlsx";
		$path = $filepath;

		$objPHPExcel = PHPExcel_IOFactory::load($path);
		foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
			$worksheetTitle     = $worksheet->getTitle();
			$highestRow         = $worksheet->getHighestRow(); // e.g. 10
// 			$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
			$highestColumn      = 'X'; // コラム数は固定
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
			$nrColumns = ord($highestColumn) - 64;



			$data = "<br>The worksheet{$worksheetTitle}has{$nrColumns} columns (A-{$highestColumn})";
			$data .= "and {$highestRow}row";
			$data .= "<br>Data:エクセルからは1/50のサンプルを抽出します。";
			$data .= "<table border='1'>";
			$data .=<<<eof
					<tr>
					<td>A</td><td>B</td><td>C</td><td>D</td><td>E</td><td>F</td><td>G</td><td>H</td>
					<td>I</td><td>J</td><td>K</td><td>L</td><td>M</td><td>N</td><td>O</td><td>P</td>
					<td>Q</td><td>R</td><td>S</td><td>T</td><td>U</td><td>V</td><td>W</td><td>X</td>
					</tr>
					<tr>
					<td>Serial_Number</td>
					<td>Valid_Sn</td>
					<td>Unique_Sn</td>
					<td>Case_Id</td>
					<td>Subcase_Id</td>
					<td>Event_Id</td>
					<td>Unique_Subcase</td>
					<td>Subcase_Series</td>
					<td>Xotc All</td>
					<td>Country</td>
					<td>Case_Title</td>
					<td>Open_Date</td>
					<td>Close_Date</td>
					<td>Delivery_Alternative</td>
					<td>Product</td>
					<td>Product_Description</td>
					<td>Owner_Work_Group</td>
					<td>Part_Number</td>
					<td>Part_Desc</td>
					<td>X_Part_Usage</td>
					<td>Product_Line</td>
					<td>Dataperiod</td>
					<td>Pure_Delivery</td>
					<td>GCSS_Customer_Name</td>
					</tr>
					<tr>
					<td colspan = '24'>上の行がサンプル項目。以下がエクセルからの出力。エクセル側の項目が正しく並べられているか、必要な項目が存在しているか確認をお願いいたします。</td>
					</tr>
eof;
			//コラムが正しいかどうかの判定
			$column_color = array();

			$cell_a = $worksheet->getCellByColumnAndRow(0, 1);
			$a = $cell_a->getValue();
			if(strpos($a,'Serial') !== false && strpos($a,'Number') !== false){
				//文字列のなかにキーワードが含まれている場合
				$column_color[0] = "";
			}else{$column_color[0] = "style=\"background: red\"";}


			$cell_b = $worksheet->getCellByColumnAndRow(1, 1);
			$b = $cell_b->getValue();
			if(strpos($b,'Valid') !== false && strpos($b,'Sn') !== false){
				//文字列のなかにキーワードが含まれている場合
				$column_color[1] = "";
			}else{$column_color[1] = "style=\"background: red\"";}

			$cell_c = $worksheet->getCellByColumnAndRow(2, 1);
			$c = $cell_c->getValue();
			if(strpos($c,'Unique') !== false && strpos($c,'Sn') !== false){
				//文字列のなかにキーワードが含まれている場合
				$column_color[2] = "";
			}else{$column_color[2] = "style=\"background: red\"";}

			$cell_d = $worksheet->getCellByColumnAndRow(3, 1);
			$d = $cell_d->getValue();
			if(strpos($d,'Case') !== false && strpos($d,'Id') !== false){
				//文字列のなかにキーワードが含まれている場合
				$column_color[3] = "";
			}else{$column_color[3] = "style=\"background: red\"";}

			$cell_e = $worksheet->getCellByColumnAndRow(4, 1);
			$e = $cell_e->getValue();
			if(strpos($e,'Subcase') !== false && strpos($e,'Id') !== false){
				//文字列のなかにキーワードが含まれている場合
				$column_color[4] = "";
			}else{$column_color[4] = "style=\"background: red\"";}

			$cell_f = $worksheet->getCellByColumnAndRow(5, 1);
			$f = $cell_f->getValue();
			if(strpos($f,'Event') !== false && strpos($f,'Id') !== false){
				//文字列のなかにキーワードが含まれている場合
				$column_color[5] = "";
			}else{$column_color[5] = "style=\"background: red\"";}

			$cell_g = $worksheet->getCellByColumnAndRow(6, 1);
			$g = $cell_g->getValue();
			if(strpos($g,'Unique') !== false && strpos($g,'Subcase') !== false){
				//文字列のなかにキーワードが含まれている場合
				$column_color[6] = "";
			}else{$column_color[6] = "style=\"background: red\"";}

			$cell_h = $worksheet->getCellByColumnAndRow(7, 1);
			$h = $cell_h->getValue();
			if(strpos($h,'Subcase') !== false && strpos($h,'Series') !== false){
				//文字列のなかにキーワードが含まれている場合
				$column_color[7] = "";
			}else{$column_color[7] = "style=\"background: red\"";}

			$cell_i = $worksheet->getCellByColumnAndRow(8, 1);
			$ii = $cell_i->getValue();
			if(strpos($ii,'Xotc') !== false && strpos($ii,'All') !== false){
				//文字列のなかにキーワードが含まれている場合
				$column_color[8] = "";
			}else{$column_color[8] = "style=\"background: red\"";}

			$cell_j = $worksheet->getCellByColumnAndRow(9, 1);
			$j = $cell_j->getValue();
			if(strpos($j,'Country') !== false){
				//文字列のなかにキーワードが含まれている場合
				$column_color[9] = "";
			}else{$column_color[9] = "style=\"background: red\"";}

			$cell_k = $worksheet->getCellByColumnAndRow(10, 1);
			$k = $cell_k->getValue();
			if(strpos($k,'Case') !== false && strpos($k,'Title') !== false){
				//文字列のなかにキーワードが含まれている場合
				$column_color[10] = "";
			}else{$column_color[10] = "style=\"background: red\"";}

			$cell_l = $worksheet->getCellByColumnAndRow(11, 1);
			$l = $cell_l->getValue();
			if(strpos($l,'Open') !== false && strpos($l,'Date') !== false){
				//文字列のなかにキーワードが含まれている場合
				$column_color[11] = "";
			}else{$column_color[11] = "style=\"background: red\"";}

			$cell_m = $worksheet->getCellByColumnAndRow(12, 1);
			$m = $cell_m->getValue();
			if(strpos($m,'Close') !== false && strpos($m,'Date') !== false){
				//文字列のなかにキーワードが含まれている場合
				$column_color[12] = "";
			}else{$column_color[12] = "style=\"background: red\"";}

			$cell_n = $worksheet->getCellByColumnAndRow(13, 1);
			$n = $cell_n->getValue();
			if(strpos($n,'Delivery') !== false && strpos($n,'Alternative') !== false){
				//文字列のなかにキーワードが含まれている場合
				$column_color[13] = "";
			}else{$column_color[13] = "style=\"background: red\"";}

			$cell_o = $worksheet->getCellByColumnAndRow(14, 1);
			$o = $cell_o->getValue();
			if(strpos($o,'Product') !== false){
				//文字列のなかにキーワードが含まれている場合
				$column_color[14] = "";
			}else{$column_color[14] = "style=\"background: red\"";}

			$cell_p = $worksheet->getCellByColumnAndRow(15, 1);
			$p = $cell_p->getValue();
			if(strpos($p,'Product') !== false && strpos($p,'Description') !== false){
				//文字列のなかにキーワードが含まれている場合
				$column_color[15] = "";
			}else{$column_color[15] = "style=\"background: red\"";}

			$cell_q = $worksheet->getCellByColumnAndRow(16, 1);
			$q = $cell_q->getValue();
			if(strpos($q,'Owner') !== false && strpos($q,'Work') !== false){
				//文字列のなかにキーワードが含まれている場合
				$column_color[16] = "";
			}else{$column_color[16] = "style=\"background: red\"";}

			$cell_r = $worksheet->getCellByColumnAndRow(17, 1);
			$r = $cell_r->getValue();
			if(strpos($r,'Part') !== false && strpos($r,'Number') !== false){
				//文字列のなかにキーワードが含まれている場合
				$column_color[17] = "";
			}else{$column_color[17] = "style=\"background: red\"";}

			$cell_s = $worksheet->getCellByColumnAndRow(18, 1);
			$s = $cell_s->getValue();
			if(strpos($s,'Part') !== false && strpos($s,'Desc') !== false){
				//文字列のなかにキーワードが含まれている場合
				$column_color[18] = "";
			}else{$column_color[18] = "style=\"background: red\"";}

			$cell_t = $worksheet->getCellByColumnAndRow(19, 1);
			$t = $cell_t->getValue();
			if(strpos($t,'Part') !== false && strpos($t,'Usage') !== false){
				//文字列のなかにキーワードが含まれている場合
				$column_color[19] = "";
			}else{$column_color[19] = "style=\"background: red\"";}

			$cell_u = $worksheet->getCellByColumnAndRow(20, 1);
			$u = $cell_u->getValue();
			if(strpos($u,'Product') !== false && strpos($u,'Line') !== false){
				//文字列のなかにキーワードが含まれている場合
				$column_color[20] = "";
			}else{$column_color[20] = "style=\"background: red\"";}

			$cell_v = $worksheet->getCellByColumnAndRow(21, 1);
			$v = $cell_v->getValue();
			if(strpos($v,'Dataperiod') !== false){
				//文字列のなかにキーワードが含まれている場合
				$column_color[21] = "";
			}else{$column_color[21] = "style=\"background: red\"";}

			$cell_w = $worksheet->getCellByColumnAndRow(22, 1);
			$w = $cell_w->getValue();
			if(strpos($w,'Pure') !== false && strpos($w,'Delivery') !== false){
				//文字列のなかにキーワードが含まれている場合
				$column_color[22] = "";
			}else{$column_color[22] = "style=\"background: red\"";}

			$cell_x = $worksheet->getCellByColumnAndRow(23, 1);
			$x = $cell_x->getValue();
			if(strpos($x,'Customer') !== false && strpos($x,'Name') !== false){
				//文字列のなかにキーワードが含まれている場合
				$column_color[23] = "";
			}else{$column_color[23] = "style=\"background: red\"";}

			//1行目はコラムの色を変えられるようにStyleタグを挿入
			$data .= "<tr>";
			for ($col = 0; $col < $highestColumnIndex; ++ $col) {
				$cell = $worksheet->getCellByColumnAndRow($col, 1);
				$val = $cell->getValue();
				$data .= "<td {$column_color[$col]}>{$val}</td>";
			}
			$data .= "</tr>";

			//2行名以降
			for ($row = 2; $row <= $highestRow; $row+=100) {
				$data .= "<tr>";
				for ($col = 0; $col < $highestColumnIndex; ++ $col) {
					$cell = $worksheet->getCellByColumnAndRow($col, $row);
					$val = $cell->getValue();
					if($row != 1 && ($col == 11 || $col == 12)){
						$val= $this->text($val, $format = 'Y-m-d H:i:s');//Open Dateを変換
					}
					$data .= "<td>{$val}</td>";
				}
				$data .= "</tr>";
			}

			$data .= "</table>";
		}


		return $data;

	}

	private function text($serial, $format){
		//エクセルのシリアル値をUnix Timestampに変換
		return gmdate($format, ($serial -25569)*60*60*24);
	}

// 	public function excel2db(){

// 		$path = "./test_snic.xlsx";

// 		$objPHPExcel = PHPExcel_IOFactory::load($path);
// 		foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
// 			$worksheetTitle     = $worksheet->getTitle();
// 			$highestRow         = $worksheet->getHighestRow(); // e.g. 10
// 			$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
// 			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
// 			$nrColumns = ord($highestColumn) - 64;

// 			$data = "<br>The worksheet{$worksheetTitle}has{$nrColumns} columns (A-{$highestColumn})";
// 			$data .= "and {$highestRow}row";
// 			$data .= "<br>Data:";
// 			$data .= "<table border='1'><tr>";
// 			for ($row = 1; $row <= $highestRow; ++ $row) {
// 				echo '<tr>';
// 				for ($col = 0; $col < $highestColumnIndex; ++ $col) {
// 					$cell = $worksheet->getCellByColumnAndRow($col, $row);
// 					$val = $cell->getValue();
// 					$dataType = PHPExcel_Cell_DataType::dataTypeForValue($val);
// 					$data .= "<td>{$val}<br>(Typ {$dataType})</td>";
// 				}
// 				$data .= "</tr>";
// 			}

// 			$data .= "</table>";
// 		}

// 		$countinsert = 0;
// 		$errorinsert = 0;
// 		for ($row = 2; $row <= $highestRow; ++ $row) {
// 			$array=array();
// 			$array[] = null;
// 			for ($col = 0; $col < $highestColumnIndex; ++ $col) {
// 				$cell = $worksheet->getCellByColumnAndRow($col, $row);
// 				$array[] = $cell->getValue();
// 			}

// 			//データベースへの登録
// 			$sql ="INSERT INTO dbd_analyzer VALUES(?,?,?,?,?, ?,?,?,?,?)";
// // 			$array2 = array(null,'1','2','3','4','5','6','7','8','9');
// 			$res = parent::executeSQL3($sql, $array);
// 			if($res == 1){
// 				++ $countinsert;
// 			}else{
// 				++ $errorinsert;
// 			}

// 		}

// 		$result = array($countinsert,$errorinsert);

// 		return $result;

// 	}

}
?>