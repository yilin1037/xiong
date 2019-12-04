<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<?
		include_once("../html/js/boot.php");
	?>
	
	<title>报损管理</title>
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
					<input id="order_time" name="order_time" class="mini-datepicker" onenter="onKeyEnter" emptyText="选择订单日期"/>
					<input id="start_time" name="start_time" class="mini-datepicker" format="yyyy-MM-dd"  onenter="onKeyEnter" emptyText="开始时间"/>
					<input id="end_time" name="end_time" class="mini-datepicker" onenter="onKeyEnter" emptyText="结束时间"/>
					<input name="order_no" id="order_no" class="mini-textbox" emptyText="订单编号" style="width:150px;" onenter="onKeyEnter"/>
					<input name="dealer_name" id="dealer_name" class="mini-textbox" emptyText="经销商" style="width:150px;" onenter="onKeyEnter"/>
					<input name="status" id="status" class="mini-combobox" url="index.php?m=system&c=warehouseOrderNum&a=status_arr" textField="text" valueField="id" emptyText="审核状态" />
						
					<a class="mini-button" onclick="search()">查询</a>
				</td>
			</tr>
			<tr>
				<td style="width:100%;">
					<a class="mini-button" onclick="add()">新增</a>
					<a class="mini-button" onclick="edit()">编辑</a>
                    <a class="mini-button" onclick="view()">查看</a>
					<a class="mini-button" onclick="remove()">删除</a>
					<a class="mini-button" onclick="examine()">审核订单</a>
					<a class="mini-button" onclick="qxea()">取消审核</a>
					<a class="mini-button" onclick="import()">导出报损单</a>
					<a class="mini-button" onclick="generate()">生成报损单</a>
				</td>
			</tr>
		</table>           
	</div>

	<div class="mini-fit">
		<div id="datagrid1" class="mini-datagrid" style="width:100%;height:100%;"
			url="index.php?m=system&c=damageOrders&a=getList" idField="id" multiSelect="true" pageSize="20" 
		>
			<div property="columns">
				<!--<div type="indexcolumn"></div> -->
				<div type="checkcolumn" ></div>        
				<div field="order_no" width="120" headerAlign="center">订单号</div>    
				<div field="dealer_name" width="120" headerAlign="center">经销商</div>    
				<div field="wh_name" width="120" headerAlign="center">入到仓库</div>
				<div field="order_time" width="120" headerAlign="center">订单日期</div>  
				<div field="status" width="120" headerAlign="center">审核状态</div>
				<div field="remark" width="120" headerAlign="center">备注</div>
				<div field="" width="120" headerAlign="center">操作</div>
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
