<?php
$path = str_replace($_SERVER['PHP_SELF'],'',__FILE__);
include_once $path.'/config/config.inc.php';

$table = '테이블명을 입력하세요';

# 디비
$db=new DbMySqli();

# 기본 쿼리 1
$rlt1 = $db->query(sprintf("SELECT * FROM `%s`", $table));
$row1 = $rlt1->fetch_assoc();
Out::prints_r($row1);

# 심플쿼리 2
$row2 = $db->get_record('*', '테이브명', '');
Out::prints_r($row2);

# 바인딩쿼리 3
$row_bind_rlt = $db->query(
    $db->query_bind_params(
        "SELECT ? FROM ?",
        'ss',
        array(
            '*',
            $table
        )
    )
);
while($row_bind = $row_bind_rlt->fetch_assoc()){
    Out::prints_r($row_bind);
}


# 루프 4
$rlt3= $db->query(sprintf("SELECT * FROM `%s`", $table));
while($row3 = $rlt3->fetch_assoc()){
    Out::prints_r($row3);
}

# insert
Out::prints_ln('insert /-----------------------');
$db['uid'] = 1;
$db['name']='이름';
$db->insert($table);

# update
Out::prints_ln('update /-----------------------');
$db['name']='이름2';
$db->update($table, sprintf("uid='%d'", 1));

# delete
Out::prints_ln('delete /-----------------------');
$db->delete($table, sprintf("uid='%d'", 1));
?>