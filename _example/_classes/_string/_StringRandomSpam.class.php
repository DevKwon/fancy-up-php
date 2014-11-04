<?php
$path = str_replace($_SERVER['PHP_SELF'],'',__FILE__);
include_once $path.'/config/config.inc.php';

# 스팸 방지용 랜덤 클래스 테스트
$spamFilter = new StringRandomSpam();

# 문자에 css style 효과 넣기
$spamFilter->setCssStyle(array( 'font-size:16pt', 'font-weight:bold', 'color:red' ));

#실제로 비교할 문자
Out::prints_ln($spamFilter->filter_spam_str);

# 사용자에게 보여줄 문자
Out::prints_html($spamFilter->input_mix_str);