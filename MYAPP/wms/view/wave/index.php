<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<?
		include_once("../html/js/boot.php");
	?>
	
	<title>预配管理</title>
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
					<input name="plan_no" id="plan_no" class="mini-textbox" emptyText="计划号" style="width:150px;" onenter="onKeyEnter"/>
					<a class="mini-button" onclick="search()">查询</a>
				</td>
			</tr>

			<tr>
				<td style="width:100%;">
					<a class="mini-button" onclick="createWave()">生成波次</a>
				</td>
			</tr>
		</table>           
	</div>

	<div class="mini-fit">
		<div id="datagrid1" class="mini-datagrid" style="width:100%;height:100%;"
			url="index.php?m=wms&c=wave&a=getCreateWaveList"  idField="id" pageSize="20"
		>
			<div property="columns">
				<!--<div type="indexcolumn"></div> -->
				<div type="checkcolumn" ></div>
                <div field="plan_no" width="120" headerAlign="center" allowSort="">计划号</div>
                <div field="order_count" width="120" headerAlign="center" allowSort="">订单数</div>
				<div field="re_same_day" width="120" headerAlign="center" allowSort="">当天订单</div>
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