<?php


//上传图片函数
//$arr $_FILES中图片信息: name,error,size,tmp_name,type
function uploadImg($arr) {


//判断错误：$_FILES[‘myfile’][‘error’];
    switch ($arr['error']) {
        case 0:
            break;
        case 1:
           msginfo('表示上传文件的大小超出了约定值。文件大小的最大值是在PHP配置文件中指定的，该指令是：upload_max_filesize!', 'javascript:history.go(-1);');   //自定义函数, 
            break;
        case 2:
             msginfo('表示上传文件大小超出了HTML表单隐藏域属性的MAX＿FILE＿SIZE元素所指定的最大值', 'javascript:history.go(-1);');   //自定义函数, 
          
            break;
        case 3:
                msginfo('表示文件只被部分上传', 'javascript:history.go(-1);');   //自定义函数, 
       
            break;
        case 4:
              msginfo('表示没有上传任何文件', 'javascript:history.go(-1);');   //自定义函数, 
         
            break;
        case 6:
              msginfo('表示找不到临时文件夹', 'javascript:history.go(-1);');   //自定义函数, 
            break;
        default:
              msginfo('未知错误', 'javascript:history.go(-1);');   //自定义函数, 
            break;
    }
//3)判断上传文件扩展名是否符合要求：$_FILES[‘myfile’][‘name’]中获取
    $arrTmp = explode('.', $arr['name']);
    $ext = array_pop($arrTmp); //jpg

    $allPicExt = array('jpg', 'png', 'gif');
    if (!in_array($ext, $allPicExt)) {
         msginfo('上传的文件类型不合法', 'javascript:history.go(-1);');   //自定义函数, 
      
    }
//4)判断上传文件大小是否符合要求：$_FILES[‘myfile’][‘size’]单位字节;
    $maxSize = 1024 * 1024;
    if ($arr['size'] > $maxSize) {
          msginfo('上传文件超过最大' . $maxSize . '字节', 'javascript:history.go(-1);');   //自定义函数, 
        
    }
//设置保存服务器上的新文件名包含扩展名：date(‘YmdHis’);

    $path='../../upload/';//保存 路径
    $newName =md5(uniqid()) . '.' . $ext;//图片名称
   
//新文件保存位置目录是否存在，不存在需要新建目录：mkdir；
        if(!is_dir($path)){
            if(!mkdir($path,0777)){
                 msginfo('上传目录创建失败' . $maxSize . '字节', 'javascript:history.go(-1);');   //自定义函数,    
            }
        }
//复制或移动临时文件到指定位置保存：move_uploaded_file ；
    if (move_uploaded_file($arr['tmp_name'], $path.$newName)) {
       // echo '上传成功'; 
        return '/upload/'.$newName; //返回上传的图片路径
    } else {
        msginfo('上传失败' . $maxSize . '字节', 'javascript:history.go(-1);');   //自定义函数, 
    }
}

