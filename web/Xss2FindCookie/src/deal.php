<?php

$message=isset($_POST['content'])?$_POST['content']:'';
$f=file_get_contents("./template.html");
$message=preg_replace('/script/i','',$message);
$data=str_replace('{{content}}',$message,$f);
$str="QWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm";
str_shuffle($str);
$filename=md5($str.date("Y-m-d h:i:sa"));
$m=fopen('tmp1/'.$filename.'.html','w');
fwrite($m,$data);
fclose($m);
$command="DISPLAY=:9 phantomjs /var/scripts/shotpic.js   http://127.0.0.1/tmp1/$filename.html /var/www/html/pics/$filename.png 2>&1";
shell_exec($command);

header('Content-Type:application/json');
echo json_encode(array("result"=>"success","ans"=>"/pics/".$filename.".png"));
