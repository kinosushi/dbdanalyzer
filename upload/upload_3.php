<?php
require('upload_DB.php');
$upload_db = new upload_DB();

//include the following 2 files
require_once("../Classes/PHPExcel.php");
require_once ("../Classes/PHPExcel/IOFactory.php");
require_once('uploading_DB.php');
$uploading_db = New uploading_DB();

$memoryusage_file = './memoryusagelog.txt';//メモリ使用量ログファイル
$memoryusage7 = "Step3:ページ先頭時のメモリ使用量は";
$memoryusage7 .= memory_get_usage()/(1024*1024);//デバッグ用、メモリ使用量計測
$memoryusage7 .= "MB\r\n";
file_put_contents($memoryusage_file, $memoryusage7, FILE_APPEND | LOCK_EX);//ログにメモリ使用量を追記

$data = "";

$filepath = $_POST['filepath'];
$filename = $_POST['filename'];

$uploadinstruction_style = "";

session_start();

if(isset($_POST['step3'])){
//ステップ３でのデータベース登録用のトークン（チケット）を生成
// session_start();
$_SESSION['ticket'] = md5(uniqid().mt_rand());
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge"><!--これを追加して、IEだけで一部のスタイルシートが反映されなかった問題が解決 -->
<link rel="shortcut icon" href="./pic/favicon.png"/>
<title>DBD Analyzer Test</title>

<!--  スタイルシート -->
<link rel="stylesheet" type="text/css" href="style_upload.css" />

<!--<script type="text/javascript" language="javascript" src="./case_javascript.js"></script>-->
<script src="../javascript/jquery-1.7.min.js"></script>
<script>
function disp(btn){
	setTimeout(function(){
		document.getElementById("section_instruction").style.display = "none";
 $('.loading').prepend('<img src="../image/load.gif" width="10%"><br>数分かかる場合があります');
},100);
	//btn.main3.submit();
	//$('#main3').submit();
}

</script>


</head>
<body>
<!--$_SESSION['ticket']は：<?php echo $_SESSION['ticket'];?><br>--><!-- デバッグ用 -->
<!--$_POST['ticket']は：<?php echo $_POST['ticket'];?>--><!-- デバッグ用 -->

<h1>RAWデータアップロード</h1>

<h2>Step 3: データベースへの登録</h2>

<div class="loading"></div>

現在読み込み中のファイル：<?php echo $filename;?><br>
ファイルのパス：<?php echo $filepath;?><br>

<?php

if (isset($_POST['insert2db'], $_SESSION['ticket'], $_POST['ticket']) && $_SESSION['ticket'] === $_POST['ticket']) {
// 	if (isset($_POST['insert2db'])) {

	//$memoryusage_file = './memoryusagelog.txt';//メモリ使用量ログファイル
	$memoryusage8 = "Step3:登録ボタンが押された直後のメモリ使用量は";
	$memoryusage8 .= memory_get_usage()/(1024*1024);//デバッグ用、メモリ使用量計測
	$memoryusage8 .= "MB\r\n";
	file_put_contents($memoryusage_file, $memoryusage8, FILE_APPEND | LOCK_EX);//ログにメモリ使用量を追記

	unset($_SESSION['ticket']);//チケットを破棄

	$uploadinstruction_style = "class = 'hide'";

	$filename = $_POST['filename'];
	$file = './insertlog.txt';//ログファイル
	$str = new DateTime('now', new DateTimeZone('Asia/Tokyo'));
	$date = $str->format('Y-m-d H:i:s');
	$date .= "\r\n";

	$filename_log = "ファイル名：".$filename."\r\n";

	file_put_contents($file, $date, FILE_APPEND | LOCK_EX);//ログに日時を追記
	file_put_contents($file, $filename_log, FILE_APPEND | LOCK_EX);//ログにファイル名を追記
	$time_start = microtime(true);// 処理時間計測スタート

	ini_set("max_execution_time",600);//タイムアウト時間を10分に設定
	ini_set('memory_limit', '1G');//PHPが使用するメモリのサイズを変更

	// ini_set("output_buffering", 0);
	// echo "しばらくお待ちください";
	// for ($i = 0; $i < 5; $i++) {
	// 	echo "→";
	// 	sleep(2);
	// }

	echo "処理を開始します。しばらくお待ちください...<br />\n";

	// $path = "./test_snic.xlsx";
	// $path = "./snic.xlsx";
	$path = $_POST['filepath'];
	$objPHPExcel = PHPExcel_IOFactory::load($path);
	$worksheet = $objPHPExcel->getActiveSheet();//追加
	$worksheetTitle     = $worksheet->getTitle();
	$highestRow         = $worksheet->getHighestRow(); // e.g. 10
	// $highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
	$highestColumn      = 'X'; // コラム数は固定
	$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
	$nrColumns = ord($highestColumn) - 64;

	echo "読み込み予定のファイルパス：".$path."<br />\n";

	echo $highestRow."行のデータを処理中。<br />\n";

	$highestRow_log = "総行数：".$highestRow."行"."\r\n";
	file_put_contents($file, $highestRow_log, FILE_APPEND | LOCK_EX);//ログに総行数を追記

	echo str_pad(" ",4096)."<br />\n";//試しに

	$i = 0;//処理100回ごとの区切りカウント
	$countinsert = 0;//データベース登録成功数カウント
	$errorinsert = 0;//データベース登録失敗数カウント
	$nocount = 0;//データベース登録対象外数カウント
	$pro_countinsert = 0;//Productデータベース登録成功数カウント
	$pro_errorinsert = 0;//Productデータベース登録失敗数カウント
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
				$array[] = null;//最後にもう一つNULLを追加(os)
				$array[] = null;//最後にもう一つNULLを追加(category)
				$array[] = null;//最後にもう一つNULLを追加(customer)

				//有効なSNとJapanの行だけデータベースへの登録を試みる
				if($array[10] == "Japan" && $array[2] == "Y"){//"Country"がJapan 且つ"Valid SN"がYのみ登録
					//データベースへの登録
					$res = $uploading_db->upload2db($array);
					if($res[0] == 1){
						++ $countinsert;
						if($res[1]){
							++ $pro_countinsert;
						}else{
							++ $pro_errorinsert;
						}
					}else{
						++ $errorinsert;
						if($res[1]){
							++ $pro_countinsert;
						}else{
							++ $pro_errorinsert;
						}
					}
				}else{
					++ $nocount;
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
				$array[] = null;//最後にもう一つNULLを追加(os)
				$array[] = null;//最後にもう一つNULLを追加(category)
				$array[] = null;//最後にもう一つNULLを追加(customer)

				//有効なSNとJapanの行だけデータベースへの登録を試みる
				if($array[10] == "Japan" && $array[2] == "Y"){//"Country"がJapan 且つ"Valid SN"がYのみ登録
					//データベースへの登録
					$res = $uploading_db->upload2db($array);
					if($res[0] == 1){
						++ $countinsert;
						if($res[1]){
							++ $pro_countinsert;
						}else{
							++ $pro_errorinsert;
						}
					}else{
						++ $errorinsert;
						if($res[1]){
							++ $pro_countinsert;
						}else{
							++ $pro_errorinsert;
						}
					}
				}else{
					++ $nocount;
				}

			}
			echo 100*($i+1)."件が処理済み・・\n";
		}

		$limitedrow = $limitedrow - 100;
		++$i;

		ob_flush();
		flush();
	}

	//$memoryusage_file = './memoryusagelog.txt';//メモリ使用量ログファイル
	$memoryusage9 = "Step3:データベースへの登録処理終了時のメモリ使用量は";
	$memoryusage9 .= memory_get_usage()/(1024*1024);//デバッグ用、メモリ使用量計測
	$memoryusage9 .= "MB\r\n";
	file_put_contents($memoryusage_file, $memoryusage9, FILE_APPEND | LOCK_EX);//ログにメモリ使用量を追記


	$time_end = microtime(true);// 処理終了時間
	$time = $time_end - $time_start;// 処理終了時間処理時間計測

	// 処理時間を　分,秒表示に変換
	$min_sec_time = sprintf("%02d分%02d秒",$time / 60, $time % 60);

	echo "処理が完了しました<br />\n";
	echo $countinsert."件の処理が成功しました<br />\n";
	echo $errorinsert."件の処理が失敗しました<br />\n";
	echo $nocount."件が処理対象外でした<br />\n";
	echo "処理時間は".$min_sec_time."でした<br /><br />\n";

	echo $pro_countinsert."つのProduct Numberが新たにProductテーブルに追加されました<br />\n";
	echo $pro_errorinsert."つのProduct Numberは、既にProductテーブルに登録が存在していました<br /><br />\n";

	$result_log = "処理結果："."\r\n";
	$result_log .= "成功：".$countinsert."\r\n";
	$result_log .= "失敗：".$errorinsert."\r\n";
	$result_log .= "対象外：".$nocount."\r\n";
	$result_log = "Productテーブルへの追加："."\r\n";
	$result_log .= "新規追加：".$pro_countinsert."\r\n";
	$result_log .= "処理時間：".$min_sec_time."\r\n\r\n";

	file_put_contents($file, $result_log, FILE_APPEND | LOCK_EX);//ログに処理結果を追記


}
?>

