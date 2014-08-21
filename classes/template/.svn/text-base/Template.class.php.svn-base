<?php
#include_once 'TemplateCompiler.class.php';
/** ======================================================
| @Author	: 김종관 | 010-4023-7046
| @Email	: apmsoft@gmail.com
| @HomePage	: http://www.apmsoftax.com
| @Editor	: Eclipse(default)
| @version : 1.0.1
----------------------------------------------------------*/

# purpose : MVC 패턴목적, 디자인과 프로그램의 분리
class Template extends TemplateCompiler implements ArrayAccess
{
	/**
	* var filename;	# 파일명
	* var filemtime;# 파일수정시간
	*/
	private $filename;
	private $filemtime= 0;
	
	/**
	* @var compile_ext	: 파일 저장 확장자명
	* @var cache_dir	: 캐슁파일 디렉토리명
	* @var permission	: 폴더 권한
	*/
	const compile_ext	= 'php';
	const cache_dirname	= 'cache_';
	const permission	= 0707;
	
	/**
	 * @var compile 	: true 강제실행, false 자동
	 * @var compile_dir	: 컴파일 경로
	 * @var cache		: true 강제캐슁, false 안함
	 * @var cache_dir	: 캐슁 경로
	 * @var safemode	: true php태그코딩지우기, false 유지
	 * @var chgimgsrc	: true 경로변경, false 사용자코딩 유지
	 */
	private $compile 		= false;
	private $compile_dir	= '';
	private $cache 			= false;
	private $cache_dir;
	protected $safemode 	= true;
	protected $chgimgpath= false; 
	
	# 처음 실행 
	public function __construct($filename)
	{
		# 파일 체크
		parent::__construct($filename);
		$this->filename=parent::getRealPath();
		if(!self::isExists($this->filename)) throw new ErrorException(Status::getStatusMessage('404'));
		$this->filemtime=parent::getMTime();
		
		# 기본경로 설정
		$this->compile_dir=$_SERVER['DOCUMENT_ROOT'];
	}
	
	#@ interface : ArrayAccess
	public function offsetSet($offset, $value){
		if(is_array($value)){
			if(isset($this->variables[$offset])) $this->var_[$offset] = array_merge($this->var_[$offset],$value);
			else $this->var_[$offset] = $value;
		}
		else{ $this->var_[$offset] = $value; }
	}

	#@ interface : ArrayAccess
	public function offsetExists($offset){
		if(isset($this->var_[$offset])) return isset($this->var_[$offset]);
		else return isset($this->var_[$offset]);
	}

	#@ interface : ArrayAccess
	public function offsetUnset($offset){
		if(self::offsetExist($offset)) unset($this->var_[$offset]);
		else unset($this->var_[$offset]);
	}

	#@ interface : ArrayAccess
	public function offsetGet($offset) {
		return isset($this->var_[$offset]) ? $this->var_[$offset] : $this->var_[$offset];
	}

	# 컴파일된 파일 만들기
	private function makeCompileFile($filename,$source){
		if(!is_resource($fp=fopen($filename,"w"))) return false;
		if(fwrite($fp,$source)===false) return false;
		if(fclose($fp)===false) return false;

		if(!@chmod($filename,self::permission)) return false;
		#if(!@chown($filename,getmyuid())) return false;
	return true;
	}
	
	# 폴더 만들기
	private function makeDirs($newdirname){
		$result = true;
		
		$dirObj = new DirInfo($newdirname);
		$dirObj->makesDir();
	return $result;
	}
	
	# 로컬 파일인지 체크
	public function isExists($filename){
		if(!file_exists($filename)) return false;
	return true;
	}
	
	# 디렉토리인지 확인
	public function isDir($dir){
		if(!is_dir($dir)) return false;
	return true;
	}
	
	# 프라퍼티 값 리턴받기
	public function __get($propertyName){
		if(property_exists(__CLASS__,$propertyName)){
			return $this->{$propertyName};
		}
	}
	
	# compile,cache,chgimgpath 설정변경
	public function __set($name,$value){
		if(!empty($value)){
			switch($name){
				case 'compile':
				case 'compile_dir':
				case 'cache':
				case 'cache_dir':
				case 'chgimgpath':
				case 'safemode':
					$this->{$name} = $value;
					break;
			}
		}
	}
	
