<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<?
		include_once("../html/js/boot.php");
	?>
	
	<title>价格策略</title>
	<style type="text/css">
	html, body{
		margin:0;padding:0;border:0;width:100%;height:100%;overflow:hidden;
	}

	</style>
</head>
<body>
	<div class="mini-toolbar" style="padding:2px;border-bottom:0;">
		<table style="width:100%;">
			<tr style="display:block;margin-bottom:2px">
				<td style="white-space:nowrap;">
					<input name="price_no" id="price_no" class="mini-textbox" emptyText="价格策略编号" style="width:150px;" onenter="onKeyEnter"/>
					<input name="price_name" id="price_name" class="mini-textbox" emptyText="价格策略名称" style="width:150px;" onenter="onKeyEnter"/>
					<a class="mini-button" onclick="search()">查询</a>
				</td>
			</tr>
			<tr>
				<td style="width:100%;">
					<a class="mini-button" iconCls="icon-add" onclick="add()">新增</a>
                    <a class="mini-button" iconCls="icon-edit" onclick="edit()">编辑</a>
                    <a class="mini-button" iconCls="icon-edit" onclick="view()">查看</a>
					<a class="mini-button" iconCls="icon-remove" onclick="remove()">删除</a>
					
					<a class="mini-button" iconCls="icon-ok" onclick="auditOk()">启用</a>
					<a class="mini-button" iconCls="icon-cancel" onclick="auditCancel()">禁用</a>
				</td>
			</tr>
		</table>           
	</div>

	<div class="mini-fit">
		<div id="datagrid1" class="mini-datagrid" style="width:100%;height:100%;"
			url="index.php?m=system&c=priceStrategy&a=getList" idField="id" multiSelect="true" pageSize="20">
			<div property="columns">
				<!--<div type="indexcolumn"></div> -->
				<div type="checkcolumn"></div>        
				<div field="price_no" width="120" headerAlign="center" align="center" allowSort="">价格策略编号</div>
				<div field="price_name" width="120" headerAlign="center" align="center" allowSort="">价格策略名称</div>
				<div field="status" width="120" headerAlign="center" align="center" allowSort="">订单状态</div>
				
				<div field="addtime" width="120" headerAlign="center" align="center" allowSort="">创建时间</div>  
				<div field="update_time" width="120" headerAlign="center" align="center" allowSort="">最后修改时间</div>  
				
				<div field="remark" width="120" headerAlign="center" align="center" allowSort="">备注</div>
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
