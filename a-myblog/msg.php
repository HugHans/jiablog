<?php
//显示留言面板
header('Content-type: text/html; charset=utf-8');

require dirname(__FILE__) . "/include/config.php"; //配置信息
require dirname(__FILE__) . "/include/mysql.php"; //操作数据库自定义函数
require dirname(__FILE__) . "/include/myfunction.php"; //加载函数库

$conn = conn($dbinfo); //调函数链接数据库

$sql="select * from comments order by subject";
$tlist = query($sql, $conn);
//echo "<pre>";

//print_r($tlist);
$mat="";
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>comments</title>
<link rel="shortcut icon" href="img/avicon.ico" type="image/x-icon">
 <link rel="stylesheet" href="css/msgcss.css">
</head>

<body>

<div class="list">
<?php foreach ($tlist as $v) {
	
?>
      
     <div class="box">
         <?php
         if($v['subject']!=$mat){
         	$mat=$v['subject'];
         	echo '<div class="title"><h2>'.$v['subject'].'</h2></div>';
         	}
     ?>
       
       <div class="content">
            <div class="pic"><img src="img/profile.jpg" alt="" width="50px" height="50px"></div>
            <div class="message">
                 <p><span><?php echo $v['name'].":";?></span><br/>
                  <?php echo $v['content'];?>
                 </p>
            </div>
            <div class="clear"></div>
       </div>
       
     </div>
<?php }?>
</div>

</body>
</html>