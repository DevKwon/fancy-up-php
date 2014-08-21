<?php
/** ===========================
| @Author   : 김종관
| @Email    : apmsoft@gmail.com
| @Editor   : Eclipse(default)
| @UPDATE   : 14-08-19
-------------------------------------------------------*/

# 접속에 따른 디바이스|브라우저등 정보
class ApplicationEnviron implements ArrayAccess
{
    private $environ = array();
    
    public function __construct()
    {
        # 기본 디바이스 인지 확인 하기 위한 체크
        $agent=$_SERVER[ 'HTTP_USER_AGENT'];
        if(preg_match( '/(Android|iPod|iPhone|Windows Phone|lgtelecom|Windows CE)/', $agent)){
            if(strstr($agent,'Android')) $this->environ['agent']='Android';
            else if(strstr($agent,'iPod')) $this->environ['agent']='iPod';
            else if(strstr($agent,'iPhone')) $this->environ['agent']='iPhone';
            else if(strstr($agent,'iPad')) $this->environ['agent']='iPad';
            else if(strstr($agent,'Windows Phone')) $this->environ['agent']='Windows Phone';
            else if(strstr($agent,'Windows CE')) $this->environ['agent']='Windows CE';
            else if(strstr($agenst,'lgtelecom')) $this->environ['agent']='lgtelecom';
        }
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
    public static function offsetSet($offset, $value){
        if(is_array($value)){
            if(isset($this->environ[$offset])) $this->environ[$offset] = array_merge($this->environ[$offset],$value);
            else $this->environ[$offset] = $value;
        }
        else{ $this->environ[$offset] = $value; }
    }

    #@ interface : ArrayAccess
    public function offsetExists($offset){
        if(isset(self::$environ[$offset])) return isset(self::$environ[$offset]);
        else return isset(self::$environ[$offset]);
    }

    #@ interface : ArrayAccess
    public function offsetUnset($offset){
        if(self::offsetExist($offset)) unset(self::$environ[$offset]);
        else unset(self::$environ[$offset]);
    }

    #@ interface : ArrayAccess
    public function offsetGet($offset) {
        return isset(self::$environ[$offset]) ? self::$environ[$offset] : self::$environ[$offset];
    }
}
?>