//添加水印函数
//$filePathMax 要加水印的图片路径
//$c 水印添加的位置 1:左上角,2.右上角 3.右下角 4.左下角 5.居中
function watermark($filePathMax, $c) {
   //$imgPath= /upload/1111.jpg 默认传递的是前端的绝对路径, 要改成服务器端绝对路径
    //__FILE__当前代码所在磁盘路径, 函数所在路径
    $rootPath=dirname(__FILE__).'/..';//绝对路径 网站根目录
    
    $filePathMax=$rootPath.$filePathMax;//处理路径问题
    $filePathMin = $rootPath.'/img/logo.png'; //水印图片 logo
//print_r(getimagesize($filePath)); //getimagesize(图片路径);//获取图片宽高；
    
    $infoImgMax = getimagesize($filePathMax); //要加水印的图片 宽高/图片类型
    $infoImgMin = getimagesize($filePathMin); //水印图片 宽高/图片类型
    /*getimagesize(图片路径)
      返回索引数组其中有四个元素,常用有：
      0)宽度:图片宽度单位像素
      1)高度：图片高度单位像素
      2)图片格式(1/2/3)：1为gif、2为jpg／jpeg、3为png
      ........
     *  */

    /* imagecreatefrompng(图片路径);//从给定的png文件获取图像,返回图像资源变量； */

//读取大图;也就是要加水印的图片
    switch ($infoImgMax[2]) {//图片格式(1/2/3)：1为gif、2为jpg／jpeg、3为png
        case 1:
            $imgMax = imagecreatefromgif($filePathMax);
            break;
        case 2:
            $imgMax = imagecreatefromjpeg($filePathMax);
            break;
        case 3:
            $imgMax = imagecreatefrompng($filePathMax);
            break;
    }
//读取小图;也就是水印图片 logo
    switch ($infoImgMin[2]) {//图片格式(1/2/3)：1为gif、2为jpg／jpeg、3为png
        case 1:
            $imgMin = imagecreatefromgif($filePathMin);
            break;
        case 2:
            $imgMin = imagecreatefromjpeg($filePathMin);
            break;
        case 3:
            $imgMin = imagecreatefrompng($filePathMin);
            break;
    }

//拷贝小图到大图中
//左上角
    switch ($c) {
        case 1://左上角
            imagecopy($imgMax, $imgMin, 0, 0, 0, 0, $infoImgMin[0], $infoImgMin[1]);
            break;
        case 2://右上角
            imagecopy($imgMax, $imgMin, ($infoImgMax[0] - $infoImgMin[0]), 0, 0, 0, $infoImgMin[0], $infoImgMin[1]);
            break;
        case 3://右下角
            imagecopy($imgMax, $imgMin, ($infoImgMax[0] - $infoImgMin[0]), ($infoImgMax[1] - $infoImgMin[1]), 0, 0, $infoImgMin[0], $infoImgMin[1]);
            break;
        case 4://左下角
            imagecopy($imgMax, $imgMin, 0, ($infoImgMax[1] - $infoImgMin[1]), 0, 0, $infoImgMin[0], $infoImgMin[1]);
            break;
         case 5://居中
            imagecopy($imgMax, $imgMin,(($infoImgMax[0] - $infoImgMin[0])/2), (($infoImgMax[1] - $infoImgMin[1])/2), 0, 0, $infoImgMin[0], $infoImgMin[1]);
            break;
    }


    /*
      复制小图到大图中使用的函数
      imagecopy($im,$im2,x1,y1,x2,y2,$w,$h);//拷贝图像或部分
      说明：返回true，false
      参数：$im：目标图像；$im2：被拷贝图像；
      x1\y1：目标图像开始x/y位置(0,0为左上角开始)
      x2\y2：拷贝图像开始x/y位置(0,0为左上角开始)
      $w/$h：拷贝图像宽高的像素值

     *  */
       $path='../../upload/watermark/';
        if(!is_dir($path)){
            if(!mkdir($path,0777)){
                 msginfo('水印目录创建失败' . $maxSize . '字节', 'javascript:history.go(-1);');   //自定义函数,    
            }
        }
    $saveImg = time(); //保存加过水印图片的地址
    switch ($infoImgMax[2]) {//图片格式(1/2/3)：1为gif、2为jpg／jpeg、3为png
        case 1:
            //header('content-type:image/gif');
            $imgPath = $path.$saveImg . '.gif';
            imagegif($imgMax, $imgPath); //保存到硬盘中
            break;
        case 2:
            // header('content-type:image/jpeg');
            $imgPath = $path.$saveImg . '.jpg';
            imagejpeg($imgMax, $imgPath); //拼接扩展名
            break;
        case 3:
            // header('content-type:image/png');
            $imgPath = $path.$saveImg . '.png';
            imagepng($imgMax, $imgPath); //拼接扩展名
            break;
    }
    //echo '水印添加成功!';
    imagedestroy($imgMax);
    imagedestroy($imgMin);
    return '/upload/watermark/'.$saveImg; //返回加好水印的图片路径,前端使用的
    
}



