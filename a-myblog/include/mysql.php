<?php

//数据库操作函数库
//链接数据库
function conn($arr) {
    //1)链接数据库服务器
    $con = @mysql_connect($arr['host'] . ':' . $arr['port'], $arr['user'], $arr['pwd']);
    if (!$con) {
        exit('链接数据库失败');
    }
    //2)选择数据库
    $selectdb = mysql_select_db($arr['dbname'], $con);
    if (!$selectdb) {
        exit('选择数据库失败');
    }
    //3)设置编码
    mysql_query('SET NAMES utf8', $con);
    //屏蔽宽字符集漏洞
    mysql_query("SET character_set_client=binary", $con);

    return $con;
}

//执行sql语句自定义函数
//$sql sql语句
//$conn  数据库连接资源
//$num 查询结果中获取一条数据,返回一维数组, 
//           1 只获取一条,  其他有可能是 1或多条,根据 Sql中limit决定
function query($sql,$conn, $num=0) {
    
    
    $sql = strtoupper($sql);//转换成大写
    $re = mysql_query($sql, $conn);//执行sql
    $tmp = substr($sql, 0, 6);//获取sql字符串中 前 6位
//    select * from user
//    insert into user() values()
//    update user set username='fff' where   id=1
//    delete from user where id=1
            
    if($tmp=='SELECT'){
        //循环获取记录
        $data=array();
        while($row=mysql_fetch_array($re,MYSQL_ASSOC)){
               $data[]=$row;  //把查询到的一行记录保存到数组中
        }   
//        $data=array(
//            0=>array(
//                'id'=>1,
//                'title'=>'说的方法都是发',
//                'content'=>'水电费收到收到',
//                'addtime'=>1221222
//            ),     
//             1=>array(
//                'id'=>1,
//                'title'=>'说的方法都是发',
//                'content'=>'水电费收到收到',
//                'addtime'=>1221222
//            )           
//        );
        
        
        if($num==1 && !empty($data)){
          //只获取一条记录,返回一维关联数组; 
          //下标就是表中的字段名,值就数据表中的值;
            return  $data[0];//从结果中 获取
        }else{
            return $data;//返回二维数组 或 空(没有数据)
        }
//   netbeans :ctrl+\ 提示信息
    }elseif($tmp=='INSERT'){//insert
        return mysql_insert_id($conn); //返回最后添加的记录自增长字段的值    
    }else{//其他  update delete 
          return mysql_affected_rows($conn); //返回值为上一次执行的sql影响的行数 
    }
    
}
