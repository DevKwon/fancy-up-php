<?php
#include_once 'TemplateCompiler.class.php';
/** ======================================================
| @Author	: 김종관 | 010-4023-7046
| @Email	: apmsoft@gmail.com
| @HomePage	: http://www.apmsoftax.com
| @Editor	: Eclipse(default)
| @version : 1.0.4
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
	* @var permission	: 폴더 권한
    * @var gzip_level   : gzip 압축 레벨
	*/
	const compile_ext	= 'php';
	const permission	= 0707;
    const gzip_level    = 4;

	/**
	 * @var compile 	: true 강제실행, false 자동
	 * @var compile_dir	: 컴파일 경로
	 * @var safemode	: true php태그코딩지우기, false 유지
	 * @var chgimgsrc	: true 경로변경, false 사용자코딩 유지
     * @var compression : 소스코드 압축, false 소스코드 상태 유지
     * @var gzlib_compression : HTML gzip 압축여부 결정(echo 명령어 없이도 header에 바로 출력됨)
	 */
	private $compile           = false;
	private $compile_dir       = '';
	protected $safemode        = true;
	protected $chgimgpath      = true;
	protected $compression     = true;
	protected $compression_tag = '';
	private $gzlib_compression = true; // gzip 압축출력(헤더에 바로 출력됨)

	# 처음 실행
	public function __construct($filename)
	{
		# 파일 체크
		parent::__construct($filename);
		$this->filename=parent::getRealPath();
		if(!self::isExists($this->filename)) throw new ErrorException(Status::getStatusMessage('404'));
        $this->file_path_arg  = explode('/',parent::getPath());
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
		switch($name){
			case 'compile':
			case 'compile_dir':
			case 'chgimgpath':
			case 'safemode':
				$this->{$name} = $value;
				break;
            case 'compression':
                $this->compression = $value;
                if(!$value) $this->compression_tag = '';
                else $this->compression_tag = "\n";
                break;
            case 'gzlib_compression' :
                $this->gzlib_compression = (boolean)$value;
                break;
		}
	}

    #@ void
    # templateCompiler.class :: funs = array 에 사용함수 목록 추가
    # 사용자 함수 자유롭게 등록 (출력 return용으로 사용할 것)
    public function addFunction($funtion_name){
        #함수명 문자로 입력했는지 체크
        if(!is_string($funtion_name)){
            $funtion_name = strval($funtion_name);
        }

        # 함수 목록에 있는지 체크
        if(!in_array($funtion_name, $funs))
        {
            # 함수 인지 체크
            if (function_exists($funtion_name)) {
                parent::$funs[] = $funtion_name;
            } else {
                throw new ErrorException(__CLASS__.' :: '.__LINE__.' '.strval($funtion_name).' functions are not available');
            }
        }
    }

	#@ void
	# import 파일들 환경설정용 inc.php 파일 만들기
	private function make_import_resource_incfile($files)
	{
		if(is_array($files) && count($files)>0)
		{
			$_inc_name = $this->compile_dir.'/'.str_replace(array('.html','.htm','.tpl'),'.include.inc.'.self::compile_ext,basename($this->filename));
			$source = '<?php'."\n";
			$source.= '$include_args = array();'."\n";
			foreach($files as $inv){
				$source.= '$include_args[] = "'.str_replace(';','',$inv).'";'."\n";
			}
			$source.= '?>';
			self::makeCompileFile($_inc_name,$source);
		}else{# 인클루드 파일 삭제
			$_inc_name=$this->compile_dir.'/'.str_replace(array('.html','.htm','.tpl'),'.include.inc.'.self::compile_ext,basename($this->filename));
			if(self::isExists($_inc_name) === true){
				@unlink($_inc_name);
			}
		}
	}

	#@ return array
	# 인클루드/import/link 관련 처리
	private function _compile_import_resource($files)
	{
		$result = '';
		if(is_array($files) && count($files)>0)
		{
		    $root_dir_depth = count($this->file_path_arg);
			foreach($files as $k => $filename)
			{
				// 파일확장자확인에 따른 작업
				$snum = strrpos($filename,'.');
				$ext = substr($filename,$snum+1);
				switch($ext){
					case 'html':
					case 'htm':
					case 'tpl':
                        # 기존파일 디렉토리 경로 찾기
                        $dir_parent_depth = 0;
                        $real_parent_dir = _ROOT_PATH_;
                        if(strpos($filename,'/')!==false)
                        {
                            $dir_parent_arg = explode('/', $filename);
                            if($dir_parent_arg[0]=='.'){
                                $real_parent_dir = implode('/', $this->file_path_arg).'/';
                                $filename = str_replace('./','', $filename);
                                //echo $real_parent_dir.$filename;
                            }else{
                                foreach($dir_parent_arg as $dir_parent_name){
                                    if($dir_parent_name=='..') $dir_parent_depth++;
                                }
                                if($dir_parent_depth>0){
                                    $real_parent_dir_arg =array_slice($this->file_path_arg, 0, ($root_dir_depth-$dir_parent_depth));
                                    $real_parent_dir = implode('/', $real_parent_dir_arg).'/';
                                    $filename = str_replace('../','', $filename);
                                    #echo $real_parent_dir.$filename;
                                }
                            }
                        }

						# 기존파일정보
						parent::__construct($real_parent_dir.$filename);
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

    #@ return String
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

			# includes 컴파일 및 이름 바꾸기
			self::make_import_resource_incfile($this->includes);
			$infiles=self::_compile_import_resource($this->includes);
			if(is_array($infiles) && count($infiles)>0){
				$source = str_replace($this->includes,$infiles,$source);
			}

			# root tpl 파일만들기
			if(self::makeCompileFile($compile_name,$source)===false) return false;
		}
		# 인클루드되는 파일들의 수정 사항을 체크 및 컴파일링 하기
		else{
			# include.inc.php
			$include_inc_name = $this->compile_dir.'/'.str_replace(array('.html','.htm','.tpl'),'.include.inc.'.self::compile_ext,basename($this->filename));
			if(self::isExists($include_inc_name) === true){
				include_once $include_inc_name;
				if(is_array($include_args)){
					self::_compile_import_resource($include_args);
				}
			}
		}

        ob_start();
		include_once $compile_name;
		$source=ob_get_contents();
        if($this->gzlib_compression && substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')){
            ini_set('zlib.output_compression', self::gzip_level);
        }
		ob_end_clean();
		return $source;
	}

	# 출력 echo
	public function __toString(){
		return self::display();
	}
}
?>