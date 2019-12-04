<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<?
		include_once("../html/js/boot.php");
	?>
	
	<title>移仓单管理</title>
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
					<input id="date_begin" name="date_begin" class="mini-datepicker" format="yyyy-MM-dd"  onenter="onKeyEnter" emptyText="开始时间"/>
					<input id="date_end" name="date_end" class="mini-datepicker" onenter="onKeyEnter" emptyText="结束时间"/>
					<input name="order_no" id="order_no" class="mini-textbox" emptyText="订单编号" style="width:150px;" onenter="onKeyEnter"/>
					<input name="dealer_no" id="dealer_no" class="mini-textbox" emptyText="经销商编号" style="width:150px;" onenter="onKeyEnter"/>
					<input name="status" id="status" class="mini-combobox" url="index.php?m=system&c=warehouseOrderNum&a=status_arr" textField="text" valueField="id" emptyText="审核状态" />
						
					<a class="mini-button" onclick="search()">查询</a>
				</td>
			</tr>
			<tr>
				<td style="width:100%;">
					<a class="mini-button" iconCls="icon-add" onclick="add()">新增订单</a>
					<a class="mini-button" iconCls="icon-edit" onclick="edit()">编辑</a>
                    <a class="mini-button" iconCls="icon-edit" onclick="view()">查看</a>
					<a class="mini-button" iconCls="icon-remove" onclick="remove()">删除</a>
					<a class="mini-button" iconCls="icon-user" onclick="auditOk()">审核订单</a>
					<a class="mini-button" iconCls="icon-cancel" onclick="auditCancel()">取消审核</a>
					<a class="mini-button" iconCls="icon-print" onclick="print()">打印清单</a>
					<a class="mini-button" iconCls="icon-excel" onclick="import()">导出订单</a>
					<a class="mini-button" iconCls="icon-ok" onclick="generate()">生成出库单</a>
					<!--<a class="mini-button" iconCls="icon-edit" onclick="custType()">配送中心设置</a>-->
				</td>
			</tr>
		</table>           
	</div>

	<div class="mini-fit">
		<div id="datagrid1" class="mini-datagrid" style="width:100%;height:100%;"
			url="index.php?m=system&c=moveOrder&a=getList"  idField="id" multiSelect="true" pageSize="20"
		>
			<div property="columns">
				<!--<div type="indexcolumn"></div> -->
				<div type="checkcolumn"></div>        
				<div field="order_no" width="120" headerAlign="center" align="center" allowSort="">订单号</div>
				<div field="status" width="120" headerAlign="center" align="center" allowSort="">订单状态</div>
				
				<div field="dealer_no" width="120" headerAlign="center" align="center" allowSort="">经销商编号</div>
				<div field="dealer_name" width="120" headerAlign="center" align="center" allowSort="">经销商名称</div>
				
			<!--	<div field="" width="120" headerAlign="center" align="center" allowSort="">调出类型</div>  -->
				<!--<div field="" width="120" headerAlign="center" align="center" allowSort="">配送中心</div> -->
				
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
