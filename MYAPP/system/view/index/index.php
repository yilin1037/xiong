<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <?
        include_once("../html/js/boot.php");
		?>
        <?
        include_once("../html/js/myAppBoot.php");
		?>
        <title><?=$userConfigObj['username']?></title>
		<style>
		.logo{
		    /* background-position: 0 -38px; */
			width: 130px;
			height: 48px;
			display: block;
			position: absolute;
			/* top: 10px; */
			left: 10px;
			z-index: 10002;
		}
		.layui-tab-title li{
			line-height: 49px !important;
			//background-color:#8A8A8A;
		}
		.layui-nav .layui-nav-item a {
			color:black !important;
		}
		#layui-tab-content{
			padding-left:200px;
		}
		</style>
    </head>
    <body>
        <div id="flow">
            <div class="layui-side my-side" style="top: 50px;">
                <div class="layui-side-scroll">
                    <ul class="layui-nav layui-nav-tree" lay-filter="side">
                    <?
                        if(is_array($menulist)){
                        
                            foreach($menulist as $menulevel => $menuItem){
                                if(is_array($menulimit) && !$menulimit[$menulevel]){
                                    $menuItem['show'] = "hidden";
                                }
                                
                                if($menuItem['show'] == "show"){
                            
                                    if(1){
                                        $str = '';
                                        
                                        $str .= '<li class="layui-nav-item"><a href="javascript:;"><i class="layui-icon">&#xe628;</i>'.$menuItem['name'].'</a><dl class="layui-nav-child"> ';
                                        if(is_array($menuItem['child'])){
                                            foreach($menuItem['child'] as $menuid => $vo){
                                                $str .='<dd><a href="javascript:;" id="nav'.$menuid.'" data-id="'.$menuid.'" href-url="'.$vo['url'].'"><i class="layui-icon">&#xe621;</i>'.$vo['name'].'</a></dd>';
                                            }
                                        }
                                        $str .= '<dl/></li>';
                                        
                                        echo $str;
                                    }else{
                                        echo '<li class="layui-nav-item  layui-nav-itemed"><a href="javascript:;"><i class="layui-icon">&#xe628;</i>'.$menuItem['name'].'</a></li>';
                                    }
                                }
                                
                            }
                        }
                    ?>
                    </ul>
                </div>
            </div>

            <div lay-filter="my-tab" class="layui-tab layui-tab-card my-tab" lay-allowClose="true">
                <span class="logo" style="z-index:1000">
                    <img src="/images/logo.png" width="100%" height="100%">
                </span>
				<div class=tb-wrap>
                    <ul>
                        <li id="user" style="height: 24px;;padding-left: 0px; color: rgb(255, 255, 255); border: 1px solid rgb(48, 119, 209); background-position: 0px -114px;">
                            <b class="iconfont layui-icon" style="font-size:1.5em;padding: 3px 6px 3px 6px;float: left; line-height: 18px;">&#xe612;</b>
                            <span><?=$_SESSION['LOGIN_USERNAME']?></span>
            				<i></i><div class="content" style="width:160px;">
            					<div class="links" style="height:80px;">
									<a style="overflow: hidden;text-overflow:ellipsis;white-space: nowrap;"><?=$sys_user_name?></a>
                                    <a onClick="modify_password()">修改密码</a>
                                    <a onClick="re_login()">退出</a>
                                </div>
                            </div>
            			</li>
                        
					</ul>
				</div>
				<ul class="layui-tab-title" style="width: 100%;height: 50px;position: relative;background-color: #3077D1;color: #FFF;z-index: 99;padding-left:160px;">
		
				</ul>
             <div class="layui-tab-content" id="layui-tab-content"></div>
            </div>
        </div>
        
        <script type="text/javascript">
            //商城按钮状态显示与隐藏
            var shopStatus = '<?=$mallShopStatusObj?>';
			mini.Cookie.set('miniuiSkin', 'metro-white');
			mini.Cookie.set('miniuiMode', 'large');
        </script>
    </body>
</html>