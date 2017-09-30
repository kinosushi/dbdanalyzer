<?php
require('DB_product_up.php');
$db_product_up = new DB_product_up();


// $memoryusage_file = './memoryusagelog.txt';//メモリ使用量ログファイル
// $memoryusage1 = "Step1:ページ先頭時点でのメモリ使用量は";
// $memoryusage1 .= memory_get_usage()/(1024*1024);//デバッグ用、メモリ使用量計測
// $memoryusage1 .= "MB\r\n";
// file_put_contents($memoryusage_file, $memoryusage1, FILE_APPEND | LOCK_EX);//ログにメモリ使用量を追記

$before_upload_style = "";
$tostep2_class = "class = 'hide'";

$filepath = "";
$filename = "";


if(isset($_POST['uploadfile'])){
	$uploadinstruction_style = "class = 'hide'";
}



?>

<?php include('html_head.php'); ?>

<h1>Product Master アップロード</h1>

<h2>エクセルの選択</h2>



<div id = "fileuploadresult">
<?php
//アップロードボタンが押された時の動作
if(isset($_POST['uploadfile'])){
	
	$before_upload_style = "class = 'hide'";
	$tostep2_class = "";

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
		}else{
			echo "アップロード失敗！！";
		}
	}
}
?>
</div>

<div <?php echo $before_upload_style;?>>
	<p>準備ができたら、以下からエクセルファイルをサーバーにアップロードします。</p>
	
	<form enctype="multipart/form-data" action = "" method = "post" >
		<input type="file" name="file_data1">
		ファイルをサーバーにアップロードします：
		<input type="submit" name="uploadfile" value="FILE送信" >
	</form>
</div>

<div class="loading"></div>

<div <?php echo $tostep2_class;?>>
<form name="main2" method="post" action="product_up2.php">
			データベース登録へ：
			<input type="hidden" value="<?php echo $filepath;?>" name="filepath">
			<input type="hidden" value="<?php echo $filename;?>" name="filename">
			<input type="submit" value="Step 2" name="step2">
</form>
</div>


</body>
</html>


