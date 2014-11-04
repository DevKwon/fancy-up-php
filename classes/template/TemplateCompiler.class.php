<?php
/** ======================================================
| @Author	: 김종관
| @Email	: apmsoft@gmail.com
| @Editor	: Eclipse(default)
| version : 1.1.1
----------------------------------------------------------*/

# purpose : MVC 패턴목적, 디자인과 프로그램의 분리
class TemplateCompiler extends TemplateVariable
{
	/**
	 * @var current_id	: 현재 진행중인 루프배열
	 * @var current_depth:현재 진행중인 루프깊이
	 */
	private $current_id		= array();
	private $current_depth	= 0;

	/**
	* @var defines	: 사용자 선언 define 변수값
	* @var globals	: 전역변수에 선언된 값
	*/
	private $defines		= array();
	private $globals		= array('_SERVER','_ENV','_COOKIE','_GET','_POST','_FILES','_REQUEST','_SESSION');

	/**
	 * @var funs : 사용할 수 있는 함수(인클루드 된 사용자 함수 포함) 목록
	 * @var objs : 사용할 수 있는 클래스(사용자 선언 클래스 포함) 목록
	 */
	protected $funs	=array('echo','array','empty','for','if','else','isset','unset','eval','include','include_once','require','require_once','if',
			'str_replace','substr','str_repeat','count','strlen','trim','number_format','strcmp','date');
	protected $objs	=array();

