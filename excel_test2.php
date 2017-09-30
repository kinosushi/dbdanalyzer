<?php
// PHPExcelの読み込み
require_once("./Classes/PHPExcel.php");
require_once ("./Classes/PHPExcel/IOFactory.php");

// 新規ファイル作成準備
$excel = new PHPExcel();
$sheet = $excel->getActiveSheet();

//セル位置指定
//列はA=0,B=1...　行はそのまま
$sheet->setCellValueByColumnAndRow(1,2,'書き込み内容');
//$sheet->setCellValue('A1', 'TEST');

// Excelファイルの出力準備
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment;filename="downloadexceltest.xlsx"');

// Excekファイルの出力（ダウンロード）
$writer = PHPExcel_IOFactory::createWriter($excel, "Excel2007");
$writer->save('php://output');

// 以下は絶対必須！！
exit;
?>
