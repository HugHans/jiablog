<?php

require dirname(__FILE__) . "/../../include/myfunction.php"; //加载函数库
$content = isset($_POST['content']) ? in($_POST['content']) : ''; //内容
$ty = isset($_POST['ty']) ? in($_POST['ty']) : ''; //验证类型

//判断验证类型
if ($ty == 10) {//验证主题
    $len1 = mb_strlen($content, 'utf-8');
    if ($len1 > 16 || $len1 < 4) {
        exit('名字长度限制在4~16字符之间');
    } 
}
if ($ty == 3){
	exit(check($content,$ty));
	}
if ($ty == 20){
	$len2 = mb_strlen($content, 'utf-8');
    if ($len2 > 20 || $len2 < 4) {
        exit('主题长度限制在4~20字符之间');
    } 
	}
if ($ty == 30){
	$len3 = mb_strlen($content, 'utf-8');
    if ($len3 > 150 || $len3 < 5) {
        exit('留言内容长度限制在5~150字符之间');
    } 
	}
 
 