	# compile
	protected function compile($filename)
	{
		$fp=fopen($filename,'rb');
		$source=fread($fp,filesize($filename));
		fclose($fp);

		# user가 선언한 define 키:값
		$const = get_defined_constants(true);
		if(is_array($const['user'])){
			$this->defines = array_keys($const['user']);
		}

		# 사용가능 함수 : 사용자함수포함 사용하고자 하는 함수 목록을 등록하면 됨
		if(function_exists('get_defined_functions')){
			$splfunargs=get_defined_functions();
			$this->funs=array_merge($splfunargs['user'],$this->funs);
		}

		# 사용가능 클래스 : 사용자가 선언한 클래스 포함
		#$this->objs = array_merge(array_values(spl_classes()),get_declared_classes());

		# about utf-8
		$source = preg_replace('/^\xEF\xBB\xBF/', '', $source);

		# php 태그 전부 삭제여부
		if ($this->safemode===true){
			$source=preg_replace("|<\?[^\?\>](.*)[^\?\>]+\?>|U",'',$source);
		}

		# 이미지 경로 자동 바꾸기
		if($this->chgimgpath === true)
		{
			preg_match_all('@<img\s[^>]*src\s*=\s*(["\'])([^\s>]+?)\1@i',$source,$imgs);
			if(is_array($imgs[2]))
			{
				$img_args = self::checkFileDirectoryPath($imgs[2]);
				if(is_array($img_args)){
					foreach($imgs[2] as $num => $src){
						if($img_args[$num]){
							$source = str_replace($src,$img_args[$num],$source);
						}
					}
				}
			}
		}

		# 자바 스크립트/CSS 압축용
		# js
		preg_match_all('@<script\s[^>]*src\s*=\s*(["\'])([^\s>]+?)\1@i',$source,$js);
		if(is_array($js[2]) && count($js[2])>0){
			$js_args = self::checkFileDirectoryPath($js[2]);
			if(is_array($js_args))
			{
				#경로바꾸기
				foreach($js[2] as $js_src){
					if($js_src!=''){
						#min 파일 저장
						$min_filename_bool = self::compressJSMinify($js_src);
						if($min_filename_bool){
							$source = str_replace($js_src,$min_filename_bool,$source);
						}
					}
				}
			}
		}

		#css
		preg_match_all('@<link\s[^>]*href\s*=\s*(["\'])([^\s>]+?)\1@i',$source,$css);
		if(is_array($css[2]) && count($css[2])>0){
			$css_args = self::checkFileDirectoryPath($css[2]);
			if(is_array($css_args))
			{
				#경로바꾸기
				foreach($css[2] as $css_src){
					if($css_src!=''){
						#min 파일 저장
						$min_filename_bool = self::compressCSSMinify($css_src);
						if($min_filename_bool){
							$source = str_replace($css_src,$min_filename_bool,$source);
						}
					}
				}
			}
		}

		preg_match_all("|{%[^%}](.*)[^%}]+%}|U",$source,$match,PREG_PATTERN_ORDER);
		if(is_array($match[0]))
		{
			$matchv= $match[0];
			$count=count($matchv);
			for($i=0;$i<$count;$i++)
			{
				$matchvs=str_replace('{%','',trim($matchv[$i]));
				$matchvs=str_replace('%}','',$matchvs);
				$matchvs=str_replace("\r\n","\n",$matchvs);
				$matchv_arg=explode("\n",$matchvs);
				#print_r($matchv_arg);
				$re_matchvs = '';
				$nlcnt=count($matchv_arg);
				for($j=0;$j<$nlcnt;$j++)
				{
					if(trim($matchv_arg[$j]))
					{
						$callv= trim($matchv_arg[$j]);

						# {%=%} -> <? echo
						if(strpos($callv,'=') !==false){
							if(substr($callv,0,1)=='='){
								$callv='echo '.substr($callv,1);
							}
						}
						switch($callv)
						{
							case ':for':
							case 'endfor':
								if($this->current_depth>2){
									$this->current_depth -= 1;
									array_splice($this->current_id,0,$this->current_depth);
								}else{
									$this->current_id = array();
									$this->current_depth = 0;
								}
								$re_matchvs.='}';
								break;
							case ':if':
							case 'endif':
								$re_matchvs.='}';
								break;
							case ':else:':
							case '/else/':
								$re_matchvs.= '}else{';
								break;
							default :
								$scallvz = preg_replace("/(\"|\')(.*)(\"|\')/","",$callv);
								preg_match_all("/[a-zA-Z0-9_\.\x7f-\xff][a-zA-Z0-9_\.\x7f-\xff]*/",$scallvz,$scriptlet,PREG_PATTERN_ORDER);
								#print_r($scriptlet[0]);
								if(trim($scriptlet[0][0]))
								{
									$scriptletv=$scriptlet[0][0];

									//함수검색
									/**
									@if 문 예제
									{%if(style=='m'):%}수정
									{%:else if(style=='w'):%}	쓰기
									{%:else if(style=='r'):%}답글
									{%:if%}

									@함수문예제
									{%=str_replace("w","",_SERVER.SCRIPT_NAME);%}
									*/
									if( array_search($scriptletv,$this->funs) !== false){
										switch($scriptletv){
											case 'include':
											case 'include_once':
											case 'require':
											case 'require_once':
												$infiles=str_replace('(','',str_replace(')','',$callv));
												$infiles=str_replace('"','',str_replace("'",'',$infiles));
												$infiles=str_replace(";","",$infiles);
												$this->includes[] = trim(str_replace($scriptletv,'',trim($infiles)));
												$re_matchvs.=(strpos($callv,';') !==false) ? $callv."\n" : $callv.';'.$this->compression_tag;
												break;
											case 'else':
												$args = self::getTplVars($scriptlet[0]);
												foreach($args as $av){
													$callv=str_replace($av, self::callback($av), $callv);
												}
												$re_matchvs.=str_replace('):','){',str_replace(':e','}e',$callv));
												break;
											case 'if':
												$args = self::getTplVars($scriptlet[0]);
												foreach($args as $av){
													$callv=str_replace($av, self::callback($av), $callv);
												}
												$re_matchvs.=str_replace('):','){',$callv);
												break;
											case 'for':
												$this->current_id[] = $scriptlet[0][1];
												$this->current_depth= count($this->current_id);

												if($this->current_depth>1){ // 깊이1부터
													$parentid=$this->current_id[$this->current_depth-2];
													$plustag = '$'.$scriptlet[0][1].' = &$'.$parentid.'[$'.$parentid.'i][\''.$scriptlet[0][1].'\'];'.$this->compression_tag;
													$plustag.= '$'.$scriptlet[0][1].'_cnt = count($'.$scriptlet[0][1].');'.$this->compression_tag;

                                                    // 배열 갯수 일반 변수에 자동 저장
                                                    $plustag.='$this->var_[\''.$scriptlet[0][1].'_count\']=$'.$scriptlet[0][1].'_cnt;'.$this->compression_tag;
												}else{ // 깊이 0일때
													$plustag = '$'.$scriptlet[0][1].' = &$this->var_[\''.$scriptlet[0][1].'\'];'.$this->compression_tag;
													$plustag.= '$'.$scriptlet[0][1].'_cnt = count($'.$scriptlet[0][1].');'.$this->compression_tag;
                                                    $plustag.= '$'.$scriptlet[0][1].'_cnt = count($'.$scriptlet[0][1].');'.$this->compression_tag;

                                                    // 배열 갯수 일반 변수에 자동 저장
                                                    $plustag.='$this->var_[\''.$scriptlet[0][1].'_count\']=$'.$scriptlet[0][1].'_cnt;'.$this->compression_tag;
												}
												$re_matchvs.=str_replace($callv,$plustag.'for($'.$scriptlet[0][1].'i=0; $'.$scriptlet[0][1].'i<$'.$scriptlet[0][1].'_cnt; $'.$scriptlet[0][1].'i++){',$callv);

                                                # 순번 자동 index 라는 변수에 등록
                                                $re_matchvs.='$'.$scriptlet[0][1].'[$'.$scriptlet[0][1].'i][\'index\']=$'.$scriptlet[0][1].'i;'.$this->compression_tag;
												break;
											default : // 함수,클래스 전부삭제하고 변수값만 남겨놓는다
												$args = self::getTplVars($scriptlet[0]);
												foreach($args as $av){
													$callv=str_replace($av,self::callback($av),$callv);
												}
												$re_matchvs.= (strpos($callv,';') !==false) ? $callv : $callv.';';
										}
									}
									// 클래스 검색
									else if( array_search($scriptletv,$this->objs) !== false ){
										$args = self::getTplVars($scriptlet[0]);
										if(is_array($args)){
											foreach($args as $av){ $callv=str_replace($av,self::callback($av),$callv);	}
										}
										$re_matchvs.= (strpos($callv,';') !==false) ? $callv."\n" : $callv.';';
									}

									// 변수값 처리
									else{ $re_matchvs.= 'echo '.self::callback($scriptletv).';'; }
								}
						}
					}
				}
    			$matchvs = str_replace($matchvs,$re_matchvs,$matchvs);
    			$source = str_replace($match[0][$i],'<?php '.$matchvs.'?>',$source);
			}
		}

		#html COMPRESS
		if($this->compression)
		{
			# <!-- -->
			$source =preg_replace("/<!--(.*?)-->/is", '', $source );
			# /* */
			$source = preg_replace("/\/\*(.*?)\*\//is",'',$source);
			# // 주석제거
			#$source=preg_replace('/(?<!\S)\/\/(.*)\n/', '', $source);
			$source=preg_replace('/(?<!\S)\/\/\s*[^\r\n]*/', '', $source);
			# # 주석제거
			#$source=preg_replace('/#(.*)\n/', '', $source);

			# 2개 공백
			$source =preg_replace("/(\s\s+)/is", '', $source );
		}
	return $source;
	}

