<?php
$path = str_replace($_SERVER['PHP_SELF'],'',__FILE__);
include_once $path.'/config/config.inc.php';
include_once $path.'/config/config.ftp.php';

# ftp 클랙스
$ftp = new Ftp();

# 현재경로
Out::prints_ln('현재디렉토리 : '.$ftp->ftp_pwd());

# ftp 경로에 따른 파일 목록
$nlist = $ftp->ftp_nlist(_FTP_DIR_);
Out::prints_r($nlist);

# strings_ko.xml 파일 읽기
if($file_xml = $ftp->open_file_read(_ROOT_PATH_.'/'._DATA_.'/strings_ko.xml', _FTP_DIR_.'/'._VALUES_.'/strings_ko.xml'))
{
    Out::prints_ln('원본 내용 /========================');
    $xml = new XmlSimpleXMLElementPlus($file_xml);
    echo $xml->asXML();

    Out::prints_ln('바뀐 내용 /========================');

    // 엘레멘트 값 변경하기
    $xml->resources->app_name = '크리에이티브플랫폼';
    Out::prints_ln((string)$xml->resources->app_name);

    // ELEMENT 존재 하는지 체크 여부 및 ELEMENT 추가
    $xml->resources->addChildPlus('test', 'test');
    Out::prints_ln((string)$xml->resources->test);
    echo $xml->asXML();

    // ELEMENT CDATA 값 넣
    $xml->resources->addCData('testcdata', '대한민국');

    # strings_ko.xml 파일 쓰기
    if($ftp->open_file_write(_ROOT_PATH_.'/'._DATA_.'/strings_ko.xml', _FTP_DIR_.'/'._VALUES_.'/strings_ko.xml', $xml->asXML()))
       Out::prints_ln('success');
}

# array.xml 파일 읽기
if($array_xml = $ftp->open_file_read(_ROOT_PATH_.'/'._DATA_.'/array.xml', _FTP_DIR_.'/'._VALUES_.'/array.xml'))
{
    Out::prints_ln('원본 내용 /========================');
    $xml = new XmlSimpleXMLElementPlus($array_xml);
    echo $xml->asXML();

    Out::prints_ln('바뀐 내용 /========================');

    // ELEMENT 존재 하는지 체크 여부 및 ELEMENT 추가
    $xml->array_member_level->addChildPlus('level_10', 'test');

    # strings_ko.xml 파일 쓰기
    if($ftp->open_file_write(_ROOT_PATH_.'/'._DATA_.'/array.xml', _FTP_DIR_.'/'._VALUES_.'/array.xml', $xml->asXML()))
       Out::prints_ln('success');
}

?>