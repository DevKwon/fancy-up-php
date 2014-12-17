<?php
/** ======================================================
| @Author	: 김종관 | 010-4023-7046
| @Email	: apmsoft@gmail.com
| @HomePage	: http://www.apmsoftax.com
| @Editor	: Eclipse(default)
| @UPDATE	: 2010-03-09
----------------------------------------------------------*/

class UrlPathRedirect
{
	private $tracks = array();
	private $track_length = 0;
	private $base_url = '';
	private $index_page = 'index.php';
	private $redirect_urls = array();

	# void
	# 추가 기본 경로 지정
	public function __construct($base_directory)
	{
		# gg
		$rurls = $_SERVER['PATH_INFO'];
		$length = 0;
		if(!empty($rurls)){
			$this->tracks = preg_split('/\//', $rurls, -1, PREG_SPLIT_NO_EMPTY);
			$this->track_length = count($this->tracks);
			#print_r($this->tracks);
		}

		# 경로
		$this->base_url = str_replace($_SERVER['DOCUMENT_ROOT'],'',_ROOT_PATH_);
		if(!empty($base_directory))
			$this->base_url.= $base_directory;
	}

	#@ void
	# 기본페이지 파일명 설정 확장자 포함
	# ex) index.php, index.ax, mail.html
	public function setIndexPage($idxpagename){
		if(!empty($idxpagename)){
			$this->index_page = $idxpagename;
		}
	}

	#@ void
	# member = 'member.php';
	public function setRedirectPath($k,$path){
		$this->redirect_urls[$k] = $path;
	}

	# return array
	public function getTracks(){
		return $this->tracks;
	}

	#@ return string
	public function getUrls($k){
		$result = false;
		if($this->redirect_urls[$k])
			$result = $this->redirect_urls[$k];

	return $result;
	}

	public function getRedirect()
	{
		# 아무값도 없으면 클럽 메인으로 이동
		$redirect_url = $this->base_url.'/'.$this->index_page;
		if($this->track_length>0){
			$dir1 = trim($this->tracks[0]);
			if($this->redirect_urls[$dir1]){
				$redirect_url = $this->base_url.'/'.$dir1.'/'.$this->redirect_urls[$dir1];
			}
		}
	return $redirect_url;
	}
}
?>