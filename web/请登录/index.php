

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="pragma" content="no-cache">
    <meta http-equiv="cache-control" content="no-cache">
    <meta http-equiv="expires" content="0"> 
	<title>后台登录</title>
    
	<!--必要样式-->
    <link href="css/styles.css" rel="stylesheet" type="text/css" />
    <link href="css/demo.css" rel="stylesheet" type="text/css" />
    <link href="css/loaders.css" rel="stylesheet" type="text/css" />
</head>
<body>
<h1 align="center" >后台管理系统</h1>
	<div class='login'>
	  <div class='login_title'>
	    <span>管理员登录</span>
	  </div>
        <form>
	  <div class='login_fields'>
	    <div class='login_fields__user'>
	      <div class='icon'>
	        <img alt="" src='img/user_icon_copy.png'>
	      </div>

	      <input name="username" placeholder='管理员账号' maxlength="16" type='text' autocomplete="off" value=""/>
	        <div class='validation'>
	          <img alt="" src='img/tick.png'>
	        </div>
	    </div>
	    <div class='login_fields__password'>
	      <div class='icon'>
	        <img alt="" src='img/lock_icon_copy.png'>
	      </div>
	      <input name="password" placeholder='密码' maxlength="16" type='text' autocomplete="off">
	      <div class='validation'>
	        <img alt="" src='img/tick.png'>
	      </div>
	    </div>
	
	    <div class='login_fields__submit'>
	      <input type='submit' formmethod="post" value='登录' >
	    </div>
	  </div>
        </form>
	  <div class='success'>
	  </div>
		<?php

            error_reporting(0);

            if(isset($_POST['username'])&&isset($_POST['password'])) {

                $username = $_POST['username'];
                $password = $_POST['password'];
                $conn = new mysqli("127.0.0.1", "root", "password", "ctf");
                $conn->query("SET NAMES 'UTF8'");
                if (!$conn) {
                    die('数据库连接失败');
                }
                error_reporting(0);
                $sql = "SELECT * FROM users WHERE username='$username' and password='$password'";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_array($result, MYSQLI_BOTH);

                if ($row) {

                    echo "<font size=\"3\"color=\"white\" /></br></br></br></br></br></br></br></br>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp登录成功";
	    echo "<font size=\"3\"color=\"white\" /></br></br></br></br></br></br></br></br></br>nyctf{You_are_just_amazing}";

                } else {

                    echo "<font size=\"3\"color=\"white\" /></br></br></br></br></br></br></br></br>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp账号或密码错误";

                }
            }

        ?>


	  <div class='disclaimer'>
	    <p>欢迎登陆后台管理系统</p>
	  </div>
	</div>
	
  
    
</body>
</html>
