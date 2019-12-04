<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<link rel="stylesheet" href="css/system/main/login.css" />
		<link rel="stylesheet" href="js/plug-in/bootstrap/css/bootstrap.css" />
		<title>注册</title>
		<script src="js/boot.js"></script>
	</head>
	<body>
		<div class="all">
			<div class="header header1">
				<div class="headerIn">
					<div class="logo"><img src="images/chaoqunlogo.png" alt="" width="230" height="44"/></div>
				</div>
			</div>
			
			<div class="center">
				
				<div class="steps">
					<ul class="stepsUL">
						<li><i>1</i>手机验证</li>
						<li><i>2</i>补充信息</li>
						<li class="steping"><i class="stepingI">3</i>注册成功</li>
					</ul>
				</div>
				
				<div>
					<div class="phoneRes">
						<div style="text-align:center;">
							<i><img src="images/success.png" style="width:35px;height:35px;"></i><span style="margin-left:30px;font-size:22px;color:red;">恭喜您！您已注册成功！</span>
						</div>
						<div class="reciprocal" style="text-align:center;"></div>
					</div>
					<div style="text-align:center;margin-top:40px;">
					<button type="button" class="btn btn-success" style="width:300px;" onclick="turnTo()">立即登录</button>
					</div>
				</div>
				
			</div>
				
			
		</div>
		
	<script src="js/system/main/distpicker.data.js"></script>
	<script src="js/system/main/distpicker.js"></script>
	<?
			include_once("../html/js/myAppBoot.php");
		?>
	<script src="js/plug-in/bootstrap/js/bootstrap.js"></script>
	</body>
	
</html>
