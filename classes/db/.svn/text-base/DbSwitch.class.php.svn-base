<?php
/** ======================================================
| @Author	: 김종관 | 010-4023-7046
| @Email	: apmsoft@gmail.com
| @HomePage	: http://www.apmsoftax.com
| @Editor	: Eclipse(default)
| @UPDATE	: 2010-02-16
----------------------------------------------------------*/

# purpose : 각종 SQL 관련 디비를 통일성있게  작성할 수 있도록 틀을 제공
interface DbSwitch
{
    public function query($query);			# 쿼리
    public function insert($table);				# 저장
    public function update($table,$where);	# 수정
    public function delete($table,$where);	# 삭제
    public function query_bind_params($query,$bind,$args=array());	#쿼리 문자 바인드효과
}
?>