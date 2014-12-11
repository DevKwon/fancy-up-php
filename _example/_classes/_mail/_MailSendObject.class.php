<?php
$path = str_replace($_SERVER['PHP_SELF'],'',__FILE__);
include_once $path.'/config/config.inc.php';

$message_html = '<html><body><p style="font:normal bold 30px/1.2 Gulim; color: #006600; text-align: center;">메일 테스트 젠장</p></body></html>';
$message_text = '메일 테스트 젠장';

$mail = new MailSendObject();
$mail->setTo('미', 'ehomebuild@naver.com');
#$mail->setTo('미', 'apmsoft@gmail.com');
$mail->setFrom('apmsoft@gmail.com', 'CreativePlatform');
$mail->setTextHtml($message_html);
//$mail->setTextPlain($message_text);
if($mail->send('메일 테스트 지금이 몇시냐 ggg ')){
    echo '성공';
}else{
    echo '실패';
}


// $to      = 'ehomebuild@naver.com';
// $subject = 'the subject';
// $message = 'hello';
// $headers = 'From: apmsoft@gmail.com' . "\r\n" .
//     'Reply-To: apmsoft@gmail.com' . "\r\n" .
//     'X-Mailer: PHP/' . phpversion();

// mail($to, $subject, $message);

?>