<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge"><!--これを追加して、IEだけで一部のスタイルシートが反映されなかった問題が解決 -->
<link rel="shortcut icon" href="./pic/favicon.png"/>
<title>処理中・・・・</title>

<!--  スタイルシート -->
<!--<link rel="stylesheet" type="text/css" href="style.css" /> -->

<!--<script type="text/javascript" language="javascript" src="./case_javascript.js"></script>-->

</head>
<body>
<h1>処理が完了したら、このページはすぐに閉じてください。</h1>
<?php
//include the following 2 files
require_once("../Classes/PHPExcel.php");
require_once ("../Classes/PHPExcel/IOFactory.php");
require_once('uploading_DB.php');

$uploading_db = New uploading_DB();

ini_set("max_execution_time",600);//タイムアウト時間を10分に設定
ini_set('memory_limit', '1G');//PHPが使用するメモリのサイズを変更

// $path = "./test_snic.xlsx";
$path = "./snic.xlsx";
$objPHPExcel = PHPExcel_IOFactory::load($path);
$worksheet = $objPHPExcel->getActiveSheet();//追加
$worksheetTitle     = $worksheet->getTitle();
$highestRow         = $worksheet->getHighestRow(); // e.g. 10
$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
$nrColumns = ord($highestColumn) - 64;



echo "処理を開始します。しばらくお待ちください...<br />\n";

echo $highestRow."行のデータを処理中。<br />\n";

echo str_pad(" ",4096)."<br />\n";//試しに

$i = 0;//処理100回ごとの区切りカウント
$countinsert = 0;//データベース登録成功数カウント
$errorinsert = 0;//データベース登録失敗数カウント
$limitedrow = $highestRow;

ob_end_flush();
ob_start('mb_output_handler');

while(($limitedrow-1) > 0){
// 	echo "whileループに入りました。<br />\n";
	if(($limitedrow-1) < 100){
// 		echo "データ数が100より小さい場合のIF文に入りました。<br />\n";
		for ($row = 2+(100*$i); $row <= $highestRow; ++ $row) {
			$array=array();
			$array[] = null;
			for ($col = 0; $col < $highestColumnIndex; ++ $col) {
				$cell = $worksheet->getCellByColumnAndRow($col, $row);
				$array[] = $cell->getValue();
			}
			$array[] = null;//最後にもう一つNULLを追加(Product_Name)
			$array[] = null;//最後にもう一つNULLを追加(Title_Code)
			$array[] = null;//最後にもう一つNULLを追加(Issue)
			$array[] = null;//最後にもう一つNULLを追加(timing)
			$array[] = null;//最後にもう一つNULLを追加(howoften)
			$array[] = null;//最後にもう一つNULLを追加(flag)
			
			

			//データベースへの登録
			$res = $uploading_db->upload2db($array);
			
			if($res == 1){
				++ $countinsert;
			}else{
				++ $errorinsert;
			}
		}
		echo 100*$i + $limitedrow."件が処理済み<br />\n";
	}else{
// 		echo "データ数が100より大きい場合のIF文に入りました。<br />\n";
		for ($row = 2+(100*$i); $row <= (101 + 100*$i); ++ $row) {
			$array=array();
			$array[] = null;
			for ($col = 0; $col < $highestColumnIndex; ++ $col) {
				$cell = $worksheet->getCellByColumnAndRow($col, $row);
				$array[] = $cell->getValue();
			}
			$array[] = null;//最後にもう一つNULLを追加(Product_Name)
			$array[] = null;//最後にもう一つNULLを追加(Title_Code)
			$array[] = null;//最後にもう一つNULLを追加(Issue)
			$array[] = null;//最後にもう一つNULLを追加(timing)
			$array[] = null;//最後にもう一つNULLを追加(howoften)
			$array[] = null;//最後にもう一つNULLを追加(flag)

			//データベースへの登録
			$res = $uploading_db->upload2db($array);
			if($res == 1){
				++ $countinsert;
			}else{
				++ $errorinsert;
				
			}

		}
		echo 100*($i+1)."件が処理済み<br />\n";
	}

	$limitedrow = $limitedrow - 100;
	++$i;

	ob_flush();
	flush();
}

echo "処理が完了しました<br />\n";
echo $countinsert."件の処理が成功しました<br />\n";
echo $errorinsert."件の処理が失敗しました<br />\n";
?>
<?php var_dump($array);?>
</body>
</html>