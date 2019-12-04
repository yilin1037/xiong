<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<?
			include_once("../html/js/boot.php");
		?>
		<link rel="stylesheet" href="js/plug-in/bootstrap/css/bootstrap.css" />
		<link rel="stylesheet" href="js/plug-in/layui-2.0/css/layui.css" />
		
		<title>客户类型管理</title>
		<style type="text/css">
		html, body{
			margin:0;padding:0;border:0;width:100%;height:100%;overflow:hidden;
		}

		
		.New_Button, .Edit_Button, .Delete_Button, .Update_Button, .Cancel_Button {
			font-size: 11px;
			color: #1B3F91;
			font-family: Verdana;
			margin-right: 5px;
		}
		</style>
	</head>
<body>
	
	<div class="mini-toolbar" style="margin:2px 0;border:0;">
		<table style="width:100%;border:0;">
			<tr>
				<td style="width:100%;">
					<a class="mini-button" iconCls="icon-undo" onclick="backBan()">取消禁用</a>
				</td>
			</tr>
		</table>           
	</div>
		
<div class="mini-fit">
<div id="datagrid1" class="mini-datagrid" style="width:100%;height:100%;" allowResize="true"
	url="index.php?m=system&c=customer&a=getBanList"  idField="id" multiSelect="true" sizeList="[20,30,50,100]" pageSize="20">
	<div property="columns">
		<!--<div type="indexcolumn"></div> -->
		<div type="checkcolumn" ></div>        
		<div field="cust_no" width="120" headerAlign="center" align="center" allowSort="">客户编号</div>    
		<div field="cust_name" width="120" headerAlign="center" align="center" allowSort="">客户名称</div>    
		<div field="status" width="120" headerAlign="center" align="center" allowSort="">审核状态</div>    
		<div field="cust_type" width="120" headerAlign="center" align="center" allowSort="">客户类型</div> 
		<div field="cust_grade" width="120" headerAlign="center" align="center" allowSort="">客户等级</div>
		<div field="province" width="120" headerAlign="center" align="center" allowSort="">详细地址</div>  
		<div field="link_man" width="120" headerAlign="center" align="center" allowSort="">联系人</div>  
		<div field="link_tel" width="120" headerAlign="center" align="center" allowSort="">联系电话</div>  
		<div field="link_mobile" width="120" headerAlign="center" align="center" allowSort="">联系手机</div>  
		<div field="sales_man" width="120" headerAlign="center" align="center" allowSort="">所属业务员</div>
	</div>
</div>
</div>
	
	
	<?
		include_once("../html/js/myAppBoot.php");
	?>
	<script src="js/plug-in/bootstrap/js/bootstrap.js"></script>
	<script src="js/plug-in/layui-2.0/layui.js"></script>
	
	<style>
	.layui-table-page select{height:25px}
	</style>
	
<script type="text/javascript">
	mini.parse();
	
	var grid = mini.get("datagrid1");
	grid.load();
	
	function backBan() {
		var rows = grid.getSelecteds();
		if (rows.length > 0) {
			if (confirm("确定取消禁用选中的记录？")) {
				var ids = [];
				for (var i = 0, l = rows.length; i < l; i++) {
					var r = rows[i];
					ids.push(r.id);
				}
				var id = ids.join(',');
				//grid.loading("操作中，请稍后......");
				$.ajax({
					url: "index.php?m=system&c=customer&a=backBan&id=" + id,
					success: function (text) {
						grid.load();
					},
					error: function () {
					}
				});
			}
		} else {
			alert("请选中一条记录");
		}
	}
</script>
	
	</body>	
</html>
