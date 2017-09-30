<?php
require('DB_product_up.php');
$db_product_up = new DB_product_up();

//include the following 2 files
require_once("../Classes/PHPExcel.php");
require_once ("../Classes/PHPExcel/IOFactory.php");


// $memoryusage_file = './memoryusagelog.txt';//メモリ使用量ログファイル
// $memoryusage7 = "Step3:ページ先頭時のメモリ使用量は";
// $memoryusage7 .= memory_get_usage()/(1024*1024);//デバッグ用、メモリ使用量計測
// $memoryusage7 .= "MB\r\n";
// file_put_contents($memoryusage_file, $memoryusage7, FILE_APPEND | LOCK_EX);//ログにメモリ使用量を追記

$data = "";

$filepath = $_POST['filepath'];
$filename = $_POST['filename'];

$uploadinstruction_style = "";
$uploadinstbtn_style = "";

session_start();

if(isset($_POST['step2'])){
	//ステップ３でのデータベース登録用のトークン（チケット）を生成
	// session_start();
	$_SESSION['ticket'] = md5(uniqid().mt_rand());
}
?>

<?php include('html_head.php'); ?>

<!--$_SESSION['ticket']は：<?php echo $_SESSION['ticket'];?><br>--><!-- デバッグ用 -->
<!--$_POST['ticket']は：<?php echo $_POST['ticket'];?>--><!-- デバッグ用 -->
<h1>Product Master アップロード</h1>

<h2>Product データベースへの登録</h2>

現在読み込み中のファイル：<?php echo $filename;?><br>
ファイルのパス：<?php echo $filepath;?><br>

<?php

if (isset($_POST['insert2db'], $_SESSION['ticket'], $_POST['ticket']) && $_SESSION['ticket'] === $_POST['ticket']) {
// 	if (isset($_POST['insert2db'])) {

	unset($_SESSION['ticket']);//チケットを破棄

	$filename = $_POST['filename'];
	$file = './productinsertlog.txt';//ログファイル
	$str = new DateTime('now', new DateTimeZone('Asia/Tokyo'));
	$date = $str->format('Y-m-d H:i:s');
	$date .= "\r\n";

	$filename_log = "ファイル名：".$filename."\r\n";

	file_put_contents($file, $date, FILE_APPEND | LOCK_EX);//ログに日時を追記
	file_put_contents($file, $filename_log, FILE_APPEND | LOCK_EX);//ログにファイル名を追記
	$time_start = microtime(true);// 処理時間計測スタート

	ini_set("max_execution_time",600);//タイムアウト時間を10分に設定
	ini_set('memory_limit', '1G');//PHPが使用するメモリのサイズを変更


	echo "処理を開始します。しばらくお待ちください...<br />\n";

	// $path = "./test_snic.xlsx";
	// $path = "./snic.xlsx";
	$path = $_POST['filepath'];
	$objPHPExcel = PHPExcel_IOFactory::load($path);
	$worksheet = $objPHPExcel->getActiveSheet();//追加
	$worksheetTitle     = $worksheet->getTitle();
	$highestRow         = $worksheet->getHighestRow(); // e.g. 10
	// $highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
	$highestColumn      = 'G'; // コラム数は固定
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
	$limitedrow = $highestRow;

	ob_end_flush();
	ob_start('mb_output_handler');
	
	$uploadinstruction_style = "class = 'hide'";
	$uploadinstbtn_style = "class = 'hide'";

	while(($limitedrow-1) > 0){
		 	echo "whileループに入りました。<br />\n";
		if(($limitedrow-1) < 100){
			 echo "データ数が100より小さい場合のIF文に入りました。<br />\n";
			for ($row = 2+(100*$i); $row <= $highestRow; ++ $row) {
				$array=array();
				$array[] = null;//id分のnull値
				for ($col = 0; $col < $highestColumnIndex; ++ $col) {
					$cell = $worksheet->getCellByColumnAndRow($col, $row);
					$array[] = $cell->getValue();
				}
				
				//プロダクト名がnullだった場合は””空文字に変更（nullのままだとなぜかリストでフィルターできないため）
				if($array[2] == null){
					$array[2] = "";
				}
				
				//データベースへの登録
				$res = $db_product_up->upload2db($array);
				
				if($res == 1){
					++ $countinsert;
				}else{
					++ $errorinsert;
				}

			}
			echo 100*$i + $limitedrow."件が処理済み<br />\n";
		}else{
			echo "データ数が100より大きい場合のIF文に入りました。<br />\n";
			for ($row = 2+(100*$i); $row <= (101 + 100*$i); ++ $row) {
				$array=array();
				$array[] = null;
				for ($col = 0; $col < $highestColumnIndex; ++ $col) {
					$cell = $worksheet->getCellByColumnAndRow($col, $row);
					$array[] = $cell->getValue();
				}
				
					//データベースへの登録
					$res = $db_product_up->upload2db($array);
					
					if($res == 1){
						++ $countinsert;
					}else{
						++ $errorinsert;
					}
				
			}
			echo 100*($i+1)."件が処理済み・・\n";
		}
		
		$limitedrow = $limitedrow - 100;
		++$i;
		
		ob_flush();
		flush();
	}

	$time_end = microtime(true);// 処理終了時間
	$time = $time_end - $time_start;// 処理終了時間処理時間計測

	// 処理時間を　分,秒表示に変換
	$min_sec_time = sprintf("%02d分%02d秒",$time / 60, $time % 60);

	echo "処理が完了しました<br />\n";
	echo $countinsert."件の処理が成功しました<br />\n";
	echo $errorinsert."件の処理が失敗しました<br />\n";
	echo "処理時間は".$min_sec_time."でした<br /><br />\n";


	$result_log = "処理結果："."\r\n";
	$result_log .= "成功：".$countinsert."\r\n";
	$result_log .= "失敗：".$errorinsert."\r\n";
	$result_log .= "処理時間：".$min_sec_time."\r\n\r\n";

	file_put_contents($file, $result_log, FILE_APPEND | LOCK_EX);//ログに処理結果を追記


}
?>

<!-- 最後の$array配列の中身：<?php //var_dump($array);?><br /> --><!-- デバッグ用 -->

<div id = "section_instruction" <?php echo $uploadinstruction_style;?>>
	<p>エクセルファイルは以下のルールに則ってデータベースに登録されます。<br>
	一番上の行から読み込みが開始されます。</p>

	<p>下の登録ボタンが押されると、エクセルの総行数が表示されたのち、処理100件ずつの進捗状況が表示されます。</p>
	<p>処理が完了すると、登録完了数と登録失敗数が表示されます。</p>
</div>
<div id = "section_uploadbtn" <?php echo $uploadinstbtn_style;?>>
	<form id ="main3" method="post" action="">
			エクセルからデータを読み込んでデータベースに登録します。同じページで処理。：
			<input type="hidden" value="<?php echo $filepath;?>" name="filepath">
			<input type="hidden" value="<?php echo $filename;?>" name="filename">
			<input type="hidden" name="ticket" value="<?php echo htmlspecialchars($_SESSION['ticket'], ENT_QUOTES); ?>">
			<input type="submit" value="登録開始" name="insert2db" onClick="disp()">
	</form>
</div>
<div class="loading"></div>

</body>
</html>


