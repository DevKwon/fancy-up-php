<?php
/** ======================================================
| @Author	: 김종관 | 010-4023-7046
| @Email	: apmsoft@gmail.com
| @HomePage	: http://www.apmsoftax.com
| @Editor	: Eclipse(default)
| @UPDATE	: 2010-02-04
----------------------------------------------------------*/

# 문자 암호화하기
class EncryptManager
{
	private $encrypt_str = '';

	#@ void
	public function init($strz){
		$this->encrypt_str = $strz;
	}

	#@ return String
	# md5
	# enMd5cbf930bbece24547baec219c9089f2eb
	public function encrypt_md5()
	{
		$result ='';
		try{
			$result = md5($this->encrypt_str);
		}catch(Exception $e){
			throw new ErrorException($e->getMessage(),__LINE__);
		}

	return $result;
	}

	#@ return String
	# md5+base64_encdoe
	# y/kwu+ziRUe67CGckIny6w
	public function encrypt_md5_base64(){
		$result ='';
		try{
			$result = preg_replace('/=+$/','',base64_encode(pack('H*',md5($this->encrypt_str))));
		}catch(Exception $e){
			throw new ErrorException($e->getMessage(),__LINE__);
		}
	return $result;
	}

	#@ return String
	# md5+utf8_encode
	# cbf930bbece24547baec219c9089f2eb
	public function encrypt_md5_utf8encode(){
		$result ='';
		try{
			$result = md5(utf8_encode($this->encrypt_str));
		}catch(Exception $e){
			throw new ErrorException($e->getMessage(),__LINE__);
		}
	return $result;
	}

	#@ return String
	# sha512+base64_encode
	# ZDE4OTkyNjE1ZjRlMjgyZmZlMDNjODQxNWQ2ZTZiZDhjN2JkZWRjNDg5MWE5NWU1NDA0Yjk4OTk0MjdmZTc0MmE5ZjU2ZWNhZmQwOWFlNTBlZjVhODNiNTU2NDBiNjcwNzlhZDBkYzE3NWFkMDA3OTU5YjU1YWI2OWJkMzBjMzg=
	public function encrypt_hash_base64($hash='sha512'){
		$result ='';
		try{
			$result = base64_encode(hash($hash, $this->encrypt_str));
		}catch(Exception $e){
			throw new ErrorException($e->getMessage(),__LINE__);
		}
	return $result;
	}
}
?>