<?php
$path = str_replace($_SERVER['PHP_SELF'],'',__FILE__);
include_once $path.'/config/config.inc.php';

# ftp 클랙스
$ftp = new Ftp('fancyup.c1p.kr');
$ftp->ftp_login('fancyup','fuqlqjs');

# 현재경로
Out::prints_ln('현재디렉토리 : '.$ftp->ftp_pwd());

# ftp 경로에 따른 파일 목록
$nlist = $ftp->ftp_nlist('/www/res/values');
Out::prints_r($nlist);

# strings_ko.xml 파일 읽기
if($file_xml = $ftp->open_file_read(_ROOT_PATH_.'/'._VALUES_.'/strings_ko.xml'))
{
    Out::prints_ln('원본 내용 /========================');
    $xml = new SimpleXMLElement($file_xml);
    echo $xml->asXML();

    Out::prints_ln('바뀐 내용 /========================');
    $xml->resources->app_name = '크리에이티브플랫폼';
    Out::prints_ln((string)$xml->resources->app_name);
    echo $xml->asXML();

    # strings_ko.xml 파일 쓰기
    if($ftp->open_file_write(_ROOT_PATH_.'/'._VALUES_.'/strings_ko.xml', $xml->asXML()))
       Out::prints_ln('success');
}
?>