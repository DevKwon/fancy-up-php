<?php
/* ======================================================
| @Author	: 김종관 | 010-4023-7046
| @Email	: apmsoft@gmail.com
| @HomePage	: http://www.apmsoftax.com
| @Editor	: Eclipse(default)
| @UPDATE	: 2010-03-17
----------------------------------------------------------*/
class PagingRelation
{
	private $url;					# 이동주소
	private $urlQuery;				# 주소쿼리
	private $urlQueryArray;			# 배열
	private $page		= 1;		# 현제 페이지
	private $totalPage	= 0;		# 총페이지
	private $qLimitStart= 0;		# query LIMIT [0],[]
	private $qLimitEnd	= 0;		# query LIMIT [],[0]
	private $totalBlock	= 0;
	private $blockCount	= 0;
	private $blockStartPage	= 0;	# 블록 시작페이지
	private $blockEndPage	= 0;	# 블록 끝페이지
	private $pageLimit = 0;
	private $totalRecord = 0;		# 총레코드 수

	# 출력 div id 및 추가 태그 설정
	private $tags = array();			# 추가 태그 등록
	private $tags_txt = '';
	private $divElementId = 'paging'; # 페이징 둘러싸고 있는 div 아이디명
	private $current_page_tags = ''; #현재블록 추가 태그

	private $numeric_prefix = '';

	# 페이지 출력명 [다국어]
	private $prev_name = array(
			'ko' =>'이전',
			'en' =>'prev'
	);
	private $next_name = array(
			'ko' =>'다음',
			'en' =>'next'
	);
	private $rewind_name = array(
			'ko' =>'처음',
			'en' =>'first page'
	);
	private $last_name = array(
			'ko' =>'마지막',
			'en' =>'last page'
	);

	/**1
	 * 필요한 기본값 등록
	 * @param $url			: 기본경로(./list.php || list.php?a=1&b=2)
	 * @param $totalRecord	: 총 레코드 갯수
	 * @param $page			: 요청 페이지
	 */
	public function __construct($url, $totalRecord, $page){
		$this->url = $url;
		$this->totalRecord	= $totalRecord;
		$this->page			= (!empty($page)) ? $page : 1;
	}

	# 2 한페이지에 출력할 레코드 갯수
	public function setQueryCount($pagecount=10){
		$this->totalPage= @ceil($this->totalRecord/$pagecount);

		if($this->totalRecord ==0){
			$this->qLimitStart= 0;
			$this->qLimitEnd= 0;
		}else{
			$this->qLimitStart= $pagecount * ($this->page-1);
			$this->qLimitEnd=$pagecount;
		}

		$this->totalBlock	= ceil($this->totalPage/$pagecount);
		$this->blockCount	= ceil($this->page/$pagecount);
		$this->blockStartPage= ($this->blockCount-1) * $pagecount;
		$this->blockEndPage	= $this->blockCount*$pagecount;

		if($this->totalBlock <=$this->blockCount) {
			$this->blockEndPage = $this->totalPage;
		}

		$this->pageLimit = $pagecount;
	}

	/** 3
	 * @void
	 * url 뒤에 붙일 http query 값
	 * @param $params
	 * @param $numeric_prefix
	 */
	public function setBuildQuery($params='',$numeric_prefix='')
	{
		# 배열
		if(is_array($params) && count($params)>0){
			foreach($params as $pk=>$pv){
				if(!$pv){
					unset($params[$pk]);
				}else{
					$this->urlQueryArray[$pk]=$pv;
				}
			}

			$this->urlQuery = http_build_query($this->urlQueryArray, $numeric_prefix);
		}

		if(!$numeric_prefix){
			if(strpos($this->url,'?') !==false) $this->url.= $this->urlQuery;
			else $this->url.= '?'.$this->urlQuery;
		}else{
			$this->numeric_prefix = 'js';
		}
	}

	# 4 @void
	# 태그 추가 등록, css 클래스 명이라던지 추가 기능
	# <a href="" class="paging">
	public function addTags($name,$value){
		if(!$this->tags[$name]){
			$this->tags[$name]= $value;
			$this->tags_txt .= $name.'="'.$value.'" ';
		}
	}

