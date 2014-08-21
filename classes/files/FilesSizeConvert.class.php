<?php
/** ======================================================
| @Author	: 김종관 | 010-4023-7046
| @Email	: apmsoft@gmail.com
| @HomePage	: http://www.apmsoftax.com
| @Editor	: NotePad++
| @UPDATE	: 2010-04-28
----------------------------------------------------------*/

class FilesSizeConvert
{
	private $filename;
	private $filesize_bytes = 0;
	private $convert_type = array('B', 'Kb', 'MB', 'GB', 'TB', 'PB');
	
	public function __construct($filenamez){
		if(!$filenamez) return false;
		if(!file_exists($filenamez)) return false;
		
		$this->filename = $filenamez;
		$this->filesize_bytes = filesize($this->filename);
	}
	
	#@int
	public function setFileSizeBytes($bytes){
		$this->filesize_bytes = $bytes;
	}
	
	#@int
	public function getFileSizeBytes(){
		return $this->filesize_bytes;
	}
	
	#@String
	public function getFileSizeConvert(){
        $e = floor(log($this->filesize_bytes)/log(1024));
        return sprintf('%.2f '.$this->convert_type[$e], ($this->filesize_bytes/pow(1024, floor($e)))); 
	}
}	
?>