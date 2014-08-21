<?php
/** ======================================================
| @Author	: 김종관 | 010-4023-7046
| @Email	: apmsoft@gmail.com
| @HomePage	: http://www.apmsoftax.com
| @Editor	: Eclipse(default)
| @UPDATE	: 2010-02-04
----------------------------------------------------------*/

# purpose : 카메라 촬영 정보
class ImageExif 
{
	private $exifargs = array();
	private $setkey_args = array(
		'file'		=> array('FileName','FileSize','FileDateTime','MimeType'),
		'computed'	=> array('Width','Height','ApertureFNumber','FocusDistance','CCDWidth'),
						// 넓이,높이,조리개,촬영거리,CCD
		'ifdo'		=> array('Make','Model','Software'),
		'exif'		=> array('ExposureTime','FNumber','Flash','WhiteBalance','DigitalZoomRatio','ISOSpeedRatings','FocalLength','MeteringMode','DateTimeOriginal'),
						// 노출모드,조리개값,플래시사용여부,화이트발란스,줌,ISO감도,초점거리,측광모드 ,오리지날촬영시간
		'makenote'	=> array('FirmwareVersion','UndefinedTag:0x0095')
						// 펌웨어버전,사용렌즈
	);
	
	# 사진 풀경로
	public function __construct($picture){
		# 로컬 파인인지 체크
		if(!file_exists($picture))
			throw new ErrorReporter('',30);

		# 함수 enable 체크
		if(function_exists(read_exif_data)){
			$this->exifargs = read_exif_data($picture,0,true);
			if($this->exifargs ===false)
				throw new ErrorReporter('');
		}
	}
	
	# 파일 계산
	public function calcuSize($size)
	{
		$result = '';
		if(!empty($size)){
			$result = sprintf("%0.1f KB", ($size/1024));
			if($r>1024){
				$result = sprintf("%0.1f MB", ($r/1024)); //수점 이하가 0.5 는 1로 반올림한다.
			}
		}
	return $result;
	}
	
	# FILE
	public function getFile()
	{
		$result = array();
		if($this->exifargs['FILE']){
			$args =& $this->exifargs['FILE'];
			foreach($this->setkey_args['file'] as $k){
				switch($k){
					case 'FileDateTime': $result[$k] = date("Y년 m월 d일 H:i:s",$args[$k]); break;
					case 'FileSize' : $result[$k] = self::calcuSize($args[$k]); break;
					default : $result[$k]= $args[$k];
				}
			}
		}
	return $result;
	}
	
	# COMPUTED
	public function getComputed()
	{
		$result = array();
		if($this->exifargs['COMPUTED']){
			$args =& $this->exifargs['COMPUTED'];
			foreach($this->setkey_args['computed'] as $k){
				switch($k){
					case 'FocusDistance':
						$result[$k] = $args[$k];
						if(strpos($args[$k],'/') !==false){
							$tmpdistance = explode('/',$args[$k]);
							$result[$k] = ($tmpdistance[0]/$tmpdistance[1]).'mm';
						}
						break;
					case 'CCDWidth':
						$result[$k] = (!empty($args[$k])) ? substr($args[$k],0,5).' mm' : '';
						break;
					default :
						$result[$k] = $args[$k];
				}
			}
		}
	return $result;
	}
	
	# IFDO
	public function getIfdo()
	{
		$result = array();
		if($this->exifargs['IFD0']){
			$args =& $this->exifargs['IFD0'];
			foreach($this->setkey_args['ifdo'] as $k){
				switch($k){
					case 'Make':
						$result[$k] = str_replace('CORPORATION','',$args[$k]);
						break;
					default:
						$result[$k] = $args[$k];	
				}
			}
		}
	return $result;
	}
	
	# EXIF
	public function getExif()
	{
		$result = array();
		if($this->exifargs['EXIF']){
			$args =& $this->exifargs['EXIF'];
			foreach($this->setkey_args['exif'] as $k){
				switch($k){
					case 'Flash': $result[$k] = ($args[$k]==1) ? 'ON' : 'OFF'; break;
					case 'ExposureTime':
						$result[$k] = $args[$k];
						if(strpos($args[$k],'/') !==false){
							$tmpexpo = explode('/',$args[$k]);
							$result[$k] = ($tmpexpo[0]/$tmpexpo[0]).'/'.($tmpexpo[1]/$tmpexpo[0]).'s';
						}
						break;
					case 'FocalLength':
						$result[$k] = $args[$k];
						if(strpos($args[$k],'/') !==false){
							$tmpfocal	= explode('/',$args[$k]);
							$result[$k] = ($tmpfocal[0]/$tmpfocal[1]).'mm';
						}
						break;
					default: $result[$k] = $args[$k];
				}
			}
		}
	return $result;
	}
	
	# MAKENOTE
	public function getMakenote()
	{
		$result = array();
		if($this->exifargs['MAKENOTE']){
			$args =& $this->exifargs['MAKENOTE'];
			foreach($this->setkey_args['makenote'] as $k){
				$result[$k] = $args[$k];
			}
		}
	return $result;
	}
	
	# 한번에 추출하기
	public function getAvailable(){
		$args = array();
		if(count($this->exifargs)>0){
			foreach($this->setkey_args as $k => $v){
				$methodName = 'get'.ucwords($k);
				$args += call_user_func_array(array(&$this, $methodName), array());
			}
		}
	return $args;
	}
}
?>