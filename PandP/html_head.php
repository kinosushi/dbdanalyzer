<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge"><!--これを追加して、IEだけで一部のスタイルシートが反映されなかった問題が解決 -->
<link rel="shortcut icon" href="./pic/favicon.png"/>

<title>Productマスター</title>

<!--  スタイルシート -->
<link rel="stylesheet" type="text/css" href="./style_pandp.css" />

<!--<script type="text/javascript" language="javascript" src="./case_javascript.js"></script>-->
<script src="../javascript/jquery-1.7.min.js"></script>

<script type="text/javascript">
	function CheckDelete(){
		if(window.confirm('削除すると元に戻せません。本当に削除してもよろしいですか？')){
			return true;
		}else{
			window.alert('キャンセルされました');
			return false;
		}
	}
	
	function disp(btn){
		setTimeout(function(){
			//document.getElementById("section_instruction").style.display = "none";
	 $('.loading').prepend('<img src="../image/load6.gif" width="20%"><br>数分かかる場合があります');
	},100);
		//btn.main3.submit();
		//$('#main3').submit();
	}

	//新折りたたみ -->
	//http://d-cyst.m78.com/~mizusawa/penguin/html_hint/javascript/java_s_showhide.html

	function showHide(targetID) { //functionの宣言。受けとったIDは変数targetIDに格納。
	        if( document.getElementById(targetID)) { //指定のIDのついたオブジェクトがあったら処理する
	                //指定されたIDのstyle.displayがnoneなら
	                if( document.getElementById(targetID).style.display == "none") {
	                    //blockに変更する
	                    document.getElementById(targetID).style.display = "block";
	                } else { //noneでなければ、つまりblockなら
	                    //noneにする
	                    document.getElementById(targetID).style.display = "none";
	                }
	        }
	}

// 	$(function() {
// 	    $('#newreg').click(function(){
// 	        $('#section_newproduct').slideToggle("fast");
// 	    });
// 	});

	$(function(){
		$("#newreg").on("click", function() {
			$(this).toggleClass("active");//開いた時、ボタンにクラスを追加
			$("#section_newproduct").slideToggle("normal");//”slow”、”normal”、”fast”
		});
		$("#prolist").on("click", function() {
			$(this).toggleClass("active");//開いた時、ボタンにクラスを追加
			$("#section_productlist").fadeToggle("slow");//”slow”、”normal”、”fast
		});
// 		$("#modify_btn").on("click", function() {
// 			//$(this).toggleClass("active");//開いた時、ボタンにクラスを追加
// 			$("#section_productupdate").fadeToggle("slow");//”slow”、”normal”、”fast
// 		});
	});
	
</script>
</head>
<body>

<div id="menu">
<ul>
<li><a href="product_up1.php">Productアップロード</a></li>
<li><a href="product.php">Productアップデート</a></li>
<li><a href="../pandp/product_name.php" target="_blank">登録済み機種名確認</a></li>
<li><a href="../upload/upload_1.php" target="_blank">RAWデータアップロード</a></li>
</ul>
</div>
