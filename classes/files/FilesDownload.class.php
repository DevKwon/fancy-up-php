<?php
/** ======================================================
| @Author	: 김종관 | 010-4023-7046
| @Email	: apmsoft@gmail.com
| @HomePage	: http://www.apmsoftax.com
| @Editor	: Eclipse(default)
| @UPDATE	: 2010-04-13
----------------------------------------------------------*/

# parnet : Files
# purpose : 파일 다운로드
class FilesDownload
{
	private $filename;
	
	public function __construct($dirs, $filenamez){
		if(!$filenamez)
			Out::prints('파일명이 없습니다');
		
		#디렉토리에 특수문자 체크
		if (!preg_match("/[^a-z0-9_-]/i",$dirs)){
		   Out::prints('디렉토리에 특수문자를 사용할 수 없습니다');
        }
		
		# 특수문자 체크
        if (preg_match("/[^\xA1-\xFEa-z0-9._-]|\.\./i",urldecode($filenamez))){
        	Out::prints('파일이름에 특수문자를 사용할 수 없습니다');
        }
		
		# 서버에 파일 존애 여부 체크
		if(!file_exists($dirs.'/'.$filenamez))
			Out::prints('파일이 존재하지 않습니다');
		
		# 파일 풀네임
		$this->filename = $dirs.'/'.$filenamez;
	}
	
	# header
	public function download($title){
		header("Content-type:application/octet-stream");
        header("Cache-control: private");
        header("Content-Disposition:attachment;filename=\"".$title."\"");
        header("Content-Transfer-Encoding:binary");
        header("Pragma:no-cache");
        //header("Expires:0");
 
        if(is_file($this->filename)) $fp=fopen($this->filename,'r');
        if(!fpassthru($fp)) fclose($fp);
        exit;
	}
}	
?>