//生成缩略图函数
//$thumb_w 缩略图宽度
//$thumb_h 缩略图高度
//$imgPath 源图,要生成缩略图的源图片
function thumb($thumb_w, $thumb_h, $imgPath) {
    //$imgPath= /upload/1111.jpg 是前端的绝对路径
    $o_imgPath=$imgPath;//保留原地址
    $imgPath=dirname(__FILE__).'/..'.$imgPath;//处理路径问题
    $arrImg = getimagesize($imgPath);
    /* getimagesize(图片路径)
      返回索引数组其中有四个元素,常用有：
      0)宽度:图片宽度单位像素
      1)高度：图片高度单位像素
      2)图片格式(1/2/3)：1为gif、2为jpg／jpeg、3为png
      ........
     *  */

    //判断源图 是否小于 缩略图大小,源图小于缩略图就不用缩略直接返回源图
    if($thumb_w>$arrImg[0] &&  $thumb_h> $arrImg[1]){
        return $o_imgPath; 
    }
 
//3、根据源图片类型选择读取图片函数：imagecreatefrompng()；

    switch ($arrImg[2]) {//图片格式(1/2/3)：1为gif、2为jpg／jpeg、3为png
        case 1:
            $imgS = imagecreatefromgif($imgPath);
            break;
        case 2:
            $imgS = imagecreatefromjpeg($imgPath);
            break;
        case 3:
            $imgS = imagecreatefrompng($imgPath);
            break;
    }

//4、创建缩略图图像资源：imagecreatetruecolor();
    $thumbImg = imagecreatetruecolor($thumb_w, $thumb_h);
//$bgcolor = imagecolorallocate($thumbImg, 222, 100, 222);
//imagefill($thumbImg, 0, 0, $bgcolor);
//设置透明背景
    $bgcolor = imagecolorallocatealpha($thumbImg, 0, 0, 0, 127); //拾取一个完全 透明的颜色,  imagecolorallocatealpha(画布, R, G, B, 透明度)透明度:1-127之间
    imagefill($thumbImg, 0, 0, $bgcolor);
    imagesavealpha($thumbImg, true); //设置保存PNG是保留透明背景
//END
//5、根据缩略图宽高和源图宽高计算等比例缩放尺寸；
    $sW = $thumb_w / $arrImg[0]; //宽缩放比例
    $sH = $thumb_h / $arrImg[1]; //高缩放比例
    $s = min($sW, $sH); //最小的比例
    $new_W = $s * $arrImg[0]; //新的图像宽度
    $new_H = $s * $arrImg[1]; //新的图像高度
//居中显示
    $x = ($thumb_w - $new_W) / 2;
    $y = ($thumb_h - $new_H) / 2;

//6、使用新尺寸重采样拷贝部分图像并调整大小：ImageCopyResized()；
    /*
      imagecopyresized($im,$im2,x1,y1,x2,y2,$w,$h,$sw,$sh);
      说明：返回true，false
      参数：$im：目标画布(缩略图)；$im2：源图像；
      x1\y1：目标图像开始x/y位置(0,0为左上角开始)
      x2\y2：源图像开始x/y位置(0,0为左上角开始)
      $w/$h：目标图像宽高的像素值
      $sw/$sh：源图像宽高的像素值
     */
    ImageCopyResized(
            $thumbImg, //缩略图,
            $imgS, //源图
            $x, $y, //缩略图 开始位置 坐标
            0, 0, //源图    开始位置 坐标
            $new_W, $new_H, //缩略图 结束位置(坐标)
            $arrImg[0], $arrImg[1] //源图  结束位置 坐标
    );


//7、输出保存缩略图
//header('content-type:image/png');
        $path='../../upload/thumb/';
        if(!is_dir($path)){
            if(!mkdir($path,0777)){
                 msginfo('缩略图目录创建失败' . $maxSize . '字节', 'javascript:history.go(-1);');   //自定义函数,    
            }
        }
    $imgPath = time() . '.png';
    imagepng($thumbImg, $path.$imgPath);
//8、释放图像资源
    imagedestroy($thumbImg);

    //返回生成的缩略图路径
    return '/upload/thumb/'.$imgPath;
}

