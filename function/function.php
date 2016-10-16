<?php
//load needed functions
require_once("rewrite.php"); //url splitter from rewrite rule and ip related
require_once("variables.php"); //function for working with POST and GET variables
require_once("sql.php"); //sql helper
require_once("json.php"); //json helper
require_once("view.php"); //including view helper
require_once("auth.php"); //authorization helper
require_once("encoder.php"); //encoder helper
require_once("mailer.php"); //mailer helper
require_once("get_file.php"); //file downloader
function load_var(){
	global $sql;
	$q=$sql->query("SELECT * FROM `config`");
	$config=array();
		while($temp=$q->fetch_object()){
			$config[$temp->name]=$temp;
		}
	return $config;
}
function update_var($var,$val){
	global $sql;
	if(substr($val,0,1)=="+"||substr($val,0,1)=="-"){
		$q="UPDATE `config` SET `value`=`value`$val WHERE `name`='$var'";
	}else{
		$q="UPDATE `config` SET `value`='$val' WHERE `name`='$var'";
	}
	$sql->query($q);
}
function sid(){
	return session_id();
}
function isnew($fromTime, $toTime = 0) {
date_default_timezone_set('Europe/Moscow');
	if($toTime==0) $toTime=time();
	$fromTime=strtotime($fromTime);
     $distanceInSeconds = round(abs($toTime - $fromTime));
     $distanceInMinutes = round($distanceInSeconds / 60);
         if ( $distanceInMinutes < 1440 ) {
             return true;
         }
		 return false;
 }
function nicetime($fromTime, $toTime = 0) {
//echo date("d-m-Y H:i:s ",time());
//return $fromTime;
date_default_timezone_set('Europe/Moscow');
	if($toTime==0) $toTime=time();
	$fromTime=strtotime($fromTime);
	//echo date("d-m-Y h:i:s ",$fromTime);
	//echo date("d-m-Y h:i:s",$toTime);
     $distanceInSeconds = round(abs($toTime - $fromTime));
     $distanceInMinutes = round($distanceInSeconds / 60);
         if ( $distanceInMinutes < 1440 ) {
             return ''._nicetime($fromTime);
         }
		 return date("d-m-Y H:i:s",$fromTime);
 }
function _nicetime($date)
{
date_default_timezone_set('Europe/Moscow');
    if(empty($date)) {
        return "Empty";
    }
    $periods         = array("seconds", "minutes", "hours", "days", "week", "month", "year", "thế kỉ");
    $lengths         = array("60","60","24","7","4.35","12","10");
    $now             = time();
    $unix_date         = $date; //strtotime($date);
       // check validity of date
    if(empty($unix_date)) {
        return "Bad date";
    }
    // is it future date or past date
	//$difference     = abs(strtotime(date("d-m-Y h:i:s ",$now)) - strtotime(date("d-m-Y h:i:s ",$unix_date)));
	$difference = abs($now-$date);
    if($now > $unix_date) {
        $tense         = "ago";
    } else {
        $tense         = "next";
    }
	//echo $difference;
    for($j = 0; $difference > $lengths[$j] && $j < count($lengths)-1; $j++) {
        $difference /= $lengths[$j];
    }
    $difference = round($difference);
    //if($difference != 1) {
      //  $periods[$j].= "(s)";
    //}
    return "$difference $periods[$j] {$tense}";
}