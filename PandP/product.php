<?php
require_once('DB_product.php');
$db_product = new DB_product();
$errorCss = "id='unexposed'";
$entryCss = "class='hide'";
$updateCss = "class='hide'";

$result ="";

//プロダクトテーブルの項目
//id, Product_Number, Product_Name, Product_Description, Product_Line, Name_Unique, Name_Blank_Flag, SP_Flag

//更新処理
if(isset($_POST['submitUpdate'])){
  $db_product->Updateproduct();
}
//更新用フォーム要素の表示
if(isset($_POST['update'])){
	
  //更新対象の値を取得
  $id   = $_POST['id'];
  $Product_Number= $db_product->Product_NumberForUpdate($_POST['id']);
  $Product_Name= $db_product->Product_NameForUpdate($_POST['id']);
  $Product_Description= $db_product->Product_DescriptionForUpdate($_POST['id']);
  $Product_Line= $db_product->Product_LineForUpdate($_POST['id']);
  $Name_Unique= $db_product->Name_UniqueForUpdate($_POST['id']);
  $Name_Blank_Flag= $db_product->Name_Blank_FlagForUpdate($_POST['id']);
  $SP_Flag= $db_product->SP_FlagForUpdate($_POST['id']);
  //クラスを記述することで表示/非表示を設定
  $entryCss = "class='hide'";
  $updateCss = "";
}

//テーブル完全削除ボタンを押した時
if(isset($_POST['delete_table'])){
	$res = $db_product->DeleteAll();
}


//削除処理
if(isset($_POST['delete'])){
  $db_product->Deleteproduct($_POST['id']);
}
//新規登録処理
if(isset($_POST['submitEntry'])){
	$db_product->Insertproduct();
}
//テーブルデータの一覧表示
$data = $db_product->SelectproductAll();


//検索結果の表示
if(isset($_POST['run_search'])){
	//更新対象の値を取得
	$searchword = $_POST['searchword'];
	if (strlen($searchword)>0){
		list($result, $countresult) = $db_product->productSearch($searchword);
		$_SESSION['Casesearchbox'] = $searchword;
		$searchresultCss = "";
	}else{
		$result = "<p>検索したいキーワードを入力してください</p>";
		$searchresultCss = "";
	}

}

//検索のリセット
if(isset($_POST['reset_search'])){
	//更新対象の値を取得
	$searchword = "";
	$result = "";
	header("Location: " . $_SERVER['SCRIPT_NAME']);
}

//ダウンロードボタンを押した時
if(isset($_POST['download_table'])){
	$res = $db_product->Download2Excel();
}

?>

<?php include('html_head.php'); ?>

<h1 id = "title_productmaster">DBD Analyzer　Productマスター</h1>


<h2 id = "newreg" class = "btn">新規登録</h2><!-- onclick = "showHide('section_newproduct')" -->
<div id="section_newproduct" <?php echo $entryCss;?>>
<form action="" method="post">
<input type="hidden" name='id' value="null">
<label><span class="entrylabel">Product_Number</span><input type='text' name='Product_Number' size="8" required></label>
<label><span class="entrylabel">Product_Name</span><input type='text' name='Product_Name' size="20"></label>
<label><span class="entrylabel">Product_Description</span><input type='text' name='Product_Description' size="40"></label>
<label><span class="entrylabel">Product_Line</span><input type='text' name='Product_Line' size="5" required></label><br>
<label><span class="entrylabel">Name_Unique</span><input type='text' name='Name_Unique' size="5"></label>
<label><span class="entrylabel">Name_Blank_Flag</span><input type='text' name='Name_Blank_Flag' size="5"></label>
<label><span class="entrylabel">SP_Flag</span><input type='text' name='SP_Flag' size="5"></label>
<input type='submit' name='submitEntry' value='Create'>
</form>
</div>
<div id="section_productupdate" <?php echo $updateCss;?>>
<h2>更新</h2>
<form action="" method="post">
<p>ID: <?php echo $id;?></p>
<input type="hidden" name='id' value="<?php echo $id;?>" />
<label><span class="entrylabel">Product_Number</span><input type='text' name='Product_Number'
 size="8" value="<?php echo $Product_Number;?>" required></label>
 <label><span class="entrylabel">Product_Name</span><input type='text' name='Product_Name'
 size="20" value="<?php echo $Product_Name;?>" required></label>
<label><span class="entrylabel">Product_Description</span><input type='text' name='Product_Description'
 size="40" value="<?php echo $Product_Description;?>" required></label>
<label><span class="entrylabel">Product_Line</span><input type='text' name='Product_Line'
 size="5" value="<?php echo $Product_Line;?>" required></label><br>
<label><span class="entrylabel">Name_Unique</span><input type='text' name='Name_Unique'
 size="5" value="<?php echo $Name_Unique;?>" required></label>
 <label><span class="entrylabel">Name_Blank_Flag</span><input type='text' name='Name_Blank_Flag'
 size="5" value="<?php echo $Name_Blank_Flag;?>" required></label>
 <label><span class="entrylabel">SP_Flag</span><input type='text' name='SP_Flag'
 size="5" value="<?php echo $SP_Flag;?>" required></label>
<input type='submit' name='submitUpdate' value='Update'>
</form>
</div>
<div id="section_productsrchlist">
	<form name="form_productsearch" method="post" action="">
		<label>検索：</label>
		<input type='text' name='searchword' size="40">
		<input type="submit" value="Search" name="run_search">
		<input type="submit" value="Reset" name="reset_search">
	</form>

	<form name="form_productsearchresult" method="post" action="">
		<table id= "table_productsearchresult">
			<?php echo $result;?>
		</table>
	</form>
</div>

<h2 id = "prolist" class = "btn">機種名未登録リスト</h2>
<div id="section_productlist">
<?php echo $data;?>
</div>
<div id="section_tabledownload">
<h2>Productテーブルのダウンロード</h2>
	<form name="form_tabledownload" method="post" action="">
		<label>Productテーブルの内容をエクセルファイルでダウンロードします：</label>
		<input type="submit" value="Download" name="download_table">
	</form>
</div>
<div id="section_tabledelete">
<h2>Productテーブル完全削除</h2>
	<form name="form_tabledelete" method="post" action="" onSubmit='return CheckDelete()'>
		<label>Productテーブルの内容を全て削除します。この操作は元に戻せません。：</label>
		<input type="submit" value="Delete" name="delete_table">
	</form>
</div>
</body>
</html>
