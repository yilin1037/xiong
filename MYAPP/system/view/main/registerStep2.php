<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<link rel="stylesheet" href="css/system/main/login.css" />
		<title>注册</title>
		<script src="js/boot.js"></script>
		<link rel="stylesheet" href="js/plug-in/bootstrap/css/bootstrap.css" />
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
						<li class="steping"><i class="stepingI">2</i>补充信息</li>
						<li><i>3</i>注册成功</li>
					</ul>
				</div>
				
				<div class="phoneRes">
					<form action="" class="form-horizontal">
						
						<div class="form-group">
						    <label for="company" class="col-sm-3 control-label"><span style="color:red;position:relative;top:3px;">*</span>公司名称：</label>
						    <div class="col-sm-9">
						      <input type="text" class="form-control" id="company" name="company" placeholder="请输入公司名称">
						    </div>
							<div class="companyTrue hideNow">
							<label class="col-sm-3 control-label"></label>
						    <div class="col-sm-9 tips">
						      请填写公司名称
						    </div>
							</div>
					    </div>
						<div data-toggle="distpicker">
						<div class="form-group">
						<label class="col-sm-3 control-label"><span style="color:red;position:relative;top:3px;">*</span>选择地址：</label>
							<div class="col-sm-3" style="padding-right:0;">
							  <label class="sr-only" for="province2">Province</label>
							  <select class="form-control" id="province2" data-province="选择省"></select>
							</div>
							<div class="col-sm-3" style="padding-right:0;">
							  <label class="sr-only" for="city2">City</label>
							  <select class="form-control" id="city2" data-city="选择市"></select>
							</div>
							<div class="col-sm-3">
							  <label class="sr-only" for="district2">District</label>
							  <select class="form-control" id="district2" data-district="选择区"></select>
							</div>
							<div class="placeTrue hideNow">
							<label class="col-sm-3 control-label"></label>
						    <div class="col-sm-9 tips">
						      请选择完整地址
						    </div>
							</div>
						</div>
						</div>
					    
					    <div class="form-group">
						    <label for="concact" class="col-sm-3 control-label"><span style="color:red;position:relative;top:3px;">*</span>联系人：</label>
						    <div class="col-sm-9">
						      <input type="text" class="form-control" id="concact" name="concact" placeholder="请输入联系人">
						    </div>
							<div class="concactTrue hideNow">
							<label class="col-sm-3 control-label"></label>
						    <div class="col-sm-9 tips tipCon">
						      请填写联系人
						    </div>
							</div>
					    </div>
						
						<div class="form-group">
						    <label for="adminPass" class="col-sm-3 control-label"><span style="color:red;position:relative;top:3px;">*</span>管理员密码：</label>
						    <div class="col-sm-9" style="position:relative;">
						      <input type="password" class="form-control" id="adminPass" name="adminPass" placeholder="请输入管理员密码">
							  <div style="color:red;position:absolute;left:390px;line-height:34px;width:180px;top:0;"><span style="color:red;position:relative;top:3px;">*</span>密码长度(6~20)</div>
						    </div>
							<div class="adminPassTrue hideNow">
							<label class="col-sm-3 control-label"></label>
						    <div class="col-sm-9 tips tipPa">
						      请填写管理员密码
						    </div>
							</div>
					    </div>
						
						<div class="form-group">
						    <label for="adminPassAgain" class="col-sm-3 control-label"><span style="color:red;position:relative;top:3px;">*</span>确认密码：</label>
						    <div class="col-sm-9">
						      <input type="password" class="form-control" id="adminPassAgain" name="adminPassAgain" placeholder="">
						    </div>
							<div class="adminPassAgainTrue hideNow">
							<label class="col-sm-3 control-label"></label>
						    <div class="col-sm-9 tips different">
						      请填写确认密码
						    </div>
							</div>
					    </div>
						
						<div class="form-group">
						    <label for="invite" class="col-sm-3 control-label">邀请码：</label>
						    <div class="col-sm-9">
						      <input type="text" class="form-control" id="invite" name="invite" placeholder="请输入邀请码">
						    </div>
					    </div>
						<input type="text" style="display:none;" id="seller_nick">
						<input type="text" style="display:none;" id="user_id">
						<input type="text" style="display:none;" id="shopname">
						<input type="text" style="display:none;" id="sessionkey">
						<input type="text" style="display:none;" id="shoptype">
						<input type="text" style="display:none;" id="deadline">
						<input type="text" style="display:none;" id="appkey">
						<input type="text" style="display:none;" id="secretkey">
					</form>
					
					<div style="text-align:center;margin-top:20px;">
						<div class="col-sm-3"></div><div class="col-sm-9"><button type="button" onclick="next()" class="btn btn-success" style="width:100%;">下一步</button></div>
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
