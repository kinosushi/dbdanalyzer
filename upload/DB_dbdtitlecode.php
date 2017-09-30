<?php
require_once('db.php');
class DB_dbdtitlecode extends DB{
	//rceテーブルのCRUD担当
	
	
	public function SelectdbdcodeAll($team){
		if($team == "7f"){
			$sql = "SELECT * FROM 7f_titlecode";
		}else if($team == "an"){
			$sql = "SELECT * FROM an_titlecode";
		}
		$res = parent::executeSQL($sql, null);
		$data = "<table class='recordlist' id='codeTable'>";
		$data .= "<tr><th>ID</th><th>code</th><th>issue</th><th>timing</th><th>how often</th><th>flag</th><th></th><th></th></tr>\n";
		foreach($rows = $res->fetchAll(PDO::FETCH_NUM) as $row){
			$data .= "<tr>";
			for($i=0;$i<count($row);$i++){
				$data .= "<td>{$row[$i]}</td>";
			}
			//更新ボタンのコード
			$data .= <<<eof
      <td><form method='post' action=''>
      <input type='hidden' name='id' value='{$row[0]}'>
	  <input type='hidden' name='team' value='{$team}'>
      <input type='submit' name='update' value='更新'>
      </form></td>
eof;
			//削除ボタンのコード
			$data .= <<<eof
      <td><form method='post' action=''>
      <input type='hidden' name='id' id='Deleteid' value='{$row[0]}'>
	  <input type='hidden' name='team' value='{$team}'>
      <input type='submit' name='delete' id='delete' value='削除'
       onClick='return CheckDelete()'>
      </form></td>
eof;
			$data .= "</tr>\n";
		}
		$data .= "</table>\n";
		return $data;
	}
	
	
	public function Insertdbdcode($team){
		if($team == "7f"){
			$sql = "INSERT INTO 7f_titlecode VALUES(?,?,?,?,?,?)";
		}else if($team == "an"){
			$sql = "INSERT INTO an_titlecode VALUES(?,?,?,?,?,?)";
		}
		$array = array($_POST['id'],$_POST['code'],$_POST['issue'],$_POST['timing'],$_POST['howoften'],$_POST['flag']);
		parent::executeSQL($sql, $array);
	}
	
	
	public function Updatedbdcode($team){
		if($team == "7f"){
			$sql = "UPDATE 7f_titlecode SET code=?, issue=?, timing=?, howoften=?, flag=? WHERE id=?";
		}else if($team == "an"){
			$sql = "UPDATE an_titlecode SET code=?, issue=?, timing=?, howoften=?, flag=? WHERE id=?";
		}
		//array関数の引数の順番に注意する
		$array = array($_POST['code'],$_POST['issue'],$_POST['timing'],$_POST['howoften'],$_POST['flag'],$_POST['id']);
		parent::executeSQL($sql, $array);
	}
	
	public function getCodeForUpdate($id,$team){
		return $this->FieldValueForUpdate($id,$team,"code");
	}
	
	public function getIssueForUpdate($id,$team){
		return $this->FieldValueForUpdate($id,$team,"issue");
	}
	
	public function getTimingForUpdate($id,$team){
		return $this->FieldValueForUpdate($id,$team,"timing");
	}
	
	public function getHowoftenForUpdate($id,$team){
		return $this->FieldValueForUpdate($id,$team,"howoften");
	}
	
	public function getFlagForUpdate($id,$team){
		return $this->FieldValueForUpdate($id,$team,"flag");
	}
	
	
	private function FieldValueForUpdate($id, $team, $field){
		//private関数　上のget関数で使用している
		if($team == "7f"){
			$sql = "SELECT {$field} FROM 7f_titlecode WHERE id=?";
		}else if($team == "an"){
			$sql = "SELECT {$field} FROM an_titlecode WHERE id=?";
		}
		$array = array($id);
		$res = parent::executeSQL($sql, $array);
		$rows = $res->fetch(PDO::FETCH_NUM);
		return $rows[0];
	}
	
	public function Deletedbdcode($id,$team){
		if($team == "7f"){
			$sql = "DELETE FROM 7f_titlecode WHERE id=?";
		}else if($team == "an"){
			$sql = "DELETE FROM an_titlecode WHERE id=?";
		}
		$array = array($id);
		parent::executeSQL($sql, $array);
	}
	

	
}
?>