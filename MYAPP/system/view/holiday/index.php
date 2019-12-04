<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<?
		include_once("../html/js/boot.php");
	?>
	<title>假日设置</title>
</head>
<body>
	<style type="text/css">
	html, body{
		margin:0;padding:0;border:0;width:100%;height:100%;overflow:hidden;
	}    
	.mini-grid-rows-view{
        overflow-x: hidden;
	}
	</style>
	<div class="mini-toolbar" style="padding:2px;border-bottom:0;">
		<table style="width:100%;">
			<tr>
				<td style="width:100%;">
					<a class="mini-button" iconCls="icon-add" onclick="add()">增加</a>
					<a class="mini-button" iconCls="icon-edit" onclick="edit()">编辑</a>
					<a class="mini-button" onclick="remove()">删除</a>
				</td>
			</tr>
		</table>           
	</div>
	<div class="mini-fit">
		<div id="datagrid1" class="mini-datagrid" style="width:100%;height:100%;" url="index.php?m=system&c=holiday&a=getList" idField="id" multiSelect="true" pageSize="20">
			<div property="columns">
				<!--<div type="indexcolumn"></div> -->
				<div type="checkcolumn" ></div>        
				<div field="name" width="120" headerAlign="center" allowSort="true">节日名称</div>    
				<div field="begin_date" width="120" headerAlign="center" allowSort="true">开始日期</div>    
				<div field="end_date" width="120" headerAlign="center">结束日期</div>  
				<div field="multiple" width="120" headerAlign="center">收费倍数</div>  
			</div>
		</div>
	</div>
	
	
	<?
		include_once("../html/js/myAppBoot.php");
	?>
	<style>
	.layui-table-page select{height:25px}
	</style>
	
	</body>	
</html>
