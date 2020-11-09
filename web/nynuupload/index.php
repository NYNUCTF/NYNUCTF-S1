<?php
$SELF_PAGE = substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'],'/')+1);

if ($SELF_PAGE = "clientcheck.php"){
    $ACTIVE = array('','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','active open','','','','active','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','');
}

include_once 'uploadfunction.php';

$html='';
if(isset($_POST['submit'])){
    $type=array('jpg','jpeg','png');//指定类型
    $mime=array('image/jpg','image/jpeg','image/png');
    $save_path='uploads'.date('/Y/m/d/');//根据当天日期生成一个文件夹
    $upload=upload('uploadfile','512000',$type,$mime,$save_path);//调用函数
    if($upload['return']){
        $html.="<p class='notice'>success！</p><p class='notice'>文件保存的路径为：{$upload['save_path']}</p>";
    }else{
        $html.="<p class=notice>{$upload['error']}</p>";

    }
}
?>

<head>
    <title>Welcome to NYNU upload</title>
    <style>
        body{
            background-image: url(./include/background.jpg)!important;
            background-size: 100% 100%!important;
            background-repeat: no-repeat;
            background-attachment: fixed!important;
            background-color: #000;
            padding: 0!important;
            min-width: 1300px;
            font-size: 14px!important




        }

        html,body{
            position: relative;
            height: 100%;
        }

        .main-content{
            position: relative;
            width: 300px;
            margin: 80px auto;
            padding: 20px 40px 40px;
            text-align: center;
            background: #fff;
            border: 1px solid #ccc;
        }

        .main-content::before,.main-content::after{
            content: "";
            position: absolute;
            width: 100%;height: 100%;
            top: 3.5px;left: 0;
            background: #fff;
            z-index: -1;
            -webkit-transform: rotateZ(4deg);
            -moz-transform: rotateZ(4deg);
            -ms-transform: rotateZ(4deg);
            border: 1px solid #ccc;
        }

        .main-content::after{
            top: 5px;
            z-index: -2;
            -webkit-transform: rotateZ(-2deg);
            -moz-transform: rotateZ(-2deg);
            -ms-transform: rotateZ(-2deg);
        }

        .main-content1{
            position: relative;
            width: 300px;
            margin: 80px auto;
            padding: 20px 40px 40px;
            text-align: center;
            background: #fff;
            border: 1px solid #ccc;
        }

        .main-content1::before,.main-content::after{
            content: "";
            position: absolute;
            width: 100%;height: 100%;
            top: 3.5px;left: 0;
            background: #fff;
            z-index: -1;
            -webkit-transform: rotateZ(4deg);
            -moz-transform: rotateZ(4deg);
            -ms-transform: rotateZ(4deg);
            border: 1px solid #ccc;
        }

        .main-content1::after{
            top: 5px;
            z-index: -2;
            -webkit-transform: rotateZ(-2deg);
            -moz-transform: rotateZ(-2deg);
            -ms-transform: rotateZ(-2deg);
        }
    </style>
</head>
<body>
<div class="main-content">
    <div class="main-content-inner">
        <div class="page-content">
            <div id="usu_main">
                <form class="upload" method="post" enctype="multipart/form-data"  action=""><br/>
                    <input class="uploadfile" type="file"  name="uploadfile" /><br/>
                    <input class="sub" type="submit" name="submit" value="点击上传" />
                </form>
                <?php
                echo $html;//输出了上传文件的路径
                ?>
            </div>
        </div><!-- /.page-content -->
    </div>
</div><!-- /.main-content -->

<?php

$SELF_PAGE = substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'],'/')+1);

if ($SELF_PAGE = "fi_local.php"){
    $ACTIVE = array('','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','active open','',
        'active','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','');
}

$html='';
if(isset($_GET['submit']) && $_GET['filename']!=null){
    $filename=$_GET['filename'];
    include "include/$filename";//变量传进来直接包含,没做任何的安全限制
//     安全的写法,使用白名单，严格指定包含的文件名
//     if($filename=='file1.php' || $filename=='file2.php' || $filename=='file3.php' || $filename=='file4.php' || $filename=='file5.php'){
//         include "include/$filename";
//     }
}

?>


<div class="main-content1">
    <div class="main-content-inner1">
        <div class="page-content1">
            <div id=fi_main>
                <p class="fi_title">PS:这里可以看到一些好看的图片示例哦~</p>
                <form method="get">
                    <select name="filename">
                        <option value="">--------------</option>
                        <option value="1.php">first</option>
                        <option value="2.php">second</option>

                    </select>
                    <input class="sub" type="submit" name="submit" />
                </form>
                <?php echo $html;?>
            </div>
        </div><!-- /.page-content1 -->
    </div>
</div><!-- /.main-content1 -->

</body>