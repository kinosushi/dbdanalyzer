<?php
require_once('DB_product_name.php');
$db_product_name = new DB_product_name();

//検索ボックスの中身の有無

session_start();

if (isset($_SESSION['namesearchbox']) && isset($_SESSION['namesearchbox'])) {
	$searchword2 = $_SESSION['namesearchbox'];
	$productline = $_SESSION['select_pl'];
} else {
	$searchword2 = "";
	$productline= "";
}



//検索結果の表示
if(isset($_POST['run_namesearch'])){
	//更新対象の値を取得
	$searchword2 = $_POST['searchword2'];
	$productline = $_POST['select_pl'];
	if (strlen($searchword2)>0){
		list($result, $countresult) = $db_product_name->nameSearch($searchword2,$productline);
		$_SESSION['namesearchbox'] = $searchword2;
		$_SESSION['select_pl'] = $productline;
		//$searchresultCss = "";
	}else{
		$result = "<p>検索したいキーワードを入力してください</p>";
		//$searchresultCss = "";
	}

}

//検索のリセット
if(isset($_POST['reset_search'])){
	//更新対象の値を取得
	$_SESSION['namesearchbox']="";
	$_SESSION['select_pl'] = "";
	$searchword2 = "";
	$productline = "";
	$result = "";
	header("Location: " . $_SERVER['SCRIPT_NAME']);
}

//プロダクトラインセレクトタグの生成
$tag = $db_product_name->productlineSelected($productline);

?>

<?php include('html_head.php'); ?>

<h1 id = "title_productmaster">DBD Analyzer　Productマスター</h1>

<div id="section_namesrchlist" <?php echo $entryCss;?>>
<h2>検索</h2>
	<form name="form_namesearch" method="post" action="">

		<?php echo $tag;?>

		<label>機種名フリー検索：</label>
		<input type='text' name='searchword2' size="40" value = "<?php echo $searchword2;?>">
		<input type="submit" value="Search" name="run_namesearch">
		<input type="submit" value="Reset" name="reset_search">
	</form>

	<form name="form_namesearchresult" method="post" action="">
		<table id= "table_namesearchresult">
			<?php echo $result;?>
		</table>
	</form>
</div>



</body>
</html>
