<?php
function check_fields($check_fields,$data){
	$list=explode(",",$check_fields);
	foreach($list as $v){
		if($data[$v]=="") return $v;
	}
	return "";
}
function chk($str){
global $_POST;
	return isset($_POST[$str]);
}
function take_get($value,$def=""){
	if(strstr($value,',')){
		$list=explode(',',$value);
		$return=[];
		foreach($list as $item){
			$return[$item]=take_get($item,$def);
		}
		return $return;
	}else
		return _take_get($value,$def);
}
function _take_get($value,$def=""){
	global $_GET;
	if(isset($_GET[$value])){
		return post_in($_GET[$value]);
	}else
		return $def;
}
function take_post($value,$def=""){
	if(strstr($value,',')){
		$list=explode(',',$value);
		$return=[];
		foreach($list as $item){
			$return[$item]=_take_post($item,$def);
		}
		return $return;
	}else
		return _take_post($value,$def);
}
function _take_post($value,$def=""){
	global $_POST;
	if(isset($_POST[$value])){
		if(is_array($_POST[$value])){
			foreach($_POST[$value] as $a => $b){
				$tmp[$a]=post_in($b);
			}
			return $tmp;
		}
		else
		return post_in($_POST[$value]);
	}else
		return $def;
}
function post_in($str){
    if(is_array($str)){
        $return=[];
        foreach($str as $key => $value){
            $return[$key]=post_in($value);
        }
        return $return;
    }else{
        $str = htmlspecialchars(mysslashes($str));
        $str=str_replace("'","&#39;",$str);
        $str=str_replace('"',"&quot;",$str);
        return $str;
    }
}
function post_out($str){
	$str=str_replace("&amp;","&",$str);
	$str=str_replace("&#34;",'&quot;',$str);
return ($str);
}
function html_text($str){
    $str=post_out($str);
    $str=str_replace("\n","<br />",$str);
    return $str;
}
function html_content($str){
    $str=html_entity_decode($str);
    return $str;
}
function mysslashes($text) {
	$text = str_replace("\\\"","\"",$text);
	$text = str_replace("\\\\","\\",$text);
	$text = str_replace("\\'","'",$text);
	$text = str_replace("\t","",$text);
	return $text;
}
function post_imp($values,$from=""){
	$list=explode(",",$values);
	$data=array();
	foreach($list as $v){
		if(!is_array($from))
			$data[$v]=take_post(trim($v));
		else
			$data[$v]=post_in($from[trim($v)]);
	}
	return $data;
}
function _substr($txt,$num){
	if(strlen($txt)>$num){
		$txt=substr($txt,0,$num);
		$txt=substr($txt,0,strrpos($txt," "));
		$txt=trim($txt);
	}
	return $txt;
	
}
function set_cookie($name, $value){
	setcookie($name, $value, time()+30*24*60*60);
	return "";
}
function get_cookie($name){
	if(isset($_COOKIE[$name])){
		return post_in($_COOKIE[$name]);
	}else return "";
}
function get_session($name){
	if(isset($_SESSION[$name])){
		return post_in($_SESSION[$name]);
	}else return "";
}
function set_session($name, $value){
	$_SESSION[$name]=$value;
	return "";
}

function config($path){
	global $config;
	$dir=explode(".",$path);
	$result=$config;
	foreach($dir as $sub){
		if(!isset($result[$sub])) die("Cannot find config $sub in $path");
		$result=$result[$sub];
	}
	return $result;
}
function lang($path){
	global $lang;
	$dir=explode(".",$path);
	$result=$lang;
	foreach($dir as $sub){
		if(!isset($result[$sub])) die("Cannot find lang $sub in $path");
		$result=$result[$sub];
	}
	return $result;
}

function arr_compare($in,$out){
    $result=0;
    if(!is_array($in)){
		if($in==''||$out=='') return 2;
        if(strtolower(trim($in))==strtolower(trim($out)))
            $result=1;
        else
            $result=2;
    }else{
        foreach($in as $key1 => $val1){
            if(strtolower(trim($val1))==strtolower(trim($out[$key1]))){
                $result=1;
            }else{
                $result=2;
                break;
            }
        }
    }
    return $result;
}
function _token(){
	$token='';
	if(isset($_SESSION['token'])){
		$token_time=$_SESSION['token_time'];
		if($token_time>time())
			$token=$_SESSION['token'];
	}
	if($token==''){
		$_SESSION['token_time']=time()+60*60;
		$token=md5hex(randomPassword());
		$_SESSION['token']=$token;
	}
	echo "<input type='hidden' name='{$token}' value='1' />";
}
function _token_check(){
	if(!isset($_SESSION['token']) || !isset($_SESSION['token_time'])) exit('Token mismatch! Please hit back and refresh the last page!');
	$token		=	$_SESSION['token'];
	$token_time	=	$_SESSION['token_time'];
	if($token_time<time()) exit('Token expired! Please hit back and refresh the last page!');
	$_token=take_post($token);
	if($_token!=1) exit('Token mismatch! Please hit back and refresh the last page!');
	$_SESSION['token_time']=time()+60*60;
	$token=md5hex(randomPassword());
	$_SESSION['token']=$token;
	return true;
}