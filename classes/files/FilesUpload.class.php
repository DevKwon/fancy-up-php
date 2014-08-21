<?php
/** ======================================================
| @Author	: 김종관 | 010-4023-7046
| @Email	: apmsoft@gmail.com
| @HomePage	: http://www.apmsoftax.com
| @Editor	: Eclipse(default)
| @UPDATE	: 2010-04-13
----------------------------------------------------------*/

# parnet : Files
# purpose : 파일 전송 값 처리를 목적으로 함
class FilesUpload
{	
	private $ext_args = array();
	private $upfilemaxsize = 1048576; // 1M (1024 * 1024)
	private $_files = array('tmpfile'=>'','filename'=>'','size'=>0);
	private $_upload_error_number = 0;
	private $_upload_error = array(
		'ko'=>array(
				0=>'',
				1=>"업로드한 파일이 php.ini upload_max_filesize 지시어보다 큽니다", 
				2=>"업로드한 파일이 HTML 폼에서 지정한 MAX_FILE_SIZE 지시어보다 큽니다",
				3=>"파일이 일부분만 전송되었습니다",
				4=>"파일이 전송되지 않았습니다",
				6=>"Missing a temporary folder",
				7=>'Failed to write file to disk',
				8=>'File upload stopped by extension',
				999=>'정상적인 업로드 파일이 아닙니다'
			),
		'en'=>array(
				0=>'',
				1=>"The uploaded file exceeds the upload_max_filesize directive in php.ini", 
				2=>"The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",
				3=>"The uploaded file was only partially uploaded",
				4=>"No file was uploaded",
				6=>"Missing a temporary folder",
				7=>'Failed to write file to disk',
				8=>'File upload stopped by extension',
				999=>'is not uploaded file'
			)
	);
	
	# 정상적인 업로드 파일인지 체크
	# _FILES['파일명']['tmp_name']
	public function __construct($tmpfile, $filename, $upfiles_size, $errornum){
		if(!empty($filename))
		{
			if($errornum > 0){
				$this->_upload_error_number = $errornum;
			}else{			
				// 파일업로드 확인
				if(!self::is_upload_files($tmpfile)){
					$this->_upload_error_number = 999;
				}else{
					# 업로드 파일정보
					$this->_files = array(
						'tmpfile' => $tmpfile,
						'filename'=> $filename,
						'size'	=> $upfiles_size
					);
				}
			}
		}
	}
	
	#@ void
	# 전송이 허용된 파일 확장자 등록
	# 방법1 : gif,jpeg,txt,png
	# 방법2 : gif|jpeg|txt|png
	# 방법3 : gif
	public function setFileExtention($ext){
		if(!empty($ext)){
			$ext = str_replace('|',',',$ext);
			if(strpos($ext, ',') !==false){
				$tmpargs = explode(',',$ext);
				$this->ext_args = array_merge($this->ext_args,$tmpargs);
			}else{
				$this->ext_args[] = $ext;
			}
		}
	}
	
	#@ void
	# 최대 업로드 전송 허용 사이즈
	# 8(M),12(M),100(M)
	public function setMaxFilesize($maxsize){
		$this->upfilemaxsize = (int)(1024 * 1024 * $maxsize);
	}
	
	#@ return boolean
	// 허용된 업로드 파일인지 체크
	public function isFileExtention(){		
		$ext = implode('|',$this->ext_args);
		if(!preg_match("/(?:{$ext})$/i", basename($this->_files['filename']))) return false;
	return true;
	}
	
	#@ return boolean
	# 설정한 용량값을 넘어쓴지 체크
	public function isMaxFilesize(){
		if($this->_files['size'] >= $this->upfilemaxsize) return false;
	return true;
	}
	
	#@ return boolean
	# 업로드된 파일인지 체크
	private function is_upload_files($tmpfile){
		if(!is_uploaded_file($tmpfile)) return false;
	return true;
	}
	
	#@ return String boolean
	# 업로드 파일 복사하기
	public function move_upload_files($tmpfile, $sfilename){
		if(!move_uploaded_file($tmpfile, $sfilename)) return false;
	return $sfilename;
	}
	
	#@ return String boolean
	# 파일 복사하기
	public function copy_upload_files($savefilename){
		if(is_array($this->_files))
		{
			if(!self::isFileExtention())
				throw new ErrorException(Status::getStatusMessage('err_is_file_extention'));
			
			// 허용용량(max)인지 체크
			if(self::isMaxFilesize())
				throw new ErrorException(Status::getStatusMessage('err_is_file_maxsize'));
			
			// 복사하기
			if( ($sfilename = self::move_upload_files($this->_files['tmpfile'],$savefilename)) !== false)
				return $sfilename;
		}
	return false;
	}

	#@ return String
	# 파일 확장자 추출
	public function getExtName(){
		$tmpfile = basename($this->_files['filename']);
		$count= strrpos($tmpfile,'.');
		$extention= strtolower(substr($tmpfile, $count+1));
	return $extention;
	}

	#@ return String
	# 에러메세지 가져오기
	public function getUpfileErrorMsg(){
		return $this->_upload_error[_LANG_][$this->_upload_error_number];
	}
}
?>