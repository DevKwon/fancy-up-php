<?php
$path = str_replace($_SERVER['PHP_SELF'],'',__FILE__);
include_once $path.'/config/config.inc.php';

$contents_type='html';
$message = '<html><body><p style="font:normal bold 30px/1.2 Gulim; color: #006600; text-align: center;">UTF-8로 보낸 메일입니다.</p></body></html>';

$mail = new MailSendMime();
$mail->setTo('미에게','apmsoft@gmail.com');
$mail->setFrom('surpport@c1p.kr', '클리에이티브플랫폼');
if($contents_type=='html'){
    $mail->setHtmlMessage($message);
}
else{
    $mail->setHtmlText($message);
}
$mail->send('메일 테스트');
?>