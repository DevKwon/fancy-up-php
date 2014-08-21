<?php
/** ======================================================
| @Author	: 김종관 | 010-4023-7046
| @Email	: apmsoft@gmail.com
| @HomePage	: http://www.apmsoftax.com
| @Editor	: Eclipse(default)
| @version :1.0
----------------------------------------------------------*/

# purpose : sqlite 함수를 활용해 확장한다
# @ define('_CHRSET_','utf-8');
class OutPane
{	
	public function window_location($url,$msg=''){
		if($msg) $outmsg= 'window.alert("'.$msg.'");'."\n";
		$outmsg.= 'window.location="'.$url.'";'."\n";
		self::error_report_prints($outmsg);
	}

	public function history_go($msg,$num=-1){
		$outmsg= 'window.alert("'.$msg.'");'."\n";
		$outmsg.= 'history.go('.$num.');'."\n";
		self::error_report_prints($outmsg);
	}

	public function window_close($msg=''){
		if($msg) $outmsg= 'window.alert("'.$msg.'");'."\n";
		$outmsg.= 'top.close();'."\n";
		self::error_report_prints($outmsg);
	}

	public function opener_location($url){
		$outmsg.= 'opener.location.href="'.$url.'";'."\n";
		$outmsg.= 'window.close();';
		self::error_report_prints($outmsg);
	}
	
	# 자바스크립트 prompt 창을 통해 데이타 값 받기
	public function input_prompt($title,$defaultval=''){
		$title = self::checkSetCharet($title);
		$defaultval = self::checkSetCharet($defaultval);
		
		$outmsg = '';
		$outmsg.= 'var inputmsg;'."\n";
		$outmsg.= 'inputmsg = prompt("'.$title.'","'.$defaultval.'");'."\n";
		$outmsg.= 'document.write(inputmsg);'."\n";
		self::error_report_prints($outmsg);
	}
	
	# 문자 출력 값이 utf-8인지 체크 후 변환하기
	public function checkSetCharet($msg){
		# 전송된 값을 원하는 문자셋으로 변경
		if(iconv(_CHRSET_,_CHRSET_,$msg)==$msg)
			return $msg;
		else
			return iconv('euc-kr',_CHRSET_,$msg);
	}
	
	public function error_report_prints($outmsg){
		$printMsg = '<meta http-equiv="Content-Type" content="text/html; charset='._CHRSET_.'" />'."\n";
		$printMsg .= '<script type="text/javascript" language="javascript">'."\n";
		$printMsg .= $outmsg;
		$printMsg .= '</script>';
		echo $printMsg;
		exit;
	}
}
?>