<?php
/** ======================================================
| @Author	: 김종관
| @Email	: apmsoft@gmail.com
| @HomePage	: http://www.apmsoftax.com
| @Editor	: Eclipse(default)
| @UPDATE	: 
----------------------------------------------------------*/

# _AUTH_MODE_
class AuthSession
{
	private $authType = '';	# 관리자, 웹 세션 활용 모드
	
	# 관리자용 세션 항목
	private $auth_admin_args = array(
		'uid' => 'admin_uid',
		'userid' => 'admin_userid',
		'email' => 'admin_email',
		'level' => 'admin_level'
	);
	
	# 웹용 세션 항목
	private $auth_www_args = array(
		'uid'=>'auth_uid',
		'userid'=>'auth_userid',
		'name'=>'auth_name',
		'nicname'=>'auth_nickname',
		'email'=>'auth_email',
		'level'=>'auth_level',
		'cell_phone'=>'auth_cell_phone',
		'ip_address'=>'auth_ip_address'
	);
	
	# 로그인 체크 값
	private $authinfo = array();
	
	# run
	public function __construct($type=""){
		if(!strcmp($type, "admin"))
			$this->authType = $type;
		else 
			$this->authType = 'www';
	}
	
	# return data
	public function __set($k, $v){
		$this->authinfo[$k] = $v;
	}
	
	# return data
	public function __get($k){
		if(array_key_exists($k, $this->authinfo))
			return $this->authinfo[$k];
	}
	
	#@ void
	# 세션스타트 및 배열에 담기
	public function sessionStart()
	{
		if(is_array($_SESSION)){
			if(!strcmp($this->authType,'admin')){
				$args =&$this->auth_admin_args;
			}else{
				$args =&$this->auth_www_args;
			}
			
			foreach($args as $k=>$v){
				$this->authinfo[$k]=$_SESSION[$v];
			}
		}
	}
	
	#@ void
	# 세션등록
	public function regiAuth($data_args)
	{
		if(is_array($data_args)){
			@session_start();
			if(!strcmp($this->authType, 'admin')){
				$args=&$this->auth_admin_args;
			}else{
				$args=&$this->auth_www_args;
			}
			foreach($args as $k=>$v){
				session_register($v);
				$_SESSION[$v]=$data_args[$k];
			}
		}
	}

	#void
	# 세션비우기
	public function unregiAuth(){session_destroy();}
}
?>