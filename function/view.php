<?php

function view($_page,$arrays=[]){
	global $user, $sql;
	$_page=str_replace(".","/",$_page);
	foreach($arrays as $key => $value){
		$$key=$value;
	}
	require "page/".$_page.".php";
}