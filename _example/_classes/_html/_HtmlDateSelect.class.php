<?php
$path = str_replace($_SERVER['PHP_SELF'],'',__FILE__);
include_once $path.'/config/config.inc.php';

# 날짜 전용 셀렉트 메뉴 샘플1
Out::prints_ln('날짜전용 셀렉트 메뉴 : /======================');

$htmlDateObj = new HtmlDateSelect('syear',date('Y'));
Out::prints( $htmlDateObj->getYear(1965,2020).'년' );

$htmlDateObj = new HtmlDateSelect('smonth',date('m'));
Out::prints( $htmlDateObj->getMonth().'월' );

$htmlDateObj = new HtmlDateSelect('sday',date('d'));
Out::prints( $htmlDateObj->getDay().'일' );
?>