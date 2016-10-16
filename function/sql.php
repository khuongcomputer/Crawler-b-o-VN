<?php
function insert($table,$data,$values){
	global $ip;
	$data['ip']=$ip;
	$list=explode(",",$values);
	$v1="";
	$v2="";
	foreach($list as $v){
		if($v1=="") $v1.="`$v`";
			else $v1.=",`$v`";
		if($v2=="") $v2.="'".$data[$v]."'";
			else $v2.=",'".$data[$v]."'";
	}
	$sql="INSERT INTO `$table` ($v1)
			VALUES ($v2)";
	return $sql;
}
function update($table,$data,$values,$condition){
	global $ip;
	$data['ip']=$ip;
	$list=explode(",",$values);
	$v1="";
	foreach($list as $v){
	if($data[$v]=="") $data[$v]="";
		if($v1=="") $v1.="`$v`='".$data[$v]."'";
			else $v1.=",`$v`='".$data[$v]."'";
	}
	$sql="UPDATE `$table` SET $v1
			WHERE $condition;";
	return $sql;
}
function createarray($values,$default=""){
	$arr=array();
	$list=explode(",",$values);
	foreach($list as $v){
		if(isset($default[$v]))
			$arr[$v]=$default[$v];
		else
			$arr[$v]="";
	}
	return $arr;
}