	#@ return boolean | string
	# js 파일 압축 및 min 파일 만들기
	private function compressJSMinify($filename){
		if(strpos($filename, '.min.')!==false) return false;
		if(strpos($filename, '-min')!==false) return false;
		if(strpos($filename, 'http')!==false) return false;

		$real_file = _ROOT_PATH_.$filename;
		$file_basename = basename($real_file);
		$min_file = _ROOT_PATH_.'/'._DATA_.'/min'.str_replace('.js','.min.js',$filename);

		# 디렉토리 만들기
		$dirObj = new DirInfo(_ROOT_PATH_.'/'._DATA_.'/min'.str_replace('/'.$file_basename, '', $filename));
		$dirObj->makesDir();

		# 새로 파일 만들것인지 비교
		if(self::compareFileMTime($real_file, $min_file)){
			$js_source = file_get_contents($real_file, FILE_USE_INCLUDE_PATH);
			$jsmin_source = TemplateLibJSMin::minify($js_source);

			self::makeCompressMinifyFile($min_file, $jsmin_source);
		}
	return str_replace(_ROOT_PATH_, '', $min_file);
	}

	#@ return boolean | string
	# css 파일 압축 및 min 파일 만들기
	private function compressCSSMinify($filename){
		if(strpos($filename, '.min.')!==false) return false;
		if(strpos($filename, '-min')!==false) return false;
		if(strpos($filename, 'http')!==false) return false;

		$real_file = _ROOT_PATH_.'/'.$filename;
		$file_basename = basename($real_file);
		$min_file = _ROOT_PATH_.'/'._DATA_.'/min'.str_replace('.css','.min.css',$filename);

		# 디렉토리 만들기
		$dirObj = new DirInfo(_ROOT_PATH_.'/'._DATA_.'/min'.str_replace('/'.$file_basename, '', $filename));
		$dirObj->makesDir();

		# 새로 파일 만들것인지 비교
		if(self::compareFileMTime($real_file, $min_file)){
			$css_source = file_get_contents($real_file, FILE_USE_INCLUDE_PATH);
			$cssminObj = new TemplateLibCSSMin();
			$cssmin_source = $cssminObj->run($css_source);

			self::makeCompressMinifyFile($min_file, $cssmin_source);
		}
	return str_replace(_ROOT_PATH_, '', $min_file);
	}

	#@ return bool
	#파일 수정 시간 비교
	private function compareFileMTime($real_file, $compile_file){
		$compile_file_mtime = 0;
		if(!file_exists($real_file)) return false;
		$real_file_mtime = filemtime($real_file);

		if(file_exists($compile_file)){
			$compile_file_mtime = @filemtime($compile_file);
		}

		if($real_file_mtime>$compile_file_mtime){
			return true;
		}
	return false;
	}

