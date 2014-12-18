<?php
/** ======================================================
| @Author	: 김종관 | 010-4023-7046
| @Email	: apmsoft@gmail.com
| @HomePage	: http://www.apmsoftax.com
| @Editor	: Eclipse(default)
| @UPDATE	: 
----------------------------------------------------------*/

# _AUTH_MODE_
class AuthCookie extends ArrayAccess
{	
	#쿠키나 세션에 등록할 변수 설정
	protected $auth_args = array(
		'uid'		=> 'axUid',
		'userid'	=> 'axUserID',
		'level'		=> 'axLevel',
		'name'		=> 'axName',
		'nickname'	=> 'axNickName',
		'email'		=> 'axEmail',
		'sex'		=> 'axSex',
		'ip'		=> 'axIP'
	);
	protected $authinfo = array();

	# path_session : 세션 파일 임시 저장 경로
	public function __constructor()
	{
		# 세션값을 사용하기 쉬게 $AuthInfo[] 란 배열에 다시할당		
		if(is_array($this->auth_args)){
			foreach($this->auth_args as $key => $value){ $AuthInfo[$key] = $_COOKIE[$value]; }
		}
	}

	#@ interface : ArrayAccess
    public function offsetSet($offset, $value){
		if(self::offsetExists($offset) === false)
			$this->auth_args[$offset] = $value;
    }
    
    #@ interface : ArrayAccess
	public function offsetExists($offset){
        if(isset($this->auth_args[$offset])) return true;
	return false;
    }
    
    #@ interface : ArrayAccess
	public function offsetUnset($offset){
		if(self::offsetExists($offset)) unset($this->auth_args[$offset]);
    }
    
    #@ interface : ArrayAccess
	public function offsetGet($offset){
        return (self::offsetExists($offset)) ? $this->auth_args[$offset] : false;
    }
	
	# 로그인
	public function login($args,$savetime=0, $domain='/')
	{
		if(is_array($args)){
			foreach($this->auth_args as $key => $value){
				setcookie($key,$args[$key], $savetime, $domain);
			}			
		}
	return false;
	}

	#void
	public function logout()
	{
		if(is_array($this->auth_args)){
			foreach($this->auth_args as $key => $value){
				setcookie($key, '', -1, $this->domain);
			}
		}
	}
}
?>