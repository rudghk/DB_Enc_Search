<?php
	session_start();
	$con=mysqli_connect("localhost", "root", "", "soldier_DB") or die("MySQL 접속 실패 !!");
	
	$id=$_POST["s_id"];
	$password=$_POST["pw"];
	$sql="SELECT * FROM soldier_tbl";
	
	$ret=mysqli_query($con,$sql);
	
	if($ret){
		while($row=mysqli_fetch_array($ret)){
			if($id==$row["S_ID"]){
				$salt=$row["SALT"];
				$token=substr(hash('sha512',"$password$salt"),0,20);
				$rank = $row["RANK"];
				if($row['PW']==$token){
					$_SESSION['is_logged'] = 'YES';
					$_SESSION['s_id'] = $id;
					switch($rank){
						case '이병':	$r_num=1;	break;
						case '일병':	$r_num=2;	break;
						case '상병':	$r_num=3;	break;
						case '병장':	$r_num=4;	break;
						case '하사': $r_num=5;	break;
						case '중사': $r_num=6;	break;
						case '상사':	$r_num=7;	break;
						case '원사': $r_num=8;	break;
						case '준위': $r_num=9;	break;
						case '소위': $r_num=10;	break;
						case '중위': $r_num=11;	break;
						case '대위': $r_num=12;	break;
						case '소령': $r_num=13;	break;
						case '중령': $r_num=14;	break;
						case '대령': $r_num=15;	break;
						case '준장': $r_num=16;	break;
						case '소장': $r_num=17;	break;
						case '중장': $r_num=18;	break;
						case '대장': $r_num=19;	break;
						case '원수': $r_num=20;	break;
					}
					$_SESSION['r_num']=$r_num;
					$_SESSION['time']=time();
					header("Location:search.php");
					break;
				}
			}
		}
		if($row==false)
		{
			echo "인증 실패";
		}
	}
	else{
		echo "결과 실패";
	}
	mysqli_close($con);
?>
