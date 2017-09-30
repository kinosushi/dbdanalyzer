<?php

//20170915 3つのExcuteメソッド全てにエラーログを吐き出すコードを追加

class DB{
  //MySQLとやり取りをするクラス
//    private  $USER = "root";//会社での環境
//    private  $PW   = "Pavinet2014";//会社での環境
// 	private  $USER = "root";//自宅XAMPPの環境
// 	private  $PW   = "1234";//自宅XAMPPの環境
	private  $USER = "atsushi";//自宅MAMPの環境
	private  $PW   = "1234";//自宅MAMPの環境
//    private  $USER = "analyzer";//会社での環境
//    private  $PW   = "!qaz2wsx";////会社での環境
  private  $dns  = "mysql:dbname=test;host=localhost;charset=utf8";


  private function Connectdb(){
//    try{
//      $pdo = new PDO($this->dns,$this->USER,$this->PW);
//      return $pdo;
//    }catch(Exception $e){
//      echo $e->getMessage();
//    }

      $option = array(PDO::MYSQL_ATTR_INIT_COMMAND =>
                      "SET CHARACTER SET 'utf8'");
      $pdo = new PDO($this->dns,$this->USER,$this->PW,$option);

      return $pdo;

  }

  protected function executeSQL($sql, $array){

    //SQLを実行する関数
    try{
      if(!$pdo = $this->Connectdb())return false;
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//エラーモードの設定（ログに出力する際に必要）
      $stmt = $pdo->prepare($sql);
      $stmt->execute($array);
      return $stmt;   //戻り値はPDOStatementのインスタンス
    }catch(Exception $e){
//       echo $e->getMessage();
//      return false;

		//SQLエラーをログに出力
    	$mes = $e->getMessage();
    	$str = new DateTime('now', new DateTimeZone('Asia/Tokyo'));
    	$date = "\r\n";
    	$date .= $str->format('Y-m-d H:i:s');
    	$date .= "\r\n";
    	$sqlerrorlog_file = './sqlerrorlog.txt';//SQLエラーログファイル
    	file_put_contents($sqlerrorlog_file, $date, FILE_APPEND | LOCK_EX);//ログに日時を追記
    	file_put_contents($sqlerrorlog_file, $mes, FILE_APPEND | LOCK_EX);//ログにSQLエラーを追記
    }
  }


  protected function executeSQL2($sql, $array){

  	//Insertを行って、すぐにIDを取得するため専用の関数
  	try{
  		if(!$pdo = $this->Connectdb())return false;
  		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//エラーモードの設定（ログに出力する際に必要）
  		$stmt = $pdo->prepare($sql);
  		$stmt->execute($array);
  		$lastid = $pdo->lastInsertId();
  		return $lastid;   //戻り値は直前にInsertした時に生成されたAutoIncrement値
  	}catch(Exception $e){
//   		echo $e->getMessage();
		//SQLエラーをログに出力
  		$mes = $e->getMessage();
  		$str = new DateTime('now', new DateTimeZone('Asia/Tokyo'));
  		$date = "\r\n";
  		$date .= $str->format('Y-m-d H:i:s');
  		$date .= "\r\n";
  		$sqlerrorlog_file = './sqlerrorlog.txt';//SQLエラーログファイル
  		file_put_contents($sqlerrorlog_file, $date, FILE_APPEND | LOCK_EX);//ログに日時を追記
  		file_put_contents($sqlerrorlog_file, $mes, FILE_APPEND | LOCK_EX);//ログにSQLエラーを追記

  	}
  }

  protected function executeSQL3($sql, $array){

  	//SQLを実行する関数　Insertが成功したかどうかの戻り値あり
  	try{
  		if(!$pdo = $this->Connectdb())return false;
  		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//エラーモードの設定（ログに出力する際に必要）
  		$stmt = $pdo->prepare($sql);
  		$res = $stmt->execute($array);
  		return $res;   //戻り値はInsertが成功したかどうかの値（0か1）
  	}catch(Exception $e){
  		//echo $e->getMessage();
  		//      return false;

  		//SQLエラーをログに出力
  		$mes = $e->getMessage();
  		$str = new DateTime('now', new DateTimeZone('Asia/Tokyo'));
  		$date = "\r\n";
  		$date .= $str->format('Y-m-d H:i:s');
  		$date .= "\r\n";
  		$sqlerrorlog_file = './sqlerrorlog.txt';//SQLエラーログファイル
  		file_put_contents($sqlerrorlog_file, $date, FILE_APPEND | LOCK_EX);//ログに日時を追記
  		file_put_contents($sqlerrorlog_file, $mes, FILE_APPEND | LOCK_EX);//ログにSQLエラーを追記
  	}
  }

}
?>