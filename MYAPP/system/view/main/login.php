<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,Chrome=1" />
	    <meta name="renderer" content="webkit" />
        <script src="js/plug-in/jQuery/jquery-3.2.1.min.js" type="text/javascript" ></script>
		<script src="js/plug-in/layui/layui.js" type="text/javascript" ></script>
		<link href="js/plug-in/layui/css/layui.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="css/system/main/login.css" />
		<title>登陆</title>
	</head>
	<body>
    <div class="all">
        <div class="header">
            <div class="headerIn">
                <div class="logo"><img src="images/chaoqunlogo.png" alt="" width="230" height="44"/></div>
            </div>
        </div>
        <div class="center">
            <div class="main" style="background-image:url('images/main.png')">
                <div class="login">
                    <div class="loginText">安全登陆</div>
                    <div style="padding:0 25px;"><div id="errorMsg" style="padding:5px 10px;border:1px solid red;display:none"></div></div>
                    <div class="first">
                        <div class="nameImg"><img src="images/loginName.png" alt="" /></div>
                        <input type="text" class="nameInput" id="username" name="username" placeholder="用户名" onKeydown="LoginNow()" value=""/>
                    </div>
                    
                    <div class="second">
                        <div class="nameImg"><img src="images/password.png" alt="" /></div>
                        <input type="password" class="nameInput" id="password" name="password" placeholder="密码" onKeydown="LoginNow()" value=""/>
                    </div>
                    <div class="btn">
                    <button class="loginBtn" type="submit">登陆</button>
                    </div>
                    <div style="padding-left:25px;margin-top:5px;"><input type="checkbox" id="save_user_id"> 保存用户名</div>
                </div>
            </div>
        </div>
        
        <div class="footer">
            <div class="copyright">版权所有 © 2019大连佳庆软件有限公司</div>
        </div>
    </div>
	</body>
<script>
__CreateJSPath = function (js) {
    var scripts = document.getElementsByTagName("script");
    var path = "";
    for (var i = 0, l = scripts.length; i < l; i++) {
        var src = scripts[i].src;
        if (src.indexOf(js) != -1) {
            var ss = src.split(js);
            path = ss[0];
            break;
        }
    }
    var href = location.href;
    href = href.split("#")[0];
    href = href.split("?")[0];
    var ss = href.split("/");
    ss.length = ss.length - 1;
    href = ss.join("/");
    if (path.indexOf("https:") == -1 && path.indexOf("http:") == -1 && path.indexOf("file:") == -1 && path.indexOf("\/") != 0) {
        path = href + "/" + path;
    }
    return path;
}
//获取参数
__CreateJSParam = function (js) {
    var scripts = document.getElementsByTagName("script");
    var pathParam = "";
    for (var i = 0, l = scripts.length; i < l; i++) {
        var src = scripts[i].src;
        if (src.indexOf(js) != -1) {
            var ss = src.split(js);
            pathParam = ss[1];
            break;
        }
    }
    pathParam = pathParam.split("?")[1];
    pathParam = pathParam.split("&");
    var pathParamObj = {};
    for(var i in pathParam){
        var pp = pathParam[i].split("=");
        pathParamObj[pp[0]] = pp[1];
    }
    return pathParamObj;
}
var bootPATH = __CreateJSPath("boot.js");
</script>
<?
			include_once("../html/js/myAppBoot.php");
		?>
<script src="js/md5.js"></script>
</html>
