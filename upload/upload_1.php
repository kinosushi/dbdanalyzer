<?php
require('upload_DB.php');
$upload_db = new upload_DB();

require_once('uploading_DB.php');
$uploading_db = New uploading_DB();

$memoryusage_file = './memoryusagelog.txt';//メモリ使用量ログファイル
$memoryusage1 = "Step1:ページ先頭時点でのメモリ使用量は";
$memoryusage1 .= memory_get_usage()/(1024*1024);//デバッグ用、メモリ使用量計測
$memoryusage1 .= "MB\r\n";
file_put_contents($memoryusage_file, $memoryusage1, FILE_APPEND | LOCK_EX);//ログにメモリ使用量を追記

$data = "";
$link2step2 = "";

$filepath = "";
$filename = "";

$uploadinstruction_style = "";//説明のセクションを非表示させるためのクラス変数
$ckbtn_class = "class = 'hide'";//整合性チェックボタンを非表示させるためのクラス変数
$stp2btn_class = "class = 'hide'";//Step2へ進むボタンを非表示させるためのクラス変数

// $b = $upload_db->testExcel($filepath);//test
if(isset($_POST['uploadfile'])){
	$uploadinstruction_style = "class = 'hide'";
}
//読み込んで表示ボタンが押された時の動作
if(isset($_POST['readexcel'])){

	//$memoryusage_file = './memoryusagelog.txt';//メモリ使用量ログファイル
	$memoryusage2 = "Step1:読み込んで表示ボタンが押された直後のメモリ使用量は";
	$memoryusage2 .= memory_get_usage()/(1024*1024);//デバッグ用、メモリ使用量計測
	$memoryusage2 .= "MB\r\n";
	file_put_contents($memoryusage_file, $memoryusage2, FILE_APPEND | LOCK_EX);//ログにメモリ使用量を追記

	$uploadinstruction_style = "class = 'hide'";

	$filename = $_POST['filename'];
	$filepath = $_POST['filepath'];

	//データベースへ登録し、生成されたIDを戻り値として受け取る
	$data = $upload_db->excelreadcheck($filepath);

	//step2へのボタンを表示
	$stp2btn_class = "";

	//step2へのリンクを表示
	$link2step2 = <<<eof
				<div id ="compareinstruction">
				<p>以下に表示されるサンプル項目と実際のエクセルの項目を比較し、過不足がないか確認をお願い致します。</p>
				<p>各コラムのキーワードが合致しない場合、エクセル側のコラムが黄色で表示されます。黄色の部分は特にご注意いただく必要がありますが、<br>
				元データによってはコラム名が変更されている可能性もありますので必ずしも間違えているとは限りません。</p>
				<p>元データには大量のコラムがありますが、似たような名称のコラムも存在しますのでご注意ください。</p>
				<p>問題なければ、下のボタンから「２重登録の確認」へ進んでください。</p></div>
eof;

	//$memoryusage_file = './memoryusagelog.txt';//メモリ使用量ログファイル
	$memoryusage3 = "Step1:読み込んで表示ボタンが押された処理終了後のメモリ使用量は";
	$memoryusage3 .= memory_get_usage()/(1024*1024);//デバッグ用、メモリ使用量計測
	$memoryusage3 .= "MB\r\n\r\n";
	file_put_contents($memoryusage_file, $memoryusage3, FILE_APPEND | LOCK_EX);//ログにメモリ使用量を追記

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
 $('.loading').prepend('<img src="../image/load5.gif" width="10%"><br>数分かかる場合があります');
},100);
	//btn.form.submit();
	//$('#main').submit();
}

// function hide_inst(){
// 	document.getElementById("section_instruction").style.display = "none";
// }

</script>

</head>
<body>
<?php //echo $b;?>
<h1>RAWデータアップロード</h1>

<h2>Step 1: RAWデータの準備</h2>