	# 파일 만들기
	private function makeCompressMinifyFile($filename, $source){
		if(trim($source) !=''){
			$fp=@fopen($filename,'w');
			fwrite($fp,$source);
			fclose($fp);
		}
	}

	# http://, "/" 시작하는 경로는 변경하지 않는다
	private function checkFileDirectoryPath($dirs)
	{
		foreach($dirs as $num => $src)
		{
		    $dirs[$num] = '';

		    # http 경로 제외
			if(strpos($src,'http') ===false)
			{
			    #"/"로 시작하는 경로 제외
				$slarc = substr($src,0,1);
				if($slarc != '/')
				{
					$path_info=pathinfo($src);
					switch(strtolower($path_info['extension']))
					{
						case 'gif':
						case 'jpg':
						case 'jpeg':
						case 'png':
						case 'js':
						case 'css':
                            $dir_parent_depth = 0;
                            $real_parent_dir = '/';
							if(strpos($path_info['dirname'],'/')!==false)
                            {
                                $dir_parent_arg = explode('/', $path_info['dirname']);
                                if($dir_parent_arg[0]=='.'){
                                    $real_parent_dir = implode('/', $this->file_path_arg).'/';
                                    $image_name = str_replace('./','',$path_info['dirname']).'/'.$path_info['basename'];
                                    $real_parent_dir.$image_name;
                                }else{
                                    foreach($dir_parent_arg as $dir_parent_name){
                                        if($dir_parent_name=='..') $dir_parent_depth++;
                                    }
                                    if($dir_parent_depth>0){
                                        $real_parent_dir_arg =array_slice($this->file_path_arg, 0, ($root_dir_depth-$dir_parent_depth));
                                        $real_parent_dir = implode('/', $real_parent_dir_arg).'/';
                                        $image_name = str_replace('../','',$path_info['dirname']).'/'.$path_info['basename'];
                                        #echo $real_parent_dir.$image_name;
                                    }
                                }
                            }

							$dirs[$num] = str_replace($_SERVER['DOCUMENT_ROOT'], '', $real_parent_dir.$image_name);
							break;
					}
				}
			}
		}
	return $dirs;
	}

	# 템플릿 변수 처리
	private function callback($vars)
	{
		$result ='';
		if(strpos($vars,'.') !==false)
		{
			// _DEFINE, _SERVER 절대변수인지 체크
			if(substr($vars,0,1) == '_'){
				$gvalid=substr($vars,0,strpos($vars,'.'));
				if( array_search($gvalid,$this->globals) !== false ){ // 글로벌변수인지확인
					$result = str_replace('.','[\'',$vars);
					$result = '$'.$result.'\']';
				}else{ // define 변수
					$result = str_replace($gvalid.'.','',$vars);
				}
			}
			// for | 배열 문변수 배열 {%name.required%} -> $args[name][required] = true;
			else{
				$queryid = explode('.',$vars);
				//print_r($queryid);
				//Out::prints_ln($vars);
				#배열로 직접 접근
				if($this->var_[$queryid[0]][$queryid[1]])
				{
					$result='$this->var_[\''.$queryid[0].'\']';
					$count=count($queryid);
					for($i=1; $i<$count; $i++)
					{
						$p=$i-1;
						//변수 키호출 {%month.key.0.date%}
						if($queryid[$i] =='key'){
							$k='$'.$queryid[$p].'i';
							$result.='['.$k.']';
						}
						//무조건 i 순번키 적용 {%month.i%}
						else if($queryid[$i] =='i'){
							$k=str_replace($vars,'[$'.$queryid[0].'i]',$vars);
							$result.=$k;
						}
						else{
							$k=$queryid[$i];
							$result.='[\''.$k.'\']';
						}
					}
				}
				#for문변수
				else{
					$result = '$'.$queryid[0].'[$'.$queryid[0].'i][\''.$queryid[1].'\']';
				}
			}
		}else{
			$result = '$this->var_[\''.$vars.'\']';
		}
	return $result;
	}

	# 함수,클래스등 다 지우고 변수만 추출하기
	private function getTplVars($args){
		$result_args = array();
		$objname = '';
		if(is_array($args)){
			foreach($args as $v){
				if( array_search($v,$this->funs) !== false ){
					$objname = $v;
				}
				else if( array_search($v,$this->objs) !== false ){
					$objname = $v;
				}
				else{
					if($objname){
						if(!method_exists($objname,$v)){
							if(!preg_match('/^[0-9]/', $v)){
								$result_args[] = trim($v);
							}
						}
					}else{
						if(!preg_match('/^[0-9]/', $v)){
							$result_args[] = trim($v);
						}
					}
				}
			}
		}
	return $result_args;
	}
}
?>