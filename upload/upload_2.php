<?php
require('upload_DB.php');
$upload_db = new upload_DB();

// require_once('uploading_DB.php');
// $uploading_db = New uploading_DB();

$data = "";
$tostep3_class = "class = 'hide'";

$filepath = $_POST['filepath'];
$filename = $_POST['filename'];

$memoryusage_file = './memoryusagelog.txt';//メモリ使用量ログファイル
$memoryusage4 = "Step2:ページ先頭時のメモリ使用量は";
$memoryusage4 .= memory_get_usage()/(1024*1024);//デバッグ用、メモリ使用量計測
$memoryusage4 .= "MB\r\n";
file_put_contents($memoryusage_file, $memoryusage4, FILE_APPEND | LOCK_EX);//ログにメモリ使用量を追記

//読み込んで表示ボタンが押された時の動作
if(isset($_POST['readweekexcel'])){

	//$memoryusage_file = './memoryusagelog.txt';//メモリ使用量ログファイル
	$memoryusage5 = "Step2:読み込んで表示ボタンが押された直後のメモリ使用量は";
	$memoryusage5 .= memory_get_usage()/(1024*1024);//デバッグ用、メモリ使用量計測
	$memoryusage5 .= "MB\r\n";
	file_put_contents($memoryusage_file, $memoryusage5, FILE_APPEND | LOCK_EX);//ログにメモリ使用量を追記

	ini_set("max_execution_time",600);//タイムアウト時間を10分に設定
	ini_set('memory_limit', '1G');//PHPが使用するメモリのサイズを変更

	//データベースへ登録し、生成されたIDを戻り値として受け取る
	$filepath = $_POST['filepath'];
	$week = $upload_db->getweekformExcel($filepath);

	$data = "エクセルファイルのDataperiod：";
	$data .= "<table border='1'><tr>";
	for($i=0; $i < count($week); ++$i){
		$data .= "<td>{$week[$i]}</td>";
	}
	$data .= "</tr></table>";

	$weekcount = $upload_db->getweekformDB($week);
// 	$weekcount = array();//デバッグ用

	$data2 = "データベースに同じDataperiodの登録が存在するか。あればその数を表示します。：<br><br>\n";

	$countsum = "";
	for($i =0; $i < count($weekcount); ++$i){
		$countsum += $weekcount[$i];
	}

	if(empty($weekcount)){
		$data2 .= "同じDataperiodの登録はありませんでした。Step 3へ進んでください。<br><br>\n";
		$tostep3_class = "";
	}else{
		$data2 .="<table border='1'><tr><th>Dataperiod</th><th>既存登録数</th></tr>\n";
		for($i=0; $i < count($week); ++$i){
			$data2 .= "<tr><td>{$week[$i]}</td><td>{$weekcount[$i]}</td></tr>\n";
		}
		$data2 .="</table>\n";
		if($countsum == 0){
			$data2 .= "同じDataperiodの登録はありませんでした。Step 3へ進んでください。<br><br>\n";
			$tostep3_class = "";
		}else{
			$data2 .="<p style='color:red'>データベースの整合性を確保するため、これ以上は先に進めません。データベース管理者までお問い合わせください。</p>\n";
			$tostep3_class = "class = 'hide'";
		}

	}

	//$memoryusage_file = './memoryusagelog.txt';//メモリ使用量ログファイル
	$memoryusage6 = "Step2:読み込んで表示ボタンが押された処理終了後のメモリ使用量は";
	$memoryusage6 .= memory_get_usage()/(1024*1024);//デバッグ用、メモリ使用量計測
	$memoryusage6 .= "MB\r\n";
	file_put_contents($memoryusage_file, $memoryusage6, FILE_APPEND | LOCK_EX);//ログにメモリ使用量を追記


}




//データベース登録ボタンが押された時の動作
// if(isset($_POST['excel2db'])){
// 	//データベースへ登録し、生成されたIDを戻り値として受け取る
// 	$result = $upload_db->excel2db();
// }

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
 $('.loading').prepend('<img src="../image/load.gif" width="10%"><br>数分かかる場合があります');
},100);
	//btn.form.submit();
	//$('#main').submit();
}

</script>


</head>
<body>

<h1>RAWデータアップロード</h1>

<h2>Step 2: 二重登録確認</h2>

<div id = "compareinstruction">
現在読み込み中のファイル：<?php echo $filename;?><br>
ファイルのパス：<?php echo $filepath;?><br>

<p>これからデータベースに登録しようとしているデータが、すでに登録済みではないかを確認します。<br>
Dataperiodを基準にしています。<br>
もしデータベースに同じDataperiodの登録が見つかった場合は、先に進めず登録できません。</p>
</div>
<form id="main" method="post" action="">
		エクセルからDataperiodを読み込んで表示します：
		<input type="hidden" value="<?php echo $filepath;?>" name="filepath">
		<input type="hidden" value="<?php echo $filename;?>" name="filename">
		<input type="submit" value="読み込んで表示" name="readweekexcel" onClick="disp()">
</form>
<!-- $weekの内容は：<?php var_dump($week);?><br> -->

<div class="loading"></div>

<?php echo $data;?><br>
<?php echo $data2;?><br>

<!-- <form name="main2" method="post" action=""> -->
<!-- 		エクセルからデータを読み込んでデータベースに登録します： -->
<!-- 		<input type="submit" value="データベース登録" name="excel2db"> -->
<!-- </form> -->

<div <?php echo $tostep3_class;?>>
<form name="main2" method="post" action="upload_3.php">
			データベース登録へ：
			<input type="hidden" value="<?php echo $filepath;?>" name="filepath">
			<input type="hidden" value="<?php echo $filename;?>" name="filename">
			<input type="submit" value="Step 3" name="step3">
</form>
</div>



</body>
</html>