	# 현재페이지 출력
	public function currentPage()
	{
		$page_url = str_replace('page='.$this->page,'',$this->url);
		$s_page =$this->blockStartPage + 1;
		for($i = $s_page; $i<=$this->blockEndPage; $i++)
		{
			$pagenum = $i;
			$cur_page_tag = '';
			if($this->page == $i){
				$pagenum = $i;
				$cur_page_tag = $this->current_page_tags;
			}
			if($this->numeric_prefix=='js')
				$result.= '<li><a href="#" onclick="'.$this->url.'(\''.$this->urlQuery.'&page='.$i.'\');return false;" '.$cur_page_tag.' '.$this->tags_txt.'>'.$pagenum.'</a></li>';
			else
				$result.= '<li><a href="'.$page_url.'&page='.$i.'" '.$cur_page_tag.' '.$this->tags_txt.'>'.$pagenum.'</a></li>';
		}
	return $result;
	}

	# return 이전페이지
	public function prevPage(){
		$result = '';
		$page_url = str_replace('page='.$this->page,'',$this->url);
		if($this->blockCount > 1){
			$prepage = $this->pageLimit * ($this->blockCount - 1);
			if($this->numeric_prefix=='js')
				$result ='<li><a href="#" onclick="'.$this->url.'(\''.$this->urlQuery.'&page='.$prepage.'\');return false;" '.$cur_page_tag.' '.$this->tags_txt.'>'.$pagenum.'</a></li>';
			else
				$result ='<li><a href="'.$page_url.'&page='.$prepage.'" '.$this->tags_txt.'>'.$this->prev_name[_LANG_].'</a></li>';
		}
	return $result;
	}

	# return 다음페이지
	public function nextPage(){
		$result = '';
		$page_url = str_replace('page='.$this->page,'',$this->url);
		if($this->blockCount< $this->totalBlock)
		{
			$nextpage	= $this->pageLimit + 1;
			if($this->numeric_prefix=='js')
				$result ='<li><a href="#" onclick="'.$this->url.'(\''.$this->urlQuery.'&page='.$nextpage.'\');return false;" '.$cur_page_tag.' '.$this->tags_txt.'>'.$pagenum.'</a></li>';
			else
				$result = '<li><a href="'.$page_url.'&page='.$nextpage.'" '.$this->tags_txt.'>'.$this->next_name[_LANG_].'</a></li>';
		}
	return $result;
	}

	# return 처음페이지
	public function rewindPage(){
		$result = '';
		$page_url = str_replace('page='.$this->page,'',$this->url);
		if($this->page > 1){
			if($this->numeric_prefix=='js')
				$result ='<li><a href="#" onclick="'.$this->url.'(\''.$this->urlQuery.'&page=1\');return false;" '.$cur_page_tag.' '.$this->tags_txt.'>'.$pagenum.'</a></li>';
			else
				$result = '<li><a href="'.$page_url.'&page=1" '.$this->tags_txt.'>'.$this->rewind_name[_LANG_].'</a></li>';
		}
	return $result;
	}

	# return 마지막페이지
	public function lastPage(){
		$result = '';
		$page_url = str_replace('page='.$this->page,'',$this->url);
		if($this->blockEndPage > 1 && $this->blockEndPage != $this->page){
			if($this->numeric_prefix=='js')
				$result ='<li><a href="#" onclick="'.$this->url.'(\''.$this->urlQuery.'&page='.$this->totalPage.'\');return false;" '.$cur_page_tag.' '.$this->tags_txt.'>'.$pagenum.'</a></li>';
			else
				$result ='<li><a href="'.$page_url.'&page='.$this->totalPage.'" '.$this->tags_txt.'>'.$this->last_name[_LANG_].'</a></li>';
		}
	return $result;
	}

	# 프라퍼티 값 포함한 가져오기
	public function __get($propertyName){
		if(property_exists(__CLASS__,$propertyName)){
			return $this->{$propertyName};
		}
	}

	# 프라퍼티 값 변경하기
	public function __set($propertyName, $valuez){
		if(property_exists(__CLASS__,$propertyName)){
			return $this->{$propertyName} = $valuez;
		}
	}

	# @return
	# 출력
	public function printOut(){
		$result = '';
		#$result.= self::rewindPage();
		$result.= self::prevPage();
		$result.= self::currentPage();
		$result.= self::nextPage();
		#$result.= self::lastPage();
	return $result;
	}

	# @return
	# obj 출력
	public function __toString(){
		return self::printout();
	}
}
?>