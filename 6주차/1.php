<?php
   $db_host="10.1.2.10"; # MySQL IP 주소
   $db_user="root"; # MySQL 계정
   $db_password="qwe123"; # MySQL 암호 
   $db_name=""; # 스키마 이름
   $con=mysqli_connect($db_host, $db_user, $db_password, $db_name);
   if ( mysqli_connect_error($con) ) {
	   echo "MySQL 접속 실패 !!", "<br>";
	   echo "오류 원인 : ", mysqli_connect_error();
	   exit();
   }
   echo "MySQL 접속 완전히 성공!!";
   mysqli_close($con);
?>