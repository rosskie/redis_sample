<?php
/**
 * バッチ処理が生成したAPIのキャッシュデータを画面表示する
 * （課題プログラムB）
 * 
 * @author ichiyaangi
 * @create 2015/03/18
 */
//初期設定ファイル読み込み
include_once "{$_SERVER['DOCUMENT_ROOT']}/lib/autoloader.php";
include_once "./class/api_view.php";

$obj = new ApiView();
$table = $obj->output_table();

//画面表示
$template = new SimpleTemplate();
$template->template_dir('../template');
$template->assign('title', "APIキャッシュ課題ページ");
$template->assign('center', $table);
$template->display('main.tpl');