<div id = "section_instruction" <?php echo $uploadinstruction_style;?>>
	<p>「ppsnd」や「SNIC」のRAWデータをWeekly単位で用意します。</p>

	<p>配布されているRAWデータはバイナリ形式（拡張子が.xlsb）であることが多いので、エクセル形式（.xlsx）に変更してください。</p>

	<p>不要なコラムは全て削除ます。必要なコラムは以下の通りです。</p>
	<table border='1'>
	<tr>
	<td>A</td><td>B</td><td>C</td><td>D</td><td>E</td><td>F</td><td>G</td><td>H</td>
	<td>I</td><td>J</td><td>K</td><td>L</td><td>M</td>
	</tr>
	<tr>
	<td>Serial Number</td>
	<td>Valid Sn</td>
	<td>Unique Sn</td>
	<td>Case Id</td>
	<td>Subcase Id</td>
	<td>Event Id</td>
	<td>Unique Subcase</td>
	<td>Subcase Series</td>
	<td>Xotc All</td>
	<td>Country</td>
	<td>Case Title</td>
	<td>Open Date</td>
	<td>Close Date</td>
	</tr></table>
	<table border='1'>
	<tr>
	<td>N</td><td>O</td><td>P</td>
	<td>Q</td><td>R</td><td>S</td><td>T</td><td>U</td><td>V</td><td>W</td><td>X</td>
	</tr>
	<tr>
	<td>Delivery Alternative</td>
	<td>Product</td>
	<td>Product Description</td>
	<td>Owner Work Group</td>
	<td>Part Number</td>
	<td>Part Desc</td>
	<td>X Part Usage</td>
	<td>Product Line</td>
	<td>Dataperiod</td>
	<td>Pure Delivery</td>
	<td>GCSS Customer Name</td>
	</tr></table>

	<p>シートは一つのみ残してください。</p>

	<p>フィルターやテーブルは解除しておいてください。</p>

</div>
<div <?php echo $uploadinstruction_style;?>>
	<p>準備ができたら、以下からエクセルファイルをサーバーにアップロードします。</p>
	
	<form enctype="multipart/form-data" action = "" method = "post" >
		<input type="file" name="file_data1">
		ファイルをサーバーにアップロードします：
		<input type="submit" name="uploadfile" value="FILE送信" ><!-- onClick="hide_inst()" -->
	</form>
</div>
<div id = "fileuploadresult">
<?php
//アップロードボタンが押された時の動作
if(isset($_POST['uploadfile'])){

	// アップロードファイル情報を表示する。
	echo "アップロードファイル名　：　" , $_FILES["file_data1"]["name"] , "<BR>";
	echo "MIMEタイプ　：　" , $_FILES["file_data1"]["type"] , "<BR>";
	echo "ファイルサイズ　：　" , $_FILES["file_data1"]["size"] , "<BR>";
	echo "テンポラリファイル名　：　" , $_FILES["file_data1"]["tmp_name"] , "<BR>";
	echo "エラーコード　：　" , $_FILES["file_data1"]["error"] , "<BR>";

	// アップロードファイルを格納するファイルパスを指定
// 	$filename = "c:\\tmp\\" . $_FILES["file_data1"]["name"];
	$filepath = "/Users/Atsushi/Documents/" . $_FILES["file_data1"]["name"];//家の環境
// 	$filepath = "C:\\Apache2.2\\htdocs\\DBD_Analyzer\\excel\\" . $_FILES["file_data1"]["name"];//会社の環境
	$filename = $_FILES["file_data1"]["name"];

	if ( $_FILES["file_data1"]["size"] === 0 ) {
		echo "ファイルはアップロードされてません！！ アップロードファイルを指定してください。";
	}else{
		// アップロードファイルされたテンポラリファイルをファイル格納パスにコピーする
		$result = @move_uploaded_file( $_FILES["file_data1"]["tmp_name"], $filepath);
		if( $result === true ){
			echo "アップロード成功！！";
			$ckbtn_class = "";//アップロードが成功したら整合性チェックのご案内とボタンを表示させる
		}else{
			echo "アップロード失敗！！";
		}
	}
}
?>
</div>

<div <?php echo $ckbtn_class;?>>
	<form id="main" method="post" action="">
			アップロードしたエクセルからデータを読み込んで表示し、整合性をチェックします。：
			<input type="hidden" value="<?php echo $filepath;?>" name="filepath">
			<input type="hidden" value="<?php echo $filename;?>" name="filename">
			<input type="submit" value="読み込んで表示" name="readexcel" onClick="disp()">
	</form>
</div>

<div class="loading"></div>


<!-- <div> -->
<!-- 	<form name="main" method="post" action="upload_1_1.php"> -->
<!-- 			別ページにて、アップロードしたエクセルからデータを読み込んで表示し、整合性をチェックします。： -->
			<input type="hidden" value="<?php echo $filepath;?>" name="filepath">
			<input type="hidden" value="<?php echo $filename;?>" name="filename">
<!-- 			<input type="submit" value="読み込んで表示" name="readexcel"> -->
<!-- 	</form> -->
<!-- </div> -->


<?php echo $link2step2;?>

<div <?php echo $stp2btn_class;?>>
	<form name="main2" method="post" action="upload_2.php">
			２重登録の確認へ：
			<input type="hidden" value="<?php echo $filepath;?>" name="filepath">
			<input type="hidden" value="<?php echo $filename;?>" name="filename">
			<input type="submit" value="Step 2" name="step2">
	</form>
</div>

<?php echo $data;?>

</body>
</html>

