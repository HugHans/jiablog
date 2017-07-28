﻿<meta charset="utf-8">
<?php

/*处理留言  */
require dirname(__FILE__) . '/../../include/config.php'; //配置信息
require dirname(__FILE__) . '/../../include/mysql.php'; //操作数据库自定义函数
require dirname(__FILE__) . "/../../include/myfunction.php"; //加载函数库

$name = isset($_POST['name']) ? in($_POST['name']) : ''; 
$email = isset($_POST['email']) ? in($_POST['email']) : ''; 
$subject = isset($_POST['subject']) ? in($_POST['subject']) : ''; 
$message = isset($_POST['message']) ? in($_POST['message']) : ''; 
$err = '';


    $len1 = mb_strlen($name, 'utf-8');
    if ($len1 > 16 || $len1 < 4) {
         $err .='名字长度限制在4~16字符之间<br>';
    } 


	  $err .=check($email,3).'<br>';


	  $len2 = mb_strlen($subject, 'utf-8');
    if ($len2 > 20 || $len2 < 4) {
        $err .='主题长度限制在4~20字符之间<br>';
    } 
	

	  $len3 = mb_strlen($message, 'utf-8');
    if ($len3 > 150 || $len3 < 5) {
        $err .='留言内容长度限制在5~150字符之间<br>';
    } 
    
    if(empty($err)){
    	echo '留言失败'.'<br>';
      echo $err.'<br>';
      echo '<a href="javascript:history.go(-1);">返回</a>';
      exit;	
    }
 
 //数据库
 
  $conn = conn($dbinfo); //调函数链接数据库
  
  $sql = "insert into comments(name,email,subject,content,addtime) values('$name','$email','$subject','$message','".time()."')";
  
  $info = query($sql, $conn);
  
  if ($info > 0) {//插入成功   
        //$_SESSION['info'] = '留言成功,请等待管理员回复;<br><a href="msg.php">返回</a>';
        //header('location:../info.php');
        echo '留言成功';
  }  
  else {//失败 
        //$_SESSION['info'] = '留言失败,请稍后再试!<br><a href="javascript:history.go(-1);">返回</a>';
        //header('location:../info.php');
        echo '留言失败';
  }