<!-- 最後の$array配列の中身：<?php //var_dump($array);?><br /> --><!-- デバッグ用 -->

<div id = "section_instruction" <?php echo $uploadinstruction_style;?>>
	<p>エクセルファイルは以下のルールに則ってデータベースに登録されます。<br>
	一番上の行から読み込みが開始され、<br>
	「Valid SN」が「Y」且つ「Country」が「Japan」の行がデータベースに登録されます。それ以外は対象外となります。<br>
	ただし、以下のコラムの情報が空欄になっていた場合は登録されず、「失敗」にカウントされます。</p>
	<p>
	Unique_Sn<br>
	Case_Id<br>
	Unique_Subcase<br>
	Subcase_Series<br>
	Xotc All<br>
	Case_Title<br>
	Product<br>
	Product_Line<br>
	Dataperiod<br>
	</p>
	<p>下の登録ボタンが押されると、エクセルの総行数が表示されたのち、処理100件ずつの進捗状況が表示されます。</p>
	<p>処理が完了すると、登録完了数と登録失敗数が表示されます。</p>

</div>
	<form id ="main3" method="post" action="">
			エクセルからデータを読み込んでデータベースに登録します。同じページで処理。：
			<input type="hidden" value="<?php echo $filepath;?>" name="filepath">
			<input type="hidden" value="<?php echo $filename;?>" name="filename">
			<input type="hidden" name="ticket" value="<?php echo htmlspecialchars($_SESSION['ticket'], ENT_QUOTES); ?>">
			<input type="submit" value="登録開始" name="insert2db" onClick="disp()">
	</form>

<!-- 	<form name="main4" method="post" action="uploading.php" target = "_blank"> -->
<!-- 			エクセルからデータを読み込んでデータベースに登録します。別ページで処理。： -->
			<input type="hidden" value="<?php echo $filepath;?>" name="filepath">
			<input type="hidden" value="<?php echo $filename;?>" name="filename">
<!-- 			<input type="submit" value="登録開始" name="insert2db"> -->
<!-- 	</form> -->








</body>
</html>

