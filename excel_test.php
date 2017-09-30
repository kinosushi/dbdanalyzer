<?php
// PHPExcelの読み込み
require_once("./Classes/PHPExcel.php");

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
$objSheet->setCellValueByColumnAndRow(1,2,'書き込み内容22');

// Excelファイルのダウンロード
$objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Disposition: attachment;filename=" . "TestDownload.xlsx");
header("Content-Transfer-Encoding: binary ");
$objWriter->save('php://output');


// メモリの開放
$objExcel->disconnectWorksheets();
unset($objWriter);
unset($objSheet);
unset($objExcel);



?>