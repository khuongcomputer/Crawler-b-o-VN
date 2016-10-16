<?php
function check_login(){
	if(get_cookie('id')){
		set_session('id',get_cookie('id'));
		set_session('password',get_cookie('password'));
	}
	if(get_session('id')){
		$id=get_session('id');
		$password=get_session('password');
		global $sql;
		$query=$sql->query("SELECT * FROM `users` 
						WHERE `id`='{$id}'
						AND `password`=MD5(CONCAT('{$password}',`salt`))");
		if($query->num_rows==1){
			$user=$query->fetch_array();
			$user['logged']=1;
			return $user;
		}
	}
	
	return ['logged'=>0, 'username'=>'Guest', 'auth'=>4];
}
function user_can($do){
	global $user, $sql;
	if(!isset($user['perm'])){
		$auth=$sql->query("SELECT * FROM `auths` WHERE `id` IN ('{$user['auth']}')");
		$user['perm']=[];
			if($auth->num_rows>0){
				while($auth_item=$auth->fetch_array()){
					$user['perm']=array_merge($user['perm'],json_to_array($auth_item['perm']));
				}
			}
	}
	
	if(!in_array($do,$user['perm'])) return false;
	return true;
}
function do_login($email, $password, $remember){
	global $sql;
	$password=md5hex($password);
	$query=$sql->query("SELECT `id`,`email`,`salt` FROM `users` 
						WHERE LCASE(`email`)=LCASE('{$email}')
						AND `password`=MD5(CONCAT('{$password}',`salt`))");
	if($query->num_rows!=1) return false;
	$user=$query->fetch_array();
	if($remember){
		set_cookie('id',$user['id']);
		set_cookie('password',$password);
	}
	set_session('id',$user['id']);
	set_session('password',$password);
	return $user;
}
function do_logout(){
	set_session('id','');
	set_session('password','');
	set_cookie('id','');
	set_cookie('password','');
}