<?php
/** ======================================================
| @Author	: 김종관 | 010-4023-7046
| @Email	: apmsoft@gmail.com
| @HomePage	: http://www.apmsoftax.com
| @Editor	: Eclipse(default)
| @UPDATE	: 2010-07-09
----------------------------------------------------------*/

# parnet : 
# purpose : 이미지를(비율에 맞춰) 출력하기 위해
final class ImageView{
	private $picture;
	private $width;
	private $height;
	private $tags = array();

	final public function __construct($picture, $width=0, $height=0){
		# 서버에 있는 사진인지 체크
		if(!file_exists($picture)){
			return false;
		}
		
		$this->picture = $picture;
		$this->width = $width;
		$this->height= $height;
	return true;
	}
	
	# 프라퍼티에 값을 추가하기
	final public function addParams($key, $val){
		$this->tags[$key] = $val;
	}
	
	public function setFitSize(){
		$size = @getimagesize($this->picture);

		//사이즈 수정
		if ($this->width<$size[1] && $this->height<$size[1]){
			$this->width=$size[0];$this->height=$size[1];
		}
		else if($size[0]<$this->width && $size[1]<$this->height){
			$this->width=$size[0];$this->height=$size[1];
		}
		else if($size[0]>$size[1]){
			$this->height=ceil(($size[1]*$this->width)/$size[0]);
		}
		else if($size[0]<$size[1]){      
			$this->width=ceil(($size[0]*$hsize)/$size[1]);
		}
	}
	
	# 프라퍼티 값만 추출하기
	public function __get($propertyName){
		return $this->{$propertyName};
	}
	
	# 이미지 출력
	public function __toString(){
		self::setFitSize();
		$outTag = '';
		$outTag = '<img src="'.str_replace($_SERVER['DOCUMENT_ROOT'],'',$this->picture).'" width="'.$this->width.'" height="'.$this->height.'"';
		foreach($this->tags as $k => $v){
			$outTag .= ' '.$k.'="'.$v.'"';
		}
		$outTag.= ' />';
	return $outTag;
	}
}
?>