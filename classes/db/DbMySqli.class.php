<?php
/** ======================================================
| @Author	: 김종관 | 010-4023-7046
| @Email	: apmsoft@gmail.com
| @HomePage	: http://www.apmsoftax.com
| @Editor	: Eclipse(default)
| @UPDATE	: 2010-02-16
----------------------------------------------------------*/

# Parent : MySqli
# Parent : DBSwitch
# purpose : mysqli을 활용해 확장한다
class DbMySqli extends mysqli implements DbSwitch,ArrayAccess
{
	private $params = array();
	
	# dsn : host:dbname = localhost:dbname
	public function __construct($dsn='',$user='',$passwd='',$chrset='utf8')
	{
		# 데이타베이스 접속
		if(!empty($dsn)){
			$dsn_args = explode(':',$dsn);
			parent::__construct($dsn_args[0],$user,$passwd,$dsn_args[1]);
		}else{//config.inc.php > config.db.php
			parent::__construct(_DB_HOST_,_DB_USER_,_DB_PASSWD_,_DB_NAME_);
		}

		if (mysqli_connect_error()){
			throw new ErrorException(mysqli_connect_error(),mysqli_connect_errno());
		}

		# 문자셋
		$chrset_is = parent::character_set_name();
		if(strcmp($chrset_is,$chrset)) parent::set_charset($chrset);
	}
	
	#@ interface : ArrayAccess
	# 사용법 : $obj["two"] = "A value";
	public function offsetSet($offset, $value) {
		$this->params[$offset] = $value;
	}

	#@ interface : ArrayAccess
	# 사용법 : isset($obj["two"]); -> bool(true)
	public function offsetExists($offset) {
		return isset($this->params[$offset]);
	}
	
	#@ interface : ArrayAccess
	# 사용법 : unset($obj["two"]); -> bool(false)
	public function offsetUnset($offset) {
		unset($this->params[$offset]);
	}
	
	#@ interface : ArrayAccess
	# 사용법 : $obj["two"]; -> string(7) "A value"
	public function offsetGet($offset) {
		return isset($this->params[$offset]) ? $this->params[$offset] : null;
	}

	#@ return int
	# 총게시물 갯수 추출
	public function get_total_record($table, $where=""){
		$wh = ($where) ? " WHERE ".$where : '';
		if($result = parent::query("SELECT count(*) FROM `".$table."`".$wh)){
			$row = $result->fetch_row();
			return $row[0];
		}
	return 0;
	}
	
	#@ return int
	# 총게시물 쿼리문에 의한 갯수 추출
	public function get_total_query($qry){
		if($result = parent::query($qry)){
			$row = $result->fetch_row();
			return $row[0];
		}
	return 0;
	}

	// 하나의 레코드 값을 가져오기
	public function get_record($field, $table, $where){
		$where = ($where) ? " WHERE ".$where : '';
		$qry = "SELECT ".$field." FROM `".$table."` ".$where;
		if($result = $this->query($qry)){
			$row = $result->fetch_assoc();
			return $row;
		}
	return false;
	}

	# @ interface : DBSwitch
	// SELECT ? FROM kc_bbs_table WHERE id='?' AND del_date !='1' ORDER BY fid ASC LIMIT ?,?
	// query_bind_params($bbs_qry,'ssdd',array('*',$bbs_id,0,10));
	public function query_bind_params($query,$bind,$args=array())
	{
		if(strpos($query,'?') !==false)
		{
			preg_match_all("/([?])+/s",$query,$matches);
			if(is_array($matches))
			{
				# 바인드 갯수 여부 확인
				$len =strlen($bind);
				$matches_count=count($matches[0]);
				if($len !=$matches_count)
					throw new ErrorException('Parameter mismatch');

				# 쿼리문장 배열로나눔
				$qry_args=preg_split('/[?]/s', $query);
				$qry_count=count($qry_args);

				# 데이타 형식 체크 및 쿼리문 만들기
				$result='';
				for($i=0;$i<$qry_count;$i++){
					if($args[$i]){
						$bindtype=substr($bind,$i,1);
						switch($bindtype){
							case 's': if(!is_string($args[$i])) strval($args[$i]); break;
							case 'd': if(!is_int($args[$i])) intval($args[$i]); break;
							case 'u': if(!is_double($args[$i])) doubleval($args[$i]); break;
							case 'f': if(!is_float($args[$i])) floatval($args[$i]); break;
						}
					}
					$result.=$qry_args[$i].$args[$i];
				}
			return $result;
			}
		}else{
			return $query;
		}
	}

	# @ interface : DBSwitch
	public function query($query){
		$result = parent::query($query);
		if( !$result ){
			throw new ErrorException(mysqli_error(&$this).' '.$query,mysqli_errno(&$this));
		}
	return $result;
	}

	# @ interface : DBSwitch
	# args = array(key => value)
	# args['name'] = 1, args['age'] = 2;
	public function insert($table){
		$fieldk = '';
		$datav	= '';
		if(count($this->params)<1) return false;
		foreach($this->params as $k => $v){
			$fieldk .= sprintf("`%s`,",$k);
			$datav .= sprintf("'%s',", parent::real_escape_string($v));
		}
		$fieldk	= substr($fieldk,0,-1);
		$datav	= substr($datav,0,-1);
		$this->params = array(); #변수값 초기화
		
		$query= sprintf("INSERT INTO `%s` (%s) VALUES (%s)",$table,$fieldk,$datav);
		$this->query($query);
	}
    
	# @ interface : DBSwitch
	public function update($table,$where)
	{
		$fieldkv = '';
		
		if(count($this->params)<1) return false;
		foreach($this->params as $k => $v){
			$fieldkv .= sprintf("`%s`='%s',",$k,parent::real_escape_string($v));
		}
		$fieldkv = substr($fieldkv,0,-1);
		$this->params = array(); #변수값 초기화
		
		$query= sprintf("UPDATE `%s` SET %s WHERE %s",$table,$fieldkv,$where);
		$this->query($query);
	}

	# @ interface : DBSwitch
	public function delete($table,$where){
		$query = sprintf("DELETE FROM `%s` WHERE %s",$table,$where);
		$this->query($query);
	}

	# 상속한 부모 프라퍼티 값 포함한 가져오기
	public function __get($propertyName){
		if(property_exists(__CLASS__,$propertyName)){
			return $this->{$propertyName};
		}
	}

	# db close
	public function __destruct(){
		parent::close();
	}
}
?>