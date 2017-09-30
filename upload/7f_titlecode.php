<?php
require_once('DB_dbdtitlecode.php');
$db_dbdtitlecode= new DB_dbdtitlecode();
$errorCss = "id='unexposed'";
$entryCss = "";
$updateCss = "class='hideArea'";

$result ="";

$team = "7f";


//更新処理
if(isset($_POST['submitUpdate'])){
	$db_dbdtitlecode->Updatedbdcode($_POST['team']);
}
//更新用フォーム要素の表示
if(isset($_POST['update'])){
	//更新対象の値を取得
	$id   = $_POST['id'];
	$code   = $db_dbdtitlecode->getCodeForUpdate($_POST['id'],$_POST['team']);
	$issue   = $db_dbdtitlecode->getIssueForUpdate($_POST['id'],$_POST['team']);
	$timing   = $db_dbdtitlecode->getTimingForUpdate($_POST['id'],$_POST['team']);
	$howoften  = $db_dbdtitlecode->getHowoftenForUpdate($_POST['id'],$_POST['team']);
	$flag     = $db_dbdtitlecode->getFlagForUpdate($_POST['id'],$_POST['team']);
	//クラスを記述することで表示/非表示を設定
	$entryCss = "class='hideArea'";
	$updateCss = "";
}


//削除処理
if(isset($_POST['delete'])){
	$db_dbdtitlecode->Deletedbdcode($_POST['id'],$_POST['team']);
}
//新規登録処理
if(isset($_POST['submitEntry'])){
	$db_dbdtitlecode->Insertdbdcode($_POST['team']);
}
//テーブルデータの一覧表示
$data = $db_dbdtitlecode->SelectdbdcodeAll($team);


?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge"><!--これを追加して、IEだけで一部のスタイルシートが反映されなかった問題が解決 -->
<link rel="shortcut icon" href="./pic/favicon.png"/>
<title>7F Title Code マスター</title>
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
<li><a href="an_titlecode.php">AN Title Code マスター</a></li>
<li><a href="ccc_titlecode.php">CCC Title Code マスター</a></li>
<li><a href="upload_1.php" target="_blank">RAWデータアップロード</a></li>
</ul>
</div>
<h1>7F Title Code マスター</h1>


<div <?php echo $entryCss;?>>
<form action="" method="post">
<h2>新規登録</h2>
<input type='hidden' name='id' value="">
<input type='hidden' name='team' value="7f">
<label><span class="entrylabel">code</span><input type='text' name='code' size="10"></label>
<label><span class="entrylabel">issue</span><input type='text' name='issue' size="20"></label>
<label><span class="entrylabel">timing</span><input type='text' name='timing' size="20"></label>
<label><span class="entrylabel">howoften</span><input type='text' name='howoften' size="20"></label>
<label><span class="entrylabel">flag</span><input type='text' name='flag' size="20"></label>
<input type='submit' value='create' name='submitEntry'>
</form>
</div>

<div <?php echo $updateCss;?>>
<form action="" method="post">
<h2>更新</h2>
<p>ID: <?php echo $id;?></p>
<input type='hidden' name='team' value="7f">
<input type="hidden" name='id' value="<?php echo $id;?>" />
<label><span class="entrylabel">code</span><input type='text' name='code'
 size="10" value="<?php echo $code;?>"></label>
<label><span class="entrylabel">issue</span><input type='text' name='issue'
 size="20" value="<?php echo $issue;?>"></label>
<label><span class="entrylabel">timing</span><input type='text' name='timing'
 size="20" value="<?php echo $timing;?>"></label>
<label><span class="entrylabel">howoften</span><input type='text' name='howoften'
 size="20" value="<?php echo $howoften;?>"></label>
 <label><span class="entrylabel">flag</span><input type='text' name='flag'
 size="20" value="<?php echo $flag;?>"></label>
<input type='submit' name='submitUpdate' value='Update'>
</form>
</div>

<div <?php echo $entryCss;?>>
<h2>既存 7F Title Code</h2>
<?php echo $data;?>
</div>

</body>
</html>