<?php
/** ======================================================
| @Author	: 김종관
| @Editor	: Notepad++
----------------------------------------------------------*/

# 전체 다국어 공통 메세지 설정
# 순전히 사용자 에러메세지(User Exception)를 출력하기 위한 목적임
# Status::getStatusMessage('404')
class Status
{
	# 기본 상태에 관한 메세지
	private static $status_args = array(
		'403' => array(
			'ko' =>'접근이 금지 되었습니다',
			'en' =>'is not allowed'
		),
		'404' => array(
			'ko' => '파일을 찾을 수 없습니다',
			'en' => 'is not file'
		),
		'406' => array(
			'ko' => '허용할 수 없음',
			'en' => 'Not acceptable'
		),
		'407' => array(
			'ko' => '프록시 인증 필요',
			'en' => 'Proxy authentication required'
		),
		'408' => array(
			'ko' => '요청시간이 지남',
			'en' => 'Request timeout'
		),
		'500' => array(
			'ko' => '서버 애플리케이션 버그',
			'en' => 'Server application error'
		),
		'503' => array(
			'ko' => '서버 애플리케이션 사용불가',
			'en' => 'Service Unavailable'
		),
		'err_is_array' => array(
			'ko' => '값이 배열이 아닙니다',
			'en' => 'is not an array'
		),
		'err_is_file_extention' => array(
			'ko' => '허용되지 않은 파일명입니다',
			'en' => 'Extension is not allowed'
		),
		'err_is_file_maxsize' => array(
			'ko' => '파일 용량이 초과하였습니다',
			'en' => 'Exceed the file size'
		)
	);

	#@ return String
	final public static function getStatusMessage($code){
		return self::$status_args[$code][_LANG_];
	}
}
?>