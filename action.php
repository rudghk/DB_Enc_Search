<?php
	$con=mysqli_connect("localhost", "root", "", "soldier_DB") or die("MySQL 접속 실패 !!");
	
	$search=$_POST["search"];
	$select=$_POST["select"];
	
	session_start();
	if(!(isset($_SESSION['id'])||isset($_SESSION['r_num'])||isset($_SESSION['time'])))
	{
		?>
		<script> alert("로그인 필요");location.href="http://localhost/form.html";</script>
		<?php
		exit;
	}
		
	if((time()-$_SESSION['time'])>=1800)			#세션 생성 후 30분 이상 지나면 세션 파괴
	{
		session_destroy(); ?>
		<script> alert("세션 만료됨");location.href="http://localhost/form.html";</script>
		<?php
		exit;
	}
	$r_num=$_SESSION['r_num'];	 #세션 값 받아 옴
  
	$sql="SELECT * FROM soldier_tbl";
	
	$ret=mysqli_query($con,$sql);
	$sql2="";
	
	if($ret){
		echo "<html>";
		echo "<head>";
		echo "<link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css' integrity='sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T' crossorigin='anonymous'>
			<script src='https://code.jquery.com/jquery-3.3.1.slim.min.js' integrity='sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo' crossorigin='anonymous'></script>
			<script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js' integrity='sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1' crossorigin='anonymous'></script>
			<script src='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js' integrity='sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM' crossorigin='anonymous'></script>
			<link rel='stylesheet' type = 'text/css' href='style.css'>";
		echo "</head>";
		echo "<body>";
		echo "<a href=\"http://localhost/search.php\"><img src=\"img/logo2.png\" class=\"img_logo\"/></a>";
		echo "<div class=\"card\" id=\"table_div\">";
		echo "<table class = \"table table-hover\">";
		echo "<tr>";
		if($r_num>=5) echo "<th>군번</th> <th>이름</th> <th>계급</th>";
		if($r_num>=7) echo "<th>혈액형</th>";
		if($r_num>=10) echo "<th>소속</th> <th>주소</th> <th>출생 월</th>"; 
		if($r_num>=13) echo "<th>계좌<th>";
		echo "</tr>";
		while($row=mysqli_fetch_array($ret)){
			
			$id_result = "";
			$name_result = "";
			$rank_result = "";
			$addr_result = "";
			$team_result = "";
			$bt_result = "";
			$acnt_result = "";
			$bmonth_result = 0; 
			switch($select){
				
				case 'name' : 
					if($search == $row["NAME"]){
						echo "<tr>";
						$id_result = $row["S_ID"];
						$name_result = $row["NAME"];
						$rank_result = $row["RANK"];
						$addr_result = $row["ADDRESS"];
						$team_result = openssl_decrypt(base64_decode($row["TEAM"]),'aes-256-cbc',substr(hash('sha512',$row["S_KEY"]),0,32),OPENSSL_RAW_DATA,$row["IV"]);
						$bt_result = $row["BT"];
						$acnt_result = $row["ACCOUNT"];
						$bk = $row["BUCKET"];
						$bmonth_result = b_month($bk);
						}
					break;
					
				case 'address' : #주소
					$bf = $row['BF'];
					$token = explode(' ', $search);
					$count = count($token);
					$index_num = array(array());
					$ec = 0;
					for($i = 0; $i < $count; $i++){
						$hash = $token[$i];
						for($j = 0; $j < 10; $j++){
							$hash = hash('sha512', "$hash");
							$index_num[$i][$j] = ((int)$hash)%128;
							$n = $j;
						}
					}
					for($i = 0; $i < $count; $i++){
						for($k = 0; $k < 10; $k++){
							$cmp = substr($bf, $index_num[$i][$k], 1);
							if(strcmp('1', $cmp) != 0) {$ec = 1;}
						}
					}
					if($ec == 0){
						echo "<tr>";
						$id_result = $row["S_ID"];
						$name_result = $row["NAME"];
						$rank_result = $row["RANK"];
						$addr_result = $row["ADDRESS"];
						$team_result = openssl_decrypt(base64_decode($row["TEAM"]),'aes-256-cbc',substr(hash('sha512',$row["S_KEY"]),0,32),OPENSSL_RAW_DATA,$row["IV"]);
						$bt_result = $row["BT"];
						$acnt_result = $row["ACCOUNT"];
						$bk = $row["BUCKET"];
						$bmonth_result = b_month($bk);
						}
					break;
				
				case 'rank' : #계급
					if($search== $row["RANK"]){
						echo "<tr>";
						$id_result = $row["S_ID"];
						$name_result = $row["NAME"];
						$rank_result = $row["RANK"];
						$addr_result = $row["ADDRESS"];
						$team_result = openssl_decrypt(base64_decode($row["TEAM"]),'aes-256-cbc',substr(hash('sha512',$row["S_KEY"]),0,32),OPENSSL_RAW_DATA,$row["IV"]);
						$bt_result = $row["BT"];
						$acnt_result = $row["ACCOUNT"];
						$bk = $row["BUCKET"];
						$bmonth_result = b_month($bk);
						}
					break;
					
				case 'team' : #사단
					if(base64_encode(openssl_encrypt($search,'aes-256-cbc',substr(hash('sha512',$row["S_KEY"]),0,32),OPENSSL_RAW_DATA,$row["IV"]))==$row["TEAM"]){
						echo "<tr>";
						$id_result = $row["S_ID"];
						$name_result = $row["NAME"];
						$rank_result = $row["RANK"];
						$addr_result = $row["ADDRESS"];
						$team_result = openssl_decrypt(base64_decode($row["TEAM"]),'aes-256-cbc',substr(hash('sha512',$row["S_KEY"]),0,32),OPENSSL_RAW_DATA,$row["IV"]);
						$bt_result = $row["BT"];
						$acnt_result = $row["ACCOUNT"];
						$bk = $row["BUCKET"];
						$bmonth_result = b_month($bk);
						}
					break;
				
				case 'bloodtype' :
					if($search== $row["BT"]){
						echo "<tr>";
						$id_result = $row["S_ID"];
						$name_result = $row["NAME"];
						$rank_result = $row["RANK"];
						$addr_result = $row["ADDRESS"];
						$team_result = openssl_decrypt(base64_decode($row["TEAM"]),'aes-256-cbc',substr(hash('sha512',$row["S_KEY"]),0,32),OPENSSL_RAW_DATA,$row["IV"]);
						$bt_result = $row["BT"];
						$acnt_result = $row["ACCOUNT"];
						$bk = $row["BUCKET"];
						$bmonth_result = b_month($bk);
						}
					break;
					
				case 'month' : #태어난 달
					if($search== '1' || $search == '01')
						$bucket=7;
					else if($search=='2' || $search == '02')
						$bucket=4;
					else if($search=='3' || $search == '03')
						$bucket = 12;
					else if($search=='4' || $search == '04')
						$bucket = 11;
					else if($search=='5' || $search == '05')
						$bucket = 9;
					else if($search=='6' || $search == '06')
						$bucket = 2;
					else if($search=='7' || $search == '07')
						$bucket = 10;
					else if($search=='8' || $search == '08')
						$bucket = 5;
					else if($search=='9' || $search == '09')
						$bucket = 3;
					else if($search=='10')
						$bucket = 6;
					else if($search=='11')
						$bucket = 8;
					else if($search=='12')
						$bucket = 1;
					else
						echo "잘못 입력하셨습니다.";
					
					if($bucket== $row["BUCKET"]){
						echo "<tr>";
						$id_result = $row["S_ID"];
						$name_result = $row["NAME"];
						$rank_result = $row["RANK"];
						$addr_result = $row["ADDRESS"];
						$team_result = openssl_decrypt(base64_decode($row["TEAM"]),'aes-256-cbc',substr(hash('sha512',$row["S_KEY"]),0,32),OPENSSL_RAW_DATA,$row["IV"]);
						$bt_result = $row["BT"];
						$acnt_result = $row["ACCOUNT"];
						$bk = $row["BUCKET"];
						$bmonth_result = b_month($bk);
						}
					break;
					
				
				case 'account' : 
					if($search == $row["ACCOUNT"]){
						echo "<tr>";
						$id_result = $row["S_ID"];
						$name_result = $row["NAME"];
						$rank_result = $row["RANK"];
						$addr_result = $row["ADDRESS"];
						$team_result = openssl_decrypt(base64_decode($row["TEAM"]),'aes-256-cbc',substr(hash('sha512',$row["S_KEY"]),0,32),OPENSSL_RAW_DATA,$row["IV"]);
						$bt_result = $row["BT"];
						$acnt_result = $row["ACCOUNT"];
						$bk = $row["BUCKET"];
						$bmonth_result = b_month($bk);
					}
					break;
					
					
			}
			
			if($bmonth_result != 0){
				if($r_num >= 5) echo "<td>$id_result</td> <td>$name_result</td> <td>$rank_result</td>";
				if($r_num >= 7) echo "<td>$bt_result</td>";
				if($r_num >= 10) echo "<td>$team_result</td> <td>$addr_result</td> <td>$bmonth_result</td>";
				if($r_num >= 13) echo "<td>$acnt_result</td>";
			}
			
				
			echo "</tr>";
			
		}
		echo "</table>";
		echo "</div>";
		echo "</body>";
	}
	else{
		echo "결과 실패";
	}
	mysqli_close($con);
	
	
	function b_month($bk){
		switch($bk){
			 case 7 : return "1월";
			 case 4 : return "2월";
			 case 12 : return "3월";
			 case 11 : return "4월";
			 case 9 : return "5월";
			 case 2 : return "6월";
			 case 10 : return "7월";
			 case 5 : return "8월";
			 case 3 : return "9월";
			 case 6 : return "10월";
			 case 8 : return "11월";
			 case 1 : return "12월";
		}
	}
?>


