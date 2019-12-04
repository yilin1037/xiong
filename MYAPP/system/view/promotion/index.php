<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<?
		include_once("../html/js/boot.php");
	?>
	
	<title>组合满赠</title>
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
					<input name="sales_name" id="sales_name" class="mini-textbox" emptyText="策略名称" style="width:150px;" onenter="onKeyEnter"/>
					<input id="addtime" name="addtime" class="mini-datepicker" onenter="onKeyEnter" emptyText="创建日期"/>
					<input id="begin_date" name="begin_date" class="mini-datepicker" format="yyyy-MM-dd"  onenter="onKeyEnter" emptyText="开始时间"/>
					<input id="end_date" name="end_date" class="mini-datepicker" onenter="onKeyEnter" emptyText="结束时间"/>
					<input name="is_multiple" id="is_multiple" class="mini-combobox" url="index.php?m=system&c=promotion&a=mul_arr" textField="text" valueField="id" emptyText="是否倍数赠送" />
						
					<a class="mini-button" onclick="search()">查询</a>
				</td>
			</tr>
			<tr>
				<td style="width:100%;">
					<a class="mini-button" onclick="add()">新增</a>
					<a class="mini-button" onclick="edit()">编辑</a>
                    <a class="mini-button" onclick="view()">查看</a>
					<a class="mini-button" onclick="remove()">删除</a>
				</td>
			</tr>
		</table>           
	</div>

	<div class="mini-fit">
		<div id="datagrid1" class="mini-datagrid" style="width:100%;height:100%;" url="index.php?m=system&c=promotion&a=getList" idField="id" multiSelect="true" pageSize="20">
			<div property="columns">
				<!--<div type="indexcolumn"></div> -->
				<div type="checkcolumn" ></div>
				<div field="sales_no" width="120" headerAlign="center" allowSort="true">编号</div>    
				<div field="sales_name" width="120" headerAlign="center" allowSort="true">策略名称</div>
				<div field="begin_date" width="120" headerAlign="center">开始日期</div>
				<div field="end_date" width="120" headerAlign="center">结束日期</div>  
				<div field="addtime" width="120" headerAlign="center">创建时间</div>  
				<div field="update_time" width="120" headerAlign="center">最后修改时间</div>
				<div field="is_multiple" width="120" headerAlign="center">是否倍数赠送</div>
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
