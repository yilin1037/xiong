<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<?
        include_once("../html/js/boot.php");
		?>
		<link rel="stylesheet" href="js/plug-in/bootstrap/css/bootstrap.css" />
		<link rel="stylesheet" href="js/plug-in/layui-2.0/css/layui.css" />
		<title>常用功能</title>
		<style type="text/css">
			
			html,body{
				min-height:550px;
				min-width:900px;
				width:99%;
				margin-left:0.5%;
			}
			
			.choosediv{
				margin-top:40px;
				width:100%
			}
			
			.canvasUl li{
				position:relative;
				display:none;
			}
			.canvasActive{
				display:block !important;
			}
			.showImg{
				cursor:pointer;
				width:100%;
			}
			.showImg img{
				width:100%;
			}
			
			.showFont{
				width:150%;
				height:35px;
				line-height:35px;
				text-align:center;
				font-size:15px;
				font-family:"微软雅黑";
				color:#3077D1;
				font-weight:900;
				margin-left: -25%;
				white-space:nowrap;
				overflow:hidden;
				text-overflow:ellipsis;
			}
			.css1{
				position:absolute;
				top:0%;
				left:15%;
				width:6%;
				border:1px solid transparent;
			}
			
			.css2{
				position:absolute;
				top:0%;
				left:35%;
				width:6%;
				border:1px solid transparent;
			}
			
			.css3{
				position:absolute;
				top:0%;
				left:55%;
				width:6%;
				border:1px solid transparent;
			}
			.css4{
				position:absolute;
				top:0%;
				left:75%;
				width:6%;
				border:1px solid transparent;
			}
			
			.imgHover{
				cursor:pointer;
				display:none;
			}
			.canvasHover{
				cursor:pointer;
				padding:5px;
			}
		</style>
	</head>
	<body>
		<div id="flow" class="layui-row layui-col-space20" style="margin-top:5px;">
			
			<div class="layui-col-xs12 layui-col-sm12 layui-col-md12 layui-col-lg9">
				<div class="content" style="overflow:hidden;border:1px solid #ddd;">
					<div class="choosediv">
					<?php 
						foreach($menulist as $k => $v){
					?>		
						<ul class="canvasUl">
							<li  class="canvasActive">
							<?php 
								$k = 1;
								foreach($v['child'] as $i => $j){
									switch($k){
										
										case 1:
											$pic = 'biaoqiandangkoupeihuo';
										break;									
										case 2:
											$pic = 'shenhedingdan';
										break;									
										case 3:
											$pic = 'fahuo';
										break;									
										case 4:
											$pic = 'shouhou';
										break;
										default;
									}
							?>
									<div class="css<?=$k?> canvasHover" onclick="addTab('<?=$i?>','<?=$j['url']?>','<?=$j['name']?>');">
										<div class="showImg"><img src="images/table/<?=$pic?>.png" ></div>
										<div class="showFont"><?=$j['name']?></div>
									</div>
							<?php
									$k++;
									if($k > 4)break;
								}
							?>
								<canvas id="canvaOne" width="0" height="100"></canvas>
							</li>
						</ul>
					<?php		
						}
					?>
					</div>
				</div>
				
			</div>
			
		</div>
		
		
	
	<!-- 更改信息弹窗结束 -->
	<script src="js/plug-in/bootstrap/js/bootstrap.js"></script>
	<script src="js/plug-in/layui-2.0/layui.js"></script>
	
	<script>
		
		function addTab(id,url,title){
			
			window.parent.frames.addTab(id,url,title);
		}
		var flow = new Vue({
			el: '#flow',
		});	
	</script>
	</body>	
</html>