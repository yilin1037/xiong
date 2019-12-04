<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>系统设置</title>

    <?
        include_once("../html/js/boot.php");
		?>
</head>
<body>

<div class="login-main" style="padding-left:5px">
    <div class="warm_tip warm_tip_content">
        <p>温馨提示</p>
        <div>为了保障您的信息安全，请妥善保管您的密码。</div>


    </div>

	<div style="border:1px solid #dddddd;width:500px;padding:20px;margin:0 auto;">
	
        <div style="width:400px;margin:0 auto;">
			<div style="display:inline-block;width:100px;text-align:right;">原密码：</div>
            <div style="display:inline-block;width:220px;"><input type="password" id="oldpass" required  lay-verify="required" placeholder="原密码" autocomplete="off" class="layui-input"></div>
        </div>
        <div style="width:400px;margin:0 auto;margin-top:20px;position:relative;">
			<div style="display:inline-block;width:100px;text-align:right;">新密码：</div>
            <div style="display:inline-block;width:220px;"><input type="password" id="newpass" required  lay-verify="required" placeholder="新密码" autocomplete="off" class="layui-input"></div>
			 <div style="display:inline-block;width:100px;text-align:right;color:red;line-height:38px;position:absolute;left:280px;top:0;">6~12位</div>
        </div>
		<div style="width:400px;margin:0 auto;margin-top:20px;">
			<div style="display:inline-block;width:100px;text-align:right;">确认密码：</div>
             <div style="display:inline-block;width:220px;"><input type="password" id="newpassAgain" required  lay-verify="required" placeholder="确认密码" autocomplete="off" class="layui-input"></div>
			
        </div>
		<div id="errorMsg" style="display:none;color:red;width:400px;margin:0 auto;text-align:center;">

		</div>
		
        <div style="width:84px;margin:0 auto;margin-top:20px;">
            <button type="submit" class="layui-btn">确定修改</button>
        </div>
		
	</div>
    

</div>

<?
	include_once("../html/js/myAppBoot.php");
?>
</body>
</html>