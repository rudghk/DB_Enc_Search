<html>
<head>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</head>

<body>

<h2 class="search_title"><img src="img/mnd_mark.png" width="100px" height="85px" style="margin-top:15px;">검색 항목 입력</h2>
<p class="search_title">검색 할 항목을 선택하고 입력하세요.</p>

<form method="post" action="action.php" name="form3">

<?php
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
	$r_num=$_SESSION['r_num'];
		if($r_num>=5)
		{	?>
			<div class="row">
			<select name="select" id="dropdown" class="form-control col-sm-1" style="margin-right:10px;">
			<option value="name">이름</option>
			<option value="rank">계급</option>
			<?php
			if($r_num>=7){
				?>
				<option value="bloodtype">혈액형</option>
				<?php
				if($r_num>=10)
				{
				?>
					<option value="month">출생 월</option>
					<option value="address">주소</option>
					<option value="team">소속</option>
					<?php
					if($r_num>=13)
					{
					?>
						<option value="account">계좌</option>
					<?php }
				}
			} ?>
			</select>
		<?php
		}
		else
			echo "<p style=\"color : white; margin-left: 10px;\">접근 권한 없음</p>";
		
		if($r_num<5){}
		else{
			?>
			<input type="text" name="search" placeholder="검색 내용" class="form-control col-sm-3" style="margin-right:5px;">
			<input type="submit" value="검색" class="btn btn-light">
		<?php }
		?>
		</div>
		</form>
</body>
</html>