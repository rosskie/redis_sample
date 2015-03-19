<?php
/**
 * APIからデータを取得するバッチ処理
 * （課題プログラムA）
 * 
 * @author ichiyaangi
 * @create 2015/03/18
 */
//初期設定ファイル読み込み
include_once "{$_SERVER['DOCUMENT_ROOT']}/lib/autoloader.php";
include_once "./class/api_bat.php";

$obj = new ApiBat();
$obj->fetch_api_data();
$obj->confirm_redis();

