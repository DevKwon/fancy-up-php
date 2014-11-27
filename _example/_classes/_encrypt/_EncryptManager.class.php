<?php
$path = str_replace($_SERVER['PHP_SELF'],'',__FILE__);
include_once $path.'/config/config.inc.php';

$encrypt_class = new EncryptManager;

    $passwd_no_encrypt = $_REQUEST['pass'];
    $encrypt_class->init($passwd_no_encrypt);
    $passwd_encrypt = $encrypt_class->encypt_base64_urlencode();
    $encrypt_class->init($passwd_encrypt);
    $passwd_decrypt = $encrypt_class->encypt_base64_urldecode();

    Out::prints_ln($passwd_no_encrypt.' : '.$passwd_encrypt);
    Out::prints_ln($passwd_encrypt.' : '.$passwd_decrypt);

?>