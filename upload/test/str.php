<?php

//$txt = "ANDBD_QD1k2";
//$txt = "《ANDBD_QD1k2》";
// $txt = "_QD1k42》";
//echo substr($txt, 0, 1);
// echo mb_substr($txt, 3, 2);

$titlecode = "_QD1k42》";
//$code = mb_substr($titlecode, 3, 2);
// echo mb_substr($titlecode, 3, 2);

if(mb_substr($titlecode, 3, 2) != false && strpos (mb_substr($titlecode, 3, 2), "》") === false){
		
	$code = mb_substr($titlecode, 3, 2);//コードを抜き出す　サンプル《7FDBD_QD1u02》「1u」の部分
	$sql = "SELECT issue FROM 7f_titlecode WHERE code = ?";
	$array = array($code);
	$res = parent::executeSQL($sql,$array);
	$ans = $res->fetchColumn();

	//return $ans;

	
}else{

	return null;

}

?>