//无限级分类函数思路
//1)拿到所有的分类数据
// echo '<pre>';
// print_r($info);
//2)找到 一个 父级分类
// print_r(categoryTree($info));        
//            function  categoryTree($catarr){
//                $arr=array();
//                foreach ($catarr as $v){
//                     if($v['pid']==0){
//                          $arr[]=$v;    
//                     }
//                }
//               return $arr; 
//            }
//3)任何分类都有可能有子分类或是别人的子分类,再次循环找下级分类
//   参数
//  $catarr 所有分类数组,是一个二维数组;
//  &$subarr 地址传递 拼接子分类使用,第一次调用默认为空;
//  $pid 父级的cid ,默认0顶级分类
//  $level 层级
function categoryTree($catarr, &$subarr = array(), $pid = 0, $level = 1) {
    foreach ($catarr as $v) {//每次从所有分类循环
        if ($v['pid'] == $pid) {//递归结束条件是分类没有子分类了为止
            $v['level'] = $level; //层级关系保存到数组中
            $subarr[] = $v;
            categoryTree($catarr, $subarr, $v['cid'], $level + 1); //自己调用自己  
        }
    }
    return $subarr;
}

//后台翻页函数
//$allNum 总记录数 数据表中 总条数
//$page 当前页码
//$size   每页显示条
function admin_page($allNum, $page = 1, $size = 4) {
    //print_r($_SERVER);
    //$_SERVER['PHP_SELF']; 当前页面url地址,不包含域名和参数
    $url = $_SERVER['PHP_SELF'];

    //print_r($_GET);
    //page=2&keyword=1&username=111
    $arrGet = $_GET; //获取所有地址栏的传值,是数组
    if (isset($arrGet['page'])) {//页码是否存在     
        unset($arrGet['page']); //去除$arrGet中page     
    }
    $url2 = '';
    foreach ($arrGet as $key => $v) {
        //组装 除 page传值的其他传值
        $url2 .='&' . $key . '=' . $v;
    }
    //结果类似:  &keyword=1&username=111
    //3)计算总页数
    $total = ceil($allNum / $size);
//4)处理page当前页码

    if ($page <= 1) {
        $page = 1;
    } elseif ($page > $total) {
        $page = $total;
    }
//5)拼接 limit 从那一行,获取多少行

    $limit = " limit " . ($page - 1) * $size . ',' . $size; //数据表中 行下标是从 0开始的.$page=1 ---limit 0,4; $page=2 ---limit 4,4; $page=3 ---limit 8,4; 
//6)拼接翻页html代码
    $htmltmp = '<div class="message">共<i class="blue">' . $allNum . '</i>条记录 ,总页数';
    $htmltmp .=$total . '，当前显示第&nbsp;<i class="blue">';
    $htmltmp .=$page . '&nbsp;</i>页</div><ul class="paginList"><li class="paginItem">';
    $htmltmp .='<a href="' . $url . '?page=' . ($page - 1) . $url2 . '"><span class="pagepre"></span></a></li>';
    $tmp = '';
    for ($i = 1; $i <= $total; $i++) {
        $tmp .='<li class="paginItem ';
        if ($page == $i) {
            $tmp .='current'; //当前页码,突出显示样式
        }
        $tmp .= '"><a href="' . $url . '?page=' . $i . $url2 . '">' . $i . '</a></li>';
    }
    $htmltmp .= $tmp . '<li class="paginItem"><a href="' . $url . '?page=' . ($page + 1) . $url2 . '"><span class="pagenxt"></span></a></li></ul></div>';

    return array($limit, $htmltmp);
}
//前端翻页函数
//$allNum 总记录数 数据表中 总条数
//$page 当前页码
//$size   每页显示条
function page($allNum, $page = 1, $size = 4) {
    //print_r($_SERVER);
    //$_SERVER['PHP_SELF']; 当前页面url地址,不包含域名和参数
    $url = $_SERVER['PHP_SELF'];

    //print_r($_GET);
    //page=2&keyword=1&username=111
    $arrGet = $_GET; //获取所有地址栏的传值,是数组
    if (isset($arrGet['page'])) {//页码是否存在     
        unset($arrGet['page']); //去除$arrGet中page     
    }
    $url2 = '';
    foreach ($arrGet as $key => $v) {
        //组装 除 page传值的其他传值
        $url2 .='&' . $key . '=' . $v;
    }
    //结果类似:  &keyword=1&username=111
    //3)计算总页数
    $total = ceil($allNum / $size);
//4)处理page当前页码

    if ($page <= 1) {
        $page = 1;
    } elseif ($page > $total) {
        $page = $total;
    }
//5)拼接 limit 从那一行,获取多少行

    $limit = " limit " . ($page - 1) * $size . ',' . $size; //数据表中 行下标是从 0开始的.$page=1 ---limit 0,4; $page=2 ---limit 4,4; $page=3 ---limit 8,4; 
//6)拼接翻页html代码
    $htmltmp = '<div class="fy">';
   
    
    $tmp = '';
    for ($i = 1; $i <= $total; $i++) {
        $tmp .='<a class="';
        if ($page == $i) {
            $tmp .='fy2'; //当前页码,突出显示样式
        }
        $tmp .= '"  href="' . $url . '?page=' . $i . $url2 . '">' . $i . '</a>';
    }
    if($total>1){//总页数大于1时显示翻页
    $htmltmp .= $tmp . '<a href="' . $url . '?page=' . ($page + 1) . $url2 . '" class="page_pic"><img src="img/next.jpg"></a></div>';
    }else{
        $htmltmp='';
    }
    return array($limit, $htmltmp);
}
//信息提示,跳转
//$info : 提示的内容
//$reurl : 跳到地址url
function msginfo($info, $reurl) {
    ///echo $info;
    //echo dirname(__FILE__);//当前代码所在磁盘路径,就是当前 myfunction.php 的磁盘路径

    include_once dirname(__FILE__) . '/../admin/msginfo.php';
    exit;
}

