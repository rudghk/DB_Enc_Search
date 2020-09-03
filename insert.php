<?php
   $con=mysqli_connect("localhost", "root", "", "soldier_DB") or die("MySQL 접속 실패 !!");

  $sql ="
		INSERT INTO soldier_tbl VALUES
		('1612863','1612863','김일병','940230-1234567','일병','경기도 고양시 일산동구 성석동','9','RH-A','신한 110486278383','','','','',DEFAULT),
		('7164535', '7164535','박은인','961130-1234587','상병','경기도 고양시 덕양구 화전동','30','RH-A','신한 110897546255','','','','',DEFAULT),
		('5783224', '5783224', '이승협', '921031-1579423', '일병', '전라북도 임실군 일시읍 대곡리', '35', 'RH+A', '농협 3356895212546','','','','',DEFAULT),
		('8957651', '8957651', '김재현', '940715-1964534', '이병', '대구광역시 북구 국우동', '50', 'RH+A', '신한 110356518921','','','','',DEFAULT),
		('8957652', '8957652', '권광진', '920812-1746510', '하사', '부산광역시 해운대구 좌동', '53', 'RH+AB', '농협 366786521851','','','','',DEFAULT),
		('1023786', '1023786', '차훈', '940712-1865321', '중사', '경상남도 함안군 군북면 소포리', '39', 'RH+B', '신한 110532676537','','','','',DEFAULT),
		('6654323', '6654323', '유회승', '950228-1653749', '상사', '광주광역시 북구 오치동', '31', 'RH+B', '국민 98645321008615','','','','',DEFAULT),
		('9956488', '9956488', '최이이', '970505-1313131', '이병', '경상남도 함안군 군북면 소포리', '39', 'RH+O', '기업은행 4041954865411','','','','',DEFAULT),
		('2564123', '2564123', '박두팔', '001225-5546897', '일병', '강원도 원주시 소초면 흥양리', '36', 'RH+A', '신협 8455202642477','','','','',DEFAULT),
		('4456988', '4456988', '염창식', '931111-8546998', '병장', '경기도 파주시 적성면 적암리', '28', 'RH+AB', '국민 7854456951233','','','','',DEFAULT),
		('7785462', '7785462', '황뫄뫄', '800202-2288099', '소위', '인천광역시 부평구 구성동', '17', 'RH+O', '농협 8854562405587','','','','',DEFAULT),
		('3949394', '3949394', '박땡땡', '950306-3949503', '이병', '대구광역시 북구 국우동', '50', 'RH+O', '국민 29587384712349','','','','',DEFAULT),
		('5720493', '5720493', '김팔팔', '920422-1498023', '하사', '강원도 원주시 소초면 흥양리', '36', 'RH+AB', '신한 110492739485','','','','',DEFAULT),
		('4403820', '4403820', '이부자', '960214-1948305', '일병', '강원도 화천군 사내면 삼일리', '27', 'RH+B', '국민 294017509354','','','','',DEFAULT),
		('9502745', '9502745', '권흥부', '941027-1848204', '병장', '광주광역시 북구 오치동', '31', 'RH+A', '농협 29405927145','','','','',DEFAULT),
		('5927501', '5927501', '김미미', '950708-1949350', '하사', '부산광역시 해운대구 좌동', '53', 'RH+O', '농협 9249495085','','','','',DEFAULT),
		('1871010','1871010','김지현','990504-2314598','중위','강원도 화천군 상서면 봉오리','15','RH+A','신한 110385614845','','','','',DEFAULT),
		('4815926','4815926','이병철','841026-1594826','중령','강원도 고성군 토성면 금화정리','22','RH-A','농협 3028760034791','','','','',DEFAULT),
		('7534286','7534286','박연식','650311-1456283','소령','충청북도 증평군 증평읍 연탄리','37','RH+B','국민 75468157561259','','','','',DEFAULT),
		('9518462','9518462','이병주','941130-1564813','대위','경기도 화성시 매송면 어천리','51','RH+A','농협 3216544296881','','','','',DEFAULT),
		('6219548','6219548','김소은','971114-2468597','소위','경기도 양평군 양평읍 덕평리','20','RH+O','신한 110358156485','','','','',DEFAULT)
   ";
 
   $ret = mysqli_query($con, $sql);
   if($ret) {
	   $sql2="SELECT * FROM soldier_tbl";
		$ret2=mysqli_query($con,$sql2);
			if($ret2){
			while($row=mysqli_fetch_array($ret2)){
				$salt=random_int(1,10000);
				$pw = $row["PW"];
				$en_password = hash('sha512', "$pw$salt");
			
				
				$id=$row["S_ID"];
				$key=random_int(1,1000000);
				$s_key=substr(hash('sha512',"$key"),0,32);
				$iv=substr(hash('sha512',"$id"),0,16);
				$PT=$row["TEAM"];
				$result=base64_encode(openssl_encrypt($PT,'aes-256-cbc',$s_key,OPENSSL_RAW_DATA,$iv));
				
				$bf_replace = $row['BF'];
				$token = explode(' ', $row['ADDRESS']);
				$count = count($token);
				for($i = 0 ; $i < $count ; $i++){
					$hash = $token[$i];
					for($j = 0; $j < 10 ; $j++){
						$hash = hash('sha512', "$hash");
						$index_num = ((int)$hash)%128;
						$bf_replace = substr_replace($bf_replace, "1", $index_num, 1);
					}
					
				}
				
				$sql3="UPDATE soldier_tbl set PW = '$en_password', SALT = '$salt', S_KEY='$key', IV='$iv', TEAM='$result', BF='$bf_replace' where S_ID='$id'";
				$ret3=mysqli_query($con,$sql3);
				$idn=substr($row["ID_NUM"],2,2);
				$id_num = $row['ID_NUM'];
				$sql4="";
				switch($idn){
				   case '01':
				   $sql4="UPDATE soldier_tbl set BUCKET=7,ID_NUM=SHA2('$id_num', 512) WHERE S_ID='$id'";
				   break; 
				   case '02':
				   $sql4="UPDATE soldier_tbl set BUCKET=4,ID_NUM=SHA2('$id_num', 512) WHERE S_ID='$id'";
				   break; 
				   case '03':
				   $sql4="UPDATE soldier_tbl set BUCKET=12,ID_NUM=SHA2('$id_num', 512) WHERE S_ID='$id'";
				   break; 
				   case '04':
				   $sql4="UPDATE soldier_tbl set BUCKET=11,ID_NUM=SHA2('$id_num', 512) WHERE S_ID='$id'";
				   break;
				   case '05':
				   $sql4="UPDATE soldier_tbl set BUCKET=9,ID_NUM=SHA2('$id_num', 512) WHERE S_ID='$id'";
				   break;
				   case '06':
				   $sql4="UPDATE soldier_tbl set BUCKET=2,ID_NUM=SHA2('$id_num', 512) WHERE S_ID='$id'";
				   break;
				   case '07':
				   $sql4="UPDATE soldier_tbl set BUCKET=10,ID_NUM=SHA2('$id_num', 512) WHERE S_ID='$id'";
				   break;
				   case '08':
				   $sql4="UPDATE soldier_tbl set BUCKET=5,ID_NUM=SHA2('$id_num', 512) WHERE S_ID='$id'";
				   break;
				   case '09':
				   $sql4="UPDATE soldier_tbl set BUCKET=3,ID_NUM=SHA2('$id_num', 512) WHERE S_ID='$id'";
				   break;
				   case '10':
				   $sql4="UPDATE soldier_tbl set BUCKET=6,ID_NUM=SHA2('$id_num', 512) WHERE S_ID='$id'";
				   break;
				   case '11':
				   $sql4="UPDATE soldier_tbl set BUCKET=8,ID_NUM=SHA2('$id_num', 512) WHERE S_ID='$id'";
				   break;
				   case '12':
				   $sql4="UPDATE soldier_tbl set BUCKET=1,ID_NUM=SHA2('$id_num', 512) WHERE S_ID='$id'";
				   break;
				   default : $sql4="SELECT BUCKET FROM soldier_tbl";
			   }
				$ret4= mysqli_query($con, $sql4);
			}
			if($ret4)
				echo "soldier_tbl입력 성공"."<br>";
				else{
					 echo "44soldier_tbl 데이터 입력 실패!!!"."<br>";
					echo "실패 원인 :".mysqli_error($con);
				}
		}
		else 
		{
			 echo "soldier_tbl 데이터 입력 실패!!!"."<br>";
			echo "실패 원인 :".mysqli_error($con);
		}
	}
   else {
	   echo "soldier_tbl 데이터 입력 실패!!!"."<br>";
	   echo "실패 원인 :".mysqli_error($con);
   }


   mysqli_close($con);
?>
