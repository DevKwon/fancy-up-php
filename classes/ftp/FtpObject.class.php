<?php
/** ======================================================
| @Author   : 김종관 | 010-4023-7046
| @Email    : apmsoft@gmail.com
| @HomePage : http://www.apmsoftax.com
| @Editor   : Eclipse(default)
| @version  : 0.8
----------------------------------------------------------*/
class FtpObject
{
    private $conn;

    public function __construct($ftp_url){
        $this->conn = ftp_connect($ftp_url);
    }

    public function __call($func,$params){
        if(strstr($func,'ftp_') !== false && function_exists($func)){
            array_unshift($params,$this->conn);
            return call_user_func_array($func,$params);
        }
    }

    public function __destruct(){
        ftp_close($this->conn);
    }
}
?>