//获取当前页面的完整URL地址
function get_page_url() {
    //$_SERVER : HTTP_HOST SERVER_PORT REQUEST_URI  PHP_SELF QUERY_STRING 键名
    //使用 端口判断 url协议
    $url = $_SERVER['SERVER_PORT'] == 443 ? 'https://' : 'http://';
    $url .=$_SERVER['HTTP_HOST']; //链接域名
    //域名之后判断是否是默认端口
    $url .=($_SERVER['SERVER_PORT'] == 80 || $_SERVER['SERVER_PORT'] == 443) ? '' : ':' . $_SERVER['SERVER_PORT'];
    //REQUEST_URI 只能在 apache web服务器中使用,其他都是为空
    $url .= isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];

    return $url;
}

//自定义函数库
//检查字符串是否符合要求
//$str 需要检查的字符串
// $i  验证类型,对应数组中下标
function check($str, $i = 0) {
    //正则规则
    //$arr[0][0]; 二维数组取值 结果:'/^[a-zA-Z]\w{5,14}$/'
    $arr = array(
        0 => array(//用户名验证规则
            0 => '/^[a-zA-Z]\w{5,14}$/',
            1 => '字母数字下划线,开头是字母,长度6-15位'
        ),
        1 => array(//邮箱验证规则
            0 => '/^[1-9]\d{5}$/',
            1 => '邮编格式不正确'
        ),
        2 => array(//手机
            '/^1[34578]{1}\d{9}$/', //默认不定义下标 从0开始,1递增
            '手机格式不正确'
        ),
        3 => array(//邮箱 
            '/^[a-zA-Z1-9]+\w+(\.\w+)*@(\w)+(\.\w+)*(\.[a-z]{2,4})$/',
            '邮箱格式不正确'
        ),
    );
    //判断该下标是否在数组,是否值
    if (empty($arr[$i])) {
        return 0;
    }

    //执行一次正则 判断是否匹配
    if (preg_match($arr[$i][0], $str)) {
        return '';
    } else {//不匹配返回 提示文字
        return $arr[$i][1]; //二维数组中下标为1取值
    }
    //执行一次正则 返回结果 1匹配/0不匹配
    //return  preg_match($arr[$i],$str);
}

//截取字符串
//$str :需要截取的 字符串
//$sbulen : 截取长度
//$ty: 截取类型 0 返回截取结果并 带上"..."; 1 直接返回截取结果  
function my_sub($str, $sbulen, $ty = 0) {
    //判断字符串长度
    $len = mb_strlen($str, 'utf-8');
    $tmp = '...';
    if ($ty == 1) {
        $tmp = '';
    }
    if ($len > $sbulen) {//字符串长度大于 截取的长度
        return mb_substr($str, 0, $sbulen, 'utf-8') . $tmp;
    } else {
        return $str;
    }
}

/*
 * 去除空格
 * 转换html,成特殊字符
 * 
 *  */

function in($str) {
    return htmlspecialchars(trim($str), ENT_QUOTES);
}
