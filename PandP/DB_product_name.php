<?php
require_once('../db.php');

// PHPExcelの読み込み
require_once("../Classes/PHPExcel.php");

class DB_product_name extends DB{
	//rceテーブルのCRUD担当

	public function productlineSelected($productline){
		//cd_teamtoのSelectタグの作成（該当ケースの既存値を選択済みの状態にしておく）
		$tag = "<select id ='select_pl' name='select_pl'>\n";
		$tag.= "<option value=''>プロダクトライン</option>\n";
		
		$row1 = array("1M","2G","2H","6J","9G",
				"KV","21","52","9J","MF",
				"FB","MN","FG","5U","7F",
				"9F","BO","GA","DG","6U",
				"AN","G7","MP","8J","8N",
				"5X","9H","TB","TA","2C",
				"UV","MG","9R","16","CY",
				"67","8W","9S","BQ","6X",
				"FD","FF","9T","EZ","4T",
				"7V","GB","US","UT");
		$row2 = array("Consumer Volume Desktop","Volume Displays","Volume Desktop Accessories","Volume Desktops","Volume Notebook Accessories",
				"Volume Notebooks","Handheld Info Products","Calculators","Handheld Branded Options","HP Connected Attach",
				"HP Connected Services","PC Consumer Support Services","Consumer Mobility Services","Commercial Transactional Desktop","Commercial Desktop PCs",
				"Commercial Desktop Accessories","Commercial Displays","Commercial Desktop L10 Value","Commercial Desktop L10","Commercial Transactional Notebook",
				"Commercial Notebooks","Commercial Chromebook","Commercial Notebook Accessories","Detachables","Detachables Accessories",
				"Workstation Systems","Workstation Accessories","Workstation Displays","Mobile Workstations","Thin Clients",
				"Mobile Thin Clients","PC Commercial Support Services","PC Configuration Services","PC Deployment Services","Commercial Mobility Services",
				"PC Commercial Contractual Services","Third Party Options","PC Replacement Parts","Enterprise Mobility Suite","Comm Android Tablet Accy",
				"Commercial Android Tablets","Commercial Mobility New Business","Comm Windows Tablet Accy","Commercial Windows Tablets","Consumer Tablets",
				"Consumer Tablet Accessories","Retail Mobility","Retail Solutions","Digital Signage");
		
		for($i=0;$i<count($row1);$i++){
			$tag.= "<option value='{$row1[$i]}'";
			$tag.= ($row1[$i] == $productline)?' selected':'';
			$tag.=">{$row1[$i]}-{$row2[$i]}</option>\n";
		}
		$tag.= "</select>\n";
		return $tag;
	}
	
	public function nameSearch($searchword2,$productline){
		//検索結果をテーブルに並べる
		list($res, $countresult) = $this->getsearchresult($searchword2,$productline);
		$result = "";
		$result .= <<<eof
					<tr>
					<th nowrap width="40">ID</th>
					<th nowrap width="100">Product_Number</th>
					<th nowrap width="200">Product_Name</th>
					<th nowrap width="400">Product_Description</th>
					<th nowrap width="40">Product_Line</th>
					</tr>\n
eof;

		//プロダクトテーブルの項目
		//id, Product_Number, Product_Name, Product_Description,
		//Product_Line, Name_Unique, Name_Blank_Flag, SP_Flag


		foreach($rows = $res->fetchAll(PDO::FETCH_ASSOC)as $row){

			$result .= <<<eof
					<tr>
					<td>{$row['id']}</td>
					<td>{$row['Product_Number']}</td>
					<td>{$row['Product_Name']}</td>
					<td>{$row['Product_Description']}</td>
					<td>{$row['Product_Line']}</td>
					\n
					</tr>
eof;

		}

		if($countresult == 0){
			$result = "該当する結果がありません";
		}
		return array($result, $countresult);
	}

	private function getsearchresult($searchword2,$productline){
		//検索結果セットを取得

		// 全角スペースを半角スペースに置換
		$keyword_txt = str_replace( "　" , " " , $searchword2 );
		// スペース区切りで文字列を配列に分割
		$keywordArr = explode( " " , $keyword_txt );

		$sql = <<<eof
		    SELECT *
		    FROM product

		    WHERE
eof;

		//プロダクトテーブルの項目
		//id, Product_Number, Product_Name, Product_Description,
		//Product_Line, Name_Unique, Name_Blank_Flag, SP_Flag

		$sql .= "(";
		for( $i = 0; $i < count($keywordArr);$i++ ){
			$sql .= "concat(Product_Number,' ',Product_Name,' ',Product_Description,' ',Product_Line) LIKE \"%{$keywordArr[$i]}%\" AND ";
		}
		$sql = rtrim( $sql , " AND " );
		$sql .= ")";

		if($productline != ""){
			$sql .= " AND Product_Line = \"{$productline}\" ";
		}

		$sql .= "ORDER BY id DESC";


		$res = parent::executeSQL($sql,null);
		$countresult = $res->rowCount();
		return array($res, $countresult);
	}


}
?>
