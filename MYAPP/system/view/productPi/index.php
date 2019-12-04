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
					<input name="pi_no" id="pi_no" class="mini-textbox" emptyText="盘点单号" style="width:150px;" onenter="onKeyEnter"/>
					<input name="pi_user_no" id="pi_user_no" class="mini-textbox" emptyText="操作员编号" style="width:150px;" onenter="onKeyEnter"/>
					<input name="status" id="status" class="mini-combobox" url="index.php?m=system&c=warehouseOrderNum&a=status_arr" textField="text" valueField="id" emptyText="审核状态" />
						
					<a class="mini-button" onclick="search()">查询</a>
				</td>
			</tr>
			<tr>
				<td style="width:100%;">
                    <a class="mini-button" iconCls="icon-edit" onclick="view()">查看</a>
					<a class="mini-button" iconCls="icon-user" onclick="auditOk()">审核</a>
				</td>
			</tr>
		</table>           
	</div>

	<div class="mini-fit">
		<div id="datagrid1" class="mini-datagrid" style="width:100%;height:100%;"
			url="<?=U("getList")?>"  idField="id" multiSelect="true" pageSize="20"
		>
			<div property="columns">
				<div type="checkcolumn"></div>        
				<div field="pi_no" width="120" headerAlign="center" align="center" allowSort="">盘点单号</div>
				<div field="status" width="120" headerAlign="center" align="center" allowSort="">审核状态</div>
				<div field="wh_no" displayField="wh_name" width="120" headerAlign="center" align="center" allowSort="">仓库</div>
				<div field="loc_no" displayField="loc_name" width="120" headerAlign="center" align="center" allowSort="">货位</div>
				<div field="pi_user_no" width="120" headerAlign="center" align="center" allowSort="">操作员</div>
				<div field="pi_time" width="120" headerAlign="center" align="center" allowSort="">盘点时间</div>
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
