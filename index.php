<?php
session_start();
require_once("function/function.php");
require_once("config.php");

$sql = new mysqli(	config('db.servername'),
					config('db.username'),
					config('db.password'),
					config('db.name'));
$arr=geturl();

$t=@$arr[1];
$page_header="";
$page_description="";
$ip=getRealIpAddr();
$user=check_login();
$time=time();
$sid=sid();

//$config=load_var();
$mtime = explode(" ",microtime());
$starttime = $mtime[1] + $mtime[0];
switch($t){
    default:
        $t="index";
    break;
	case 'comments':
	break;
}
require_once("page/$t.php");
?>