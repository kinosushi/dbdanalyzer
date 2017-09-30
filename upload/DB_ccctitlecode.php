<?php
require_once('db.php');
class DB_ccctitlecode extends DB{
	//rceテーブルのCRUD担当


	public function SelectccccodeAll(){
		$sql = "SELECT * FROM ccc_titlecode";
		$res = parent::executeSQL($sql, null);
		$data = "<table class='recordlist' id='codeTable'>";
		$data .= "<tr><th>ID</th><th>code</th><th>issue</th><th>timing</th><th>flag</th><th>os</th><th>category</th><th>customer</th><th></th><th></th></tr>\n";
		foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){
			$data .= "<tr>";
			for($i=0;$i<count($row);$i++){
				$data .= "<td>{$row[$i]}</td>";
			}
			//更新ボタンのコード
			$data .= <<<eof
      <td><form method='post' action=''>
      <input type='hidden' name='id' value='{$row[0]}'>
      <input type='submit' name='update' value='更新'>
      </form></td>
eof;
			//削除ボタンのコード
			$data .= <<<eof
      <td><form method='post' action=''>
      <input type='hidden' name='id' id='Deleteid' value='{$row[0]}'>
      <input type='submit' name='delete' id='delete' value='削除'
       onClick='return CheckDelete()'>
      </form></td>
eof;
			$data .= "</tr>\n";
		}
		$data .= "</table>\n";
		return $data;
	}


	public function Insertccccode(){
		$sql = "INSERT INTO ccc_titlecode VALUES(?,?,?,?,? ,?,?,?)";
		$array = array($_POST['id'],$_POST['code'],$_POST['issue'],$_POST['timing'],$_POST['flag'],$_POST['os'],$_POST['category'],$_POST['customer']);
		parent::executeSQL($sql, $array);
	}


	public function Updateccccode(){
		$sql = "UPDATE ccc_titlecode SET code=?, issue=?, timing=?, flag=?, os=?, category=?, customer=? WHERE id=?";
		//array関数の引数の順番に注意する
		$array = array($_POST['code'],$_POST['issue'],$_POST['timing'],$_POST['flag'],$_POST['os'],$_POST['category'],$_POST['customer'],$_POST['id']);
		parent::executeSQL($sql, $array);
	}

	public function getCodeForUpdate($id){
		return $this->FieldValueForUpdate($id,"code");
	}

	public function getIssueForUpdate($id){
		return $this->FieldValueForUpdate($id,"issue");
	}

	public function getTimingForUpdate($id){
		return $this->FieldValueForUpdate($id,"timing");
	}

	public function getFlagForUpdate($id){
		return $this->FieldValueForUpdate($id,"flag");
	}

	public function getOsForUpdate($id){
		return $this->FieldValueForUpdate($id,"os");
	}

	public function getCategoryForUpdate($id){
		return $this->FieldValueForUpdate($id,"category");
	}

	public function getCustomerForUpdate($id){
		return $this->FieldValueForUpdate($id,"customer");
	}




	private function FieldValueForUpdate($id, $field){
		//private関数　上のget関数で使用している
		$sql = "SELECT {$field} FROM ccc_titlecode WHERE id=?";
		$array = array($id);
		$res = parent::executeSQL($sql, $array);
		$rows = $res->fetch(PDO::FETCH_NUM);
		return $rows[0];
	}

	public function Deleteccccode($id){
		$sql = "DELETE FROM ccc_titlecode WHERE id=?";
		$array = array($id);
		parent::executeSQL($sql, $array);
	}



}
?>