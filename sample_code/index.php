<?php
/**
 * string型の動作サンプル
 * 
 * @author ichiyaangi
 * @create 2015/03/18
 */
//初期設定ファイル読み込み
include_once "{$_SERVER['DOCUMENT_ROOT']}/lib/autoloader.php";
include_once "./class/redis_demo.php";

$obj = new RedisDemo();

switch($_GET["action"]){
    case "string":
        $content = $obj->runString();
        break;
    case "list":
        $content = $obj->runList();
        break;
    case "set":
        $content = $obj->runSet();
        break;
    case "sorted":
        $content = $obj->runSorted();
        break;
    case "hash":
        $content = $obj->runHash();
        break;
    default :
        $center = "中";
        break;
}

$left = <<<END
<a href="index.php?action=string">stringデモ</a><br/>
<a href="index.php?action=list">Listデモ</a><br/>
<a href="index.php?action=set">SETデモ</a><br/>
<a href="index.php?action=sorted">ソート済みSETデモ</a><br/>
<a href="index.php?action=hash">HASHデモ</a><br/>
END;
$template = new SimpleTemplate();
$template->template_dir('../template');
$template->assign('title', "REDISデータ型動作サンプル");
$template->assign('center', $content);
$template->assign('left', $left);
$template->display('main.tpl');