<?php
require_once('db.php');
//include the following 2 files
require_once("./Classes/PHPExcel.php");
require_once ("./Classes/PHPExcel/IOFactory.php");

class product_DB extends DB{
	//データベースを操作する機能を提供するためのクラス


	//sample from https://stackoverflow.com/questions/8221096/import-an-excel-file-into-a-mysql-table-with-phpexcel


	public function excelreadcheck(){
		//エクセルを読み込んで表形式でページに表示する

		$path = "./test_Product_table.xlsx";
// 		$path = "./test_snic.xlsx";

		$objPHPExcel = PHPExcel_IOFactory::load($path);
		foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
			$worksheetTitle     = $worksheet->getTitle();
			$highestRow         = $worksheet->getHighestRow(); // e.g. 10
			$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
			$nrColumns = ord($highestColumn) - 64;

			$data = "<br>The worksheet{$worksheetTitle}has{$nrColumns} columns (A-{$highestColumn})";
			$data .= "and {$highestRow}row";
			$data .= "<br>Data:";
			$data .= "<table border='1'><tr>";
			for ($row = 1; $row <= $highestRow; ++ $row) {
				echo '<tr>';
				for ($col = 0; $col < $highestColumnIndex; ++ $col) {
					$cell = $worksheet->getCellByColumnAndRow($col, $row);
					$val = $cell->getValue();
					$dataType = PHPExcel_Cell_DataType::dataTypeForValue($val);
					$data .= "<td>{$val}<br>(Typ {$dataType})</td>";
				}
				$data .= "</tr>";
			}

			$data .= "</table>";
		}


		return $data;

	}

	public function excel2db(){
		//エクセルを読み込んでデータベースに登録する

		ini_set("max_execution_time",300);//タイムアウト時間を5分に設定

		$path = "./test_Product_table.xlsx";

		$objPHPExcel = PHPExcel_IOFactory::load($path);

		$worksheet = $objPHPExcel->getActiveSheet();//追加

// 		foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
			$worksheetTitle     = $worksheet->getTitle();
			$highestRow         = $worksheet->getHighestRow(); // e.g. 10
			$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
			$nrColumns = ord($highestColumn) - 64;

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

		$countinsert = 0;
		$errorinsert = 0;
		$i = 0;

		for ($row = 2; $row <= $highestRow; ++ $row) {
			$array=array();
			$array[] = null;
			for ($col = 0; $col < $highestColumnIndex; ++ $col) {
				$cell = $worksheet->getCellByColumnAndRow($col, $row);
				$array[] = $cell->getValue();
			}

			//データベースへの登録
			$sql ="INSERT INTO product VALUES(?,?,?,?,?, ?,?,?)";
// 			$array2 = array(null,'1','2','3','4','5','6','7','8','9');
			$res = parent::executeSQL3($sql, $array);
			if($res == 1){
				++ $countinsert;
			}else{
				++ $errorinsert;
			}

		}

		$result = array($countinsert,$errorinsert);

		return $result;

	}

}
?>