	# 인클루드 관련 처리
	private function includes_compile(){
		$result = '';
		if(is_array($this->includes) && count($this->includes)>0){
			foreach($this->includes as $k => $filename)
			{
				// 파일확장자확인에 따른 작업
				$snum = strrpos($filename,'.');
				$ext = substr($filename,$snum+1);
				switch($ext){
					case 'html':
					case 'htm':
					case 'tpl':
						# 기존파일정보
						parent::__construct(_ROOT_PATH_.$filename);
						$filename = str_replace("\\",'/',parent::getRealPath());
						$filemtime = parent::getMTime();
		
						# 컴파일정보
						$current_compile_dir =$this->compile_dir;
						$compile_name = $current_compile_dir.'/'.self::setCompileName($filename);
						parent::__construct($compile_name);
						$compile_mtime = 1;
						if($this->compile ===false){
							if(self::isExists($compile_name)===true){
								$compile_mtime = parent::getMTime();
							}
						}

						$result[$k] = $compile_name;
						if($filemtime>$compile_mtime){
							if(self::makeDirs($current_compile_dir) === true){
								$source= parent::compile($filename);
								if(self::makeCompileFile($compile_name,$source)===true) $result[$k] = $compile_name;
							}
						}
					break;
					case 'php': 
						$result[$k] = $filename;
						break;
				}
			}
		}
	return $result;
	}
	
	# 컴파일 작명
	private function setCompileName($filename){
		$compile_name = basename($filename);
		return str_replace(array('.html','.htm','.tpl'),'.'.self::compile_ext,$compile_name);
	}
	
	# 출력 return 
	public function display()
	{
		$source = '';
		
		# 컴파일 된 파일과 시간비교
		$current_compile_dir =$this->compile_dir;
		$compile_name = $current_compile_dir.'/'.self::setCompileName($this->filename);
		parent::__construct($compile_name);
		$compile_mtime = 1;
		if($this->compile ===false){
			if(self::isExists($compile_name)===true){
				$compile_mtime = parent::getMTime();
			}
		}

		// 컴파일 실행
		if($this->filemtime>$compile_mtime)
		{
			if(self::makeDirs($current_compile_dir) === false) return false;

			//echo '<!--compile...-->';
			$source.= parent::compile($this->filename);

			# includes 파일 체크용 inc.php 파일 만들기
			if(is_array($this->includes) && count($this->includes)>0)
			{
				$compile_inc_name = $this->compile_dir.'/'.str_replace(array('.html','.htm','.tpl'),'.inc.'.self::compile_ext,basename($this->filename));
				$inc_d = '<?php'."\n";
				$inc_d.= '$in_args = array();'."\n";
				foreach($this->includes as $inv){
					$inc_d.= '$in_args[] = "'.str_replace(';','',$inv).'";'."\n";
				}
				$inc_d.= '?>';
				$fp = fopen($compile_inc_name,"w");
				fwrite($fp,$inc_d);
				fclose($fp);
			}else{# 인클루드 파일 삭제
				$compile_inc_name=$this->compile_dir.'/'.str_replace(array('.html','.htm','.tpl'),'.inc.'.self::compile_ext,basename($this->filename));
				if(self::isExists($compile_inc_name) === true){
					@unlink($compile_inc_name);
				}
			}

			# includes 컴파일 및 이름 바꾸기
			$infiles=self::includes_compile();
			if(is_array($infiles) && count($infiles)>0){
				$source = str_replace($this->includes,$infiles,$source);
			}
			if(self::makeCompileFile($compile_name,$source)===false) return false;
		}
		# 인클루드되는 파일들의 수정 사항을 체크 및 컴파일링 하기
		else{
			$compile_inc_name = $this->compile_dir.'/'.str_replace(array('.html','.htm','.tpl'),'.inc.'.self::compile_ext,basename($this->filename));
			if(self::isExists($compile_inc_name) === true)
			{
				include_once $compile_inc_name;
				if(is_array($in_args)){
					$this->includes = $in_args;
					self::includes_compile();
				}
			}
		}

		ob_start();
		include_once $compile_name;
		$source=ob_get_contents();
		ob_end_clean();
		return $source;
	}

	# 출력 echo
	public function __toString(){
		return self::display(); 
	}
}
?>