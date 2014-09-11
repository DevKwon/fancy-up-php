<?php
/** ===========================
| @Author   : 김종관
| @Email    : apmsoft@gmail.com
| @Editor   : Eclipse(default)
| @UPDATE   : 14-08-26
-------------------------------------------------------*/

# 접속에 따른 디바이스|브라우저등 정보
class ApplicationEnviron
{
    private $agent;
    private $is_phone_device = false;
    private $host;
    private $lang;
    private $http_referer =null;
    
    public function __construct()
    {
        # 기본 디바이스 인지 확인 하기 위한 체크
        $agent=$_SERVER[ 'HTTP_USER_AGENT'];
        if(preg_match( '/(Android|iPod|iPhone|Windows Phone|lgtelecom|Windows CE)/', $agent)){
            if(strstr($agent,'Android')) $this->agent='Android';
            else if(strstr($agent,'iPod')) $this->agent='iPod';
            else if(strstr($agent,'iPhone')) $this->agent='iPhone';
            else if(strstr($agent,'iPad')) $this->agent='iPad';
            else if(strstr($agent,'Windows Phone')) $this->agent='Windows Phone';
            else if(strstr($agent,'Windows CE')) $this->agent='Windows CE';
            else if(strstr($agenst,'lgtelecom')) $this->agent='lgtelecom';
            
            # 스마트폰 디바이스 접속 여부
            $this->is_phone_device = true;
            
            # 이전 접속경로
            if(!is_null($_SERVER['HTTP_REFERER']) && isset($_SERVER['HTTP_REFERER'])){
                $this->http_referer = $_SERVER['HTTP_REFERER'];
            }
        }
        
        # 언어
        //preg_match_all('/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $lang_parse);
        //print_r($lang_parse);
        //$http_lang = $lang_parse[1][1];
        //$this->lang = ($http_lang) ? $http_lang : substr($lang_parse[1][0],0,2);
        //if(!$this->lang) $this->lang = 'ko';
        $this->lang = (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) ? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'],0,2) : 'ko';

        # host url
        $this->host = 'http://'.$_SERVER['SERVER_NAME'];
    }
    
    #@ return boolean
    # 애플사 제품인지 확인
    public function is_apple_device(){
        if(preg_match( '/(iPod|iPhone|iPad)/', $this->environ['agent'])){
            return 'true';
        } else {
            return 'false';
        }
    }
    
    #@ interface : ArrayAccess
    public function __set($name, $value){
        if(property_exists(__CLASS__,$name)){
            return $this->{$name} = $value;
        }
    }

    #@ return
    public function __get($name) {
        if(property_exists(__CLASS__,$name)){
            return $this->{$name};
        }
    }
}
?>