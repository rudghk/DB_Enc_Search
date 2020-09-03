<?php
   $con=mysqli_connect("localhost", "root", "", "soldier_DB") or die("MySQL 접속 실패 !!");

   $sql ="
	   CREATE TABLE soldier_tbl 
		( S_ID  	VARCHAR(20) PRIMARY KEY NOT NULL,
		  PW    	VARCHAR(20) NOT NULL,
		  NAME    VARCHAR(20)  NOT NULL,
		  ID_NUM  VARCHAR(128)  NOT NULL,	  
		  RANK   VARCHAR(20) NOT NULL,
		  ADDRESS   VARCHAR(100) NOT NULL,
		  TEAM   VARCHAR(512)  NOT NULL,
		  BT    VARCHAR(10)  NOT NULL,   
		  ACCOUNT   VARCHAR(30) NOT NULL,
		  SALT VARCHAR(20) NULL,
		  S_KEY INT(16) NULL,
		  IV VARCHAR(16) NULL,
		  BUCKET INT(20) NULL,
		  BF VARCHAR(128) DEFAULT '00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000'
		)
   ";
 
   $ret = mysqli_query($con, $sql);
 
   if($ret) {
	   echo "soldier_tbl이 성공적으로 생성됨.";
   }
   else {
	   echo "soldier_tbl 생성 실패!!!"."<br>";
	   echo "실패 원인 :".mysqli_error($con);
   }
 
   mysqli_close($con);
?>
