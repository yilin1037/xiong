<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="renderer" content="webkit|ie-stand|ie-comp">
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
						<li class="steping"><i class="stepingI">1</i>手机验证</li>
						<li><i>2</i>补充信息</li>
						<li><i>3</i>注册成功</li>
					</ul>
				</div>
				
				<div class="phoneRes">
					<form action="" method="post" class="form-horizontal">
						
						<div class="form-group">
						    <label for="phone" class="col-sm-3 control-label">手机号：</label>
						    <div class="col-sm-9">
						      <input type="text" class="form-control" id="phone" name="phone" placeholder="请输入手机号">
						    </div>
					    </div>
						
						<div class="form-group">
						    <label for="phoneRes" class="col-sm-3 control-label">短信证码：</label>
						    <div class="col-sm-6">
						      <input type="text" class="form-control" id="phoneRes" name="phoneRes" placeholder="请输入验证码">
						    </div>
						    <div class="col-sm-3">
						      <button type="button" class="btn btn-default" onclick="checkPhone()" id="getBtn">获取验证码</button>
						    </div>
						</div>
						<input type="text" style="display:none;" id="seller_nick" value="<?=$auth['seller_nick']?>">
						<input type="text" style="display:none;" id="user_id" value="<?=$auth['user_id']?>">
						<input type="text" style="display:none;" id="sub_user_id" value="<?=$auth['sub_user_id']?>">
						<input type="text" style="display:none;" id="shopname" value="<?=$auth['shopname']?>">
						<input type="text" style="display:none;" id="sessionkey" value="<?=$auth['sessionkey']?>">
						<input type="text" style="display:none;" id="shoptype" value="<?=$auth['shoptype']?>">
						<input type="text" style="display:none;" id="deadline" value="<?=$auth['deadline']?>">
						<input type="text" style="display:none;" id="appkey" value="<?=$auth['appkey']?>">
						<input type="text" style="display:none;" id="secretkey" value="<?=$auth['secretkey']?>">
						<input type="text" style="display:none;" id="expire_time" value="<?=$auth['expire_time']?>">
						<input type="text" style="display:none;" id="memberId" value="<?=$auth['memberId']?>">
                        <input type="text" style="display:none;" id="sourcedate" value="<?=$auth['sourcedate']?>">
					</form>
					
					<div style="color:red;text-align:center;font-size:14px">填写好信息后，请点击下一步</div>
					
					<div style="text-align:center;margin-top:20px;">
						<div class="col-sm-3"></div><div class="col-sm-9"><button type="button" class="btn btn-success" style="width:100%;" onclick="next()">下一步</button></div>
					</div>
					
				</div>
			</div>
				
			
		</div>
	<?
			include_once("../html/js/myAppBoot.php");
		?>	
	<script src="js/plug-in/bootstrap/js/bootstrap.js"></script>
	</body>
	
</html>
