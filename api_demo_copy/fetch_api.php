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

/**
 * APIへのURLはapi_dataディレクトリにあるファイルから取得してください。
 * csv形式と配列を読み込むphpの２種類があるので使いやすい方を使ってください。
 * APIはxmlとjsonの２種があるのでこれも使いやすい方を選択してください。
 * 
 * APIから取得できるデータのサンプルはapi_data/api_data_sample.txtを参照。
 * アニメが１作品しか放送されていない県のデータは、微妙にデータ形式が異なっているので注意してください。
 *
 */


$obj = new ApiBat();
