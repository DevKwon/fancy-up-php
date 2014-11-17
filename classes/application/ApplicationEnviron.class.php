<?php
/** ===========================
| @Author   : 김종관
| @Email    : apmsoft@gmail.com
| @Editor   : Eclipse(default)
| @UPDATE   : 14-08-26
-------------------------------------------------------*/

# 접속에 따른 디바이스|브라우저등 정보
final class ApplicationEnviron
{
    private $platform = 'Unknown';
    //private $agent;
    private $browser = 'Unknown';
    private $is_phone_device = false;
    private $host;
    private $lang;
    private $http_referer =null;
    private $ip_address = '';
    private $version = '0.9.8Beta';

    public function __construct()
    {
        # 기본 디바이스 인지 확인 하기 위한 체크
        $agent=$_SERVER[ 'HTTP_USER_AGENT'];

        # platform
        if (preg_match('/(Linux|Android|Macintosh|Mac os x|Windows|Win32)/i', $agent)) {
            if(stristr($agent,'Linux')) $this->platform='Linux';
            else if(stristr($agent,'Android')) $this->platform='Android';
            else if(stristr($agent,'Macintosh')) $this->platform='Mac';
            else if(stristr($agent,'mac os x')) $this->platform='Mac';
            else if(stristr($agent,'Windows')) $this->platform='Windows';
            else if(stristr($agenst,'Win32')) $this->platform='Windows';
        }

        # 추가 디바이스 플랫폼
        if($this->platform == 'Unknown')
        {
            if(preg_match( '/(Android|iPod|iPhone|Windows Phone|lgtelecom|Windows CE)/i', $agent)){
                if(strstr($agent,'Android')) $this->platform='Android';
                else if(strstr($agent,'iPod')) $this->platform='iPod';
                else if(strstr($agent,'iPhone')) $this->platform='iPhone';
                else if(strstr($agent,'iPad')) $this->platform='iPad';
                else if(strstr($agent,'Windows Phone')) $this->platform='Windows Phone';
                else if(strstr($agent,'Windows CE')) $this->platform='Windows CE';
                else if(strstr($agenst,'lgtelecom')) $this->platform='lgtelecom';

                # 스마트폰 디바이스 접속 여부
                $this->is_phone_device = true;
            }
        }

        #브라우저
        if (preg_match('/(MSIE|Opera|Firefox|Chrome|Safari|Opera|Netscape)/i', $agent)) {
            if(stristr($agent,'MSIE') && !stristr($agent,'Opera')) $this->browser='Explorer';
            else if(stristr($agent,'Firefox')) $this->browser='Firefox';
            else if(stristr($agent,'Chrome')) $this->browser='Chrome';
            else if(stristr($agent,'Safari')) $this->browser='Safari';
            else if(stristr($agent,'Opera')) $this->browser='Opera';
            else if(stristr($agent,'Netscape')) $this->browser='Netscape';
        }

        # 이전 접속경로
        if(!is_null($_SERVER['HTTP_REFERER']) && isset($_SERVER['HTTP_REFERER'])){
            $this->http_referer = $_SERVER['HTTP_REFERER'];
        }

        # 언어
        $this->lang = (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) ? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'],0,2) : 'ko';

        # host url
        $this->host = 'http://'.$_SERVER['SERVER_NAME'];

        # ip address
        $this->ip_address = $_SERVER['REMOTE_ADDR'];
    }

    #@ return boolean
    # 애플사 제품인지 확인
    public function is_apple_device(){
        if(preg_match( '/(iPod|iPhone|iPad)/', $this->platform)){
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