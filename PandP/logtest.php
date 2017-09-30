<?php
//
//
//SQLのエラーログを残すテストをするためのPHPです。
//
//
require('DB_product_up.php');
$db_product_up = new DB_product_up();

$array=array("","","","","","","","");//


//データベースへの登録
$resarray = $db_product_up->upload2db($array);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge"><!--これを追加して、IEだけで一部のスタイルシートが反映されなかった問題が解決 -->
<link rel="shortcut icon" href="./pic/favicon.png"/>
<title>DBD Analyzer Product Master</title>

<!--  スタイルシート -->
<link rel="stylesheet" type="text/css" href="style_upload.css" />

<!--<script type="text/javascript" language="javascript" src="./case_javascript.js"></script>-->
<script src="./jquery-1.7.min.js"></script>
<script>
</script>

</head>
<body>
失敗？成功？：<?php echo $res[0];?><br>
<?php //var_dump($res[1]);?><br>
<?php //var_dump($res[2]);?><br>


</body>
</html>



