<?php
//http://qiita.com/ao_love/items/33f4509654d3a19fe53e

//ディレクトリ設定
// $dir = '/Applications/XAMPP/xamppfiles/htdocs/DBD_Analyzer/';

// //ライブラリ読み込み
// require_once $dir . 'Classes/PHPExcel.php';
// require_once $dir . 'Classes/PHPExcel/IOFactory.php';

require_once("./Classes/PHPExcel.php");
require_once ("./Classes/PHPExcel/IOFactory.php");

$regdate = new DateTime('NOW');
$regdate = $regdate->format("Y-m-d");

//テンプレート読み込み
$file ='temp.xls';//テンプレート名

$reader = PHPExcel_IOFactory::createReader('Excel2007');
$book = $reader->load($file);

//シートを設定する
$book->setActiveSheetIndex(0);//一番最初のシートを選択(2枚目なら(1))
$sheet = $book->getActiveSheet();//選択シートにアクセスを開始
$sheet->setTitle( $regdate ); //シート名を設定、変数OK

/***************
 *　書き込み処理
 ***************/
//セルを指定して書き込み
//セル名指定
// $sheet->setCellValue('セル名','書き込み内容');

//セル位置指定
//列はA=0,B=1...　行はそのまま
$sheet->setCellValueByColumnAndRow(1,2,'書き込み内容');

/***************
 *　出力処理
 ***************/
//出力ファイル名の設定(拡張子は出力形式に合わせて変える)
$output = 'output_'.$regdate.'.xlsx';

// Excel97-2003形式(xls)で出力する
$writer = PHPExcel_IOFactory::createWriter($book, 'Excel2007');
$writer->save($output);

//DLさせる場合はこっち
header('Content-Type: application/vnd.ms-excel');
ob_end_clean();//バッファのゴミ捨て
header("Content-Disposition: attachment;filename=$output");
header('Cache-Control: max-age=0');

$writer = PHPExcel_IOFactory::createWriter($book, "Excel2007");
$writer->save('php://output');

?>