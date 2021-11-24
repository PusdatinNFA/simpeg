<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class Rest_authenticator {
    public function auth($username = "",$password = ""){
		$ci = &get_instance();
		$ci->load->library('users/auth');
		$rs = $ci->db->query("select * from users where username = ? ",array($username))->result('array');
		if(sizeof($rs)>0){
			// print_r($rs[0]['password_hash']);
			$resultlogin = $ci->auth->check_password($password,$rs[0]['password_hash']);	
			if($resultlogin){
				return true;
			}
		}
		return false;
		// return sizeof($rs)>0;
	}

}
