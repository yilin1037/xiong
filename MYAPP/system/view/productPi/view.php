<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<?
		include_once("../html/js/boot.php");
	?>
	<script src="js/distpicker.js" type="text/javascript" ></script>
	<script src="js/distpicker.data.js" type="text/javascript"></script>
	
	<title>查看 - 盘点单</title>
	<style>
		
		body{padding:15px}
		legend{font-size:12px}
		.head-box table{width:100%;}
		.head-box table tr td{padding:5px 0;}
		.head-box table tr td select{height:24px;}
		
		.New_Button, .Edit_Button, .Delete_Button, .Update_Button, .Cancel_Button {
			font-size: 11px;
			color: #1B3F91;
			font-family: Verdana;
			margin-right: 5px;
		}
	</style>
</head>


<body> 
	
	<form id="form1" method="post">
		<input class="mini-hidden" name="id"/>
		<div class="divcss"><div class="spancss">商品信息</div></div>
		<input name="id" class="mini-hidden" value="<?php echo $show['id']?>" />
		<div class="mini-fit" style="background:white;height:460px;">
			<div id="datagrid1" class="mini-datagrid" style="width:100%;height:460px" url="index.php?m=system&c=productPi&a=getItemList&pi_no=<?=$show['pi_no']?>" multiSelect="true" allowCellEdit="true" allowAlternating="true" allowCellSelect="true" idField="id" showPager="false">
				<div property="columns">
					<div field="prd_no" name="prd_no" displayField="prd_no" width="120" headerAlign="center" allowSort="">商品编号</div>
					<div field="prd_name" width="120" headerAlign="center" allowSort="">商品名称</div>
					<div field="dealer_name" width="120" headerAlign="center" allowSort="">所属经销商</div>
					<div field="spec" width="60" headerAlign="center" allowSort="">规格</div>
					<div field="wh_no" width="120" headerAlign="center" allowSort="">仓库</div>
					<div field="loc_no" width="120" headerAlign="center" allowSort="">货位</div>
					<div header="盘点前">
						<div property="columns">
							<div field="unit_name" width="60" headerAlign="center" allowSort="">单位</div>
							<div field="unit_num" width="60" headerAlign="center" allowSort="">数量</div>
							<div field="unit_name1" width="60" headerAlign="center" allowSort="">副单位</div>
							<div field="unit_num1" width="60" headerAlign="center" allowSort="">副数量</div>
							<div field="box_num" width="60" headerAlign="center" allowSort="">件数量</div>
							<div field="bat_no" width="100" headerAlign="center" allowSort="">批号</div>
						</div>
					</div>
					<div header="盘点后">
						<div property="columns">
							<div field="unit_name" width="60" headerAlign="center" allowSort="">单位</div>
							<div field="pi_unit_num" width="60" headerAlign="center" allowSort="">数量</div>
							<div field="unit_name1" width="60" headerAlign="center" allowSort="">副单位</div>
							<div field="pi_unit_num1" width="60" headerAlign="center" allowSort="">副数量</div>
							<div field="pi_box_num" width="60" headerAlign="center" allowSort="">件数量</div>
							<div field="pi_bat_no" width="100" headerAlign="center" allowSort="">批号</div>
						</div>
					</div>
					<div field="pi_time" width="160" dateFormat="yyyy-MM-dd HH:mm:ss" headerAlign="center" allowSort="">盘点时间</div>
					<div field="pi_user_no" width="100" headerAlign="center" allowSort="">操作员</div>
				</div>
			</div>
		</div>
		<div style="text-align:center;padding:10px;">
			<a class="mini-button" iconCls="icon-ok" onclick="onCancel" style="margin-right:20px;">关闭</a>
		</div>
	</form>
	
	<script>
		mini.parse();
		var grid = mini.get("datagrid1");
        grid.load({},function(e){});
        function onCancel(e) {
            CloseWindow("cancel");
        }
		function CloseWindow(action) {
            if (window.CloseOwnerWindow) return window.CloseOwnerWindow(action);
            else window.close();            
        }
	</script>
	
	<?
		include_once("../html/js/myAppBoot.php");
	?>
	<script src="js/popupSelect.js"></script>
	<script src="js/plug-in/bootstrap/js/bootstrap.js"></script>
	<script src="js/plug-in/layui-2.0/layui.js"></script>
</body>

</html>