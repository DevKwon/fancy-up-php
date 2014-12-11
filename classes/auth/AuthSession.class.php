<?php
/** ======================================================
| @Author   : 김종관
| @Email    : apmsoft@gmail.com
| @HomePage : http://www.apmsoftax.com
| @Editor   : Eclipse(default)
| @UPDATE   : 14-06-23
----------------------------------------------------------*/

# _AUTH_MODE_
class AuthSession
{
    private $authType = ''; # 관리자, 웹 세션 활용 모드

    # 관리자용 세션 항목
    private $auth_admin_args = array(
        'uid' => 'admin_uid',
        'name'=>'admin_name',
        'email' => 'admin_email',
        'level' => 'admin_level'
    );

    # 웹용 세션 항목
    private $auth_www_args = array(
        'uid'=>'auth_uid',
        'email'=>'auth_email',
        'name'=>'auth_name',
        'nickname'=>'auth_nickname',
        'level'=>'auth_level'
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

    # void
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
                if($_SESSION[$v]!=null && $_SESSION[$v]!=''){
                    $this->authinfo[$k]=$_SESSION[$v];
                }
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
                if($data_args[$v]!=null && $data_args[$v]!=''){
                    //session_register($v);
                    $_SESSION[$v]=$data_args[$v];
                }
            }
        }
    }

    #void
    # 세션비우기
    public function unregiAuth(){
        if(!strcmp($this->authType, 'admin')){
            $args=&$this->auth_admin_args;
        }else{
            $args=&$this->auth_www_args;
        }
        foreach($args as $k=>$v){
            if(isset($_SESSION[$v])){
                unset($_SESSION[$v]);
            }
        }
    }

    #@ void
    # 세션 전부 비우기
    public function clearAuth(){
        $admin_args=&$this->auth_admin_args;
        foreach($admin_args as $ak=>$av){
            if(isset($_SESSION[$av])){
                unset($_SESSION[$av]);
            }
        }

        $www_args=&$this->auth_www_args;
        foreach($www_args as $wk=>$wv){
            if(isset($_SESSION[$wv])){
                unset($_SESSION[$wv]);
            }
        }
    }
}
?>