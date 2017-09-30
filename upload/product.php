<?php
require('product_DB.php');
$product_db = new product_DB();


//読み込んで表示ボタンが押された時の動作
if(isset($_POST['readexcel'])){

	//データベースへ登録し、生成されたIDを戻り値として受け取る
	$data = $product_db->excelreadcheck();

}

//データベース登録ボタンが押された時の動作
if(isset($_POST['excel2db'])){

	//データベースへ登録し、生成されたIDを戻り値として受け取る
	$result = $product_db->excel2db();

}

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge"><!--これを追加して、IEだけで一部のスタイルシートが反映されなかった問題が解決 -->
<link rel="shortcut icon" href="./pic/favicon.png"/>
<title>Product Test</title>

<!--  スタイルシート -->
<!--<link rel="stylesheet" type="text/css" href="style.css" /> -->

<!--<script type="text/javascript" language="javascript" src="./case_javascript.js"></script>-->



</head>
<body>

$valの内容：<?php var_dump($val);?><br>
<?php echo $result[0];?>件のデータがデータベースに登録されました。<br>
<?php echo $result[1];?>件の登録に失敗しました。

<form name="main" method="post" action="">
		エクセルからデータを読み込んで表示します：
		<input type="submit" value="読み込んで表示" name="readexcel">
</form>



<form name="main2" method="post" action="">
		エクセルからデータを読み込んでデータベースに登録します：
		<input type="submit" value="データベース登録" name="excel2db">
</form>

<form name="main3" method="post" action="./proceeding.php" target = "_blank">
		エクセルからデータを読み込んでデータベースに登録します。進捗表示あり。：
		<input type="submit" value="データベース登録" name="excel2db">
</form>


<?php echo $data;?>

</body>
</html>

