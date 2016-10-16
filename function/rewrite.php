<?php
function ref(){
	if(takepost("ref")=="")
		return @$_SERVER['HTTP_REFERER'];
	else
		return takepost("ref");
}
function array_extract($num,$take=""){
	global $arr;
	$extract=@$arr[$num];
	if($take=="") return $extract;
	else{
		$data=explode("-",$extract);
		if($take==-1) return $data[sizeof($data)-1];
		else return $data[$take-1];
	}
}
function uri(){
	$uri=$_SERVER["REQUEST_URI"];
	return $uri;
}
function geturl(){
global $site;
	$uri=uri();
	if($site!="") $uri=str_replace($site,"",$uri);
	$arr=preg_split('/\//',$uri,-1,PREG_SPLIT_NO_EMPTY);
	//print_r($arr);
	$return_arr=array();
	for($i=0;$i<sizeof($arr);$i++)
		if($arr[$i]!="" && !strstr($arr[$i],"index.php")){
			if(strstr($arr[$i],"?")) $arr[$i]=substr($arr[$i],0,strpos($arr[$i],"?"));
			if(substr($arr[$i],strlen($arr[$i])-5)==".html") $arr[$i]=substr($arr[$i],0,strlen($arr[$i])-5);
			$return_arr[]=post_in($arr[$i]);
		}
	return $return_arr;
}
function pageurl(){
		$pageURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
		if ($_SERVER["SERVER_PORT"] != "80")
		{
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} 
		else 
		{
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		return $pageURL;
}
function getRealIpAddr(){ 
	if (!empty($_SERVER['HTTP_CLIENT_IP'])){ 
	//check ip from share internet 
	$ip = $_SERVER['HTTP_CLIENT_IP']; 
	} 
	elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){ 
	//to check ip is pass from proxy 
	$ip = $_SERVER['HTTP_X_FORWARDED_FOR']; 
	} 
	else{ 
	$ip = $_SERVER['REMOTE_ADDR']; 
	} 
	return $ip; 
}
function set_header($code) {
    switch($code){
        default:
            break;
        case '404':
            header("HTTP/1.0 404 Not Found");
            break;
        case '403':
            header("HTTP/1.0 403 Access denied");
            break;
    }

    return "";
}
function redirect($to){
	header("Location: {$to}");
}