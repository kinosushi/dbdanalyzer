<?php
require_once('DB_ccctitlecode.php');
$db_ccctitlecode= new DB_ccctitlecode();
$errorCss = "id='unexposed'";
$entryCss = "";
$updateCss = "class='hideArea'";

$result ="";



//更新処理
if(isset($_POST['submitUpdate'])){
	$db_ccctitlecode->Updateccccode();
}
//更新用フォーム要素の表示
if(isset($_POST['update'])){
	//更新対象の値を取得
	$id   = $_POST['id'];
	$code   = $db_ccctitlecode->getCodeForUpdate($_POST['id']);
	$issue   = $db_ccctitlecode->getIssueForUpdate($_POST['id']);
	$timing   = $db_ccctitlecode->getTimingForUpdate($_POST['id']);
	$flag     = $db_ccctitlecode->getFlagForUpdate($_POST['id']);
	$os    = $db_ccctitlecode->getOsForUpdate($_POST['id']);
	$category    = $db_ccctitlecode->getCategoryForUpdate($_POST['id']);
	$customer    = $db_ccctitlecode->getCustomerForUpdate($_POST['id']);

	//クラスを記述することで表示/非表示を設定
	$entryCss = "class='hideArea'";
	$updateCss = "";
}


//削除処理
if(isset($_POST['delete'])){
	$db_ccctitlecode->Deleteccccode($_POST['id']);
}
//新規登録処理
if(isset($_POST['submitEntry'])){
	$db_ccctitlecode->Insertccccode();
}
//テーブルデータの一覧表示
$data = $db_ccctitlecode->SelectccccodeAll();


?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge"><!--これを追加して、IEだけで一部のスタイルシートが反映されなかった問題が解決 -->
<link rel="shortcut icon" href="./pic/favicon.png"/>
<title>CCC Title Code マスター</title>
<link rel="stylesheet" type="text/css" href="style_upload.css" />
<script type="text/javascript">
function CheckDelete(){
    return confirm("削除してもよろしいですか？");
}
</script>
</head>
<body>
<div id="menu">
<ul>
<li><a href="7f_titlecode.php">7F Title Code マスター</a></li>
<li><a href="an_titlecode.php">AN Title Code マスター</a></li>
<li><a href="upload_1.php" target="_blank">RAWデータアップロード</a></li>
</ul>
</div>
<h1>CCC Title Code マスター</h1>


<div <?php echo $entryCss;?>>
<form action="" method="post">
<h2>新規登録</h2>
<input type='hidden' name='id' value="">
<label><span class="entrylabel">code</span><input type='text' name='code' size="10"></label>
<label><span class="entrylabel">issue</span><input type='text' name='issue' size="20"></label>
<label><span class="entrylabel">timing</span><input type='text' name='timing' size="20"></label>
<label><span class="entrylabel">flag</span><input type='text' name='flag' size="20"></label>
<label><span class="entrylabel">os</span><input type='text' name='os' size="20"></label>
<label><span class="entrylabel">category</span><input type='text' name='category' size="20"></label>
<label><span class="entrylabel">customer</span><input type='text' name='customer' size="20"></label>
<input type='submit' value='create' name='submitEntry'>
</form>
</div>

<div <?php echo $updateCss;?>>
<form action="" method="post">
<h2>更新</h2>
<p>ID: <?php echo $id;?></p>
<input type="hidden" name='id' value="<?php echo $id;?>" />
<label><span class="entrylabel">code</span><input type='text' name='code'
 size="10" value="<?php echo $code;?>"></label>
<label><span class="entrylabel">issue</span><input type='text' name='issue'
 size="20" value="<?php echo $issue;?>"></label>
<label><span class="entrylabel">timing</span><input type='text' name='timing'
 size="20" value="<?php echo $timing;?>"></label>
 <label><span class="entrylabel">flag</span><input type='text' name='flag'
 size="20" value="<?php echo $flag;?>"></label>
 <label><span class="entrylabel">os</span><input type='text' name='os'
 size="20" value="<?php echo $os;?>"></label>
 <label><span class="entrylabel">category</span><input type='text' name='category'
 size="20" value="<?php echo $category;?>"></label>
 <label><span class="entrylabel">customer</span><input type='text' name='customer'
 size="20" value="<?php echo $customer;?>"></label>
<input type='submit' name='submitUpdate' value='Update'>
</form>
</div>

<div <?php echo $entryCss;?>>
<h2>既存 AN Title Code</h2>
<?php echo $data;?>
</div>

</body>
</html>