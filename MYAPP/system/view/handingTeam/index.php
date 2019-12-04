<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<?
		include_once("../html/js/boot.php");
	?>
	<title>装卸队管理</title>
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
		<table style="width:100%">
			<tr style="display:block;margin-bottom:2px">
				<td style="white-space:nowrap;">
					<input name="handing_name" id="handing_name" class="mini-textbox" emptyText="装卸队名称" style="width:150px;" onenter="onKeyEnter"/>
					<input name="linkman" id="linkman" class="mini-textbox" emptyText="联系人" style="width:150px;" onenter="onKeyEnter"/>
					<input name="mobile" id="mobile" class="mini-textbox" emptyText="联系电话" style="width:150px;" onenter="onKeyEnter"/>
					
					<a class="mini-button" onclick="search()">查询</a>
				</td>
			</tr>
			<tr>
				<td style="width:100%;">
					<a class="mini-button" onclick="add()">增加</a>
					<a class="mini-button" onclick="edit()">编辑</a>
                    <a class="mini-button" onclick="view()">查看</a>
					<a class="mini-button" onclick="remove()">删除</a>
					<a class="mini-button" href="">导出</a>
					<a class="mini-button" onclick="export()">导入</a>
				</td>
			</tr>
		</table>           
	</div>
	
	<div class="mini-fit">
		<div id="datagrid1" class="mini-datagrid" style="width:100%;height:100%;" url="index.php?m=system&c=handingTeam&a=getList" idField="id" multiSelect="true" pageSize="20">
			<div property="columns">
				<!--<div type="indexcolumn"></div> -->
				<div type="checkcolumn" ></div>        
				<div field="handing_no" width="120" headerAlign="center" allowSort="true">编号</div>    
				<div field="handing_name" width="120" headerAlign="center" allowSort="true">名称</div>    
				<div field="linkman" width="120" headerAlign="center">联系人</div>  
				<div field="mobile" width="120" headerAlign="center">联系电话</div>  
				<div field="remark" width="120" headerAlign="center">备注</div>  
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
