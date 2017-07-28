<?php
session_start();//启用seesion
header('content-type:image/png');//告诉浏览器将要输出的是什么内容; 图片/png图片
//创建图像
$width=80;  //画布宽 X 轴最大值
$height=40;// 画布高  Y 轴最大值
//1)创建画布 调色板画布
$img=imagecreate($width,$height);//画布资源,调色板256色
//2)绘制图形
   //设置颜色 背景色
  imagecolorallocate($img,89,58,40);//第一执行这个函数设置背景色 R:89,G:58,B:40 颜色值
  ///$color=imagecolorallocate($img,45,120,10);//第二次执行,获取文字(前景色)颜色

  //画像素点 100个
  for($i=0;$i<100;$i++){
      $color=imagecolorallocate($img,rand(0,255),rand(0,255),rand(0,255));
     imagesetpixel($img,rand(0,$width),rand(0,$height),$color);
  }  
  //imagesetpixel(
  //      画布,
  //      点的坐标X,Y
  //      点颜色 
  //  )
  
  //画出4条直线
  for($i=0;$i<4;$i++){
      $color=imagecolorallocate($img,rand(0,255),rand(0,255),rand(0,255));
      imageline($img,rand(0,$width),rand(0,$height),rand(0,$width),rand(0,$height),$color);
  }
  // imageline(画布,
  //                  起始点X,Y,
  //                 结束点X,Y,
  //                 线颜色);
  //绘制文字 --验证码
  //获取随机的验证码 4位
  $str='123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $code='';//初始化
  $len=strlen($str)-1;//获取字符串长度,字符串下标从0开始的
  for($i=0;$i<4;$i++){   
     $code .= substr($str,rand(0,$len),1);
  }
  ///$code='5B5Y'; 使用字符串下标获取单个值 : $code[0] 得到//5
  //保存到session
  $_SESSION['code']=$code;
 
  for($i=0;$i<4;$i++){
      $color=imagecolorallocate($img,rand(0,255),rand(0,255),rand(0,255));
      //imagestring($img,5,15*$i,20,$code[$i],$color);
      imagettftext($img,18,rand(-20,20),20*$i+5,rand(25,30),$color,'simkai.ttf',$code[$i]);
  }
  /*imagettftext(
          画布,
          字号,
          切斜度,
          开始位置X,Y,
          文字颜色,
          字体(名字是字母数字),
          文字内容
          );
   * 字体从 系统盘/windows/Fonts/   下拷贝放入当前程序所在目录中
  */
   
  //imagestring(
  //              画布,
  //              字号(1-5),
  //              X轴,Y轴,           //文字的开始的位置
  //              文字颜色  
  //      )
  

  
  //3)输出这个图像
   imagepng($img);
   //imagepng($img,'code.png');//保存图片方式,注意路径
  
  //4)释放资源  
   imagedestroy($img);
  