<?php
$path = str_replace($_SERVER['PHP_SELF'],'',__FILE__);
include_once $path.'/config/config.inc.php';

# 샘플 1 [셀렉트메뉴]
Out::prints_ln('셀렉트 메뉴 샘플1 : addParams 사용, 기본값 선택 level_1 /======================');
$htmlMenuObj = new HtmlButtonMenu('select_test1','level_1');
$htmlMenuObj->addParams('level_0', '비회원');
$htmlMenuObj->addParams('level_1', '일반회원');
$htmlMenuObj->addParams('level_2', '유료회원');
$htmlMenuObj->addParams('level_9', '게시판관리자');
Out::prints_ln($htmlMenuObj->select());
Out::prints_ln('');

# 샘플 2 [셀렉트메뉴]
Out::prints_ln('셀렉트 메뉴 샘플2 : 배열한번에 넣기 사용, 기본값 선택 level_9 /======================');
$sel_args = array(
    'level_0' => '비회원',
    'level_1' => '일반회원',
    'level_2' => '유료회원',
    'level_9' => '게시판관리자'
);
$htmlMenuObj = new HtmlButtonMenu('select_test2','level_2', $sel_args);
Out::prints_ln($htmlMenuObj->select());
Out::prints_ln('');


# 샘플 3 [셀렉트메뉴]
Out::prints_ln('셀렉트 메뉴 샘플2 : 이벤트(onChange) /======================');
$sel_args = array(
    'level_0' => '비회원',
    'level_1' => '일반회원',
    'level_2' => '유료회원',
    'level_9' => '게시판관리자'
);
$htmlMenuObj = new HtmlButtonMenu('select_test2','level_2', $sel_args);
Out::prints_ln($htmlMenuObj->select('onChange="alert(\'이벤트설정 할 수 있어요\');"; return false; style="border:0px;"'));
Out::prints_ln('');

# 샘플 4 [라디오]
Out::prints_ln('라디오버튼 샘플 /======================');
$htmlMenuObj = new HtmlButtonMenu('sel_'.$key, 'sel_'.$key);
$htmlMenuObj->addParams('list', '일반');
$htmlMenuObj->addParams('news', '뉴스(웹진)');
$htmlMenuObj->addParams('faq', '자주묻는질문');
$htmlMenuObj->addParams('qna', '묻고답하기');
$htmlMenuObj->addParams('gallery', '갤러리');
Out::prints_ln($htmlMenuObj->radio(5,''));
Out::prints_ln('');

?>