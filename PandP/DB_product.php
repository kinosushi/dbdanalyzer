<?php
require_once('../db.php');

// PHPExcelの読み込み
require_once("../Classes/PHPExcel.php");

class DB_product extends DB{
	//rceテーブルのCRUD担当

	//プロダクトテーブルの項目
	//id, Product_Number, Product_Name, Product_Description, Product_Line, Name_Unique, Name_Blank_Flag, SP_Flag


	public function SelectproductAll(){
		$sql = "SELECT * FROM product WHERE Product_Name=?";
		$array = array("");
		$res = parent::executeSQL($sql, $array);
		$data = "<table id='productTable'>";
		$data .= "<tr><th>ID</th><th>Product_Number</th><th>Product_Name</th><th>Product_Description</th><th>Product_Line</th><th>Name_Unique</th><th>Name_Blank_Flag</th><th>SP_Flag</th><th></th><th></th></tr>\n";
		foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){
			$data .= "<tr>";
			for($i=0;$i<count($row);$i++){
				$data .= "<td>{$row[$i]}</td>";
			}
			//更新ボタンのコード
			$data .= <<<eof
		      <td><form method='post' action=''>
		      <input type='hidden' name='id'value='{$row[0]}'>
		      <input type='submit' name='update'  id = 'modify_btn' value='Modify'>
		      </form></td>
eof;
			//削除ボタンのコード
			$data .= <<<eof
		      <td><form method='post' action=''>
		      <input type='hidden' name='id' id='Deleteid' value='{$row[0]}'>
		      <input type='submit' name='delete' id='delete' value='Delete'
		       onClick='return CheckDelete()'>
		      </form></td>
eof;
			$data .= "</tr>\n";
		}
		$data .= "</table>\n";
		return $data;
	}

	//プロダクトテーブルの項目
	//id, Product_Number, Product_Name, Product_Description, Product_Line, Name_Unique, Name_Blank_Flag, SP_Flag

	public function Insertproduct(){
		$sql = "INSERT INTO product VALUES(?,?,?,?,?, ?,?,?)";
		$array = array($_POST['id'],$_POST['Product_Number'],$_POST['Product_Name'],$_POST['Product_Description'],$_POST['Product_Line'],$_POST['Name_Unique'],$_POST['Name_Blank_Flag'],$_POST['SP_Flag']);
		parent::executeSQL($sql, $array);
	}

	public function Updateproduct(){
		$sql = "UPDATE product SET Product_Number=?, Product_Name=?, Product_Description=?, Product_Line=?, Name_Unique=?, Name_Blank_Flag=?, SP_Flag=? WHERE id=?";
		//array関数の引数の順番に注意する
		$array = array($_POST['Product_Number'],$_POST['Product_Name'],$_POST['Product_Description'],$_POST['Product_Line'],$_POST['Name_Unique'],$_POST['Name_Blank_Flag'],$_POST['SP_Flag'],$_POST['id']);
		parent::executeSQL($sql, $array);
	}

	//プロダクトテーブルの項目
	//id, Product_Number, Product_Name, Product_Description,
	//Product_Line, Name_Unique, Name_Blank_Flag, SP_Flag

	public function Product_NumberForUpdate($id){
		return $this->FieldValueForUpdate($id, "Product_Number");
	}

	public function Product_NameForUpdate($id){
		return $this->FieldValueForUpdate($id, "Product_Name");
	}

	public function Product_DescriptionForUpdate($id){
		return $this->FieldValueForUpdate($id, "Product_Description");
	}

	public function Product_LineForUpdate($id){
		return $this->FieldValueForUpdate($id, "Product_Line");
	}

	public function Name_UniqueForUpdate($id){
		return $this->FieldValueForUpdate($id, "Name_Unique");
	}

	public function Name_Blank_FlagForUpdate($id){
		return $this->FieldValueForUpdate($id, "Name_Blank_Flag");
	}

	public function SP_FlagForUpdate($id){
		return $this->FieldValueForUpdate($id, "SP_Flag");
	}

	private function FieldValueForUpdate($id, $field){
		//private関数　上の3つの関数で使用している
		$sql = "SELECT {$field} FROM product WHERE id=?";
		$array = array($id);
		$res = parent::executeSQL($sql, $array);
		$rows = $res->fetch(PDO::FETCH_NUM);
		return $rows[0];
	}

	public function Deleteproduct($id){
		$sql = "DELETE FROM product WHERE id=?";
		$array = array($id);
		parent::executeSQL($sql, $array);
	}


	public function productSearch($searchword){
		//検索結果をテーブルに並べる
		//$res = $this->getsearchresult($searchword);
		list($res, $countresult) = $this->getsearchresult($searchword);
		$result = "";
		$result .= <<<eof
					<tr>
					<th nowrap width="40px">ID</th>
					<th nowrap width="40">Product_Number</th>
					<th nowrap width="100">Product_Name</th>
					<th nowrap width="90">Product_Description</th>
					<th nowrap width="200">Product_Line</th>
					<th nowrap width="70">Name_Unique</th>
					<th nowrap width="70">Name_Blank_Flag</th>
					<th nowrap width="70">SP_Flag</th>
					<th nowrap width="70"></th>
					<th nowrap width="70"></th>
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
					<td>{$row['Name_Unique']}</td>
					<td>{$row['Name_Blank_Flag']}</td>
					<td>{$row['SP_Flag']}</td>
					<td><form method='post' action=''>
					<input type='hidden' name='id' value='{$row['id']}'>
					<input type='submit' name='update' value='更新'>
					</form></td>
					<td><form method='post' action=''>
					<input type='hidden' name='id' id='Deleteid' value='{$row['id']}'>
					<input type='submit' name='delete' id='delete' value='削除' onClick='return CheckDelete()'>
					</form></td>
					\n
					</tr>
eof;

		}

		if($countresult == 0){
			$result = "該当する結果がありません";
		}
		return array($result, $countresult);
	}

	private function getsearchresult($searchword){
		//検索結果セットを取得

		// 全角スペースを半角スペースに置換
		$keyword_txt = str_replace( "　" , " " , $searchword );
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

		$sql .= "ORDER BY id DESC";


		$res = parent::executeSQL($sql,null);
		$countresult = $res->rowCount();
		return array($res, $countresult);
	}


	public function DeleteAll(){
		$sql = "DELETE FROM product";
		$array = null;
		parent::executeSQL($sql, $array);
	}

	public function Download2Excel(){

		// キャッシュメモリ設定（デフォルト:1MB → 256MB）
		// ※キャッシュを有効にした場合、列の挿入(insertNewColumnBefore)・削除(removeColumn)、行の挿入(insertNewRowBefore)・削除(removeRow)が正常に動作しないため注意すること！！
		$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
		$cacheSettings = array('memoryCacheSize' => '256MB');
		PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

		// Excelファイルの新規作成
		$objExcel = new PHPExcel();

		// シートの設定
		$objExcel->setActiveSheetIndex(0);
		$objSheet = $objExcel->getActiveSheet();

		// A1セルに「テスト」という文字列を設定
		//$objSheet->setCellValue('A1', 'テスト');
		//番号で指定する場合は、コラムは0、行は1からスタートする
		//$objSheet->setCellValueByColumnAndRow(1,2,'書き込み内容');
		
		//プロダクトテーブルの項目
		//id, Product_Number, Product_Name, Product_Description,
		//Product_Line, Name_Unique, Name_Blank_Flag, SP_Flag

		$sql = "SELECT * FROM product";
		$array = null;
		$res = parent::executeSQL($sql, $array);
		$colarray = array(Product_Number, Product_Name, Product_Description,Product_Line, Name_Unique, Name_Blank_Flag, SP_Flag);
		for($i=0;$i<7;$i++){//Productテーブルは８列
			//番号で指定する場合は、コラムは0、行は1からスタートする
			$objSheet->setCellValueByColumnAndRow($i,1,$colarray[$i]);
		}
		$k = 2;
		foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){

			for($i=1;$i<8;$i++){//Productテーブルは８列 idは不要なので$iは1から始める
				//番号で指定する場合は、コラムは0、行は1からスタートする
				$objSheet->setCellValueByColumnAndRow($i-1,$k,$row[$i]);
			}
			++$k;
		}
		
		$str = new DateTime('now', new DateTimeZone('Asia/Tokyo'));
		$date = $str->format('Y-m-d');

		// Excelファイルのダウンロード
		$objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header("Content-Disposition: attachment;filename=" . "ProductTable_".$date.".xlsx");
		header("Content-Transfer-Encoding: binary ");
		$objWriter->save('php://output');

		// メモリの開放
		$objExcel->disconnectWorksheets();
		unset($objWriter);
		unset($objSheet);
		unset($objExcel);
		
		// 以下は絶対必須！！
		exit;

	}

}
?>