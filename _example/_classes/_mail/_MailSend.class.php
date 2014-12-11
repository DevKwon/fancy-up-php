<?php
$path = str_replace($_SERVER['PHP_SELF'],'',__FILE__);
include_once $path.'/config/config.inc.php';

$file_args=array();
$mainSendObj = new MailSend();
$mainSendObj->setHeaaderAttach($file_args);
$mainSendObj->setFrom('surpport@c1p.kr', '나당');
$mainSendObj->setDescription('앗싸제목');
$mainSendObj->setAttachmentFiles($file_args);
$mainSendObj->setTo('나에게', 'apmsoft@gmail.com');
#$mainSendObj->send('님이 글을 등록하였습니다');
?>